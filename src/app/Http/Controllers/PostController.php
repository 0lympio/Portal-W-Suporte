<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\PostView;
use App\Models\Questionnaire;
use App\Models\QuestionnaireAnswered;
use App\Models\Upload;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:posts.index')->only('index');
        $this->middleware('permission:posts.create')->only(['create', 'store']);
        $this->middleware('permission:posts.edit')->only(['edit', 'update']);
        $this->middleware('permission:posts.destroy')->only('destroy');
        $this->middleware('permission:posts.status')->only('status');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $posts = Post::all();

        foreach ($posts as $key => $post) {
            $post->extras = $post->extras['type'];
        }

        $categories = Category::whereNull('category_id')->where('slug', '<>', 'feed-de-noticias')->get();

        return view('posts.index', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View|Factory
     */
    public function create(): Factory|View
    {
        $categories = Category::whereNull('category_id')->where('slug', '<>', 'feed-de-noticias')->with('children')->get();
        $questionnaires = Questionnaire::all();

        $files = Upload::where('mimetype', 'like', 'image/%')
            ->orWhere('mimetype', 'like', 'video/%')
            ->orderBy('created_at', 'desc')
            ->get();

        $images = [];
        $videos = [];

        foreach ($files as $key => $file) {
            if (str()->startsWith($file->mimetype, 'image')) {
                $images[] = $file;
            } else {
                $videos[] = $file;
            }
        }

        return view('posts.create', compact('categories', 'questionnaires', 'images', 'videos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $type = Category::find($data['category_id'])->name;

        $extras = $data['extras'] ?? ['type' => $type];

        if ($extras['type'] === 'Procedimentos') {
            $pops = collect(DB::select("SELECT * FROM posts WHERE JSON_EXTRACT(extras, '$.type') = 'Procedimentos' order by protocol asc"));

            $protocol = $pops->isNotEmpty() ? $pops->last()->protocol + 1 : 1;

            $newProtocol = str_pad($protocol, 3, '0', STR_PAD_LEFT);

            $data['description'] = "POP-COB-SP {$newProtocol} {$data['title']}";

            $data['protocol'] = $protocol;
        }

        $newPost =  Post::create([
            'user_id' => auth()->user()->id,
            'title' => $data['title'],
            'thumb' => isset($data['thumbnail']) ? $data['thumbnail'] : null,
            'description' => $data['description'],
            'content' => $data['content'],
            'slug' => SlugService::createSlug(Post::class, 'slug', $data['title']),
            'published_at' => (new Carbon($data['published_at'], 'America/Sao_Paulo')),
            'disabled_at' => $data['disabled_at'] === null ? null : (new Carbon($data['disabled_at'], 'America/Sao_Paulo')),
            'popup' => isset($data['popup']) ? 1 : 0,
            'extras' => $extras,
            'category_id' => $data['category_id'],
            'isMenu' => isset($data['isMenu']) ? 1 : 0,
            'last_modified_by' => auth()->user()->id,
            'protocol' => isset($data['protocol']) ? $data['protocol'] : 0,
            'status_id' => 3,
        ]);


        return $this->adminView($newPost, true);
    }

    /**
     * Display the specified resource.
     *
     * @param int $read
     * @return Application|Factory|View
     */
    public function show($slug, int $read = 0): View|Factory|Application
    {
        $post = Post::where('slug', $slug)->first();

        $post_view = $post->views->where('user_id', auth()->user()->id)->first();

        if (empty($post_view)) {
            $post_view = PostView::create([
                'user_id' => auth()->user()->id,
                'post_id' => $post->id,
                'read' => $read,
            ]);
        } else {
            if ($read === 1) {
                $post_view->read = 1;
                $post_view->save();
            }
        }

        $comments = $post->comments->where('status_id', 1)->where('comment_id', null);
        $commentsSubsTemp = $post->comments->where('status_id', 1)->where('comment_id', '<>', null);
        $commentsSubs = [];

        foreach ($commentsSubsTemp as $key => $value) {
            $idComment = $value->toArray()['comment_id'];

            if (!isset($commentsSubs[$idComment])) {
                $commentsSubs[$idComment] = [];
            }

            $commentsSubs[$idComment][] = $value;
        }

        $questionnaire = $post->extras['questionnaire_id'] ?? null;

        if ($questionnaire) {
            $user_id = auth()->user()->id;
            $questionnaireId = $post->extras['questionnaire_id'];
            $questionnairesAnsweredByThisUser = QuestionnaireAnswered::where('user_id', $user_id)
                ->where('questionnaire_id', $questionnaireId)
                ->get();

            if ($questionnairesAnsweredByThisUser->count() < intval($post->extras['tries'])) {
                $questionnaire = Questionnaire::find($questionnaireId);
            } else {
                $questionnaire = null;
            }
        }

        return view('posts.show', compact('post', 'questionnaire', 'post_view', 'comments', 'commentsSubs'));
    }

    public function adminView(Post $post, $enableButton = false): View|Factory|Application
    {
        return view('posts.admin-view', compact('post', 'enableButton'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Post $post
     * @return RedirectResponse
     */
    public function adminPublish(Request $request, Post $post): RedirectResponse
    {
        $data = $request->all();

        $data['status_id'] = 0;

        $post->fill($data);
        $post->save();

        return redirect()->route('posts.index')->with('message', 'Post publicado com sucesso!');
    }

    public function markAsRead(Post $post): JsonResponse
    {
        $post_views = $post->views->where('user_id', auth()->user()->id)->first();

        if ($post_views) {
            $post_views->read = 1;
            $post_views->save();
        } else {
            PostView::create([
                'user_id' => auth()->user()->id,
                'post_id' => $post->id,
                'read' => 1,
            ]);
        }

        return response()->json(['success' => 'Post created successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Post $post
     * @return Application|Factory|View
     */
    public function edit(Post $post): View|Factory|Application
    {
        $categories = Category::whereNull('category_id')->with('children')->get();
        $questionnaires = Questionnaire::all();
        $files = Upload::where('mimetype', 'like', 'image/%')
            ->orWhere('mimetype', 'like', 'video/%')
            ->get();

        $images = [];
        $videos = [];

        foreach ($files as $key => $file) {
            if (str()->startsWith($file->mimetype, 'image')) {
                $images[] = $file;
            } else {
                $videos[] = $file;
            }
        }

        return view('posts.edit', compact('post', 'categories', 'questionnaires', 'images', 'videos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Post $post
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->all();

        $type = Category::find($data['category_id'])->name;

        $extras = $data['extras'] ?? ['type' => $type];
        $data['extras'] = $extras;
        $data['popup'] = isset($data['popup']) ? 1 : 0;
        $data['slug'] = SlugService::createSlug(Post::class, 'slug', $data['title']);
        $data['thumb'] = $data['thumbnail'];
        $data['isMenu'] = isset($data['isMenu']) ? 1 : 0;
        $data['published_at'] = (new Carbon($data['published_at'], 'America/Sao_Paulo'));
        $data['disabled_at'] = $data['disabled_at'] === null ? null : (new Carbon($data['disabled_at'], 'America/Sao_Paulo'));
        $data['last_modified_by'] = auth()->user()->id;
        $data['version'] = $post->version + 1;
        $data['status_id'] = 3;

        $post->fill($data);
        $post->save();

        PostView::where('post_id', $post->id)->delete();

        return $this->adminView($post, true);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @return RedirectResponse
     */
    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();
        return redirect()->route('posts.index')->with('message', 'Post deletado com sucesso!');
    }

    /**
     * @param Request $request
     * @param Post $post
     * @return JsonResponse
     */
    public function changeStatus(Post $post): JsonResponse
    {
        if ($post['status_id'] === 3 || $post['status_id'] === 2) {
            $post->update(['status_id' => 0]);
        }

        if ($post['status_id'] === 1) {
            $post->update(['status_id' => 3]);
        }

        $post->last_modified_by = auth()->user()->id;
        $post->version += 1;

        $post->save();

        return response()->json(['success' => 'Status changed successfully']);
    }

    public static function hasPopup(): JsonResponse
    {
        $userId = auth()->user()->id;
        $popups = DB::select(
            "select * from posts p
            where
                p.popup = 1 and
                p.status_id = 1 and
                p.deleted_at is null and
                p.id not in (
                    select pv.post_id from post_views pv
                    where pv.user_id = {$userId}
                )"
        );

        return response()->json($popups);
    }
}

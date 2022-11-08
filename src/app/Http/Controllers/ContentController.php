<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ImageHome;
use App\Models\Post;
use App\Models\PostView;
use App\Models\Questionnaire;
use App\Models\QuestionnaireAnswered;
use App\Models\QuestionnaireView;
use App\Models\Upload;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContentController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function home(): View|Factory|Application
    {
        $faq_id = Category::where('slug', 'fique-on')->pluck('id')->first();
        $faq_posts = Post::where('category_id', $faq_id)
            ->where('status_id', 1)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $user = auth()->user()->id;

        $allQuestionnaires = Questionnaire::where('status_id', 1)->where('associate', 0)->get();
        $questionnairesAnswered = QuestionnaireAnswered::where('user_id', $user)->get();
        $quest_post = $allQuestionnaires->whereNotIn('id', $questionnairesAnswered->pluck('questionnaire_id'))->last();

        $allTraining = collect(DB::select(
            "select * from posts where JSON_EXTRACT(extras, '$.type') = 'Treinamentos' and status_id = 1 and deleted_at is null order by created_at desc"
        ));

        $trainingView = PostView::where('user_id', $user)->get();
        $training_post = $allTraining->whereNotIn('id', $trainingView->pluck('post_id'))->first();

        $imagesHome = DB::table('images_home AS ih')
                        ->select('ih.id', 'ih.type', 'ih.updated_at', 'u.path')
                        ->join('uploads AS u', 'ih.upload_id', '=', 'u.id')
                        ->where('ih.deleted_at', null)
                        ->get();

        return view('home', compact('faq_posts', 'quest_post', 'training_post', 'imagesHome'));
    }

    /**
     * @return Application|Factory|View
     */
    public function homeEdit(): View|Factory|Application
    {
        $images = Upload::where('mimetype', 'like', 'image/%')->orderBy('created_at', 'desc')->get();

        $imagesHome = DB::table('images_home AS ih')
                        ->select('ih.id', 'ih.type', 'ih.updated_at', 'u.path')
                        ->join('uploads AS u', 'ih.upload_id', '=', 'u.id')
                        ->where('ih.deleted_at', null)
                        ->get();

        return view('home.edit', compact('imagesHome', 'images'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function homeStore(Request $request)
    {
        $id = $request->id;
        $upload_id = $request->upload_id;

        ImageHome::where('id', $id)->update(['upload_id' => $upload_id]);

        return redirect()->route('home.edit')->with('message', 'Imagem alterada com sucesso!');
    }
}

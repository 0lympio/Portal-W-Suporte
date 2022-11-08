<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\QuestionnaireAnswered;
use App\Models\QuestionnaireView;
use App\Models\Upload;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuestionnaireController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:questionnaires.index')->only('index');
        $this->middleware('permission:questionnaires.show')->only('show');
        $this->middleware('permission:questionnaires.create')->only(['create', 'store']);
        $this->middleware('permission:questionnaires.edit')->only(['edit', 'update']);
        $this->middleware('permission:questionnaires.destroy')->only('destroy');
        $this->middleware('permission:questionnaires.status')->only('status');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        $questionnaires = Questionnaire::all();

        return view('questionnaires.index', compact('questionnaires'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function open(): View|Factory|Application
    {
        $user_id = auth()->user()->id;
        $allQuestionnaires = Questionnaire::where('status_id', 1)->where('associate', 0)->get();
        $questionnairesAnswered = QuestionnaireAnswered::where('user_id', $user_id)->get();

        $questionnaires = $allQuestionnaires
            ->whereNotIn('id', $questionnairesAnswered->pluck('questionnaire_id'))
            ->reverse();

        return view('questionnaires.open', compact('questionnaires'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return View|Factory
     */
    public function create(): Factory|View
    {
        $images = Upload::where('mimetype', 'like', 'image/%')->orderBy('created_at', 'desc')->get();
        $categories = Category::whereNull('category_id')->where('slug', '<>', 'feed-de-noticias')->with('children')->get();
        $questionnaires = Questionnaire::all();

        return view('questionnaires.create', compact('images', 'categories', 'questionnaires'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $questionnaire = Questionnaire::create([
            'user_id' => auth()->user()->id,
            'name' => $request->title,
            'published_at' => $request->published_at,
            'disabled_at' => $request->disabled_at,
            'thumb' => $request->thumbnail,
            'associate' => isset($request->associate) ? 1 : 0,
            'status_id' => 0
        ]);


        Question::where('questionnaire_id', $questionnaire->id)->forceDelete();

        $questions = $request->questions;

        foreach ($questions as $question) {
            $text = $question['text'];
            $type = $question['type'];
            $alternatives = collect($question['alternatives']);

            $options = [];

            foreach ($alternatives as $alternative) {
                $isCorrect = isset($alternative['checked']) ? 1 : 0;
                $image = $alternative['image'] ?? null;
                $imageName = $alternative['imageName'] ?? null;

                $option = [
                    'text' => $alternative['text'],
                    'image' => $image,
                    'imageName' => $imageName,
                    'isCorrect' => $isCorrect,
                ];

                $options[] = $option;
            }

            $question = [
                'questionnaire_id' => $questionnaire->id,
                'text' => $text,
                'type' => $type,
                'options' => $options,
            ];

            Question::create($question);
        }

        return redirect()->route('questionnaires.index', compact('questionnaire'))->with('message', 'Enquete criada com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param Questionnaire $questionnaire
     * @return Application|Factory|View
     */
    public function show(Questionnaire $questionnaire): View|Factory|Application
    {
        $questions = $questionnaire->questions;
        $images = Upload::where('mimetype', 'like', 'image/%')->orderBy('created_at', 'desc')->get();

        $user_id = auth()->user()->id;

        $questionnaire_view = QuestionnaireView::where('user_id', $user_id)->where('questionnaire_id', $questionnaire->id)->get();

        if ($questionnaire_view->isEmpty()) {
            QuestionnaireView::create([
                'user_id' => $user_id,
                'questionnaire_id' => $questionnaire->id
            ]);
        }

        return view('questionnaires.show', compact('questionnaire', 'questions', 'images'));
    }

    /**
     * Display the specified resource.
     *
     * @param Questionnaire $questionnaire
     * @return Application|Factory|View
     */
    public function edit(Questionnaire $questionnaire): View|Factory|Application
    {
        $questions = $questionnaire->questions;

        $images = Upload::where('mimetype', 'like', 'image/%')->orderBy('created_at', 'desc')->get();

        return view('questionnaires.edit', compact('questionnaire', 'questions', 'images'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Post $post
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {

        $questionnaire_id = $request->questionnaire_id;

        Question::where('questionnaire_id', $questionnaire_id)->delete();

        $questions = $request->questions;

        foreach ($questions as $question) {
            $text = $question['text'];
            $type = $question['type'];
            $alternatives = collect($question['alternatives']);

            $options = [];

            foreach ($alternatives as $alternative) {
                $isCorrect = isset($alternative['checked']) ? 1 : 0;
                $image = $alternative['image'] ?? null;
                $imageName = $alternative['imageName'] ?? null;

                $option = [
                    'text' => $alternative['text'],
                    'image' => $image,
                    'imageName' => $imageName,
                    'isCorrect' => $isCorrect,
                ];

                $options[] = $option;
            }

            $question = [
                'questionnaire_id' => $questionnaire_id,
                'text' => $text,
                'type' => $type,
                'options' => $options,
            ];

            Question::create($question);
        }

        $questionnaire = Questionnaire::find($questionnaire_id);

        $questionnaire->fill([
            'name' => $request->title,
            'published_at' => $request->published_at,
            'disabled_at' => $request->disabled_at,
            'thumb' => $request->thumbnail,
            'associate' => isset($request->associate) ? 1 : 0,
            'status_id' => 0
        ]);

        $questionnaire->save();

        return redirect()->route('questionnaires.index')->with('message', 'Enquete editada com sucesso!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Questionnaire $questionnaire
     * @return RedirectResponse
     */
    public function destroy(Questionnaire $questionnaire): RedirectResponse
    {
        $questionnaire->delete();

        return redirect()->route('questionnaires.index')->with('message', 'Enquete excluida com sucesso!');
    }

    /**
     * @param Request $request
     * @param Questionnaire $questionnaire
     * @return JsonResponse
     */
    public function changeStatus(Questionnaire $questionnaire): JsonResponse
    {


        if ($questionnaire['status_id'] === 3 || $questionnaire['status_id'] === 2) {
            $questionnaire->update(['status_id' => 0]);
        }

        if ($questionnaire['status_id'] === 1) {
            $questionnaire->update(['status_id' => 3]);
        }

        $questionnaire->save();

        return response()->json(['success' => 'Status changed successfully']);
    }
}

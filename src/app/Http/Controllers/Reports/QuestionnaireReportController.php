<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Post;
use App\Models\Questionnaire;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\QuestionnairesExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class QuestionnaireReportController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $allQuestionnaires = Questionnaire::all();
        $questionnaires = [];
        for ($x = 0; $x < sizeof($allQuestionnaires); $x++) {
            $questionnaires[$x] = [
                'id' => $allQuestionnaires[$x]->id,
                'name' => $allQuestionnaires[$x]->name,
                'type' => (sizeof(Post::getPostForQuestionnaire($allQuestionnaires[$x]->id)) > 0 ? 'Treinamento' : 'Enquetes')
            ];
        }
        return view('reports.questionnaires.index', compact('questionnaires'));
    }

    /**
     * @param Questionnaire $questionnaire
     * @param Request $request
     * @return Factory|Application|View
     */
    public function show(Questionnaire $questionnaire, Request $request): View|Factory|Application
    {
        $posts = Post::getPostForQuestionnaire($questionnaire->id);
        if (sizeof($posts) <= 0) {
            return $this->getQuizData($questionnaire, $request);
        }
        return $this->getTrainningData($questionnaire, $posts, $request);
    }

    /**
     * @param Questionnaire $questionnaire
     * @param Request|null $request
     * @return Factory|View|Application
     */
    public function getQuizData(Questionnaire $questionnaire, Request $request = null): Factory|View|Application
    {
        $users = User::all();
        $companies = Company::all();

        $views = $questionnaire->views()->count();

        $data = $this->getData($questionnaire, $request);

        return view('reports.questionnaires.quiz', compact('questionnaire', 'data', 'views', 'users', 'companies'));
    }

    /**
     * @param Questionnaire $questionnaire
     * @param $posts
     * @param Request|null $request
     * @return Factory|View|Application
     */
    public function getTrainningData(Questionnaire $questionnaire, $posts, Request $request = null): Factory|View|Application
    {
        $users = User::all();
        $companies = Company::all();

        $extras = $posts[0]->extras;
        $views = $questionnaire->views()->count();
        $data = $this->getData($questionnaire, $request, $extras['goal']);

        $fail = 0;
        $pass = 0;
        foreach ($data as $users) {
            if ($users['status'] == 'Reprovado') {
                $fail++;
            } else {
                $pass++;
            }
        }

        return view('reports.questionnaires.trainning', compact('questionnaire', 'data', 'views', 'users', 'fail', 'pass', 'companies', 'extras'));
    }

    /**
     * @param Questionnaire $questionnaire
     * @param Request $request
     * @return JsonResponse
     */
    public function filter(Questionnaire $questionnaire, Request $request): JsonResponse
    {
        $data = $this->getData($questionnaire, $request, $request['goal']);

        return response()->json(['data' => $data]);
    }

    /**
     * @param $questionnaire
     * @param $request
     * @param int $goal
     * @return array
     */
    public function getData($questionnaire, $request, int $goal = 0): array
    {
        $dateStart = Carbon::now()->subDays(7);
        $dateEnd = new Carbon();

        if ($request) {
            $start = $request->query('start');
            $end = $request->query('end');

            if ($start !== 'last_7_days') {
                $dateStart = new Carbon($start . ' 00:00:00');
                $dateEnd = new Carbon($end . ' 23:59:59');
            }
        }
        $questions = $questionnaire->questions()->get();
        $data = [];

        foreach ($questions as $question) {
            $answers = $question->answers()->whereBetween('created_at', [$dateStart, $dateEnd])->get();
            foreach ($answers as $answer) {
                if (!isset($data[$answer['user_id']])) {
                    $data[$answer['user_id']] = [
                        'name' => $answer->user->name,
                        'username' => $answer->user->username,
                        'date' => $answer->created_at,
                        'company' => $answer->user->company->name ?? '',
                        'corrects' => 0,
                        'wrongs' => 0,
                        'status' => ''
                    ];
                }

                $data[$answer['user_id']]['corrects'] += $answer['correct'];
                $data[$answer['user_id']]['wrongs'] += ($answer['correct'] == 1 ? 0 : 1);
                if ($goal > 0) {
                    $data[$answer['user_id']]['status'] = ($data[$answer['user_id']]['corrects'] >= $goal ? 'Aprovado' : 'Reprovado');
                }
            }
        }

        return $data;
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function export(Request $request): BinaryFileResponse
    {
        $data = $request->data;

        return Excel::download(new QuestionnairesExport(collect($data)), 'enquetes.xlsx');
    }
}

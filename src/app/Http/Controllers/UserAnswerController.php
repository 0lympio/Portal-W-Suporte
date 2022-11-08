<?php

namespace App\Http\Controllers;

use App\Models\QuestionnaireAnswered;
use App\Models\UserAnswer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAnswerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->all();
        $questions = $data['questions'];

        foreach ($questions as $question) {
            $userAnswer = [
                "user_id" => auth()->user()->id,
                "question_id" => $question['id'],
                "answer" => json_encode($question['answer']),
                "correct" => $question['isCorrect'] ?? 0,
            ];

            UserAnswer::create($userAnswer);
        }

        QuestionnaireAnswered::create([
            'user_id' => auth()->user()->id,
            'questionnaire_id' => $data['questionnaire_id'],
        ]);

        return response()->json(['success' => 'Replied successfully.']);
    }
}

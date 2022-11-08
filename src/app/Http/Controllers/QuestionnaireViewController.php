<?php

namespace App\Http\Controllers;

use App\Models\QuestionnaireView;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuestionnaireViewController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {

        $user_id = auth()->user()->id;
        $questionnaire_id = $request->questionnaire_id;

        $questionnaire_view = QuestionnaireView::where('user_id', $user_id)->where('questionnaire_id', $questionnaire_id)->get();

        if ($questionnaire_view->isEmpty()) {
            QuestionnaireView::create([
                'user_id' => $user_id,
                'questionnaire_id' => $questionnaire_id
            ]);
        }

        return response()->json(['success' => 'Viewed successfully.']);
    }
}

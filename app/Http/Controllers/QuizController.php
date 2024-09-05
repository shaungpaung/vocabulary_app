<?php

namespace App\Http\Controllers;

use App\Models\Vocabulary;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function getQuizGenerator(Request $request)
    {
        $quizCount = 10;

        $vocabulary = Vocabulary::where('is_revised', true)
            ->inRandomOrder()
            ->limit($quizCount)
            ->get();

        $quizzes = $vocabulary->map(function ($quiz) {
            $incorrectAnswers = Vocabulary::where('id', '!=', $quiz->id)
                ->whereNotNull('title')
                ->pluck('title')
                ->random(3)
                ->toArray();

            return [
                "question" => $quiz->definition,
                "correct_answer" => $quiz->title,
                "incorrect_answers" => $incorrectAnswers
            ];
        });

        return response()->json($quizzes);
    }


}
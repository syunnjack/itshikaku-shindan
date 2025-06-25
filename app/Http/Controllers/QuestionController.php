<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
{
    $question = Question::inRandomOrder()->first();
    return view('quiz.index', compact('question'));
}

public function check(Request $request)
{
    $question = \App\Models\Question::find($request->id);
    $userAnswer = $request->answer;
    //turn view('quiz.result', compact('isCorrect', 'question'));
    // 正解データが「〇」または「×」で登録されている前提
    $isCorrect = $question && $question->answer === $userAnswer;

    return view('quiz.result', compact('isCorrect', 'question', 'userAnswer'));
}
}

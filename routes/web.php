<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/quiz', [QuestionController::class, 'index']);
Route::post('/quiz/check', [QuestionController::class, 'check']);

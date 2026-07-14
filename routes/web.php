<?php

use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $certifications = collect(config('certifications'))->sortBy('rank')->all();

    return view('welcome', compact('certifications'));
})->name('home');

Route::get('/about', function () {
    $certifications = collect(config('certifications'))->sortBy('rank')->all();

    return view('about', compact('certifications'));
})->name('about');

Route::get('/membership', function () {
    $certifications = collect(config('certifications'))->sortBy('rank')->all();
    $certificationSlug = request('certification', 'it-passport');
    $currentCertification = $certifications[$certificationSlug] ?? $certifications['it-passport'];
    $answeredCount = (int) session('quiz_answered_count', 0);
    $freeQuestionLimit = config('membership.free_question_limit');

    return view('membership', compact('certifications', 'currentCertification', 'answeredCount', 'freeQuestionLimit'));
})->name('membership');

Route::get('/sitemap.xml', function () {
    $urls = [
        ['loc' => route('home'), 'priority' => '1.0'],
        ['loc' => route('about'), 'priority' => '0.7'],
        ['loc' => url('/llms.txt'), 'priority' => '0.4'],
    ];

    foreach (collect(config('certifications'))->sortBy('rank') as $slug => $certification) {
        $urls[] = [
            'loc' => route('quiz.index', ['certification' => $slug]),
            'priority' => $certification['rank'] === 1 ? '0.9' : '0.8',
        ];
    }

    return response()
        ->view('sitemap', ['urls' => $urls])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/robots.txt', function () {
    return response(
        "User-agent: *\nAllow: /\n\nSitemap: " . route('sitemap') . "\n",
        200,
        ['Content-Type' => 'text/plain; charset=UTF-8']
    );
})->name('robots');

Route::get('/{verificationFile}', function (string $verificationFile) {
    abort_unless($verificationFile === config('services.google_search_console.html_file'), 404);

    return response(
        config('services.google_search_console.html_content', ''),
        200,
        ['Content-Type' => 'text/html; charset=UTF-8']
    );
})->where('verificationFile', 'google[0-9a-zA-Z_-]+\.html')->name('google.search-console.verify');

Route::get('/llms.txt', function () {
    return response()
        ->view('llms')
        ->header('Content-Type', 'text/plain; charset=UTF-8');
})->name('llms');

Route::get('/quiz/{certification?}', [QuestionController::class, 'index'])->name('quiz.index');
Route::post('/quiz/{certification?}/check', [QuestionController::class, 'check'])->name('quiz.check');

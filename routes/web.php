<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\CertificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $certifications = collect(config('certifications'))->sortBy('rank')->all();

    return view('welcome', compact('certifications'));
})->name('home');

Route::get('/about', function () {
    $certifications = collect(config('certifications'))->sortBy('rank')->all();

    return view('about', compact('certifications'));
})->name('about');

Route::view('/terms', 'legal.terms')->name('legal.terms');
Route::view('/privacy', 'legal.privacy')->name('legal.privacy');
Route::view('/commercial-transactions', 'legal.commercial')->name('legal.commercial');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/billing/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
Route::get('/billing/success', [PaymentController::class, 'success'])->name('payment.success');
Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');
Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
Route::patch('/admin/users/{user}/paid-member', [AdminController::class, 'togglePaidMember'])->name('admin.users.toggle-paid-member');

Route::get('/membership', function () {
    $certifications = collect(config('certifications'))->sortBy('rank')->all();
    $certificationSlug = request('certification', 'it-passport');
    $currentCertification = $certifications[$certificationSlug] ?? $certifications['it-passport'];
    $user = request()->user();
    $answeredCount = (int) ($user?->free_questions_answered ?? 0);
    $freeQuestionLimit = config('membership.free_question_limit');
    $isPaidMember = (bool) (config('membership.force_paid_member') || ($user?->is_paid_member ?? false));

    return view('membership', compact(
        'certifications',
        'currentCertification',
        'answeredCount',
        'freeQuestionLimit',
        'isPaidMember'
    ));
})->name('membership');

Route::get('/sitemap.xml', function () {
    $urls = [
        ['loc' => route('home'), 'priority' => '1.0'],
        ['loc' => route('about'), 'priority' => '0.7'],
        ['loc' => route('legal.terms'), 'priority' => '0.3'],
        ['loc' => route('legal.privacy'), 'priority' => '0.3'],
        ['loc' => route('legal.commercial'), 'priority' => '0.3'],
        ['loc' => url('/llms.txt'), 'priority' => '0.4'],
    ];

    foreach (collect(config('certifications'))->sortBy('rank') as $slug => $certification) {
        $urls[] = [
            'loc' => route('certifications.show', ['certification' => $slug]),
            'priority' => $certification['rank'] === 1 ? '0.9' : '0.8',
        ];
        $urls[] = [
            'loc' => route('quiz.index', ['certification' => $slug]),
            'priority' => '0.6',
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
Route::get('/review/{certification?}', [QuestionController::class, 'review'])->name('quiz.review');
Route::post('/quiz/{certification?}/check', [QuestionController::class, 'check'])->name('quiz.check');
Route::get('/certifications/{certification}', [CertificationController::class, 'show'])->name('certifications.show');

<?php

namespace App\Http\Controllers;

use App\Models\QuestionAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()
                ->route('login')
                ->with('status', '学習ダッシュボードを見るにはログインしてください。');
        }

        $certifications = collect(config('certifications'))->sortBy('rank');
        $attempts = QuestionAttempt::query()
            ->where('user_id', $user->id)
            ->with('question')
            ->latest()
            ->get();

        $stats = $this->buildCertificationStats($certifications, $attempts);
        $totalAttempts = $attempts->count();
        $correctAttempts = $attempts->where('is_correct', true)->count();
        $accuracyRate = $totalAttempts > 0 ? round($correctAttempts / $totalAttempts * 100) : 0;
        $incorrectReviewCount = $attempts->where('is_correct', false)->pluck('question_id')->unique()->count();
        $dueReviewCount = $attempts
            ->filter(fn (QuestionAttempt $attempt) => $attempt->review_due_at !== null && $attempt->review_due_at->isPast())
            ->pluck('question_id')
            ->unique()
            ->count();
        $recentAttempts = $attempts->take(10);

        return view('dashboard', compact(
            'user',
            'stats',
            'totalAttempts',
            'correctAttempts',
            'accuracyRate',
            'incorrectReviewCount',
            'dueReviewCount',
            'recentAttempts'
        ));
    }

    private function buildCertificationStats(Collection $certifications, Collection $attempts): Collection
    {
        return $certifications->map(function (array $certification, string $slug) use ($attempts) {
            $certificationAttempts = $attempts->where('certification_slug', $slug);
            $attemptCount = $certificationAttempts->count();
            $correctCount = $certificationAttempts->where('is_correct', true)->count();
            $incorrectCount = $certificationAttempts->where('is_correct', false)->pluck('question_id')->unique()->count();
            $dueReviewCount = $certificationAttempts
                ->filter(fn (QuestionAttempt $attempt) => $attempt->review_due_at !== null && $attempt->review_due_at->isPast())
                ->pluck('question_id')
                ->unique()
                ->count();

            return [
                'slug' => $slug,
                'name' => $certification['name'],
                'short_name' => $certification['short_name'],
                'category' => $certification['category'],
                'attempt_count' => $attemptCount,
                'correct_count' => $correctCount,
                'accuracy_rate' => $attemptCount > 0 ? round($correctCount / $attemptCount * 100) : null,
                'incorrect_review_count' => $incorrectCount,
                'due_review_count' => $dueReviewCount,
            ];
        })->values();
    }
}

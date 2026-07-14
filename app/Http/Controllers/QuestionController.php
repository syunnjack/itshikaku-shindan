<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuestionController extends Controller
{
    public function index(Request $request, ?string $certification = null): View|RedirectResponse
    {
        $certifications = $this->certifications();
        $currentSlug = $this->resolveCertificationSlug($certification);
        $currentCertification = $certifications[$currentSlug];

        $freeQuestionLimit = $this->freeQuestionLimit();
        $answeredCount = $request->user() ? $this->answeredCount($request) : 0;
        $isPaidMember = $request->user() ? $this->isPaidMember($request) : false;
        $remainingFreeQuestions = max(0, $freeQuestionLimit - $answeredCount);

        if ($request->user() && ! $isPaidMember && $remainingFreeQuestions === 0) {
            return redirect()
                ->route('membership', ['certification' => $currentSlug])
                ->with('status', '無料で回答できる5問に到達しました。有料会員になると学習を継続できます。');
        }

        $question = Question::where('certification_slug', $currentSlug)
            ->inRandomOrder()
            ->first();

        return view('quiz.index', compact(
            'certifications',
            'currentSlug',
            'currentCertification',
            'question',
            'answeredCount',
            'freeQuestionLimit',
            'remainingFreeQuestions',
            'isPaidMember'
        ));
    }

    public function check(Request $request, ?string $certification = null): View|RedirectResponse
    {
        $certifications = $this->certifications();
        $currentSlug = $this->resolveCertificationSlug($certification);
        $currentCertification = $certifications[$currentSlug];

        if (! $request->user()) {
            return redirect()
                ->route('login')
                ->with('status', '回答するにはログインまたは新規登録してください。');
        }

        $answeredCount = $this->answeredCount($request);
        $freeQuestionLimit = $this->freeQuestionLimit();
        $isPaidMember = $this->isPaidMember($request);

        $validated = $request->validate([
            'id' => ['required', 'integer', 'exists:questions,id'],
            'answer' => ['required', 'string'],
        ]);

        if (! $isPaidMember && $answeredCount >= $freeQuestionLimit) {
            return redirect()
                ->route('membership', ['certification' => $currentSlug])
                ->with('status', '無料で回答できる5問に到達しました。有料会員になると学習を継続できます。');
        }

        $question = Question::where('certification_slug', $currentSlug)
            ->findOrFail($validated['id']);
        $userAnswer = $validated['answer'];
        $isCorrect = $question->answer === $userAnswer;

        if (! $isPaidMember) {
            $request->user()->increment('free_questions_answered');
            $answeredCount++;
        }

        $remainingFreeQuestions = max(0, $freeQuestionLimit - $answeredCount);
        $hasReachedFreeLimit = ! $isPaidMember && $remainingFreeQuestions === 0;

        return view('quiz.result', compact(
            'certifications',
            'currentSlug',
            'currentCertification',
            'isCorrect',
            'question',
            'userAnswer',
            'answeredCount',
            'freeQuestionLimit',
            'remainingFreeQuestions',
            'isPaidMember',
            'hasReachedFreeLimit'
        ));
    }

    private function certifications(): array
    {
        return collect(config('certifications'))
            ->sortBy('rank')
            ->all();
    }

    private function resolveCertificationSlug(?string $certification): string
    {
        $certifications = $this->certifications();
        $default = array_key_first($certifications);

        abort_unless($default !== null, 404);

        if ($certification === null) {
            return $default;
        }

        abort_unless(array_key_exists($certification, $certifications), 404);

        return $certification;
    }

    private function answeredCount(Request $request): int
    {
        return (int) $request->user()->free_questions_answered;
    }

    private function freeQuestionLimit(): int
    {
        return (int) config('membership.free_question_limit', 5);
    }

    private function isPaidMember(Request $request): bool
    {
        return (bool) (config('membership.force_paid_member') || $request->user()->is_paid_member);
    }
}

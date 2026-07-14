<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(?string $certification = null)
    {
        $certifications = $this->certifications();
        $currentSlug = $this->resolveCertificationSlug($certification);
        $currentCertification = $certifications[$currentSlug];
        $question = Question::where('certification_slug', $currentSlug)
            ->inRandomOrder()
            ->first();

        return view('quiz.index', compact('certifications', 'currentSlug', 'currentCertification', 'question'));
    }

    public function check(Request $request, ?string $certification = null)
    {
        $certifications = $this->certifications();
        $currentSlug = $this->resolveCertificationSlug($certification);
        $currentCertification = $certifications[$currentSlug];

        $validated = $request->validate([
            'id' => ['required', 'integer', 'exists:questions,id'],
            'answer' => ['required', 'string'],
        ]);

        $question = Question::where('certification_slug', $currentSlug)
            ->findOrFail($validated['id']);
        $userAnswer = $validated['answer'];
        $isCorrect = $question->answer === $userAnswer;

        return view('quiz.result', compact('certifications', 'currentSlug', 'currentCertification', 'isCorrect', 'question', 'userAnswer'));
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
}

<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminQuestionController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeAdmin($request);

        $certifications = collect(config('certifications'))->sortBy('rank')->all();
        $certification = $request->query('certification');
        $questions = Question::query()
            ->when($certification, fn ($query) => $query->where('certification_slug', $certification))
            ->orderBy('certification_slug')
            ->orderBy('sort_order')
            ->paginate(50)
            ->withQueryString();

        return view('admin.questions.index', compact('questions', 'certifications', 'certification'));
    }

    public function create(Request $request): View
    {
        $this->authorizeAdmin($request);

        $certifications = collect(config('certifications'))->sortBy('rank')->all();
        $selectedCertification = $request->query('certification', array_key_first($certifications));

        return view('admin.questions.form', [
            'question' => new Question([
                'certification_slug' => $selectedCertification,
                'format' => 'true_false',
                'answer' => '○',
                'sort_order' => 0,
            ]),
            'certifications' => $certifications,
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $validated = $this->validatedQuestion($request);
        $validated['certification_name'] = config('certifications.' . $validated['certification_slug'] . '.name');

        Question::create($validated);

        return redirect()
            ->route('admin.questions.index', ['certification' => $validated['certification_slug']])
            ->with('status', '問題を追加しました。');
    }

    public function edit(Request $request, Question $question): View
    {
        $this->authorizeAdmin($request);

        return view('admin.questions.form', [
            'question' => $question,
            'certifications' => collect(config('certifications'))->sortBy('rank')->all(),
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Question $question): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $validated = $this->validatedQuestion($request);
        $validated['certification_name'] = config('certifications.' . $validated['certification_slug'] . '.name');
        $question->update($validated);

        return redirect()
            ->route('admin.questions.index', ['certification' => $validated['certification_slug']])
            ->with('status', '問題を更新しました。');
    }

    public function destroy(Request $request, Question $question): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $certification = $question->certification_slug;
        $question->delete();

        return redirect()
            ->route('admin.questions.index', ['certification' => $certification])
            ->with('status', '問題を削除しました。');
    }

    private function validatedQuestion(Request $request): array
    {
        $validated = $request->validate([
            'certification_slug' => ['required', 'string', 'in:' . implode(',', array_keys(config('certifications')))],
            'sort_order' => ['required', 'integer', 'min:0'],
            'format' => ['required', 'string', 'in:true_false,multiple_choice'],
            'is_trial' => ['nullable', 'boolean'],
            'question' => ['required', 'string', 'max:1000'],
            'answer' => ['required', 'string', 'in:○,×,ア,イ,ウ,エ'],
            'choice_a' => ['nullable', 'string', 'max:500'],
            'choice_i' => ['nullable', 'string', 'max:500'],
            'choice_u' => ['nullable', 'string', 'max:500'],
            'choice_e' => ['nullable', 'string', 'max:500'],
            'explanation' => ['nullable', 'string', 'max:4000'],
        ]);

        $validated['is_trial'] = $request->boolean('is_trial');

        if ($validated['format'] === 'multiple_choice') {
            $choices = [
                'ア' => $validated['choice_a'] ?? null,
                'イ' => $validated['choice_i'] ?? null,
                'ウ' => $validated['choice_u'] ?? null,
                'エ' => $validated['choice_e'] ?? null,
            ];

            if (collect($choices)->contains(fn ($choice) => blank($choice))) {
                throw ValidationException::withMessages([
                    'choices' => '選択式問題ではア・イ・ウ・エの選択肢をすべて入力してください。',
                ]);
            }

            if (! array_key_exists($validated['answer'], $choices)) {
                throw ValidationException::withMessages([
                    'answer' => '選択式問題の正解はア・イ・ウ・エから選んでください。',
                ]);
            }

            $validated['choices'] = $choices;
        } else {
            if (! in_array($validated['answer'], ['○', '×'], true)) {
                throw ValidationException::withMessages([
                    'answer' => '○×問題の正解は○または×を選んでください。',
                ]);
            }

            $validated['choices'] = null;
        }

        unset($validated['choice_a'], $validated['choice_i'], $validated['choice_u'], $validated['choice_e']);

        return $validated;
    }

    private function authorizeAdmin(Request $request): void
    {
        $adminEmail = config('membership.admin_email');

        abort_unless($request->user() && $adminEmail && $request->user()->email === $adminEmail, 403);
    }
}

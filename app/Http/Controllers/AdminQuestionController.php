<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        return $request->validate([
            'certification_slug' => ['required', 'string', 'in:' . implode(',', array_keys(config('certifications')))],
            'sort_order' => ['required', 'integer', 'min:0'],
            'question' => ['required', 'string', 'max:1000'],
            'answer' => ['required', 'string', 'in:○,×'],
            'explanation' => ['nullable', 'string', 'max:2000'],
        ]);
    }

    private function authorizeAdmin(Request $request): void
    {
        $adminEmail = config('membership.admin_email');

        abort_unless($request->user() && $adminEmail && $request->user()->email === $adminEmail, 403);
    }
}

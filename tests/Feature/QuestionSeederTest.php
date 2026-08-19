<?php

namespace Tests\Feature;

use App\Models\Question;
use Database\Seeders\QuestionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_certification_has_questions_and_a_free_trial(): void
    {
        $this->seed(QuestionSeeder::class);

        $slugs = array_keys(config('certifications'));
        $questions = Question::all();

        $this->assertSame(count($slugs), $questions->pluck('certification_slug')->unique()->count());

        foreach ($slugs as $slug) {
            $forCertification = $questions->where('certification_slug', $slug);

            // 無料体験として案内している5問を、資格ごとに必ず用意する。
            $this->assertGreaterThanOrEqual(
                5,
                $forCertification->where('is_trial', true)->count(),
                "{$slug} の無料体験問題が5問に足りません。"
            );
            $this->assertGreaterThanOrEqual(
                10,
                $forCertification->count(),
                "{$slug} の問題数が少なすぎます。"
            );
        }
    }

    public function test_paid_questions_have_premium_explanations(): void
    {
        $this->seed(QuestionSeeder::class);

        $paidQuestions = Question::where('is_trial', false)->get();

        $this->assertNotEmpty($paidQuestions);

        $withoutGuidance = $paidQuestions
            ->reject(fn (Question $question) => str_contains($question->explanation, '本試験の見抜き方:'))
            ->pluck('certification_slug')
            ->unique()
            ->all();

        $this->assertSame([], $withoutGuidance, '有料向けの解説が付いていない問題があります。');
    }

    public function test_multiple_choice_questions_have_the_answer_among_their_choices(): void
    {
        $this->seed(QuestionSeeder::class);

        $broken = Question::where('format', 'multiple_choice')->get()
            ->reject(fn (Question $question) => is_array($question->choices)
                && array_key_exists($question->answer, $question->choices))
            ->pluck('question')
            ->all();

        $this->assertSame([], $broken, '正解の記号が選択肢に無い問題があります。');
    }
}

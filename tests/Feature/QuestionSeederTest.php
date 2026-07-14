<?php

namespace Tests\Feature;

use App\Models\Question;
use Database\Seeders\QuestionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_paid_questions_have_premium_explanations_for_all_certifications(): void
    {
        $this->seed(QuestionSeeder::class);

        $paidQuestions = Question::where('is_trial', false)->get();
        $certificationCount = $paidQuestions
            ->pluck('certification_slug')
            ->unique()
            ->count();
        $enrichedCount = $paidQuestions
            ->filter(fn (Question $question) => str_contains($question->explanation, '本試験の見抜き方:'))
            ->count();

        $this->assertSame(15, $certificationCount);
        $this->assertSame(75, $paidQuestions->count());
        $this->assertSame(75, $enrichedCount);
    }
}

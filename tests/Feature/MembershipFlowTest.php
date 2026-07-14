<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\QuestionAttempt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_certification_page(): void
    {
        Question::create([
            'certification_slug' => 'it-passport',
            'certification_name' => 'ITパスポート試験',
            'sort_order' => 1,
            'question' => 'CPUは中央処理装置である。',
            'answer' => '○',
            'explanation' => 'CPUはCentral Processing Unitの略です。',
        ]);

        $response = $this->get('/certifications/it-passport');

        $response->assertOk();
        $response->assertSee('ITパスポート試験');
        $response->assertSee('無料で問題を解く');
    }

    public function test_free_member_is_redirected_after_five_answers(): void
    {
        $user = User::factory()->create([
            'free_questions_answered' => 5,
            'is_paid_member' => false,
        ]);

        $response = $this->actingAs($user)->get('/quiz/it-passport');

        $response->assertRedirect('/membership?certification=it-passport');
    }

    public function test_paid_member_can_continue_after_five_answers(): void
    {
        $user = User::factory()->create([
            'free_questions_answered' => 5,
            'is_paid_member' => true,
        ]);

        Question::create([
            'certification_slug' => 'it-passport',
            'certification_name' => 'ITパスポート試験',
            'sort_order' => 1,
            'question' => 'CPUは中央処理装置である。',
            'answer' => '○',
            'explanation' => 'CPUはCentral Processing Unitの略です。',
        ]);

        $response = $this->actingAs($user)->get('/quiz/it-passport');

        $response->assertOk();
        $response->assertSee('有料会員');
    }

    public function test_free_member_sees_trial_multiple_choice_question_first(): void
    {
        $user = User::factory()->create();

        Question::create([
            'certification_slug' => 'it-passport',
            'certification_name' => 'ITパスポート試験',
            'sort_order' => 1,
            'question' => '通常の○×問題です。',
            'answer' => '○',
            'explanation' => '通常問題です。',
        ]);

        Question::create([
            'certification_slug' => 'it-passport',
            'certification_name' => 'ITパスポート試験',
            'sort_order' => 101,
            'format' => 'multiple_choice',
            'choices' => [
                'ア' => '選択肢A',
                'イ' => '選択肢B',
                'ウ' => '選択肢C',
                'エ' => '選択肢D',
            ],
            'is_trial' => true,
            'question' => '本試験形式の無料体験問題です。',
            'answer' => 'ウ',
            'explanation' => '詳しい解説です。',
        ]);

        $response = $this->actingAs($user)->get('/quiz/it-passport');

        $response->assertOk();
        $response->assertSee('本試験形式の無料体験問題です。');
        $response->assertSee('選択肢C');
    }

    public function test_answer_creates_review_schedule(): void
    {
        $user = User::factory()->create();
        $question = Question::create([
            'certification_slug' => 'it-passport',
            'certification_name' => 'ITパスポート試験',
            'sort_order' => 1,
            'question' => 'CPUは中央処理装置である。',
            'answer' => '○',
            'explanation' => 'CPUはCentral Processing Unitの略です。',
        ]);

        $response = $this->actingAs($user)->post('/quiz/it-passport/check', [
            'id' => $question->id,
            'answer' => '×',
        ]);

        $response->assertOk();
        $attempt = QuestionAttempt::first();

        $this->assertFalse($attempt->is_correct);
        $this->assertSame(1, $attempt->review_interval_days);
        $this->assertNotNull($attempt->review_due_at);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\QuestionAttempt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_user_can_view_learning_dashboard(): void
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

        QuestionAttempt::create([
            'user_id' => $user->id,
            'question_id' => $question->id,
            'certification_slug' => 'it-passport',
            'user_answer' => '×',
            'correct_answer' => '○',
            'is_correct' => false,
            'review_interval_days' => 1,
            'review_due_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('学習ダッシュボード');
        $response->assertSee('復習対象');
        $response->assertSee('今日の復習');
        $response->assertSee('ITパスポート');
    }
}

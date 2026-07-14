<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminQuestionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_question(): void
    {
        config(['membership.admin_email' => 'admin@example.com']);
        $admin = User::factory()->create(['email' => 'admin@example.com']);

        $response = $this->actingAs($admin)->post('/admin/questions', [
            'certification_slug' => 'it-passport',
            'sort_order' => 99,
            'format' => 'multiple_choice',
            'is_trial' => '1',
            'question' => 'テスト問題です。',
            'choice_a' => '選択肢ア',
            'choice_i' => '選択肢イ',
            'choice_u' => '選択肢ウ',
            'choice_e' => '選択肢エ',
            'answer' => 'ウ',
            'explanation' => 'テスト解説です。詳しく理解できます。',
        ]);

        $response->assertRedirect('/admin/questions?certification=it-passport');
        $this->assertDatabaseHas('questions', [
            'certification_slug' => 'it-passport',
            'question' => 'テスト問題です。',
            'answer' => 'ウ',
            'format' => 'multiple_choice',
            'is_trial' => true,
        ]);
    }

    public function test_non_admin_cannot_access_question_admin(): void
    {
        $user = User::factory()->create();
        Question::create([
            'certification_slug' => 'it-passport',
            'certification_name' => 'ITパスポート試験',
            'sort_order' => 1,
            'question' => 'CPUは中央処理装置である。',
            'answer' => '○',
            'explanation' => 'CPUはCentral Processing Unitの略です。',
        ]);

        $response = $this->actingAs($user)->get('/admin/questions');

        $response->assertForbidden();
    }
}

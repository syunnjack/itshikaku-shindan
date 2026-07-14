<?php

namespace Tests\Feature;

use App\Models\Question;
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
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->string('certification_slug')->index();
            $table->string('user_answer', 8);
            $table->string('correct_answer', 8);
            $table->boolean('is_correct')->index();
            $table->timestamps();

            $table->index(['user_id', 'certification_slug', 'is_correct']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_attempts');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('question_attempts', function (Blueprint $table) {
            $table->unsignedSmallInteger('review_interval_days')->default(1)->after('is_correct');
            $table->timestamp('review_due_at')->nullable()->after('review_interval_days')->index();
        });
    }

    public function down(): void
    {
        Schema::table('question_attempts', function (Blueprint $table) {
            $table->dropColumn(['review_interval_days', 'review_due_at']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('format')->default('true_false')->after('sort_order');
            $table->json('choices')->nullable()->after('format');
            $table->boolean('is_trial')->default(false)->after('choices')->index();
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['format', 'choices', 'is_trial']);
        });
    }
};

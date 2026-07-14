<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->string('certification_slug')->default('it-passport')->after('id');
            $table->string('certification_name')->nullable()->after('certification_slug');
            $table->unsignedSmallInteger('sort_order')->default(0)->after('certification_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['certification_slug', 'certification_name', 'sort_order']);
        });
    }
};

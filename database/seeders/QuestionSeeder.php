<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('questions')->insert([
    ['question' => '日本の首都は？', 'answer' => '東京'],
    ['question' => '1 + 1 = ?', 'answer' => '2'],
]);
    }
}

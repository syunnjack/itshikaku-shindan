<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'certification_slug',
        'certification_name',
        'sort_order',
        'format',
        'choices',
        'is_trial',
        'question',
        'answer',
        'explanation',
    ];

    protected function casts(): array
    {
        return [
            'choices' => 'array',
            'is_trial' => 'boolean',
        ];
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuestionAttempt::class);
    }

    public function isMultipleChoice(): bool
    {
        return $this->format === 'multiple_choice' && is_array($this->choices);
    }

    public function answerLabel(?string $answer = null): string
    {
        $answer ??= $this->answer;

        if (! $this->isMultipleChoice()) {
            return $answer;
        }

        return $this->choices[$answer] ?? $answer;
    }
}

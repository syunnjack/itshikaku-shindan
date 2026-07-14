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
        'question',
        'answer',
        'explanation',
    ];

    public function attempts(): HasMany
    {
        return $this->hasMany(QuestionAttempt::class);
    }
}

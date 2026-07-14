<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'certification_slug',
        'certification_name',
        'sort_order',
        'question',
        'answer',
        'explanation',
    ];
}

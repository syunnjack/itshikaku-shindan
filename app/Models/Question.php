<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Question extends Model
{
    use HasFactory;

    // テーブル名が「question」の場合、明示的に指定
    //protected $table = 'question';

    /* ホワイトリスト方式：登録可能なカラムを指定
    protected $fillable = [
        'question',
        'answer',
    ];*/
}

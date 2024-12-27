<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'user_id']; // 대량할당 대상 선언
    // protected $guarded = ['id']; // id 빼고 모두
}

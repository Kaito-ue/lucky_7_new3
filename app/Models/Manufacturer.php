<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        // 他にも必要なカラムがあればここに追加する
    ];

    // 他のモデルとの関連を定義する場合はここに記述する
}
<?php

// app\Models\Post.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Post extends Model
{
    use HasFactory;
    use Sortable;

    protected $fillable = [
        'title',
        'content',
        // 他の属性を追加
    ];

    public $sortable = [
        'title',
        'created_at',
        // ソート可能な属性を追加
    ];

    // 他のリレーションやメソッドを追加
}

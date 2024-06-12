<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'product_id', 'quantity', 'amount', 'customer_id', 'created_at'
    ];

    // 追加の関連メソッドやバリデーションをここに追加することもできます
}

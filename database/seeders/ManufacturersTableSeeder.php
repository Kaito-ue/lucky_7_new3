<?php

namespace Database\Seeders; // ここが追加された修正箇所

use Illuminate\Database\Seeder;
use App\Models\Manufacturer;

class ManufacturersTableSeeder extends Seeder
{
    public function run()
    {
        // メーカー情報を挿入する
        Manufacturer::create(['name' => 'Coca-Cola']);
        Manufacturer::create(['name' => 'サントリー']);
        // 他のメーカー情報もここに追加する
    }
}

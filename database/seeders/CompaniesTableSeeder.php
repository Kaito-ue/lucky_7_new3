<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = [
            [
                'company_name' => 'Coca-Cola',
                'street_address' => '123 Main Street',
                'representative_name' => 'John Doe', // 代表者の名前を指定
            ],
            [
                'company_name' => 'サントリー',
                'street_address' => '456 Park Avenue',
                'representative_name' => 'Jane Smith', // 代表者の名前を指定
            ],
            [
                'company_name' => 'ペプシ',
                'street_address' => '789 Elm Street',
                'representative_name' => 'Robert Brown', // 代表者の名前を指定
            ],
            [
                'company_name' => 'キリン',
                'street_address' => '101 Maple Street',
                'representative_name' => 'Emily Johnson', // 代表者の名前を指定
            ],
            // 他のデータも追加できます
        ];

        // 一括挿入
        Company::insert($companies);
    }
}

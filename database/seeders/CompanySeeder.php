<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class CompanySeeder extends Seeder
{
    public function run()
    {
        DB::table('companies')->insert([
            [
                'company_name' => 'サントリー',
                'street_address' => '東京都港区台場',
                'representative_name' => '佐藤 太郎',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'company_name' => 'boss',
                'street_address' => '東京都渋谷区神宮前',
                'representative_name' => '鈴木 一郎',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'company_name' => 'キリン',
                'street_address' => '東京都中央区京橋',
                'representative_name' => '田中 次郎',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'company_name' => 'アサヒ',
                'street_address' => '東京都千代田区有楽町',
                'representative_name' => '山田 三郎',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'company_name' => 'コカコーラ',
                'street_address' => '東京都港区赤坂',
                'representative_name' => '中村 四郎',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
        ]);
    }
}


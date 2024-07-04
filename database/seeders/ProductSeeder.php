<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'comment' => '天然水',
                'company_id' => 5,
            
                'created_at' => now(),
                'image_path' => 'path/to/image1.jpg',
                'price' => 1200,
                'product_name' => '水',
                'stock' => 200,
                'updated_at' => now(),
            ],
            [
                'comment' => 'ブラックコーヒー',
                'company_id' => 4,
                'created_at' => now(),
                'image_path' => 'path/to/image2.jpg',
                'price' => 200,
                'product_name' => 'コーヒー',
                'stock' => 200,
                'updated_at' => now(),
            ],
            [
                'comment' => '炭酸飲料',
                'company_id' => 3,
                'created_at' => now(),
                'image_path' => 'path/to/image3.jpg',
                'price' => 200,
                'product_name' => 'コーラ',
                'stock' => 200,
                'updated_at' => now(),
            ],
            [
                'comment' => '中国茶',
                'company_id' => 2,
                'created_at' => now(),
                'image_path' => 'path/to/image4.jpg',
                'price' => 100,
                'product_name' => '烏龍茶',
                'stock' => 200,
                'updated_at' => now(),
            ],
            [
                'comment' => 'ミルクコーヒー',
                'company_id' => 1,
                'created_at' => now(),
                'image_path' => 'path/to/image5.jpg',
                'price' => 100,
                'product_name' => 'コーヒー',
                'stock' => 200,
                'updated_at' => now(),
            ],
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // テーブル名の指定
    protected $table = 'products';

    // マスアサインメントを可能にするフィールドの指定
    protected $fillable = [
        'product_name',
        'price',
        'stock',
        'company_id',
        'comment',
        'image_path',
    ];

    // Productモデルがsalesテーブルとリレーション関係を結ぶためのメソッド
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    // Productモデルがcompaniesテーブルとリレーション関係を結ぶためのメソッド
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

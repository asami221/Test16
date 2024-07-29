<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // このリクエストが常に許可されるようにtrueを返します。
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_name' => ['required', 'string', 'max:255'],
            'company_id' => ['required', 'exists:companies,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'comment' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'product_name.required' => '商品名は必須です。',
            'product_name.string' => '商品名は文字列でなければなりません。',
            'product_name.max' => '商品名は255文字以内で入力してください。',
            'company_id.required' => 'メーカー名は必須です。',
            'company_id.exists' => '選択したメーカー名は無効です。',
            'price.required' => '価格は必須です。',
            'price.numeric' => '価格は数値でなければなりません。',
            'price.min' => '価格は0以上でなければなりません。',
            'stock.required' => '在庫数は必須です。',
            'stock.integer' => '在庫数は整数でなければなりません。',
            'stock.min' => '在庫数は0以上でなければなりません。',
            'comment.string' => 'コメントは文字列でなければなりません。',
            'image.image' => '画像は有効な画像ファイルでなければなりません。',
            'image.mimes' => '画像の形式はjpeg, png, jpg, gif, svgのいずれかでなければなりません。',
            'image.max' => '画像のサイズは2048KB以内でなければなりません。',
        ];
    }
}

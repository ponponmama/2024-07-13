<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->role === 1;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           'shop_name' => 'required|string|max:255',
           'description' => 'required|string|max:255',
           'genre_name' => 'required|string|max:255',
           'area_name' => 'required|string|max:255',
           'image' => 'required|image|max:2048',
           'open_time' => 'required|string',
           'close_time' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'shop_name.required' => '店舗名は必須です。',
            'shop_name.string' => '店舗名は文字列である必要があります。',
            'shop_name.max' => '店舗名は255文字以内で入力してください。',
            'description.required' => '説明は必須です。',
            'description.string' => '説明は文字列である必要があります。',
            'description.max' => '説明は255文字以内で入力してください。',
            'genre_name.required' => 'ジャンル名は必須です。',
            'genre_name.string' => 'ジャンル名は文字列である必要があります。',
            'genre_name.max' => 'ジャンル名は255文字以内で入力してください。',
            'area_name.required' => 'エリア名は必須です。',
            'area_name.string' => 'エリア名は文字列である必要があります。',
            'area_name.max' => 'エリア名は255文字以内で入力してください。',
            'image.required' => '画像ファイルは必須です。',
            'image.image' => '画像ファイルを選択してください。',
            'image.max' => '画像ファイルは2MB以下である必要があります。',
            'open_time.required' => '開店時間は必須です。',
            'open_time.string' => '開店時間は文字列である必要があります。',
            'close_time.required' => '閉店時間は必須です。',
            'close_time.string' => '閉店時間は文字列である必要があります。',
        ];
    }
}

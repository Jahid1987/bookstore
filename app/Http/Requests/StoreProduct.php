<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProduct extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'title' => 'required|max:50',
            'slug' => 'required|unique:products',
            'description' => 'max:200',
            'price' => 'required|max:1024|numeric',
            'discount_price' => 'max:100',
            'status' => 'numeric',
            'thumbnail' => 'max:1024|mimes:jpg,png,jpeg,bmp,PNG',
        ];
    }
}

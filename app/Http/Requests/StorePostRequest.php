<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'       => [
                'required',
                'string',
                'max:255',
                Rule::unique('posts', 'title')
            ],
            'slug'        => [
                'nullable',
                'string',
                Rule::unique('posts', 'slug')
            ], // nullable jika ingin di-generate otomatis
            'category_id' => [
                'required',
                'exists:categories,id'
            ], // Memastikan kategori ada di DB
            'body'        => [
                'required',
                'string',
                'min:10'
            ],
            'image'       => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048'
            ], // Validasi file
        ];
    }
}

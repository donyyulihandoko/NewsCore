<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
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
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('posts', 'title')->ignore($this->post)
            ],

            // Aturan khusus untuk slug saat update:
            // Mengabaikan 'id' milik post yang sedang diedit
            'slug' => [
                'nullable',
                'string',
                Rule::unique('posts', 'slug')->ignore($this->post)
            ],

            'category_id' => [
                'required',
                'exists:categories,id'
            ],

            'body' => [
                'required',
                'string',
                'min:10'
            ],

            // Nullable karena jika tidak upload gambar baru, 
            // gambar lama tetap dipertahankan
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048'
            ],
        ];
    }
}

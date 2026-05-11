<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true; 
    }

   
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'seo_title' => 'nullable|max:255',
            'seo_desc' => 'nullable|max:500',
            'is_active' => 'boolean',
        ];
    }

    
    public function messages(): array
    {
        return [
            'title.required' => 'Заголовок обов’язковий!',
            'content.required' => 'Напишіть хоч щось у зміст.',
            'category_id.exists' => 'Такої категорії не існує.',
        ];
    }
}
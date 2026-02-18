<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    // 1. Дозволяємо доступ (якщо юзер адмін)
    public function authorize(): bool
    {
        return true; 
    }

    // 2. Усі правила валідації в одному місці
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    // 3. (Опціонально) Гарні назви помилок українською
    public function messages(): array
    {
        return [
            'title.required' => 'Заголовок обов’язковий!',
            'content.required' => 'Напишіть хоч щось у зміст.',
            'category_id.exists' => 'Такої категорії не існує.',
        ];
    }
}
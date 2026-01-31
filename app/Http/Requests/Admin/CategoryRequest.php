<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Отримуємо ID категорії з маршруту (якщо це update)
        $categoryId = $this->route('category'); 

        return [
            'name' => [
                'required',
                'max:255',
                'unique:categories,name,' . $categoryId,
            ],
            'parent_id' => 'nullable|exists:categories,id',
            'pos' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Назва категорії обов’язкова.',
            'name.unique' => 'Категорія з такою назвою вже існує.',
            'parent_id.exists' => 'Вибрана батьківська категорія недійсна.',
        ];
    }
}
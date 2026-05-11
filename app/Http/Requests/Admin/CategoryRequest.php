<?php

namespace App\Http\Requests\Admin;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $category = $this->route('category');
    $id = is_object($category) ? $category->id : $category;

    return [
        'name' => [
            'required',
            'max:255',
            Rule::unique('categories', 'name')->ignore($id),
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
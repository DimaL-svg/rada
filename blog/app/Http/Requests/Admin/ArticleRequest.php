<?php

namespace App\Http\Requests\dmin;

use Illuminate\Foundation\Http\FormRequest;

class ArticlRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'=>'riquired|max:255',
            'content'=>'riquired',
            'category_id'=>'required|exists:categories,id',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'Заголовок обов’язковий!',
            'content.required' => 'Контент не може бути порожнім!',
            'category_id.exists' => 'Вибрана категорія не існує в базі.',
        ];
    }
}

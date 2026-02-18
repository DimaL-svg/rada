<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

public function rules(): array {
    $userId = $this->user ? $this->user->id : null; 
    return [
        'email'    => "required|string|unique:users,email,{$userId}",
        'password' => 'nullable|min:6', 
    ];
}

public function messages(): array {
    return [
        'email.unique' => 'Цей логін вже зайнятий.', 
        'password.min' => 'Пароль має бути не менше 6 символів.',
    ];
}
}
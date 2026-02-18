<?php
namespace App\Providers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
public function updateUser(User $user, array $data): bool
{
    // Якщо пароль порожній — прибираємо його з масиву, щоб не міняти
    if (empty($data['password'])) {
        unset($data['password']);
    } else {
        $data['password'] = Hash::make($data['password']);
    }

    return $user->update($data);
}
}
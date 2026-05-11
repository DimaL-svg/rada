<?php
namespace App\Providers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
public function updateUser(User $user, array $data): bool
{
<<<<<<< HEAD
=======
    // Якщо пароль порожній — прибираємо його з масиву, щоб не міняти
>>>>>>> f4fd340d8d205c0514266596c45457c60f05b3df
    if (empty($data['password'])) {
        unset($data['password']);
    } else {
        $data['password'] = Hash::make($data['password']);
    }

    return $user->update($data);
}
}
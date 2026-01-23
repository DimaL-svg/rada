<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
{
    /**
     * СТОРІНКА ВХОДУ
     * Показує форму авторизації (логін/пароль).
     */
    public function showLoginForm() {
        return view('auth.login');
    }

    /**
     * ПРОЦЕС АВТОРИЗАЦІЇ
     * Перевіряє введені дані через LoginRequest. Якщо все вірно —
     * пускає користувача в адмінку і створює безпечну сесію.
     */
    public function login(LoginRequest $request)
    {
        // Отримуємо тільки пошту та пароль
        $credentials = $request->only('email', 'password');

        // Спроба входу
        if (Auth::attempt($credentials)) {
            // Оновлюємо ID сесії для захисту від атак
            $request->session()->regenerate();
            
            // Направляємо в адмінку (в розділ статей)
            return redirect()->intended('/admin/articles');
        }

        // Якщо пароль або пошта не підійшли — повертаємо назад із помилкою
        return back()->withErrors([
            'email' => 'Надані облікові дані не збігаються з нашими записами.',
        ])->onlyInput('email');
    }

    /**
     * ВИХІД ІЗ СИСТЕМИ
     * Закриває сесію користувача та повертає його на головну сторінку сайту.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Анулюємо сесію та оновлюємо токен захисту
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
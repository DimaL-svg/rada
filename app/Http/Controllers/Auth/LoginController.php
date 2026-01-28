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
     */
    public function showLoginForm() {
        return view('Auth.LoginForm');
    }

    /**
     * ПРОЦЕС АВТОРИЗАЦІЇ
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/articles');
        }
        return back()->withErrors([
            'email' => 'Надані облікові дані не збігаються з нашими записами.',
        ])->onlyInput('email');
    }
     /**
     * ВИХІД
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
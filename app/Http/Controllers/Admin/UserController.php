<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\Admin\UserRequest;
use Illuminate\Support\Facades\Hash;
use App\Providers\UserService;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::withcount('articles')->get();
        return view('admin.user.UsersAdmin', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.UserCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
$data = $request->validated();

    User::create([
        'name'      => $data['email'], 
        'email'     => $data['email'],
        'password'  => Hash::make($data['password']),
        'role'      => 'admin',
        'is_active' => 'true',
    ]);

    return redirect()->route('admin.users.index')->with('success', 'Готово!');           
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('super-admin');
        $user = User::withcount('articles')->find($user->id);
        return view('admin.user.UserEdit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user, UserService $service)
    {
        $this->authorize('super-admin');

    // Передаємо дані в сервіс для обробки логіки
    $service->updateUser($user, $request->validated());

    return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function toggleStatus(User $user)
{
    if ($user->id === auth()->id()) {
        return redirect()->back()->with('error', 'Себе банити не можна!');
    }

    // Перемикаємо текст
    if ($user->is_active === 'true') {
        $user->is_active = 'false';
    } else {
        $user->is_active = 'true';
    }

    $user->save();

    return redirect()->back()->with('success', 'Статус змінено на ' . $user->is_active);
}
}

{{-- Підключаємо головний шаблон сторінки входу (центри стрінки, стилі фону тощо) --}}
@extends('templates.MainLoginTemplate')

@section('content')
    {{-- СТАТУС: Виводить повідомлення, наприклад "Пароль успішно змінено", якщо воно є в сесії --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- ФОРМА: Відправляє email та password методом POST на вбудований маршрут 'login' --}}
    <form method="POST" action="{{ route('login') }}">
        @csrf {{-- ЗАХИСТ: Токен, щоб сервер знав, що форму відправлено саме з твого сайту --}}

        {{-- ЛОГІН (EMAIL) --}}
        <div>
            <x-input-label for="email" :value="__('Логін')" />
            {{-- old('email') - якщо вхід невдалий, введений логін залишиться в полі, щоб не писати знову --}}
            <x-text-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email')" required autofocus autocomplete="username" />
            
            {{-- ПОМИЛКА: Якщо логін невірний, Laravel сюди виведе текст помилки --}}
            @error('email')
                <span class="invalid-feedback" style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        {{-- ПАРОЛЬ --}}
        <div class="mt-4">
            <x-input-label for="password" :value="__('Пароль')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            
            @error('password')
                <span class="invalid-feedback" style="color: red;">{{ $message }}</span>
            @enderror
        </div>

        {{-- ЗАПАМ'ЯТАТИ МЕНЕ: Якщо вибрано, сесія користувача триватиме значно довше --}}
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm">
                <span class="ms-2 text-sm text-gray-600">{{ __('Запам`ятати мене') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            {{-- ЗАБУЛИ ПАРОЛЬ: Посилання на сторінку відновлення, якщо цей функціонал увімкнено --}}
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                    {{ __('Забули пароль?') }}
                </a>
            @endif

            {{-- КНОПКА: Відправляє всю форму на перевірку в контролер --}}
            <x-primary-button class="ms-3">
                {{ __('Увійти') }}
            </x-primary-button>
        </div>
    </form>
@endsection
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        {{-- ТОКЕН ЗАХИСТУ: Потрібен для JS-запитів, щоб сервер знав, що вони справжні --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- ШРИФТИ: Підключення сучасного шрифту Figtree --}}
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- VITE: Автоматичне підключення скомпільованих стилів (Tailwind) та скриптів --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        {{-- ВЕРСТКА: Центрування вмісту по вертикалі та горизонталі, сірий фон --}}
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            
            {{-- ЛОГОТИП: Посилання на головну сторінку з іконкою додатка --}}
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            {{-- КОНТЕЙНЕР: Біла картка з тінню (shadow-md) та закругленими кутами --}}
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{-- @yield('content'): Сюди "прилетить" код самої форми з файлу login.blade.php --}}
                @yield('content')
            </div>
        </div>
    </body>
</html>
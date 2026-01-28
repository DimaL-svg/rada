<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Рада директорів')</title>
    {{-- VITE: Підключає стилі та скрипти саме для публічної частини сайту --}}
    @vite(['resources/css/app.css', 'resources/css/site.css', 'resources/js/app.js', 'resources/js/site.js'])
</head>
<body>
    <header class="header-bg">
        <div class="header-container">
            {{-- ШАПКА: Виводить головний логотип, шлях береться з папки build-images --}}
            <img src="{{ asset('build-images/logo.jpg') }}" alt="Логотип" class="header-logo">
        </div>
    </header>

    <div class="DateTime">
        {{-- ДАТА: Використовує Carbon для виводу поточної дати українською мовою --}}
        <?php
            use Carbon\Carbon;
            date_default_timezone_set('Europe/Kiev');
            Carbon::setLocale('uk');
            echo Carbon::now()->translatedFormat('d F, Y року');
        ?>
    </div>

<div class="flex-container">
    {{-- ЛІВА ПАНЕЛЬ (САЙДБАР) --}}
    <div class="sidebar">
        <div class="sidebar-top">
            <img src="{{ asset('build-images/logo_small.gif') }}" class="img-logo-small" alt="Верхня картинка">
        </div>

        <div class="sidebar-content">
            <ul class="sidebar-content-ul">
                {{-- ПЕРЕВІРКА АВТОРИЗАЦІЇ --}}
                @auth
                    {{-- Якщо зайшов адмін: показуємо кнопку входу в панель та виходу --}}
                    <li><a href="{{ route('admin') }}">Панель адміна</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="logout-form">
                            @csrf
                            <button type="submit" class="logout-button">Вихід</button>
                        </form>
                    </li>
                @else
                    {{-- Якщо просто гість: кнопка авторизації --}}
                    <li><a href="{{ route('login') }}">Авторизація</a></li>
                @endauth

                {{-- ДИНАМІЧНЕ МЕНЮ: Виводить категорії з бази даних --}}
                @foreach($menuCategories as $menu)
                    <li>
                        {{-- Якщо є підкатегорії — посилання не веде нікуди (#), якщо немає — веде на сторінку категорії --}}
                        <a href="{{ $menu->children->count() > 0 ? '#' : route('category.show', $menu->slug) }}">
                            {{ $menu->name }}
                        </a>

                        {{-- ВИПАДАЮЧЕ МЕНЮ: Якщо є "діти", малюємо вкладений список --}}
                        @if($menu->children->count() > 0)
                            <ul class="menu-drop">
                                @foreach($menu->children as $child)
                                    <li>
                                        <a href="{{ route('category.show', $child->slug) }}">
                                            {{ $child->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- ОСНОВНИЙ КОНТЕНТ: Сюди "прилітають" статті з інших файлів через @section('content') --}}
    <div class="main-content">
        @yield('content')
    </div>
</div>

<footer class="footer">
    {{-- Низ сайту --}}
</footer>
</body>
</html>
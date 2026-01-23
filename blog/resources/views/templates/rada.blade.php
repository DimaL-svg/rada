<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Рада директорів')</title>
    @vite(['resources/css/app.css', 'resources/css/site.css', 'resources/js/app.js', 'resources/js/site.js'])
</head>
<body>
    <style>
        header {
        margin-top:15px; 
        background-image: url('{{ asset('build-images/fon.gif') }}');       
        display: flex;
        justify-content: center;             
        align-items: center;
        width: 100%;
        
        }

        </style>
    <header class="header-bg">
        <div class="header-container">
            <img src="{{ asset('build-images/logo.jpg') }}" alt="Логотип" class="header-logo">
        </div>
    </header>
    <div class="DateTime">
        <?php
            use Carbon\Carbon;
            date_default_timezone_set('Europe/Kiev');
            Carbon::setLocale('uk');
            echo Carbon::now()->translatedFormat('d F, Y року');
        ?>
        
    </div>
<div class="flex-container">

    <div class="sidebar">
        <!-- Верхняя картинка -->
        <div class="sidebar-top">
            <img src="{{ asset('build-images/logo_small.gif') }}"
                 class="img-logo-small"
                 alt="Верхняя картинка">
        </div>

        <!-- Содержимое сайдбара -->
        <div class="sidebar-content">
            <ul class="sidebar-content-ul">
        @auth
            <li>
                <a href="{{ route('admin') }}">Панель адміна</a>
            </li>
<li>
    <form method="POST" action="{{ route('logout') }}" class="logout-form">
        @csrf
        <button type="submit" class="logout-button">
            Вихід
        </button>
    </form>
</li>
        @else
            <li>
                <a href="{{ route('login') }}">Авторизація</a>
            </li>
        @endauth

                @foreach($menuCategories as $menu)
                    <li>
                        <a href="{{ $menu->children->count() > 0 ? '#' : route('category.show', $menu->slug) }}">
                            {{ $menu->name }}
                        </a>

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

    <div class="main-content">
        @yield('content')
    </div>

</div>

    </div>
    <footer class="footer"></footer>
</body>
</html>
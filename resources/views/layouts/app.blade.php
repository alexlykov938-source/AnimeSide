<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AnimeSide')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-950 text-gray-100 font-sans antialiased">

    <header class="bg-gray-900 border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" class="text-2xl font-bold text-purple-400 tracking-tight hover:text-purple-300 transition">
                🎬 AnimeSide
            </a>
            <nav class="flex gap-6 text-sm font-medium">
                <a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition {{ request()->routeIs('home') ? 'text-purple-400' : '' }}">
                    🏠 Главная
                </a>
                <a href="{{ route('anime.index') }}" class="text-gray-300 hover:text-white transition {{ request()->routeIs('anime.*') ? 'text-purple-400' : '' }}">
                    🎬 Каталог
                </a>
                <a href="{{ route('posts.index') }}" class="text-gray-300 hover:text-white transition {{ request()->routeIs('posts.*') ? 'text-purple-400' : '' }}">
                    📝 Блог
                </a>
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.index') }}" class="text-yellow-400 hover:text-yellow-300 transition">🛡 Админка</a>
                    @endif
                @endauth
                @auth
                    <span class="text-gray-400">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button class="text-gray-400 hover:text-white transition">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition">Войти</a>
                    <a href="{{ route('register') }}" class="text-purple-400 hover:text-purple-300 transition">Регистрация</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8">
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-900/50 border border-green-700 text-green-300 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-gray-900 border-t border-gray-800 mt-12 py-6 text-center text-gray-500 text-sm">
        &copy; {{ date('Y') }} AnimeSide. Сделано с любовью к аниме.
    </footer>
</body>
</html>
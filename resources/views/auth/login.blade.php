@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-3xl font-bold mb-8 text-center">Вход</h1>

    @if (session('status'))
        <div class="mb-4 p-4 bg-green-900/50 border border-green-700 text-green-300 rounded-lg">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            @error('email')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Пароль</label>
            <input type="password" name="password" required autocomplete="current-password"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            @error('password')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="remember" id="remember_me" class="rounded bg-gray-800 border-gray-700 text-purple-600 focus:ring-purple-500">
            <label for="remember_me" class="text-sm text-gray-400">Запомнить меня</label>
        </div>

        <div class="flex items-center justify-between pt-2">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-purple-400 hover:text-purple-300 transition">
                    Забыли пароль?
                </a>
            @endif

            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2.5 px-6 rounded-lg transition shadow-lg shadow-purple-600/20">
                Войти
            </button>
        </div>

        <p class="text-center text-gray-500 text-sm pt-4">
            Нет аккаунта? <a href="{{ route('register') }}" class="text-purple-400 hover:text-purple-300 transition">Регистрация</a>
        </p>
    </form>
</div>
@endsection
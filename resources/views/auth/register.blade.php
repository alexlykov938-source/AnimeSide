@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-3xl font-bold mb-8 text-center">Регистрация</h1>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Имя</label>
            <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            @error('name')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            @error('email')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Пароль</label>
            <input type="password" name="password" required autocomplete="new-password"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            @error('password')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Подтверждение пароля</label>
            <input type="password" name="password_confirmation" required autocomplete="new-password"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            @error('password_confirmation')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2.5 px-6 rounded-lg transition shadow-lg shadow-purple-600/20">
                Зарегистрироваться
            </button>
        </div>

        <p class="text-center text-gray-500 text-sm pt-4">
            Уже есть аккаунт? <a href="{{ route('login') }}" class="text-purple-400 hover:text-purple-300 transition">Войти</a>
        </p>
    </form>
</div>
@endsection
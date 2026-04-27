@extends('layouts.app')

@section('title', 'Админ-панель')

@section('content')
<h1 class="text-3xl font-bold mb-8">🛡 Админ-панель</h1>

{{-- Статистика --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 text-center">
        <div class="text-3xl font-bold text-purple-400">{{ $stats['anime_count'] }}</div>
        <div class="text-gray-500 text-sm mt-1">Аниме в каталоге</div>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 text-center">
        <div class="text-3xl font-bold text-purple-400">{{ $stats['posts_count'] }}</div>
        <div class="text-gray-500 text-sm mt-1">Постов в блоге</div>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 text-center">
        <div class="text-3xl font-bold text-purple-400">{{ $stats['users_count'] }}</div>
        <div class="text-gray-500 text-sm mt-1">Пользователей</div>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 text-center">
        <div class="text-3xl font-bold text-purple-400">{{ $stats['admins_count'] }}</div>
        <div class="text-gray-500 text-sm mt-1">Админов</div>
    </div>
</div>

{{-- Последние аниме --}}
<div class="mb-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">🆕 Последние аниме</h2>
        <a href="{{ route('anime.index') }}" class="text-purple-400 text-sm">В каталог →</a>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-800 text-gray-400">
                <tr>
                    <th class="text-left px-4 py-3">Название</th>
                    <th class="text-left px-4 py-3">Тип</th>
                    <th class="text-left px-4 py-3">Рейтинг</th>
                    <th class="text-left px-4 py-3">Дата</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($latestAnime as $anime)
                    <tr class="border-t border-gray-800 hover:bg-gray-850">
                        <td class="px-4 py-3">
                            <a href="{{ route('anime.show', $anime) }}" class="text-purple-400 hover:text-purple-300">{{ $anime->title }}</a>
                        </td>
                        <td class="px-4 py-3 text-gray-400">{{ $anime->type }}</td>
                        <td class="px-4 py-3 text-gray-400">⭐ {{ $anime->rating }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $anime->created_at->format('d.m.Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Последние пользователи --}}
<div>
    <h2 class="text-xl font-bold mb-4">👥 Последние пользователи</h2>
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-800 text-gray-400">
                <tr>
                    <th class="text-left px-4 py-3">Имя</th>
                    <th class="text-left px-4 py-3">Email</th>
                    <th class="text-left px-4 py-3">Роль</th>
                    <th class="text-left px-4 py-3">Дата</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($latestUsers as $user)
                    <tr class="border-t border-gray-800">
                        <td class="px-4 py-3">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-gray-400">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $user->role === 'admin' ? 'bg-purple-900 text-purple-300' : 'bg-gray-700 text-gray-300' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $user->created_at->format('d.m.Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
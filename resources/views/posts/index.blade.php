@extends('layouts.app')

@section('title', 'Блог')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
    <h1 class="text-3xl font-bold">📝 Блог</h1>
    @auth
        @if (auth()->user()->isAdmin())
            <a href="{{ route('posts.create') }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-medium py-2.5 px-5 rounded-lg transition shadow-lg shadow-purple-600/20">
                + Новый пост
            </a>
        @endif
    @endauth
</div>

@if (session('success'))
    <div class="mb-6 p-4 bg-green-900/50 border border-green-700 text-green-300 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="space-y-6">
    @forelse ($posts as $post)
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 hover:border-gray-700 transition shadow-lg">
            <h2 class="text-xl font-bold mb-2">
                <a href="{{ route('posts.show', $post) }}" class="hover:text-purple-400 transition">{{ $post->title }}</a>
            </h2>
            <p class="text-gray-400 mb-3">{{ Str::limit($post->body, 200) }}</p>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">{{ $post->created_at->format('d.m.Y H:i') }}</span>
                @auth
                    @if (auth()->user()->isAdmin())
                        <div class="flex gap-4">
                            <a href="{{ route('posts.edit', $post) }}" class="text-purple-400 hover:text-purple-300 transition">✏ Редактировать</a>
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Удалить пост?')">
                                @csrf @method('DELETE')
                                <button class="text-red-400 hover:text-red-300 transition">🗑 Удалить</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    @empty
        <div class="text-center py-12 text-gray-500">
            Постов пока нет. <a href="{{ route('posts.create') }}" class="text-purple-400 underline">Создать первый</a>
        </div>
    @endforelse
</div>
@endsection
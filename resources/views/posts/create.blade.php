@extends('layouts.app')

@section('title', 'Новый пост')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">Новый пост</h1>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-900/50 border border-red-700 text-red-300 rounded-lg">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Заголовок *</label>
            <input type="text" name="title" value="{{ old('title') }}"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Текст *</label>
            <textarea name="body" rows="10"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('body') }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2.5 px-6 rounded-lg transition shadow-lg shadow-purple-600/20">
                💾 Опубликовать
            </button>
            <a href="{{ route('posts.index') }}" class="text-gray-400 hover:text-white py-2.5 transition">← Назад</a>
        </div>
    </form>
</div>
@endsection
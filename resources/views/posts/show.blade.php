@extends('layouts.app')

@section('title', $post->title)

@section('content')
<a href="{{ route('posts.index') }}" class="text-purple-400 hover:text-purple-300 transition text-sm mb-4 inline-block">← Назад в блог</a>

<article class="max-w-3xl">
    <h1 class="text-4xl font-bold mb-4">{{ $post->title }}</h1>
    <p class="text-gray-500 text-sm mb-8">{{ $post->created_at->format('d.m.Y H:i') }}</p>
    <div class="text-gray-300 leading-relaxed text-lg">
        {{ $post->body }}
    </div>
</article>
@endsection
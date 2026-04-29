@extends('layouts.app')

@section('title', 'Серии: ' . $anime->title)

@section('content')
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold">Серии: {{ $anime->title }}</h1>
    <div class="flex gap-2">
        <a href="{{ route('episodes.create', $anime) }}" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2.5 px-5 rounded-lg transition">
            + Добавить серию
        </a>
        <a href="{{ route('episodes.bulk', $anime) }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-5 rounded-lg transition">
            + Массовое добавление
        </a>
    </div>
</div>

@forelse ($episodes as $season => $items)
    <div class="mb-6">
        <h2 class="text-xl font-bold mb-3">Сезон {{ $season }}</h2>
        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-800 text-gray-400">
                    <tr>
                        <th class="text-left px-4 py-3">№</th>
                        <th class="text-left px-4 py-3">Название</th>
                        <th class="text-left px-4 py-3">Видео</th>
                        <th class="text-right px-4 py-3">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $ep)
                        <tr class="border-t border-gray-800">
                            <td class="px-4 py-3">{{ $ep->episode_number }}</td>
                            <td class="px-4 py-3">{{ $ep->title ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-400 truncate max-w-[200px]">{{ $ep->video_url }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('episodes.edit', ['anime' => $anime, 'episode' => $ep]) }}" class="text-purple-400 hover:text-purple-300 mr-3">✏</a>
                                <form action="{{ route('episodes.destroy', ['anime' => $anime, 'episode' => $ep]) }}" method="POST" class="inline" onsubmit="return confirm('Удалить серию {{ $ep->episode_number }}?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-400 hover:text-red-300">🗑</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@empty
    <p class="text-gray-500">Нет серий.</p>
@endforelse

<a href="{{ route('anime.index') }}" class="text-purple-400 hover:text-purple-300 transition text-sm">← В каталог</a>
@endsection
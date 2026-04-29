@extends('layouts.app')

@section('title', 'Смотрю: ' . $anime->title)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-black rounded-xl overflow-hidden shadow-lg mb-4">
            @if ($currentEpisode && $currentEpisode->video_url)
                @php
                    $videoUrl = $currentEpisode->video_url;
                    $isYoutube = str_contains($videoUrl, 'youtube') || str_contains($videoUrl, 'youtu.be');
                @endphp

                @if ($isYoutube)
                    @php
                        preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $videoUrl, $matches);
                        $youtubeId = $matches[1] ?? '';
                    @endphp
                    @if ($youtubeId)
                        <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" 
                                class="w-full aspect-video" frameborder="0" allowfullscreen
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                        </iframe>
                    @else
                        <video class="w-full aspect-video" controls>
                            <source src="{{ $videoUrl }}" type="video/mp4">
                        </video>
                    @endif
                @else
                    <video class="w-full aspect-video" controls crossorigin="anonymous">
                        <source src="{{ $videoUrl }}" type="video/mp4">
                        Ваш браузер не поддерживает видео.
                    </video>
                @endif
            @elseif ($anime->trailer_url)
                @php
                    $trailerUrl = $anime->trailer_url;
                    preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $trailerUrl, $matches);
                    $youtubeId = $matches[1] ?? '';
                @endphp
                @if ($youtubeId)
                    <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" 
                            class="w-full aspect-video" frameborder="0" allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                    </iframe>
                @else
                    <video class="w-full aspect-video" controls>
                        <source src="{{ $trailerUrl }}" type="video/mp4">
                    </video>
                @endif
            @else
                <div class="aspect-video flex flex-col items-center justify-center text-gray-500 bg-gray-900">
                    <span class="text-6xl mb-4">🎬</span>
                    <p>Нет доступных серий</p>
                </div>
            @endif
        </div>

        {{-- Выбор сезона --}}
        @if ($seasons->count() > 1)
            <div class="flex gap-2 mb-4">
                @foreach ($seasons as $season)
                    <a href="{{ route('anime.watch', ['anime' => $anime]) }}?season={{ $season }}" 
                    class="px-4 py-1 rounded-lg text-sm font-medium {{ $season == $currentSeason ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }}">
                        Сезон {{ $season }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Список серий --}}
        @if ($episodes->count())
            <div class="grid grid-cols-4 sm:grid-cols-6 gap-2 mb-4">
                @foreach ($episodes as $ep)
                    <a href="{{ route('anime.watch', ['anime' => $anime]) }}?season={{ $currentSeason }}&episode={{ $ep->episode_number }}"
                    class="text-center py-2 px-3 rounded-lg text-sm {{ $currentEpisode && $currentEpisode->id === $ep->id ? 'bg-green-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-gray-700' }}">
                        Серия {{ $ep->episode_number }}
                        @if ($ep->title)
                            <span class="block text-xs text-gray-400 truncate">{{ $ep->title }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Название текущей серии --}}
        @if ($currentEpisode && $currentEpisode->title)
            <h2 class="text-lg font-semibold mb-2">Серия {{ $currentEpisode->episode_number }}: {{ $currentEpisode->title }}</h2>
        @endif

        <h1 class="text-2xl font-bold mb-2">{{ $anime->title }}</h1>
        <div class="flex flex-wrap gap-2 mb-4">
            <span class="bg-purple-900/50 text-purple-300 px-3 py-1 rounded-full text-sm">⭐ {{ $anime->rating }}</span>
            <span class="bg-gray-800 text-gray-300 px-3 py-1 rounded-full text-sm">{{ $anime->type }}</span>
            <span class="bg-gray-800 text-gray-300 px-3 py-1 rounded-full text-sm">{{ $anime->episodes }} эп.</span>
            <span class="bg-gray-800 text-gray-300 px-3 py-1 rounded-full text-sm">{{ $anime->status === 'ongoing' ? '🟢 Выходит' : '🔵 Завершён' }}</span>
        </div>
        <p class="text-gray-400">{{ $anime->description }}</p>
    </div>

    {{-- Сайдбар --}}
    <div class="lg:col-span-1 space-y-4">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 space-y-2">
            <div><span class="text-gray-500">🎭 Жанр:</span> <span class="text-gray-300">{{ $anime->genre }}</span></div>
            <div><span class="text-gray-500">📅 Год:</span> <span class="text-gray-300">{{ $anime->year ?? '—' }}</span></div>
            <div><span class="text-gray-500">🏢 Студия:</span> <span class="text-gray-300">{{ $anime->studio ?? '—' }}</span></div>
        </div>

        <div class="space-y-2">
            <a href="{{ route('anime.show', $anime) }}" class="block w-full text-center bg-gray-800 hover:bg-gray-700 text-gray-300 font-medium py-2 px-4 rounded-lg transition border border-gray-700">
                ℹ Подробнее
            </a>
            <a href="{{ route('anime.index') }}" class="block w-full text-center bg-gray-800 hover:bg-gray-700 text-gray-300 font-medium py-2 px-4 rounded-lg transition border border-gray-700">
                ← В каталог
            </a>
        </div>
    </div>
</div>
@endsection
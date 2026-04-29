<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Http\Requests\AnimeRequest;
use Illuminate\Support\Facades\{Storage, Http};
use Illuminate\Http\Request;

class AnimeController extends Controller
{
    // Каталог с фильтрацией
    public function index(Request $request)
    {
        $query = Anime::query();

        if ($request->filled('genre')) {
            $query->where('genre', 'like', '%' . $request->genre . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $animes = $query->latest()->get();

        $allAnimes = Anime::all();

        $genres = $allAnimes->pluck('genre')
            ->flatMap(fn($g) => explode(', ', $g))
            ->unique()->sort()->values();

        $types    = $allAnimes->pluck('type')->unique()->sort()->values();

        return view('anime.index', compact('animes', 'genres', 'types'));
    }

    // Форма создания
    public function create()
    {
        return view('anime.create');
    }

    // Сохранение
    public function store(AnimeRequest $request)
    {
        $data = $request->validated();

        // Загрузка картинки
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('anime', 'public');
        }

        Anime::create($data);

        return redirect()->route('anime.index')
            ->with('success', 'Аниме добавлено!');
    }

    // Детальная страница
    public function show(Anime $anime)
    {
        return view('anime.show', compact('anime'));
    }

    // Форма редактирования
    public function edit(Anime $anime)
    {
        return view('anime.edit', compact('anime'));
    }

    // Обновление
    public function update(AnimeRequest $request, Anime $anime)
    {
        $data = $request->validated();

        // Загрузка новой картинки (с удалением старой)
        if ($request->hasFile('image')) {
            // Удаляем старый файл, если это локальный файл
            if ($anime->image && !str_starts_with($anime->image, 'http')) {
                Storage::disk('public')->delete($anime->image);
            }
            $data['image'] = $request->file('image')->store('anime', 'public');
        }

        $anime->update($data);

        return redirect()->route('anime.index')
            ->with('success', 'Аниме обновлено!');
    }

    // Удаление
    public function destroy(Anime $anime)
    {
        $anime->delete();

        return redirect()->route('anime.index')
            ->with('success', 'Аниме удалено!');
    }

    // Просмотр с плеером
    public function watch(Anime $anime)
    {
        $seasons = $anime->seasons();
        $currentSeason = request('season', $seasons->first() ?? 1);
        
        $episodes = $anime->episodes()
            ->where('season', $currentSeason)
            ->orderBy('episode_number')
            ->get();
        
        $currentEpisode = null;
        
        if (request('episode')) {
            $currentEpisode = $anime->episodes()
                ->where('season', $currentSeason)
                ->where('episode_number', request('episode'))
                ->first();
        } elseif ($episodes->isNotEmpty()) {
            $currentEpisode = $episodes->first();
        }

        //dd($currentEpisode, $episodes->toArray(), request()->all());

        return view('anime.watch', compact('anime', 'seasons', 'currentSeason', 'episodes', 'currentEpisode'));
    }
    
    public function fetch(Request $request)
    {
        $search = $request->input('search');

        if (empty($search)) {
            return back()->with('error', 'Введите название');
        }

        $query = '
        query ($search: String) {
            Page(perPage: 5) {
                media(search: $search, type: ANIME) {
                id
                title {romaji english native}
                description
                genres
                episodes
                seasonYear
                averageScore
                coverImage {large}
                studios {nodes{name}}
                }
            }
        }
        ';

        $response = Http::withoutVerifying()->post('https://graphql.anilist.co', [
            'query'     => $query,
            'variables' => ['search' => $search],
        ]);

        if ($response->failed()) {
            return back()->with('error', 'Ошибка API');
        }

        $animes = $response->json('data.Page.media');

        if (empty($animes)) {
            return back()->with('error', 'Ничего не найдено');
        }

        $count = 0;
        foreach ($animes as $data) {
            $anime = Anime::updateOrCreate(
                ['title' => $data['title']['romaji'] ?? $data['title']['english'] ?? $search],
                [
                    'genre' => implode(', ', $data['genres'] ?? []),
                    'type' => 'Сериал',
                    'episodes' => $data['episodes'] ?? 0,
                    'year' => $data['seasonYear'] ?? null,
                    'studio' => $data['studios']['nodes'][0]['name'] ?? null,
                    'rating' => ($data['averageScore'] ?? 0) / 10,
                    'description' => strip_tags($data['description'] ?? ''),
                    'image' => $data['coverImage']['large'] ?? null,
                    'status' => 'completed',
                ]
            );
            $count++;
        }

        return back()->with('success', "Добавлено аниме: {$count}");
    }
}
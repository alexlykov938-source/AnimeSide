<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Http\Requests\AnimeRequest;
use Illuminate\Support\Facades\Storage;
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
}
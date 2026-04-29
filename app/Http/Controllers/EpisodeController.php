<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\Episode;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    public function index(Anime $anime)
    {
        $episodes = $anime->episodes()
            ->orderBy('season')
            ->orderBy('episode_number')
            ->get()
            ->groupBy('season');

        return view('episodes.index', compact('anime', 'episodes'));
    }

    public function create(Anime $anime)
    {
        return view('episodes.create', compact('anime'));
    }

    public function store(Request $request, Anime $anime)
    {
        $validated = $request->validate([
            'season'         => 'required|integer|min:1',
            'episode_number' => 'required|integer|min:1',
            'title'          => 'nullable|string|max:255',
            'video_url'      => 'required|url|max:500',
            'description'    => 'nullable|string|max:1000',
        ]);

        $anime->episodes()->create($validated);

        return redirect()->route('episodes.index', $anime)
            ->with('success', 'Серия добавлена');
    }

    public function edit(Anime $anime, Episode $episode)
    {
        return view('episodes.edit', compact('anime', 'episode'));
    }

    public function update(Request $request, Anime $anime, Episode $episode)
    {
        $validated = $request->validate([
            'season'         => 'required|integer|min:1',
            'episode_number' => 'required|integer|min:1',
            'title'          => 'nullable|string|max:255',
            'video_url'      => 'required|url|max:500',
            'description'    => 'nullable|string|max:1000',
        ]);

        $episode->update($validated);

        return redirect()->route('episodes.index', $anime)
            ->with('success', 'Серия обновлена');
    }

    public function destroy(Anime $anime, Episode $episode)
    {
        $episode->delete();

        return back()->with('success', 'Серия удалена');
    }
}
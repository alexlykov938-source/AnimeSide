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
            'dubbing'        => 'nullable|string|max:1000',
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
            'dubbing'        => 'nullable|string|max:1000',
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

    public function bulk(Anime $anime)
    {
        return view('episodes.bulk', compact('anime'));
    }

    public function bulkStore(Request $request, Anime $anime)
    {
        $request->validate([
            'season' => 'required|integer|min:1',
            'data'   => 'required|string',
        ]);

        $lines = explode("\n", trim($request->data));
        $count = 0;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            // Формат: номер|название|видео|озвучка
            $parts         = explode('|', $line);
            $episodeNumber = (int) trim($parts[0]);
            $title         = trim($parts[1] ?? '');
            $videoUrl      = trim($parts[2] ?? '');
            $dubbing       = trim($parts[3] ?? '');

            if ($episodeNumber && $videoUrl) {
                $anime->episodes()->updateOrCreate(
                    [
                        'season'         => $request->season,
                        'episode_number' => $episodeNumber,
                    ],
                    [
                        'title' => $title ?: null,
                        'video_url' => $videoUrl,
                        'dubbing'   => $dubbing ?: null,
                    ]
                );
                $count++;
            }
        }
        return redirect()->route('episodes.index', $anime)
            ->with('success', "Добавленно/обновлено серий: {$count}");
    }
}
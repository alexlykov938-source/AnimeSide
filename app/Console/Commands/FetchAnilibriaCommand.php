<?php

namespace App\Console\Commands;

use App\Models\Anime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchAnilibriaCommand extends Command
{
    protected $signature = 'anime:fetch-anilibria {search : Название аниме для поиска}';
    protected $description = 'Ищет аниме через AniLibria API и сохраняет в БД';

    public function handle()
    {
        $search = $this->argument('search');
        $this->info("Поиск AniLibria: {$search}...");

        // --- 1. Поиск тайтла ---
        // Пробуем официальный API v3
        $response = Http::timeout(30)->get('https://api.anilibria.tv/v3/anime/search', [
            'query' => $search,
        ]);

        if ($response->failed() || empty($response->json())) {
            // Пробуем запасной URL из документации
            $this->warn('Официальный API не ответил, пробую зеркало...');
            $response = Http::timeout(30)->get('https://api.anilibria.live/v3/anime/search', [
                'query' => $search,
            ]);
        }

        if ($response->failed() || empty($response->json())) {
            $this->error('API AniLibria недоступно. Попробуйте позже или включите VPN.');
            return 1;
        }

        // В v3 API ответ оборачивается в 'data'
        $animeData = $response->json('data')[0] ?? $response->json()[0] ?? [];

        if (empty($animeData)) {
            $this->error('Ничего не найдено.');
            return 1;
        }

        // --- 2. Сохраняем основную информацию ---
        $title = $animeData['names']['ru'] ?? $animeData['name'] ?? $search;
        $anime = Anime::updateOrCreate(
            ['title' => $title],
            [
                'genre'       => implode(', ', $animeData['genres'] ?? []),
                'type'        => $animeData['type'] ?? 'TV',
                'episodes'    => $animeData['episodes'] ?? 0,
                'year'        => $animeData['year'] ?? null,
                'studio'      => $animeData['studio'] ?? null,
                'rating'      => ($animeData['rating'] ?? 0) / 10,
                'description' => strip_tags($animeData['description'] ?? ''),
                'image'       => $animeData['poster'] ?? null,
                'status'      => $animeData['status'] ?? 'completed',
            ]
        );
        $this->info("Сохранено: {$anime->title}");

        // --- 3. Получаем серии ---
        if (isset($animeData['id'])) {
            $this->info('Загружаем серии...');
            $episodesResponse = Http::timeout(30)->get("https://api.anilibria.tv/v3/anime/{$animeData['id']}/episodes");

            if ($episodesResponse->successful()) {
                $episodes = $episodesResponse->json('data') ?? [];
                $count = 0;
                foreach ($episodes as $ep) {
                    $videoUrl = $ep['hls'] ?? $ep['src'] ?? null;
                    if (!$videoUrl) continue;

                    $dubbing = 'AniLibria | ' . $videoUrl;
                    
                    $anime->episodes()->updateOrCreate(
                        [
                            'season'        => $ep['season'] ?? 1,
                            'episode_number' => $ep['episode'] ?? $ep['number'],
                        ],
                        [
                            'title'     => $ep['name'] ?? null,
                            'video_url' => $videoUrl,
                            'dubbing'   => $dubbing,
                        ]
                    );
                    $count++;
                }
                $this->info("Сохранено серий: {$count}");
            }
        }

        $this->info('Готово!');
        return 0;
    }
}
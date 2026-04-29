<?php

namespace App\Console\Commands;

use App\Models\Anime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchAnimeCommand extends Command
{
    protected $signature = 'anime:fetch {search : Название аниме для поиска}';
    protected $description = 'Ищет аниме через AniList API и сохраняет в БД';

    public function handle()
    {
        $search = $this->argument('search');
        $this->info("Поиск: {$search}...");

        $query = '
        query ($search: String) {
            Page(perPage: 5) {
                media(search: $search, type: ANIME) {
                    id
                    title { romaji english native }
                    description
                    genres
                    episodes
                    seasonYear
                    averageScore
                    coverImage { large }
                    studios { nodes { name } }
                }
            }
        }';

        $response = Http::post('https://graphql.anilist.co', [
            'query' => $query,
            'variables' => ['search' => $search],
        ]);

        if ($response->failed()) {
            $this->error('Ошибка API');
            return 1;
        }

        $animes = $response->json('data.Page.media');

        if (empty($animes)) {
            $this->warn('Ничего не найдено.');
            return 0;
        }

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

            $this->info("Сохранено: {$anime->title}");
        }

        $this->info('Готово!');
        return 0;
    }
}
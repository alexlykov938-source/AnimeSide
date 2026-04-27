<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Anime extends Model
{
    protected $table = 'anime';

    protected $fillable = [
        'title', 'slug', 'genre', 'type', 'episodes',
        'year', 'studio', 'rating', 'description', 'image', 'status'
    ];

    protected static function booted(): void
    {
        static::creating(function (Anime $anime) {
            if (empty($anime->slug)) {
                $anime->slug = Str::slug($anime->title);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'rating'   => 'float',
            'year'     => 'integer',
            'episodes' => 'integer',
        ];
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        return '';
    }
}
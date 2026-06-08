<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Youtube extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'youtube';

    protected $appends = ['youtube_video_id', 'youtube_embed_url'];

    public function getYoutubeVideoIdAttribute(): ?string
    {
        return self::extractYoutubeId($this->video_url);
    }

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        $id = $this->youtube_video_id;
        return $id ? "https://www.youtube.com/embed/{$id}" : null;
    }

    public static function normalizeYoutubeUrl(?string $input): ?string
    {
        $value = trim((string) $input);
        if ($value === '') {
            return null;
        }

        $id = self::extractYoutubeId($value);
        if ($id) {
            return "https://www.youtube.com/watch?v={$id}";
        }

        return $value;
    }

    public static function extractYoutubeId(?string $input): ?string
    {
        $value = trim((string) $input);
        if ($value === '') {
            return null;
        }

        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $value)) {
            return $value;
        }

        $parts = parse_url($value);
        if (!$parts || empty($parts['host'])) {
            return null;
        }

        $host = strtolower($parts['host']);
        $path = $parts['path'] ?? '';

        if (str_contains($host, 'youtu.be')) {
            $id = trim($path, '/');
            return preg_match('/^[a-zA-Z0-9_-]{11}$/', $id) ? $id : null;
        }

        if (str_contains($host, 'youtube.com') || str_contains($host, 'youtube-nocookie.com')) {
            if (!empty($parts['query'])) {
                parse_str($parts['query'], $query);
                $id = $query['v'] ?? null;
                if ($id && preg_match('/^[a-zA-Z0-9_-]{11}$/', $id)) {
                    return $id;
                }
            }

            if (preg_match('#/(embed|shorts|live)/([a-zA-Z0-9_-]{11})#', $path, $m)) {
                return $m[2];
            }
        }

        return null;
    }
}

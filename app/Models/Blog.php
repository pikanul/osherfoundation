<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    



    protected static function boot(){
        parent::boot();
        static::created(function($blog){
            $blog->slug = $blog->createSlug($blog->title);
            $blog->save();
        });
    }

    private function createSlug($title){
        $baseSlug = Str::slug((string) $title);
        if ($baseSlug === '') {
            $baseSlug = 'blog';
        }

        if (!static::where('slug', $baseSlug)->exists()) {
            return $baseSlug;
        }

        $latestSimilarSlugs = static::where('slug', 'like', $baseSlug . '%')
            ->pluck('slug');

        $maxSuffix = 0;
        foreach ($latestSimilarSlugs as $existingSlug) {
            if ($existingSlug === $baseSlug) {
                $maxSuffix = max($maxSuffix, 0);
                continue;
            }

            if (preg_match('/^' . preg_quote($baseSlug, '/') . '-(\d+)$/', (string) $existingSlug, $matches)) {
                $maxSuffix = max($maxSuffix, (int) $matches[1]);
            }
        }

        return $baseSlug . '-' . ($maxSuffix + 1);
    }
}

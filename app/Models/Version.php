<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Version extends Model
{
    use HasFactory;

    protected $fillable = [
        'software_id',
        'version',
        'release_date',
        'changelog',
        'file_path',
        'update_url',
        'update_sql',
        'environment_commands',
        'hash',
        'force_update',
        'is_stable',
    ];

    protected $casts = [
        'release_date' => 'date',
        'force_update' => 'boolean',
        'is_stable' => 'boolean',
    ];

    protected $appends = [
        'download_url',
    ];

    public function software(): BelongsTo
    {
        return $this->belongsTo(Software::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function getDownloadUrlAttribute(): ?string
    {
        if (! $this->file_path) {
            return null;
        }

        return Storage::disk('public')->url($this->file_path);
    }
}

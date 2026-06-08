<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Subscription extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_DISABLED = 'disabled';

    public const BILLING_CYCLE_MONTHLY = 'monthly';
    public const BILLING_CYCLE_LIFETIME = 'lifetime';

    protected $fillable = [
        'client_id',
        'software_id',
        'version_id',
        'license_key',
        'device_id',
        'domain',
        'status',
        'is_blocked',
        'start_date',
        'end_date',
        'grace_days',
        'plan_name',
        'billing_cycle',
        'amount',
        'auto_renew',
        'last_api_check_at',
        'last_update_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_blocked' => 'boolean',
        'auto_renew' => 'boolean',
        'grace_days' => 'integer',
        'last_api_check_at' => 'datetime',
        'last_update_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function software(): BelongsTo
    {
        return $this->belongsTo(Software::class);
    }

    public function version(): BelongsTo
    {
        return $this->belongsTo(Version::class);
    }

    public function isBlocked(): bool
    {
        return $this->is_blocked || $this->status === self::STATUS_DISABLED;
    }

    public function isLifetime(): bool
    {
        $normalizedCycle = strtolower((string) $this->billing_cycle);
        $normalizedCycle = str_replace([' ', '_', '-'], '', $normalizedCycle);

        return $normalizedCycle === self::BILLING_CYCLE_LIFETIME;
    }

    public function isExpired(?CarbonInterface $today = null): bool
    {
        if ($this->isLifetime()) {
            return false;
        }

        if (! $this->end_date) {
            return false;
        }

        $today ??= now()->startOfDay();

        return $this->end_date->lt($today);
    }

    public function isWithinGracePeriod(?CarbonInterface $today = null): bool
    {
        if (! $this->end_date || ! $this->isExpired($today)) {
            return false;
        }

        $today ??= now()->startOfDay();
        $graceEnd = $this->end_date->copy()->addDays(max(0, $this->grace_days));

        return $graceEnd->gte($today);
    }

    public function isExpiredBeyondGrace(?CarbonInterface $today = null): bool
    {
        return $this->isExpired($today) && ! $this->isWithinGracePeriod($today);
    }

    public function effectiveStatus(?CarbonInterface $today = null): string
    {
        if ($this->isBlocked()) {
            return self::STATUS_DISABLED;
        }

        if ($this->isExpiredBeyondGrace($today)) {
            return self::STATUS_DISABLED;
        }

        return self::STATUS_ACTIVE;
    }

    public function hasAccess(?CarbonInterface $today = null): bool
    {
        return $this->effectiveStatus($today) === self::STATUS_ACTIVE;
    }

    public static function generateLicenseKey(): string
    {
        return sprintf(
            'LIC-%s-%s-%s-%s',
            Str::upper(Str::random(4)),
            Str::upper(Str::random(4)),
            Str::upper(Str::random(4)),
            Str::upper(Str::random(4)),
        );
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Software;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifyLicenseController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'software' => ['nullable', 'string', 'max:255'],
            'license_key' => ['required', 'string', 'max:255'],
            'device_id' => ['nullable', 'string', 'max:255'],
            'domain' => ['nullable', 'string', 'max:255'],
        ]);

        $softwareSlug = $this->normalizeNullable($payload['software'] ?? null);

        $software = null;
        if ($softwareSlug !== null) {
            $software = Software::query()
                ->where('slug', $softwareSlug)
                ->where('is_active', true)
                ->first();

            if (! $software) {
                return response()->json([
                    'status' => 'not_found',
                    'license_valid' => false,
                    'message' => 'Software not found.',
                ], 404);
            }
        }

        $subscription = Subscription::query()
            ->with('software:id,name,slug,is_active')
            ->where('license_key', $payload['license_key'])
            ->when($software !== null, fn ($query) => $query->where('software_id', $software->id))
            ->when($software === null, fn ($query) => $query->whereHas('software', fn ($s) => $s->where('is_active', true)))
            ->latest('id')
            ->first();

        if (! $subscription) {
            return response()->json([
                'status' => 'invalid_license',
                'license_valid' => false,
                'message' => 'Invalid license key.',
            ], 403);
        }

        $software ??= $subscription->software;

        if (! $software || ! $software->is_active) {
            return response()->json([
                'status' => 'not_found',
                'license_valid' => false,
                'message' => 'Software not found.',
            ], 404);
        }

        $incomingDeviceId = $this->normalizeNullable($payload['device_id'] ?? null);
        $incomingDomain = $this->normalizeDomain($payload['domain'] ?? null);

        $subscription->forceFill([
            'last_api_check_at' => now(),
        ]);

        if ($incomingDeviceId !== null) {
            $subscription->device_id = $incomingDeviceId;
        }

        if ($incomingDomain !== null) {
            $subscription->domain = $incomingDomain;
        }

        if ($subscription->isBlocked()) {
            $subscription->save();

            return response()->json([
                'status' => 'access_denied',
                'license_valid' => true,
                'license_status' => 'disabled',
                'effective_status' => Subscription::STATUS_DISABLED,
                'message' => 'access denied',
            ], 403);
        }

        if ($subscription->isExpiredBeyondGrace()) {
            $subscription->forceFill([
                'status' => Subscription::STATUS_DISABLED,
            ])->save();

            return response()->json([
                'status' => 'expired',
                'license_valid' => true,
                'license_status' => 'expired',
                'effective_status' => Subscription::STATUS_DISABLED,
                'message' => 'expired',
            ], 403);
        }

        $subscription->status = $subscription->isExpired()
            ? Subscription::STATUS_EXPIRED
            : Subscription::STATUS_ACTIVE;

        $subscription->save();

        return response()->json([
            'status' => 'ok',
            'license_valid' => true,
            'license_status' => $subscription->effectiveStatus() === Subscription::STATUS_ACTIVE ? 'valid' : $subscription->effectiveStatus(),
            'effective_status' => $subscription->effectiveStatus(),
            'subscription_type' => $subscription->isLifetime() ? Subscription::BILLING_CYCLE_LIFETIME : Subscription::BILLING_CYCLE_MONTHLY,
            'billing_cycle' => $subscription->billing_cycle,
            'license_key' => $subscription->license_key,
            'domain' => $subscription->domain,
            'device_id' => $subscription->device_id,
            'software' => [
                'id' => $software->id,
                'name' => $software->name,
                'slug' => $software->slug,
            ],
            'message' => 'License verified.',
        ]);
    }

    private function normalizeNullable(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed !== '' ? $trimmed : null;
    }

    private function normalizeDomain(?string $value): ?string
    {
        $normalized = $this->normalizeNullable($value);

        if ($normalized === null) {
            return null;
        }

        $candidate = str_contains($normalized, '://') ? $normalized : "https://{$normalized}";
        $host = parse_url($candidate, PHP_URL_HOST);
        $domain = $host ? trim(strtolower($host), '.') : trim(strtolower($normalized), '.');

        return $domain !== '' ? $domain : null;
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Software;
use App\Models\Subscription;
use App\Models\Version;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckUpdateController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'software' => ['nullable', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:64'],
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
                'message' => 'expired',
            ], 403);
        }

        $subscription->status = $subscription->isExpired()
            ? Subscription::STATUS_EXPIRED
            : Subscription::STATUS_ACTIVE;

        $latestVersion = $this->latestVersion($software);

        if (! $latestVersion) {
            $subscription->save();

            return response()->json([
                'status' => 'ok',
                'license_valid' => true,
                'license_status' => $subscription->effectiveStatus() === Subscription::STATUS_ACTIVE ? 'valid' : $subscription->effectiveStatus(),
                'subscription_type' => $subscription->isLifetime() ? Subscription::BILLING_CYCLE_LIFETIME : Subscription::BILLING_CYCLE_MONTHLY,
                'billing_cycle' => $subscription->billing_cycle,
                'effective_status' => $subscription->effectiveStatus(),
                'update' => false,
                'update_available' => false,
                'message' => 'No published versions found.',
            ]);
        }

        $currentVersion = $this->normalizeVersion($payload['version']);
        $latestVersionNumber = $this->normalizeVersion($latestVersion->version);
        $isUpdateAvailable = version_compare($currentVersion, $latestVersionNumber, '<');

        if ($isUpdateAvailable) {
            $subscription->last_update_at = now();
        }

        $subscription->save();

        return response()->json([
            'status' => 'ok',
            'license_valid' => true,
            'license_status' => $subscription->effectiveStatus() === Subscription::STATUS_ACTIVE ? 'valid' : $subscription->effectiveStatus(),
            'subscription_type' => $subscription->isLifetime() ? Subscription::BILLING_CYCLE_LIFETIME : Subscription::BILLING_CYCLE_MONTHLY,
            'billing_cycle' => $subscription->billing_cycle,
            'effective_status' => $subscription->effectiveStatus(),
            'update' => $isUpdateAvailable,
            'update_available' => $isUpdateAvailable,
            'latest_version' => $latestVersion->version,
            'version' => $latestVersion->version,
            'download_url' => $latestVersion->download_url,
            'update_link' => $latestVersion->update_url ?: $latestVersion->download_url,
            'hash' => $latestVersion->hash,
            'force_update' => $latestVersion->force_update,
            'changelog' => $latestVersion->changelog,
            'release_note' => $latestVersion->changelog,
            'update_sql' => $isUpdateAvailable ? $latestVersion->update_sql : null,
            'environment_commands' => $isUpdateAvailable ? $latestVersion->environment_commands : null,
            'update_sql_commands' => $isUpdateAvailable ? $this->splitCommands($latestVersion->update_sql) : [],
            'environment_command_list' => $isUpdateAvailable ? $this->splitCommands($latestVersion->environment_commands) : [],
            'message' => $isUpdateAvailable ? 'Update available.' : 'System is up to date.',
        ]);
    }

    private function latestVersion(Software $software): ?Version
    {
        return $software->versions()
            ->where('is_stable', true)
            ->get()
            ->sort(function (Version $a, Version $b) {
                return version_compare(
                    $this->normalizeVersion($b->version),
                    $this->normalizeVersion($a->version)
                );
            })
            ->first();
    }

    private function normalizeVersion(string $value): string
    {
        $normalized = trim(strtolower($value));

        return ltrim($normalized, 'v');
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

    /**
     * @return array<int, string>
     */
    private function splitCommands(?string $commands): array
    {
        if ($commands === null) {
            return [];
        }

        return collect(preg_split('/\r\n|\r|\n/', $commands) ?: [])
            ->map(fn (string $line) => trim($line))
            ->filter(fn (string $line) => $line !== '')
            ->values()
            ->all();
    }
}

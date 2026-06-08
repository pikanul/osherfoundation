<?php

namespace App\Updater;

class JsonUpdaterStore
{
    public function path(): string
    {
        return storage_path('updater/system.json');
    }

    public function read(): array
    {
        $path = $this->path();
        $dir = dirname($path);

        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        if (!is_file($path)) {
            @file_put_contents($path, json_encode($this->defaults(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }

        $raw = @file_get_contents($path);
        if (is_string($raw)) {
            $raw = preg_replace('/^\xEF\xBB\xBF/', '', $raw) ?? $raw;
        }
        $decoded = is_string($raw) ? json_decode($raw, true) : [];
        $data = array_merge($this->defaults(), is_array($decoded) ? $decoded : []);

        return $this->normalize($data);
    }

    public function write(array $data): array
    {
        $current = $this->read();
        $next = array_merge($this->defaults(), $current, $data);
        $next = $this->normalize($next);
        $next['updated_at'] = now()->toDateTimeString();

        @file_put_contents($this->path(), json_encode($next, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $next;
    }

    public function defaults(): array
    {
        $baseVersion = '1.0.1.2';

        return [
            'app_version' => $baseVersion,
            'version' => $baseVersion,
            'latest_version' => $baseVersion,
            'update_available' => false,
            'remote_version_url' => '',
            'software_slug' => 'oshefoundation',
            'check_delay_minutes' => 360,
            'last_checked_at' => null,
            'system_build_number' => 'N/A',
            'release_channel' => 'stable',
            'last_update_note' => 'No release note available.',
            'release_note' => 'No release note available.',
            'system_video_url' => '',
            'license_key' => '',
            'license_status' => 'N/A',
            'download_url' => '',
            'hash' => '',
            'last_update' => null,
            'last_error' => '',
            'status' => 'ok',
            'updated_at' => now()->toDateTimeString(),
        ];
    }

    public function normalize(array $data): array
    {
        $appVersion = (string) ($data['app_version'] ?? $data['version'] ?? '');
        $version = (string) ($data['version'] ?? $data['app_version'] ?? '');
        $latestVersion = (string) ($data['latest_version'] ?? '');
        $releaseNote = (string) ($data['last_update_note'] ?? $data['release_note'] ?? 'No release note available.');

        if ($appVersion === '') {
            $appVersion = $version;
        }
        if ($version === '') {
            $version = $appVersion;
        }
        if ($latestVersion === '') {
            $latestVersion = $appVersion;
        }

        $data['app_version'] = $appVersion;
        $data['version'] = $version;
        $data['latest_version'] = $latestVersion;
        $data['last_update_note'] = $releaseNote;
        $data['release_note'] = $releaseNote;
        $data['update_available'] = $this->isUpdateAvailable($data);

        return $data;
    }

    public function isUpdateAvailable(array $data): bool
    {
        $app = (string) ($data['app_version'] ?? $data['version'] ?? '');
        $latest = (string) ($data['latest_version'] ?? '');

        if ($app === '' || $latest === '') {
            return false;
        }

        return version_compare($latest, $app, '>');
    }
}

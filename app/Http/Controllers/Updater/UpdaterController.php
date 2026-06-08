<?php

namespace App\Http\Controllers\Updater;

use App\Http\Controllers\Controller;
use App\Updater\JsonUpdaterStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use ZipArchive;

class UpdaterController extends Controller
{
    public function __construct(private readonly JsonUpdaterStore $store)
    {
    }

    public function check(Request $request)
    {
        $force = $request->boolean('force');
        $payload = $this->checkNow($force);
        $isSuccess = (bool) ($payload['success'] ?? false);

        return response()->json(array_merge($payload, [
            'title' => (string) ($payload['message'] ?? ($isSuccess ? 'Update check completed.' : 'Unable to check update.')),
            'type' => $isSuccess ? 'success' : 'error',
            'refresh' => 'false',
        ]), $isSuccess ? 200 : 422);
    }

    public function run(Request $request)
    {
        $downloadUrl = trim((string) $request->input('download_url', ''));
        $payload = $this->runUpdate($downloadUrl !== '' ? $downloadUrl : null);
        $isSuccess = (bool) ($payload['success'] ?? false);

        return response()->json(array_merge($payload, [
            'title' => (string) ($payload['message'] ?? ($isSuccess ? 'Updated successfully.' : 'Update failed.')),
            'type' => $isSuccess ? 'success' : 'error',
            'refresh' => $isSuccess ? 'true' : 'false',
        ]), $isSuccess ? 200 : 422);
    }

    private function getSystem(): array
    {
        return $this->store->read();
    }

    private function writeSystem(array $payload): void
    {
        $this->store->write($payload);
    }

    private function checkNow(bool $force = false): array
    {
        $sys = $this->getSystem();
        if (!$force && !$this->shouldCheckRemote($sys)) {
            return $this->buildCheckPayload($sys, true, 'Update check skipped by time window.');
        }

        $updateServer = rtrim((string) ($sys['remote_version_url'] ?? ''), '/');
        if ($updateServer === '') {
            $sys['status'] = 'error';
            $sys['last_error'] = 'UPDATE_SERVER is not configured.';
            $sys['last_checked_at'] = now()->toDateTimeString();
            $this->writeSystem($sys);
            return $this->buildCheckPayload($sys, false, 'UPDATE_SERVER is not configured.');
        }

        $softwareSlug = trim((string) ($sys['software_slug'] ?? 'oshefoundation'));
        $requestPayload = array_merge([
            'license_key' => $sys['license_key'] ?? '',
            'version' => $sys['app_version'] ?? $sys['version'] ?? '',
            'software' => $softwareSlug !== '' ? $softwareSlug : 'oshefoundation',
            'software_slug' => $softwareSlug !== '' ? $softwareSlug : 'oshefoundation',
        ], $this->remoteClientContext($sys));

        $res = Http::timeout(20)->acceptJson()->post($updateServer . '/check-update', $requestPayload);
        if (!$res->successful()) {
            $sys['status'] = 'error';
            $sys['last_error'] = 'Update check failed (HTTP ' . $res->status() . ').';
            $sys['last_checked_at'] = now()->toDateTimeString();
            $this->writeSystem($sys);
            return $this->buildCheckPayload($sys, false, $sys['last_error']);
        }

        $payload = $res->json();
        if (!is_array($payload)) {
            $sys['status'] = 'error';
            $sys['last_error'] = 'Invalid update check response format.';
            $sys['last_checked_at'] = now()->toDateTimeString();
            $this->writeSystem($sys);
            return $this->buildCheckPayload($sys, false, $sys['last_error']);
        }

        $latestVersion = (string) ($payload['latest_version'] ?? $payload['version'] ?? ($sys['app_version'] ?? $sys['version']));
        $updateAvailable = (bool) ($payload['update'] ?? $payload['update_available'] ?? false);
        if (!$updateAvailable && $latestVersion !== '' && !empty($sys['app_version'])) {
            $updateAvailable = version_compare($latestVersion, (string) $sys['app_version'], '>');
        }

        $sys['latest_version'] = $latestVersion;
        $sys['update_available'] = $updateAvailable;
        $sys['download_url'] = (string) ($payload['download_url'] ?? $payload['url'] ?? '');
        $sys['hash'] = (string) ($payload['hash'] ?? '');
        $sys['release_note'] = (string) ($payload['release_note'] ?? $sys['release_note'] ?? '');
        $sys['license_status'] = (string) ($payload['license_status'] ?? ($payload['license_valid'] ?? true ? 'valid' : ($sys['license_status'] ?? 'unknown')));
        $sys['status'] = 'ok';
        $sys['last_error'] = '';
        $sys['last_checked_at'] = now()->toDateTimeString();
        $this->writeSystem($sys);

        $message = (string) ($payload['message'] ?? ($updateAvailable ? 'Update available.' : 'System is up to date.'));
        return $this->buildCheckPayload($sys, true, $message);
    }

    private function runUpdate(?string $downloadUrl = null): array
    {
        $data = $this->checkNow(true);
        if (empty($data['success'])) {
            return $data;
        }

        if (!$downloadUrl) {
            $downloadUrl = (string) ($data['download_url'] ?? '');
        }
        $downloadUrl = trim((string) $downloadUrl);

        if (empty($data['update']) || $downloadUrl === '') {
            return ['success' => false, 'message' => 'No update found.'];
        }

        $lockPath = storage_path('system/update.lock');
        $updatesDir = storage_path('updates');
        $backupsDir = storage_path('backups');
        if (!is_dir($updatesDir)) {
            @mkdir($updatesDir, 0755, true);
        }
        if (!is_dir($backupsDir)) {
            @mkdir($backupsDir, 0755, true);
        }
        if (!is_dir(dirname($lockPath))) {
            @mkdir(dirname($lockPath), 0755, true);
        }
        if (file_exists($lockPath)) {
            return ['success' => false, 'message' => 'Update already running.'];
        }

        file_put_contents($lockPath, '1');
        try {
            Artisan::call('down');
            $this->backup();

            $zipPath = storage_path('updates/update_' . time() . '.zip');
            $zipBody = Http::timeout(180)->get($downloadUrl)->body();
            if ($zipBody === '') {
                throw new \Exception('Empty update package.');
            }
            file_put_contents($zipPath, $zipBody);

            $expectedHash = (string) ($data['hash'] ?? '');
            if ($expectedHash !== '' && hash_file('sha256', $zipPath) !== $expectedHash) {
                throw new \Exception('Corrupted update');
            }

            $zip = new ZipArchive;
            if ($zip->open($zipPath) !== true) {
                throw new \Exception('Unable to open update package.');
            }
            $zip->extractTo(base_path());
            $zip->close();

            Artisan::call('optimize:clear');
            Artisan::call('optimize');

            $sys = $this->getSystem();
            $sys['app_version'] = (string) ($data['version'] ?? ($sys['app_version'] ?? $sys['version'] ?? ''));
            $sys['version'] = $sys['app_version'];
            $sys['last_update'] = date('Y-m-d H:i:s');
            $sys['status'] = 'ok';
            $sys['update_available'] = false;
            $sys['latest_version'] = $sys['app_version'];
            $sys['download_url'] = '';
            $sys['hash'] = '';
            $sys['last_error'] = '';
            $sys['last_checked_at'] = now()->toDateTimeString();
            $this->writeSystem($sys);

            Artisan::call('up');
            @unlink($lockPath);
            @unlink($zipPath);

            return [
                'success' => true,
                'message' => 'Updated successfully.',
                'version' => $sys['app_version'] ?? $sys['version'] ?? null,
                'latest_version' => $sys['latest_version'] ?? null,
                'license_status' => $sys['license_status'] ?? 'unknown',
            ];
        } catch (\Exception $e) {
            $this->rollback();
            Artisan::call('up');
            @unlink($lockPath);
            return ['success' => false, 'message' => 'Update failed: ' . $e->getMessage()];
        }
    }

    private function backup(): void
    {
        $backupPath = storage_path('backups/files_' . time() . '.zip');
        $zip = new ZipArchive;
        if ($zip->open($backupPath, ZipArchive::CREATE) !== true) {
            throw new \Exception('Unable to create backup archive.');
        }

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(base_path()));
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $zip->addFile($file->getRealPath(), substr((string) $file, strlen(base_path()) + 1));
            }
        }
        $zip->close();
    }

    private function rollback(): void
    {
        $files = glob(storage_path('backups/*.zip'));
        rsort($files);
        if (empty($files)) {
            return;
        }

        $zip = new ZipArchive;
        if ($zip->open($files[0]) !== true) {
            return;
        }
        $zip->extractTo(base_path());
        $zip->close();
    }

    private function isWithinDelay(string $lastCheckedAt, int $delayMinutes): bool
    {
        if ($lastCheckedAt === '') {
            return false;
        }
        $last = strtotime($lastCheckedAt);
        if ($last === false) {
            return false;
        }
        return (time() - $last) < ($delayMinutes * 60);
    }

    private function shouldCheckRemote(array $sys): bool
    {
        $delayMinutes = max(5, (int) ($sys['check_delay_minutes'] ?? 120));
        $lastCheckedAt = (string) ($sys['last_checked_at'] ?? '');

        return !$this->isWithinDelay($lastCheckedAt, $delayMinutes);
    }

    private function buildCheckPayload(array $sys, bool $success, string $message): array
    {
        return [
            'success' => $success,
            'message' => $message,
            'update' => (bool) ($sys['update_available'] ?? false),
            'update_available' => (bool) ($sys['update_available'] ?? false),
            'version' => (string) ($sys['app_version'] ?? $sys['version'] ?? ''),
            'latest_version' => (string) ($sys['latest_version'] ?? ''),
            'download_url' => (string) ($sys['download_url'] ?? ''),
            'hash' => (string) ($sys['hash'] ?? ''),
            'license_key' => (string) ($sys['license_key'] ?? ''),
            'license_status' => (string) ($sys['license_status'] ?? 'unknown'),
            'last_checked_at' => $sys['last_checked_at'] ?? null,
            'last_update' => $sys['last_update'] ?? null,
            'release_note' => (string) ($sys['release_note'] ?? ''),
            'status' => (string) ($sys['status'] ?? 'ok'),
            'last_error' => (string) ($sys['last_error'] ?? ''),
            'check_delay_minutes' => (int) ($sys['check_delay_minutes'] ?? 120),
        ];
    }

    private function remoteClientContext(array $sys = []): array
    {
        $domain = trim((string) ($sys['client_domain'] ?? $sys['domain'] ?? request()->getHost()));
        $appUrl = trim((string) config('app.url', ''));
        $deviceName = trim((string) ($sys['device_name'] ?? (gethostname() ?: php_uname('n'))));
        $clientIp = trim((string) request()->ip());
        $deviceId = trim((string) ($sys['device_id'] ?? ''));

        if ($domain === '' && $appUrl !== '') {
            $domain = trim((string) parse_url($appUrl, PHP_URL_HOST));
        }

        $payload = ['domain' => $domain, 'app_url' => $appUrl];
        if ($deviceId !== '') {
            $payload['device_id'] = $deviceId;
        } elseif ($deviceName !== '') {
            $payload['device_name'] = $deviceName;
            $payload['device_id'] = sha1(strtolower($deviceName) . '|' . strtolower($domain));
        }
        if ($clientIp !== '') {
            $payload['ip_address'] = $clientIp;
        }
        return array_filter($payload, static fn($value) => $value !== null && $value !== '');
    }
}

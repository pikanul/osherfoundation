<?php

namespace App\Http\Controllers\Updater;

use App\Http\Controllers\Controller;
use App\Updater\JsonUpdaterStore;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class UpdaterSettingsController extends Controller
{
    public function __construct(private readonly JsonUpdaterStore $store)
    {
    }

    public function systemInformation()
    {
        $systemInfo = (object) $this->getSystemInfo(true);
        return view('admin.settings.system-information', compact('systemInfo'));
    }

    public function systemInformationUpdate(Request $request)
    {
        $validated = $request->validate([
            'license_key' => 'required|string|max:255',
        ]);

        $licenseKey = trim((string) $validated['license_key']);
        if (!preg_match('/^[A-Za-z0-9\-]{10,}$/', $licenseKey)) {
            if ($request->ajax() || $request->wantsJson()) {
                return $this->crudError('License key format is invalid. Use at least 10 characters (A-Z, a-z, 0-9, -).');
            }
            return redirect()
                ->route('admin.system-information.index')
                ->withErrors(['license_key' => 'License key format is invalid. Use at least 10 characters (A-Z, a-z, 0-9, -).'])
                ->withInput();
        }

        $result = $this->saveLicense($licenseKey);

        if (!$result['ok']) {
            if ($request->ajax() || $request->wantsJson()) {
                return $this->crudError($result['message'] ?? 'License verification failed.');
            }
            return redirect()
                ->route('admin.system-information.index')
                ->withErrors(['license_key' => $result['message'] ?? 'License verification failed.'])
                ->withInput();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return $this->crudSuccess($result['message'] ?? 'License key verified and saved successfully.');
        }

        return redirect()
            ->route('admin.system-information.index')
            ->with('status', $result['message'] ?? 'License key verified and saved successfully.');
    }

    public function systemInformationCheckUpdate(Request $request)
    {
        $result = $this->checkUpdateNow();
        if (!$result['ok']) {
            if ($request->ajax() || $request->wantsJson()) {
                return $this->crudError($result['message'] ?? 'Unable to check for updates.');
            }
            return redirect()
                ->route('admin.system-information.index')
                ->withErrors(['license_key' => $result['message'] ?? 'Unable to check for updates.']);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return $this->crudSuccess($result['message'] ?? 'Update check completed.');
        }

        return redirect()
            ->route('admin.system-information.index')
            ->with('status', $result['message'] ?? 'Update check completed.');
    }

    public function systemInformationConfirmUpdate(Request $request)
    {
        $result = $this->confirmUpdate();
        if (!$result['ok']) {
            if ($request->ajax() || $request->wantsJson()) {
                return $this->crudError($result['message'] ?? 'Unable to confirm update.');
            }
            return redirect()
                ->route('admin.system-information.index')
                ->withErrors(['license_key' => $result['message'] ?? 'Unable to confirm update.']);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return $this->crudSuccess($result['message'] ?? 'Update confirmed.');
        }

        return redirect()
            ->route('admin.system-information.index')
            ->with('status', $result['message'] ?? 'Update confirmed.');
    }

    private function getSystemInfo(bool $checkRemote = false): array
    {
        $data = $this->store->read();
        if ($checkRemote && $this->hasValidLicenseKey($data) && $this->shouldCheckRemote($data)) {
            return $this->checkRemoteLatest($data);
        }
        return $data;
    }

    private function saveLicense(string $licenseKey): array
    {
        $current = $this->store->read();
        $remoteUrl = trim((string) ($current['remote_version_url'] ?? ''));

        if ($remoteUrl === '') {
            return ['ok' => false, 'message' => 'Remote version URL is not configured in system.json.'];
        }

        $verification = $this->verifyLicenseWithRemote($remoteUrl, $licenseKey, (string) ($current['app_version'] ?? ''), $current);

        if (!$verification['ok']) {
            $this->store->write(array_merge($current, [
                'license_key' => $licenseKey,
                'license_status' => 'invalid',
            ]));
            return $verification;
        }

        $next = array_merge($current, [
            'license_key' => $licenseKey,
            'license_status' => 'valid',
            'last_checked_at' => now()->toDateTimeString(),
        ]);

        if (!empty($verification['latest_version'])) {
            $next['latest_version'] = $verification['latest_version'];
        }
        if (!empty($verification['release_note'])) {
            $next['last_update_note'] = $verification['release_note'];
        }

        $this->store->write($next);
        return ['ok' => true, 'message' => 'License key verified and saved successfully.'];
    }

    private function checkUpdateNow(): array
    {
        $current = $this->store->read();
        if (!$this->hasValidLicenseKey($current)) {
            return ['ok' => false, 'message' => 'Valid license key is required before checking updates.'];
        }

        $data = $this->checkRemoteLatest($current);
        $message = !empty($data['update_available'])
            ? 'New version available: ' . ($data['latest_version'] ?? 'N/A')
            : 'System is already up to date.';

        return ['ok' => true, 'message' => $message, 'data' => $data];
    }

    private function confirmUpdate(): array
    {
        $before = $this->store->read();
        if (!$this->hasValidLicenseKey($before)) {
            return ['ok' => false, 'message' => 'Valid license key is required before confirming update.'];
        }
        if (empty($before['update_available'])) {
            return ['ok' => true, 'message' => 'No pending update to confirm.'];
        }

        $before['app_version'] = (string) ($before['latest_version'] ?? $before['app_version'] ?? '');
        $before['version'] = $before['app_version'];
        $before['update_available'] = false;
        $before['last_update_note'] = trim((string) ($before['last_update_note'] ?? '')) . ' | Update confirmed at ' . now()->toDateTimeString();

        $data = $this->store->write($before);
        return ['ok' => true, 'message' => 'Update confirmed. Current app version is now ' . ($data['app_version'] ?? 'N/A') . '.', 'data' => $data];
    }

    private function shouldCheckRemote(array $data): bool
    {
        $url = trim((string) ($data['remote_version_url'] ?? ''));
        if ($url === '') {
            return false;
        }
        $delay = max(5, (int) ($data['check_delay_minutes'] ?? 360));
        $lastChecked = (string) ($data['last_checked_at'] ?? '');
        if ($lastChecked === '') {
            return true;
        }
        $last = strtotime($lastChecked);
        if ($last === false) {
            return true;
        }
        return (time() - $last) >= ($delay * 60);
    }

    private function checkRemoteLatest(array $data): array
    {
        $url = trim((string) ($data['remote_version_url'] ?? ''));
        if ($url === '') {
            return $this->store->write($data);
        }

        try {
            $base = rtrim($url, '/');
            $endpoint = str_ends_with($base, '/check-update')
                ? $base
                : (str_ends_with($base, '/api') ? $base . '/check-update' : $base . '/api/check-update');

            $payload = array_merge([
                'license_key' => (string) ($data['license_key'] ?? ''),
                'version' => (string) ($data['app_version'] ?? ''),
            ], $this->remoteClientContext($data));

            $response = $this->postWithSoftwareFallback($endpoint, $payload);
            if (($response['status'] ?? 0) >= 200 && ($response['status'] ?? 0) < 300) {
                $json = $response['json'] ?? null;
                if (is_array($json)) {
                    $latest = (string) ($json['latest_version'] ?? $json['version'] ?? '');
                    if ($latest !== '') {
                        $data['latest_version'] = $latest;
                    }
                    $releaseNote = (string) ($json['release_note'] ?? $json['changelog'] ?? '');
                    if ($releaseNote !== '') {
                        $data['last_update_note'] = $releaseNote;
                    }
                    if (array_key_exists('license_valid', $json)) {
                        $data['license_status'] = (bool) $json['license_valid'] ? 'valid' : 'invalid';
                    } elseif (isset($json['license_status'])) {
                        $data['license_status'] = strtolower((string) $json['license_status']) === 'valid' ? 'valid' : (string) $json['license_status'];
                    }
                }
            }
        } catch (\Throwable $e) {
            // Keep existing values.
        }

        $data['last_checked_at'] = now()->toDateTimeString();
        return $this->store->write($data);
    }

    private function hasValidLicenseKey(array $data): bool
    {
        $licenseKey = trim((string) ($data['license_key'] ?? ''));
        if ($licenseKey === '') {
            return false;
        }
        if (!preg_match('/^[A-Za-z0-9\-]{10,}$/', $licenseKey)) {
            return false;
        }
        $licenseStatus = strtolower(trim((string) ($data['license_status'] ?? '')));
        if ($licenseStatus !== '' && $licenseStatus !== 'valid') {
            return false;
        }
        return true;
    }

    private function verifyLicenseWithRemote(string $url, string $licenseKey, string $appVersion, array $systemData = []): array
    {
        try {
            $base = rtrim($url, '/');
            $endpoint = $base . '/verify-license';

            $domain = trim((string) ($systemData['client_domain'] ?? $systemData['domain'] ?? request()->getHost()));
            $appUrl = trim((string) config('app.url', ''));
            $software = strtolower(trim((string) ($systemData['software_slug'] ?? 'oshefoundation')));
            $software = preg_replace('/[^a-z0-9\-]/', '', $software) ?: 'oshefoundation';
            $deviceName = trim((string) ($systemData['device_name'] ?? (gethostname() ?: php_uname('n'))));
            $deviceId = trim((string) ($systemData['device_id'] ?? ''));
            $clientIp = trim((string) request()->ip());

            if ($domain === '' && $appUrl !== '') {
                $domain = trim((string) parse_url($appUrl, PHP_URL_HOST));
            }

            $payload = [
                'license_key' => $licenseKey,
                'version' => $appVersion,
                'domain' => $domain,
                'software' => $software,
                'software_slug' => $software,
                'app_url' => $appUrl,
            ];

            if ($deviceId !== '') {
                $payload['device_id'] = $deviceId;
            } elseif ($deviceName !== '') {
                $payload['device_name'] = $deviceName;
                $payload['device_id'] = sha1(strtolower($deviceName) . '|' . strtolower($domain));
            }
            if ($clientIp !== '') {
                $payload['ip_address'] = $clientIp;
            }
            $payload = array_filter($payload, static fn($value) => $value !== null && $value !== '');

            $client = new Client([
                'timeout' => 10,
                'http_errors' => false,
                'verify' => false,
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
            ]);

            $responseRaw = $client->post($endpoint, ['json' => $payload]);
            $status = (int) $responseRaw->getStatusCode();
            $body = (string) $responseRaw->getBody();
            $json = json_decode($body, true);

            if (!($status >= 200 && $status < 300)) {
                $errorJson = is_array($json) ? $json : null;
                $errorText = is_array($errorJson)
                    ? (string) ($errorJson['message'] ?? '')
                    : trim($body);
                $suffix = $errorText !== '' ? (' ' . $errorText) : '';

                if ($status === 401) {
                    return ['ok' => false, 'message' => 'Remote license verification failed (HTTP 401). Unauthorized.'];
                }
                return ['ok' => false, 'message' => 'Remote license verification failed (HTTP ' . $status . ').' . $suffix];
            }

            if (!is_array($json)) {
                return ['ok' => false, 'message' => 'Remote license verification returned invalid response format.'];
            }

            $isValid = null;
            if (array_key_exists('license_valid', $json)) {
                $isValid = (bool) $json['license_valid'];
            } elseif (isset($json['license_status'])) {
                $isValid = strtolower((string) $json['license_status']) === 'valid';
            } elseif (isset($json['status'])) {
                $isValid = in_array(strtolower((string) $json['status']), ['ok', 'valid', 'success'], true);
            }

            if ($isValid !== true) {
                return ['ok' => false, 'message' => (string) ($json['message'] ?? 'License key is invalid according to remote server.')];
            }

            return [
                'ok' => true,
                'message' => 'License verified.',
                'latest_version' => (string) ($json['latest_version'] ?? $json['version'] ?? ''),
                'release_note' => (string) ($json['release_note'] ?? $json['changelog'] ?? ''),
            ];
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => 'Remote license verification error: ' . $e->getMessage()];
        }
    }

    private function resolveSoftwareSlug(array $data = []): string
    {
        $fromData = strtolower(trim((string) ($data['software_slug'] ?? '')));
        if ($fromData !== '') {
            $normalized = preg_replace('/[^a-z0-9\-]/', '', $fromData);
            return $normalized !== '' ? $normalized : 'oshefoundation';
        }
        return 'oshefoundation';
    }

    private function remoteClientContext(array $data = []): array
    {
        $domain = trim((string) ($data['client_domain'] ?? $data['domain'] ?? request()->getHost()));
        $software = $this->resolveSoftwareSlug($data);
        $appUrl = trim((string) config('app.url', ''));
        $deviceName = trim((string) ($data['device_name'] ?? (gethostname() ?: php_uname('n'))));
        $clientIp = trim((string) request()->ip());
        $deviceId = trim((string) ($data['device_id'] ?? ''));

        if ($domain === '' && $appUrl !== '') {
            $domain = trim((string) parse_url($appUrl, PHP_URL_HOST));
        }

        $payload = [
            'domain' => $domain,
            'software' => $software,
            'software_slug' => $software,
            'app_url' => $appUrl,
        ];
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

    private function postWithSoftwareFallback(string $endpoint, array $payload, array $headers = []): array
    {
        $response = $this->postJson($endpoint, $payload, $headers);
        if (!$this->isSoftwareNotFound($response)) {
            return $response;
        }

        $normalized = strtolower(trim((string) ($payload['software'] ?? '')));
        $fallbackCandidates = array_values(array_unique(array_filter([
            'oshefoundation',
            $normalized !== '' ? preg_replace('/[^a-z0-9\-]/', '', $normalized) : null,
        ])));

        foreach ($fallbackCandidates as $slug) {
            if ($slug === '' || $slug === $normalized) {
                continue;
            }
            $retryPayload = $payload;
            $retryPayload['software'] = $slug;
            $retryPayload['software_slug'] = $slug;
            $retry = $this->postJson($endpoint, $retryPayload, $headers);
            if (!$this->isSoftwareNotFound($retry)) {
                return $retry;
            }
            $response = $retry;
        }


        return $response;
    }

    private function postJson(string $endpoint, array $payload, array $headers = []): array
    {
        $client = new Client([
            'timeout' => 10,
            'http_errors' => false,
            'verify' => false,
            'headers' => array_merge([
                'Accept' => 'application/json',
            ], $headers),
        ]);

        $response = $client->post($endpoint, [
            'json' => $payload,
        ]);

        $body = (string) $response->getBody();
        $json = json_decode($body, true);

        return [
            'status' => $response->getStatusCode(),
            'json' => is_array($json) ? $json : null,
            'body' => $body,
        ];
    }

    private function isSoftwareNotFound(array $response): bool
    {
        if (($response['status'] ?? 0) !== 404) {
            return false;
        }
        $json = $response['json'] ?? null;
        $message = is_array($json)
            ? strtolower((string) ($json['message'] ?? ''))
            : strtolower(trim((string) ($response['body'] ?? '')));
        return str_contains($message, 'software not found');
    }
}

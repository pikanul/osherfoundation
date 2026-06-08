<?php

namespace App\Providers;

use Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(125);
        Paginator::useBootstrap();
        // Theme is set per-request by App\Http\Middleware\SetTheme.

        $systemInfo = $this->readSystemJson();
        view()->share('version', $systemInfo['app_version'] ?? '1.0.1.8');
        view()->share('systemInfo', $systemInfo);

        Auth::macro('hasP', function ($permission = null, $for_all_users = false, $type = 'any') {

            // Not logged in as admin → deny
            if (!auth()->guard('admin')->check()) {
                return false;
            }

            $user = auth()->guard('admin')->user();

            // Theme permission list
            $themePermissions = config('permission', []);

            // Nothing requested → deny
            if ($permission === null) {
                return false;
            }

            /*
            |--------------------------------------------------------------------------
            | 1. Theme Permission Validation
            |--------------------------------------------------------------------------
            | Confirm that requested permission(s) exist INSIDE theme permissions.
            | If not, deny immediately.
            */

            // If single string permission
            if (is_string($permission)) {
                if (!in_array($permission, $themePermissions)) {
                    return false; // invalid for theme
                }
            }

            // If array of permissions
            if (is_array($permission)) {
                // Keep only those that exist in theme
                $permission = array_intersect($permission, $themePermissions);

                // If none remain → nothing valid for this theme
                if (empty($permission)) {
                    return false;
                }
            }

            /*
           |--------------------------------------------------------------------------
            | 2. Super Admin (ID = 1)
            |--------------------------------------------------------------------------
            */
            if ($user->id == 1) {
                return true;
            }

            if ($for_all_users === true) {
                return true;
            }

            /*
            |--------------------------------------------------------------------------
            | 3. Load User Permissions
            |--------------------------------------------------------------------------
            */
            $userPermissions = $user->permissions
                ? explode(',', $user->permissions)
                : [];

            /*
            |--------------------------------------------------------------------------
            | 4. Matching Logic (string or array)
            |--------------------------------------------------------------------------
            */

            // If string permission
            if (is_string($permission)) {
                return in_array($permission, $userPermissions);
            }

            // Array of permissions
            if (is_array($permission)) {

                // ANY → at least one match
                if ($type === 'any') {
                    foreach ($permission as $perm) {
                        if (in_array($perm, $userPermissions)) {
                            return true;
                        }
                    }
                    return false;
                }

                // ALL → all must match
                if ($type === 'all') {
                    foreach ($permission as $perm) {
                        if (!in_array($perm, $userPermissions)) {
                            return false;
                        }
                    }
                    return true;
                }
            }

            return false;
        });

        Auth::macro('hasAcademicRelations', function () {
            static $cache = [];

            $currentTheme = config('database.connections.mysql.theme');

            if (array_key_exists($currentTheme, $cache)) {
                return $cache[$currentTheme];
            }

            $themePermissions = config('permission.' . $currentTheme, []);
            $requiredPermissions = ['sm_classes', 'departments', 'sections'];

            foreach ($requiredPermissions as $permission) {
                if (!in_array($permission, $themePermissions, true)) {
                    return $cache[$currentTheme] = false;
                }
            }

            return $cache[$currentTheme] = Schema::hasTable('sm_classes')
                && Schema::hasTable('departments')
                && Schema::hasTable('sections');
        });


    }

    private function readSystemJson(): array
    {
        $path = storage_path('updater/system.json');
        $baseVersion = '1.0.1.6';
        $defaults = [
            'app_version' => $baseVersion,
            'latest_version' => $baseVersion,
            'update_available' => false,
            'remote_version_url' => '',
            'check_delay_minutes' => 360,
            'last_checked_at' => null,
            'system_build_number' => 'N/A',
            'release_channel' => 'stable',
            'last_update_note' => 'No release note available.',
            'system_video_url' => '',
            'license_key' => '',
            'license_status' => 'N/A',
            'updated_at' => now()->toDateTimeString(),
        ];

        if (!is_file($path)) {
            return $defaults;
        }

        $raw = @file_get_contents($path);
        $decoded = is_string($raw) ? json_decode($raw, true) : [];
        $data = array_merge($defaults, is_array($decoded) ? $decoded : []);

        $app = (string) ($data['app_version'] ?? '');
        $latest = (string) ($data['latest_version'] ?? '');
        $data['update_available'] = ($app !== '' && $latest !== '') ? version_compare($latest, $app, '>') : false;

        return $data;
    }
}

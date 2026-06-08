<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SetTheme
{
    /**
     * Set the theme for the current request.
     *
     * - Global (web group): uses default theme
     * - Route middleware: ->middleware('theme:theme2')
     */
    public function handle(Request $request, Closure $next, ?string $theme = null)
    {
        $defaultTheme = (string) config('theme.default', 'theme1');
        $availableThemes = (array) config('theme.available', [$defaultTheme]);

        $selectedTheme = $theme ?: $defaultTheme;

        if (!empty($availableThemes) && !in_array($selectedTheme, $availableThemes, true)) {
            $selectedTheme = $defaultTheme;
        }

        config(['database.connections.mysql.theme' => $selectedTheme]);
        View::share('theme', $selectedTheme);

        return $next($request);
    }
}


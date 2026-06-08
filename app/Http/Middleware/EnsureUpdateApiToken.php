<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUpdateApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expectedToken = (string) config('services.update_api.token', '');
        $providedToken = (string) (
            $request->bearerToken()
            ?: $request->header('X-Update-Token')
            ?: $request->header('X-API-TOKEN')
            ?: $request->input('api_token')
        );

        if ($expectedToken === '') {
            return $next($request);
        }

        if (! hash_equals($expectedToken, $providedToken)) {
            return new JsonResponse([
                'message' => 'Unauthorized.',
            ], 401);
        }

        return $next($request);
    }
}

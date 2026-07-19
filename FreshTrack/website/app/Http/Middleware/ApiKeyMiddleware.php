<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $expectedKey = env('APP_API_KEY', 'freshtrack-demo-key');

        if ($request->header('X-API-KEY') !== $expectedKey) {
            return response()->json([
                'message' => 'Unauthorized. Provide a valid X-API-KEY header.',
            ], 401);
        }

        return $next($request);
    }
}

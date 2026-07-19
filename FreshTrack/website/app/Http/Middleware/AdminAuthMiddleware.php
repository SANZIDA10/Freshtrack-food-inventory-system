<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $isAdmin = $request->session()->get('is_admin', false);

        if (! $isAdmin) {
            $rememberToken = $request->cookie('admin_remember');
            $expectedRememberToken = hash('sha256', 'admin@freshtrack.com|admin123');

            if ($rememberToken === $expectedRememberToken) {
                $request->session()->put('is_admin', true);
                $isAdmin = true;
            }
        }

        if (! $isAdmin) {
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}

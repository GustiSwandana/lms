<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            return redirect()->guest(route('login'))->with('error', 'Silakan masuk terlebih dahulu untuk melanjutkan.');
        }

        $user = auth()->user();

        // Check if account is active
        if (isset($user->is_active) && ! $user->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Akun Anda dinonaktifkan oleh Administrator Pusat.');
        }

        // Support multiple comma-separated roles or variadic arguments
        $allowedRoles = [];
        foreach ($roles as $roleGroup) {
            foreach (explode(',', $roleGroup) as $r) {
                $trimmed = trim($r);
                if ($trimmed !== '') {
                    $allowedRoles[] = $trimmed;
                }
            }
        }

        if (! empty($allowedRoles) && ! in_array($user->role, $allowedRoles, true)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akses ditolak: Anda tidak memiliki izin untuk halaman ini.'], 403);
            }

            abort(403, 'Akses Ditolak: Akun Anda ('.$user->role_label.') tidak memiliki wewenang untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}

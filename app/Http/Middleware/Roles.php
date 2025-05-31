<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class Roles
{
    public function handle($request, Closure $next, $role, $guard = null)
    {
        $authGuard = Auth::guard($guard);

        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        $roles = is_array($role)
            ? $role
            : explode('|', $role);

        if (! $authGuard->user()->hasAnyRole($roles)) {
            return response()->json([
                'status'  => false,
                'message' => "El usuario no tiene el rol para acceder a este recurso.",
                'errors'  => null,
                'data'    => null,
            ], 403);
        }

        return $next($request);
    }
}

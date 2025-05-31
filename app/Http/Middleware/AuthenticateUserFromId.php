<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Modules\Auth\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthenticateUserFromId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::find($request->route('id'));

        if (!$user) {
            return response('User not found', 404);  // Ajusta esto según tus necesidades
        }

        Auth::login($user);

        return $next($request);
    }
}



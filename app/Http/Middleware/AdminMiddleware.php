<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin/signIn') || $request->is('admin/login')) {
            return $next($request);
        }

        if (!Auth::check() || !in_array(Auth::user()->role, [User::ROLES_ADMIN, User::ROLES_SUPER_ADMIN])) {
            return redirect()->route('admin.signIn');
        }
        return $next($request);
    }
}

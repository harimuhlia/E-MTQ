<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Middleware to restrict access based on user role.
 *
 * This middleware accepts a comma-separated list of roles that are
 * permitted to access the route. If the authenticated user does not
 * have a role in the allowed list, a 403 error is thrown. Use like:
 *
 * Route::middleware(['auth', 'role:administrator'])->group(function() {
 *     // routes for administrators
 * });
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $roles  Comma-separated allowed roles
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle(Request $request, Closure $next, string $roles)
    {
        $user = $request->user();
        if (! $user) {
            throw new HttpException(403, 'Unauthorized');
        }
        $allowed = array_map('trim', explode(',', $roles));
        if (! in_array($user->role, $allowed)) {
            throw new HttpException(403, 'Unauthorized');
        }
        return $next($request);
    }
}
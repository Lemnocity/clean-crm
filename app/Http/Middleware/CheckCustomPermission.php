<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCustomPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $action = null, $model = null)
    {
        $user = $request->user();

        $hasPermission = false;

        if ($action && $model) {
            $hasPermission = $user->can($action, $model);
        } elseif ($action) {
            $hasPermission = $user->can($action);
        }
        if (!$hasPermission) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }    
}

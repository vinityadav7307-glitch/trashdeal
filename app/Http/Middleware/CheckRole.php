<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        if (!$request->user() || $request->user()->role !== $role) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. You do not have permission for this action.'
            ], 403);
        }

        return $next($request);
    }
}

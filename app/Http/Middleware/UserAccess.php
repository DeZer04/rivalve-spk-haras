<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $userRoles): Response
    {
        if (auth()->user()->role == $userRoles) {
            return $next($request);
        }

        $userRole = is_array($userRoles) ? implode(', ', $userRoles) : $userRoles;
        $message = "You do not have permission to access this page. Required role(s): $userRole";

        return response()->json([
            'message' => $message,
            'required_role' => $userRole, // Add "required_role" key for clarity
        ], 403); // Set appropriate HTTP status code (e.g., 403 for Forbidden)
    }
}

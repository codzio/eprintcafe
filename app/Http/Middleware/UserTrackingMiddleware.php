<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;

class UserTrackingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Check if the user_id cookie is not set
        if (!$request->cookie('tempUserId')) {
            // Generate a unique identifier for the user
            $tempUserId = uniqid();

            // Set the user_id cookie
            Cookie::queue('tempUserId', $tempUserId, 60 * 24 * 30); // 30 days expiration
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BypassBotProtection
{
    /**
     * Handle an incoming request.
     * This middleware helps bypass InfinityFree bot protection for API requests
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Add headers that make the request look more legitimate to bot protection
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->headers->set('X-API-Request', 'true');
        
        // If request has ?i=1 parameter (InfinityFree bypass pattern), ensure it's handled
        if ($request->has('i') && $request->get('i') == '1') {
            // This indicates the request has passed bot protection
            $request->headers->set('X-Bot-Protection-Bypassed', 'true');
        }
        
        $response = $next($request);
        
        // Ensure API responses are JSON even if bot protection interfered
        if ($request->is('api/*')) {
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('X-API-Version', '1.0');
        }
        
        return $response;
    }
}


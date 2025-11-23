<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force JSON response for API requests
        $request->headers->set('Accept', 'application/json');
        
        $response = $next($request);
        
        // Ensure response is JSON
        if (!$response instanceof \Illuminate\Http\JsonResponse) {
            // If it's not already JSON, try to convert it
            if ($response->getContent() && !json_decode($response->getContent())) {
                // Only force JSON if content is not already valid JSON
                $response->headers->set('Content-Type', 'application/json');
            }
        }
        
        return $response;
    }
}


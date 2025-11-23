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
        
        // Check if response is HTML (bot protection interference)
        $content = $response->getContent();
        if ($content && (str_starts_with(trim($content), '<html') || str_starts_with(trim($content), '<!DOCTYPE'))) {
            // Bot protection is returning HTML instead of JSON
            // Return a proper JSON error response
            return response()->json([
                'success' => false,
                'message' => 'Server protection is active. Please wait a moment and retry your request with ?i=1 parameter.',
                'error' => 'bot_protection_active',
                'suggestion' => 'Add ?i=1 to your API endpoint URL to bypass protection',
            ], 503); // Service Unavailable
        }
        
        // Ensure response is JSON
        if (!$response instanceof \Illuminate\Http\JsonResponse) {
            // If it's not already JSON, try to convert it
            if ($content && !json_decode($content)) {
                // Only force JSON if content is not already valid JSON
                $response->headers->set('Content-Type', 'application/json');
            }
        }
        
        return $response;
    }
}


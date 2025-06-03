<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonPrettyPrint
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        if (strpos($response->headers->get('Content-Type', ''), 'application/json') !== false || $request->is('api/*')) {
            $content = $response->getContent();
            $json = json_decode($content);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                $response->headers->set('Content-Type', 'application/json');

                $response->setContent(json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS));
            }
        }
        
        return $response;
    }
} 
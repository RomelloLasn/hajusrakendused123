<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

trait CartSession
{
    protected function getCartSessionId()
    {
        $sessionId = session('cart_session_id');
        
        if (!$sessionId) {
            $userId = Auth::id() ? Auth::id() : 'guest';
            $sessionId = $userId . '_' . Str::random(40);
            session(['cart_session_id' => $sessionId]);
        }
        
        return $sessionId;
    }
} 
<?php

use App\Models\KonselingSession;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('session.{sessionId}', function ($user, $sessionId) {
    $session = KonselingSession::find($sessionId);
    if (!$session) return false;

    return (int) $user->id === (int) $session->konseli_id
        || (int) $user->id === (int) $session->konselor_id;
});

Broadcast::channel('konseli.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('konselor.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
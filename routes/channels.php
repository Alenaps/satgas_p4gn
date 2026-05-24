<?php

use App\Models\KonselingSession;
use Illuminate\Support\Facades\Broadcast;

/*
 * Hanya konseli atau konselor yang terlibat
 * dalam sesi tersebut yang boleh masuk ke channel
 */
Broadcast::channel('session.{sessionId}', function ($user, $sessionId) {
    $session = KonselingSession::find($sessionId);

    if (!$session) return false;

    return $user->id === $session->konseli_id
        || $user->id === $session->konselor_id;
});
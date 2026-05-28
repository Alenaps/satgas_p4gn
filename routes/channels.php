<?php

use App\Models\KonselingSession;
use Illuminate\Support\Facades\Broadcast;

/*
 * Channel untuk sesi spesifik — dipakai di halaman chat
 * Hanya konseli atau konselor yang terlibat yang boleh masuk
 */
Broadcast::channel('session.{sessionId}', function ($user, $sessionId) {
    $session = KonselingSession::find($sessionId);

    if (!$session) return false;

    return $user->id === $session->konseli_id
        || $user->id === $session->konselor_id;
});

/*
 * Channel private per-konseli
 * Dipakai untuk notif realtime status sesi (approved, rejected, completed)
 * di halaman Ruang Cerita dan Daftar Konselor milik konsuli
 */
Broadcast::channel('konseli.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/*
 * Channel private per-konselor
 * Dipakai untuk notif realtime permintaan sesi baru masuk
 * di halaman ruang praktik konselor
 */
Broadcast::channel('konselor.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
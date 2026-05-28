<?php

namespace App\Http\Controllers\Konsuli;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\KonselingSession;
use App\Models\KonselingMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use App\Events\SessionStatusUpdated;
use App\Events\KonselorStatusUpdated;

class KonselingController extends Controller
{
    public function daftarKonselor()
{
    $konselors = User::where('role', 'konselor')
        ->with('konselorProfile')
        ->get();

    $sessions = KonselingSession::where('konseli_id', Auth::id())
        ->whereIn('status', ['pending', 'active'])
        ->get();

    $busyKonselorIds = KonselingSession::whereIn('status', ['pending', 'active'])
        ->pluck('konselor_id')
        ->unique()
        ->toArray();

    return view('konsuli.konselor.index', compact('konselors', 'sessions', 'busyKonselorIds'));
}

    public function index()
    {
        $konselors = User::where('role', 'konselor')->get();
        $sessions  = KonselingSession::where('konseli_id', Auth::id())
            ->with('konselor')
            ->latest()
            ->get();

        return view('konsuli.konseling.index', compact('sessions', 'konselors'));
    }

    public function request(Request $request)
    {
        $request->validate([
            'konselor_id' => 'required|exists:users,id'
        ]);

        $existingSession = KonselingSession::where('konseli_id', Auth::id())
            ->where('konselor_id', $request->konselor_id)
            ->whereIn('status', ['pending', 'active'])
            ->first();

        if ($existingSession) {
            return redirect()->back()->with('error', 'Anda sudah memiliki sesi dengan konselor ini.');
        }

        $session = KonselingSession::create([
            'konseli_id'  => Auth::id(),
            'konselor_id' => $request->konselor_id,
            'status'      => 'pending',
        ]);

        $session->load('konseli', 'konselor');

        broadcast(new SessionStatusUpdated($session));
        event(new KonselorStatusUpdated($session->konselor, 'busy'));

        return redirect()->route('konsuli.konseling.index')
            ->with('success', 'Permintaan konseling berhasil dikirim! Silakan tunggu persetujuan konselor.');
    }

    public function chat($sessionId)
    {
        $konseling = KonselingSession::with(['konselor', 'messages.sender'])
            ->where('id', $sessionId)
            ->where('konseli_id', Auth::id())
            ->firstOrFail();

        if ($konseling->status !== 'active') {
            return redirect()->route('konsuli.konseling.index')
                ->with('error', 'Sesi konseling belum aktif atau sudah selesai.');
        }

        $konseling->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('konsuli.konseling.chat', compact('konseling'));
    }

    public function sendMessage(Request $request, $sessionId)
    {
        $request->validate(['message' => 'required|string|max:5000']);

        $session = KonselingSession::where('id', $sessionId)
            ->where('konseli_id', Auth::id())
            ->where('status', 'active')
            ->firstOrFail();

        $message = KonselingMessage::create([
            'session_id' => $sessionId,
            'sender_id'  => Auth::id(),
            'message'    => $request->message,
        ]);

        $message->load('sender');
        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function getMessages(Request $request, $sessionId)
    {
        $session = KonselingSession::where('id', $sessionId)
            ->where('konseli_id', Auth::id())
            ->firstOrFail();

        $query = $session->messages()->orderBy('created_at', 'asc');

        if ($request->filled('after_id')) {
            $query->where('id', '>', (int) $request->after_id);
        }

        $messages = $query->get(['id', 'sender_id', 'message', 'is_read', 'created_at']);

        $session->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['messages' => $messages]);
    }

    public function endSession($sessionId)
    {
        $session = KonselingSession::where('id', $sessionId)
            ->where('konseli_id', Auth::id())
            ->where('status', 'active')
            ->firstOrFail();

        $session->update(['status' => 'completed', 'ended_at' => now()]);

        $session->load('konseli', 'konselor');

        broadcast(new SessionStatusUpdated($session));
        event(new KonselorStatusUpdated($session->konselor, 'available'));

        return redirect()->route('konsuli.konseling.index')
            ->with('success', 'Sesi konseling telah diakhiri.');
    }

    public function showRiwayat($id)
    {
        $session = KonselingSession::with(['konselor', 'messages'])->findOrFail($id);

        if ($session->konseli_id !== auth()->id()) {
            abort(403);
        }

        return view('konsuli.konseling.detail', compact('session'));
    }
}
<?php

namespace App\Http\Controllers\Konsuli;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\KonselingSession;
use App\Models\KonselingMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KonselingController extends Controller
{
    
    /**
     * MENU KONSELOR - Menampilkan daftar konselor yang tersedia
     */
    public function daftarKonselor()
    {
        $konselors = User::where('role', 'konselor')->get();
        $sessions = KonselingSession::where('konseli_id', Auth::id())
            ->whereIn('status', ['pending', 'active'])
            ->get();

        return view('konsuli.konselor.index', compact('konselors', 'sessions'));
    }

    /**
     * MENU KONSELING - Menampilkan sesi konseling saya
     */
    public function index()
    {
        // Ambil semua konselor
        $konselors = User::where('role', 'konselor')->get();
        
        // Ambil sesi konseling user
        $sessions = KonselingSession::where('konseli_id', Auth::id())
            ->with('konselor')
            ->latest()
            ->get();

        return view('konsuli.konseling.index', compact('sessions', 'konselors'));
    }

    /**
     * Mengajukan permintaan konseling
     */
    public function request(Request $request)
    {
        $request->validate([
            'konselor_id' => 'required|exists:users,id'
        ]);

        // Cek apakah sudah ada sesi aktif atau pending dengan konselor yang sama
        $existingSession = KonselingSession::where('konseli_id', Auth::id())
            ->where('konselor_id', $request->konselor_id)
            ->whereIn('status', ['pending', 'active'])
            ->first();

        if ($existingSession) {
            return redirect()->back()->with('error', 'Anda sudah memiliki sesi dengan konselor ini.');
        }

        $session = KonselingSession::create([
            'konseli_id' => Auth::id(),
            'konselor_id' => $request->konselor_id,
            'status' => 'pending'
        ]);

        return redirect()->route('konsuli.konseling.index')
            ->with('success', 'Permintaan konseling berhasil dikirim! Silakan tunggu persetujuan konselor.');
    }

    /**
     * Halaman chat
     */
   public function chat($sessionId)
{
    // UBAH DARI $session JADI $konseling
    $konseling = KonselingSession::with(['konselor', 'messages.sender'])
        ->where('id', $sessionId)
        ->where('konseli_id', Auth::id())
        ->firstOrFail();

    if ($konseling->status !== 'active') {
        return redirect()->route('konsuli.konseling.index')
            ->with('error', 'Sesi konseling belum aktif atau sudah selesai.');
    }

    // Mark messages as read
    $konseling->messages()
        ->where('sender_id', '!=', Auth::id())
        ->where('is_read', false)
        ->update(['is_read' => true]);

    // UBAH compact('session') JADI compact('konseling')
    return view('konsuli.konseling.chat', compact('konseling'));
}
    /**
     * Kirim pesan
     */
    public function sendMessage(Request $request, $sessionId)
    {
        $request->validate([
            'message' => 'required|string|max:5000'
        ]);

        $session = KonselingSession::where('id', $sessionId)
            ->where('konseli_id', Auth::id())
            ->where('status', 'active')
            ->firstOrFail();

        $message = KonselingMessage::create([
            'session_id' => $sessionId,
            'sender_id' => Auth::id(),
            'message' => $request->message
        ]);

        // Load sender relationship
        $message->load('sender');

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Get new messages (AJAX)
     */
    public function getMessages($sessionId)
    {
        $session = KonselingSession::where('id', $sessionId)
            ->where('konseli_id', Auth::id())
            ->firstOrFail();

        $messages = $session->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark as read
        $session->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    /**
     * End session dari sisi konseli
     */
    public function endSession($sessionId)
    {
        $session = KonselingSession::where('id', $sessionId)
            ->where('konseli_id', Auth::id())
            ->where('status', 'active')
            ->firstOrFail();

        $session->update([
            'status' => 'completed',
            'ended_at' => now()
        ]);

        return redirect()->route('konsuli.konseling.index')
            ->with('success', 'Sesi konseling telah diakhiri.');
    }
}
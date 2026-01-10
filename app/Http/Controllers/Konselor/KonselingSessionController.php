<?php

namespace App\Http\Controllers\Konselor;

use App\Http\Controllers\Controller;
use App\Models\KonselingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KonselingSessionController extends Controller
{
    public function index()
    {
        $konselorId = Auth::id();

        // Ambil sesi pending
        $pendingSessions = KonselingSession::where('konselor_id', $konselorId)
            ->where('status', 'pending')
            ->with('konseli')
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil sesi aktif
        $activeSessions = KonselingSession::where('konselor_id', $konselorId)
            ->where('status', 'active')
            ->with('konseli')
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil sesi completed dengan pagination
        $completedSessions = KonselingSession::where('konselor_id', $konselorId)
            ->where('status', 'completed')
            ->with('konseli')
            ->orderBy('ended_at', 'desc')
            ->paginate(10);

        return view('konselor.konseling.index', compact('pendingSessions', 'activeSessions', 'completedSessions'));
    }

    public function approve(KonselingSession $session)
    {
        // Pastikan session milik konselor yang login
        if ($session->konselor_id !== Auth::id()) {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Anda tidak memiliki akses ke sesi ini.');
        }

        // Pastikan status masih pending
        if ($session->status !== 'pending') {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Sesi ini sudah diproses.');
        }

        // Update status ke active dan set started_at
        $session->update([
            'status' => 'active',
            'started_at' => now()
        ]);

        return redirect()->route('konselor.konseling.index')
            ->with('success', 'Permintaan konseling berhasil diterima.');
    }

    public function reject(KonselingSession $session)
    {
        // Pastikan session milik konselor yang login
        if ($session->konselor_id !== Auth::id()) {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Anda tidak memiliki akses ke sesi ini.');
        }

        // Pastikan status masih pending
        if ($session->status !== 'pending') {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Sesi ini sudah diproses.');
        }

        // Update status ke rejected
        $session->update([
            'status' => 'rejected'
        ]);

        return redirect()->route('konselor.konseling.index')
            ->with('success', 'Permintaan konseling berhasil ditolak.');
    }

    public function showEndSessionForm(KonselingSession $session)
    {
        // Pastikan session milik konselor yang login
        if ($session->konselor_id !== Auth::id()) {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Anda tidak memiliki akses ke sesi ini.');
        }

        // Pastikan status active
        if ($session->status !== 'active') {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Sesi ini tidak dalam status aktif.');
        }

        $session->load('konseli');
        return view('konselor.konseling.end-session', compact('session'));
    }

    public function endSession(Request $request, KonselingSession $session)
    {
        $request->validate([
            'catatan_konselor' => 'nullable|string|max:5000'
        ]);

        // Pastikan session milik konselor yang login
        if ($session->konselor_id !== Auth::id()) {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Anda tidak memiliki akses ke sesi ini.');
        }

        // Pastikan status active
        if ($session->status !== 'active') {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Sesi ini tidak dalam status aktif.');
        }

        // Update status ke completed dan set ended_at
        $session->update([
            'status' => 'completed',
            'ended_at' => now(),
            'catatan_konselor' => $request->catatan_konselor
        ]);

        return redirect()->route('konselor.konseling.index')
            ->with('success', 'Sesi konseling berhasil diselesaikan.');
    }

    public function getMessages(KonselingSession $session)
    {
        // Pastikan session milik konselor yang login
        if ($session->konselor_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $session->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read
        $session->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    public function chat(KonselingSession $session)
    {
        // Pastikan session milik konselor yang login
        if ($session->konselor_id !== Auth::id()) {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Anda tidak memiliki akses ke sesi ini.');
        }

        // Pastikan status active
        if ($session->status !== 'active') {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Sesi ini tidak aktif.');
        }

        // Load messages
        $messages = $session->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages as read (yang dikirim konseli)
        $session->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('konselor.konseling.chat', compact('session', 'messages'));
    }

    public function detail(KonselingSession $session)
    {
        // Pastikan session milik konselor yang login
        if ($session->konselor_id !== Auth::id()) {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Anda tidak memiliki akses ke sesi ini.');
        }

        // Load messages
        $messages = $session->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('konselor.konseling.detail', compact('session', 'messages'));
    }

    public function showDetail(KonselingSession $session)
{
    // Pastikan session milik konselor yang login
    if ($session->konselor_id !== Auth::id()) {
        return redirect()->route('konselor.konseling.index')
            ->with('error', 'Anda tidak memiliki akses ke sesi ini.');
    }

    // Pastikan status completed
    if ($session->status !== 'completed') {
        return redirect()->route('konselor.konseling.index')
            ->with('error', 'Hanya sesi yang sudah selesai yang dapat dilihat detailnya.');
    }

    // Load relasi yang diperlukan
    $session->load([
        'konseli',
        'messages' => function($query) {
            $query->orderBy('created_at', 'asc');
        }
    ]);

    return view('konselor.konseling.detail', compact('session'));
}

    public function sendMessage(Request $request, KonselingSession $session)
    {
        $request->validate([
            'message' => 'required|string|max:5000'
        ]);

        // Pastikan session milik konselor yang login
        if ($session->konselor_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Pastikan status active
        if ($session->status !== 'active') {
            return response()->json(['error' => 'Sesi tidak aktif'], 400);
        }

        // Simpan pesan
        $message = $session->messages()->create([
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'is_read' => false
        ]);

        $message->load('sender');

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
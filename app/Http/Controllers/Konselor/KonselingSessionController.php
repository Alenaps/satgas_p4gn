<?php

namespace App\Http\Controllers\Konselor;

use App\Http\Controllers\Controller;
use App\Models\KonselingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use App\Events\SessionStatusUpdated;

class KonselingSessionController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    // INDEX — Halaman utama ruang praktik
    // ─────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $konselorId = Auth::id();

        $pendingSessions = KonselingSession::where('konselor_id', $konselorId)
            ->where('status', 'pending')
            ->with('konseli')
            ->orderBy('created_at', 'desc')
            ->get();

        $activeSessions = KonselingSession::where('konselor_id', $konselorId)
            ->where('status', 'active')
            ->with('konseli')
            ->orderBy('created_at', 'desc')
            ->get();

        $completedQuery = KonselingSession::where('konselor_id', $konselorId)
            ->where('status', 'completed')
            ->with('konseli');

        if ($request->filled('search')) {
            $search = $request->search;
            $completedQuery->whereHas('konseli', function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%")
                      ->orWhere('npm_nip', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tanggal')) {
            $completedQuery->whereDate('started_at', $request->tanggal);
        }

        $completedSessions = $completedQuery
            ->orderBy('ended_at', 'desc')
            ->paginate(10);

        return view('konselor.konseling.index', compact(
            'pendingSessions',
            'activeSessions',
            'completedSessions'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // APPROVE — Terima permintaan konseling
    // ─────────────────────────────────────────────────────────────
    public function approve(KonselingSession $session)
    {
        if ((int) $session->konselor_id !== (int) Auth::id()) {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Anda tidak memiliki akses ke sesi ini.');
        }

        if ($session->status !== 'pending') {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Sesi ini sudah diproses.');
        }

        $session->update([
            'status'     => 'active',
            'started_at' => now(),
        ]);

        broadcast(new SessionStatusUpdated($session->fresh()));

        return redirect()->route('konselor.konseling.index')
            ->with('success', 'Permintaan konseling berhasil diterima.');
    }

    // ─────────────────────────────────────────────────────────────
    // REJECT — Tolak permintaan konseling
    // ─────────────────────────────────────────────────────────────
    public function reject(KonselingSession $session)
    {
        if ((int) $session->konselor_id !== (int) Auth::id()) {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Anda tidak memiliki akses ke sesi ini.');
        }

        if ($session->status !== 'pending') {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Sesi ini sudah diproses.');
        }

        $session->update(['status' => 'rejected']);

        broadcast(new SessionStatusUpdated($session->fresh()));

        return redirect()->route('konselor.konseling.index')
            ->with('success', 'Permintaan konseling berhasil ditolak.');
    }

    // ─────────────────────────────────────────────────────────────
    // END SESSION FORM — Form ringkasan sebelum akhiri sesi
    // ─────────────────────────────────────────────────────────────
    public function showEndSessionForm(KonselingSession $session)
    {
        if ((int) $session->konselor_id !== (int) Auth::id()) {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Anda tidak memiliki akses ke sesi ini.');
        }

        if ($session->status !== 'active') {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Sesi ini tidak dalam status aktif.');
        }

        $session->load(['konseli.statusSivitas', 'konseli.unit', 'messages']);

        return view('konselor.konseling.end-session', compact('session'));
    }

    // ─────────────────────────────────────────────────────────────
    // END SESSION — Simpan & selesaikan sesi
    // ─────────────────────────────────────────────────────────────
    public function endSession(Request $request, KonselingSession $session)
    {
        $request->validate([
            'catatan_konselor' => 'nullable|string|max:5000',
        ]);

        if ((int) $session->konselor_id !== (int) Auth::id()) {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Anda tidak memiliki akses ke sesi ini.');
        }

        if ($session->status !== 'active') {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Sesi ini tidak dalam status aktif.');
        }

        $session->update([
            'status'           => 'completed',
            'ended_at'         => now(),
            'catatan_konselor' => $request->catatan_konselor,
        ]);

        broadcast(new SessionStatusUpdated($session->fresh()));

        return redirect()->route('konselor.konseling.index')
            ->with('success', 'Sesi konseling berhasil diselesaikan.');
    }

    // ─────────────────────────────────────────────────────────────
    // CHAT — Halaman obrolan sesi aktif
    // ─────────────────────────────────────────────────────────────
    public function chat(KonselingSession $session)
    {
        if ((int) $session->konselor_id !== (int) Auth::id()) {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Anda tidak memiliki akses ke sesi ini.');
        }

        if ($session->status !== 'active') {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Sesi ini tidak aktif.');
        }

        $messages = $session->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        $session->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('konselor.konseling.chat', compact('session', 'messages'));
    }

    // ─────────────────────────────────────────────────────────────
    // GET MESSAGES — Polling / fetch pesan terbaru (JSON)
    // ─────────────────────────────────────────────────────────────
    public function getMessages(Request $request, KonselingSession $session)
    {
        if ((int) $session->konselor_id !== (int) Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

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

    // ─────────────────────────────────────────────────────────────
    // SEND MESSAGE — Kirim pesan (JSON)
    // ─────────────────────────────────────────────────────────────
    public function sendMessage(Request $request, KonselingSession $session)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        if ((int) $session->konselor_id !== (int) Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($session->status !== 'active') {
            return response()->json(['error' => 'Sesi tidak aktif'], 400);
        }

        $message = $session->messages()->create([
            'sender_id' => Auth::id(),
            'message'   => $request->message,
            'is_read'   => false,
        ]);

        $message->load('sender');

        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // DETAIL — Lihat detail sesi yang sudah selesai
    // ─────────────────────────────────────────────────────────────
    public function detail(KonselingSession $session)
    {
        if ((int) $session->konselor_id !== (int) Auth::id()) {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Anda tidak memiliki akses ke sesi ini.');
        }

        $messages = $session->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('konselor.konseling.detail', compact('session', 'messages'));
    }

    // ─────────────────────────────────────────────────────────────
    // SHOW DETAIL — Alias khusus sesi completed
    // ─────────────────────────────────────────────────────────────
    public function showDetail(KonselingSession $session)
    {
        if ((int) $session->konselor_id !== (int) Auth::id()) {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Anda tidak memiliki akses ke sesi ini.');
        }

        if ($session->status !== 'completed') {
            return redirect()->route('konselor.konseling.index')
                ->with('error', 'Hanya sesi yang sudah selesai yang dapat dilihat detailnya.');
        }

        $session->load([
            'konseli.statusSivitas',
            'konseli.unit',
            'messages' => fn($q) => $q->orderBy('created_at', 'asc'),
        ]);

        return view('konselor.konseling.detail', compact('session'));
    }
}
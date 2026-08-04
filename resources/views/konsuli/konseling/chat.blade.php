@extends('layouts.konsuli')

@section('title', 'Ruang Obrolan - SATGAS P4GN UNILA')

@section('content')

@vite(['resources/js/app.js'])

<div class="min-h-screen bg-[#0f172a] py-6 sm:py-10 font-sans relative overflow-hidden">
    <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-emerald-500/10 rounded-full blur-[100px] -z-0 -mr-48 -mt-48"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-500/10 rounded-full blur-[100px] -z-0 -ml-48 -mb-48"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10">

        {{-- Header --}}
        <div class="bg-white rounded-t-[2.5rem] shadow-2xl border-b border-slate-100 p-5 sm:p-7 sticky top-0 z-20">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('konsuli.konseling.index') }}" class="sm:hidden text-slate-400 hover:text-emerald-600 transition-colors">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-tr from-emerald-400 to-teal-300 rounded-full blur opacity-25 group-hover:opacity-50 transition duration-1000"></div>
                        @if($konseling->konselor->foto)
                            <img src="{{ asset('storage/' . $konseling->konselor->foto) }}"
                                 alt="{{ $konseling->konselor->nama }}"
                                 class="relative w-14 h-14 rounded-full object-cover border-2 border-white shadow-md">
                        @else
                            <div class="relative w-14 h-14 bg-gradient-to-br from-slate-800 to-slate-900 rounded-full flex items-center justify-center border-2 border-white shadow-md">
                                <span class="text-white font-black text-xl">{{ strtoupper(substr($konseling->konselor->nama, 0, 1)) }}</span>
                            </div>
                        @endif
                        <span class="absolute bottom-0 right-0 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full shadow-sm animate-pulse"></span>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-black text-slate-900 leading-tight tracking-tight">{{ $konseling->konselor->nama }}</h1>
                        <div class="flex items-center gap-2">
                            <span id="status-dot" class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                            {{-- Pisah antara status normal dan typing agar tidak konflik class uppercase --}}
                            <p id="status-normal" class="text-[10px] sm:text-xs text-emerald-600 font-black uppercase tracking-widest">Online | Konselor Profesional</p>
                            <p id="status-typing" class="text-[10px] sm:text-xs text-emerald-500 font-semibold hidden">sedang mengetik...</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('konsuli.konseling.index') }}" class="hidden sm:flex px-5 py-2.5 bg-slate-50 hover:bg-slate-100 rounded-2xl text-slate-600 font-black text-xs uppercase tracking-widest border border-slate-100 transition-all items-center gap-3">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
            </div>
        </div>

        {{-- Security Banner --}}
        <div class="bg-emerald-50/50 backdrop-blur-sm border-x border-b border-emerald-100/50 p-3 sm:px-8 sm:py-4 flex items-center gap-4 shadow-sm">
            <div class="w-8 h-8 bg-emerald-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-shield-alt text-emerald-500 text-sm"></i>
            </div>
            <p class="text-[11px] text-emerald-800 font-semibold leading-relaxed">
                <span class="font-black uppercase mr-1">Ruang Aman:</span> Percakapan dienkripsi. Identitas Anda dirahasiakan sepenuhnya oleh sistem SATGAS P4GN.
            </p>
        </div>

        {{-- Chat Area --}}
        <div class="bg-white/80 backdrop-blur-xl border-x border-slate-100 shadow-sm overflow-hidden flex flex-col h-[55vh] sm:h-[60vh]">
            <div id="chatMessages" class="flex-1 overflow-y-auto p-6 sm:p-10 space-y-6 scroll-smooth">

                <div id="emptyState" class="flex flex-col items-center justify-center h-full text-center {{ $konseling->messages->count() > 0 ? 'hidden' : '' }}">
                    <div class="w-24 h-24 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mb-6 border border-slate-100 shadow-inner">
                        <i class="far fa-comment-dots text-4xl text-slate-300"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 mb-2">Mulai Langkah Pertama</h3>
                    <p class="text-slate-500 text-sm max-w-xs font-medium leading-relaxed">Sapa konselormu dan bicarakan apa yang ada di pikiranmu. Kami ada untukmu.</p>
                </div>

                <div id="messagesList" class="space-y-4">
                    @php
                        $messagesArray = $konseling->messages->values()->all();
                        $totalMessages = count($messagesArray);
                    @endphp

                    @foreach($messagesArray as $index => $msg)
                        @php
                            $prevMsg = $index > 0 ? $messagesArray[$index - 1] : null;
                            $nextMsg = $index < $totalMessages - 1 ? $messagesArray[$index + 1] : null;
                            $showTime = !$nextMsg || $nextMsg->sender_id !== $msg->sender_id;
                            $isNewGroup = !$prevMsg || $prevMsg->sender_id !== $msg->sender_id;
                        @endphp

                        @if($msg->sender_id === $konseling->konselor_id)
                            <div class="flex items-end gap-3 {{ $isNewGroup ? 'mt-8' : 'mt-1' }} animate-pop-in">
                                <div class="flex flex-col max-w-[85%] sm:max-w-md">
                                    <div class="bg-white text-slate-700 rounded-[1.5rem] px-5 py-3.5 shadow-sm border border-slate-100 {{ $isNewGroup ? 'rounded-tl-none' : '' }}">
                                        <p class="text-sm leading-relaxed font-medium break-words">{{ $msg->message }}</p>
                                    </div>
                                    @if($showTime)
                                        <p class="text-[10px] font-black text-slate-400 mt-1.5 ml-2 uppercase tracking-tighter">{{ $msg->created_at->format('H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="flex items-end justify-end gap-3 {{ $isNewGroup ? 'mt-8' : 'mt-1' }} animate-pop-in">
                                <div class="flex flex-col items-end max-w-[85%] sm:max-w-md">
                                    <div class="bg-slate-900 text-white rounded-[1.5rem] px-5 py-3.5 shadow-lg shadow-slate-200/50 {{ $isNewGroup ? 'rounded-tr-none' : '' }}">
                                        <p class="text-sm leading-relaxed font-medium break-words">{{ $msg->message }}</p>
                                    </div>
                                    @if($showTime)
                                        <div class="flex items-center gap-1.5 mt-1.5 mr-2">
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">{{ $msg->created_at->format('H:i') }}</p>
                                            <i class="fas fa-check-double text-[9px] text-emerald-500"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- Typing Indicator --}}
                <div id="typingIndicator" class="hidden items-end gap-3 mt-8">
                    <div class="bg-white text-slate-700 rounded-[1.5rem] rounded-tl-none px-5 py-3.5 shadow-sm border border-slate-100">
                        <div class="flex items-center gap-1">
                            <span class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                            <span class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                            <span class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Input --}}
        <div class="bg-white border-t border-slate-100 rounded-b-[2.5rem] p-5 sm:p-7 shadow-2xl z-20 relative">
            <form id="chatForm" class="flex gap-3 sm:gap-4 items-end">
                @csrf
                <div class="flex-1 bg-slate-50 border border-slate-200 rounded-[1.8rem] overflow-hidden focus-within:ring-4 focus-within:ring-emerald-500/10 focus-within:border-emerald-400 transition-all duration-300">
                    <textarea
                        id="messageInput"
                        name="message"
                        rows="1"
                        placeholder="Tulis pesanmu di sini..."
                        class="w-full px-6 py-4 bg-transparent border-none focus:ring-0 resize-none max-h-32 text-sm text-slate-700 font-medium placeholder:text-slate-400"
                        required
                    ></textarea>
                </div>
                <button
                    type="submit"
                    id="sendButton"
                    class="h-[54px] w-[54px] sm:w-auto sm:px-8 bg-slate-900 hover:bg-emerald-600 text-white font-black rounded-[1.5rem] shadow-xl hover:shadow-emerald-500/20 transition-all duration-300 flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed group flex-shrink-0"
                >
                    <i class="fas fa-paper-plane text-lg sm:text-base group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                    <span class="hidden sm:inline uppercase tracking-widest text-xs">Kirim</span>
                </button>
            </form>
        </div>

    </div>
</div>

<style>
@keyframes popIn {
    0% { opacity: 0; transform: scale(0.9) translateY(20px); }
    100% { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-pop-in { animation: popIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.msg-optimistic { opacity: 0.6; }

#chatMessages p {
    word-break: break-all;
    overflow-wrap: anywhere;
}

#chatMessages::-webkit-scrollbar { width: 4px; }
#chatMessages::-webkit-scrollbar-track { background: transparent; }
#chatMessages::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
#chatMessages::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
textarea { scrollbar-width: none; }
textarea::-webkit-scrollbar { display: none; }

@keyframes typingPulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
}
#status-typing { animation: typingPulse 1.2s ease-in-out infinite; }
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const chatMessages    = document.getElementById('chatMessages');
    const messagesList    = document.getElementById('messagesList');
    const emptyState      = document.getElementById('emptyState');
    const chatForm        = document.getElementById('chatForm');
    const messageInput    = document.getElementById('messageInput');
    const statusDot       = document.getElementById('status-dot');
    const statusNormal    = document.getElementById('status-normal');
    const statusTyping    = document.getElementById('status-typing');
    const typingIndicator = document.getElementById('typingIndicator');

    const konselingId   = {{ $konseling->id }};
    const konselorId    = {{ $konseling->konselor_id }};
    const currentUserId = {{ auth()->id() }};
    const sendUrl       = `/konsuli/konseling/${konselingId}/send`;
    const csrfToken     = document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

    let lastMessageId  = {{ $konseling->messages->count() > 0 ? $konseling->messages->last()->id : 0 }};
    let lastSenderId   = {{ $konseling->messages->count() > 0 ? $konseling->messages->last()->sender_id : 'null' }};
    let typingTimer    = null;
    let isTyping       = false;
    let channel        = null;
    let hideTypingTimer = null;

    messageInput.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    messageInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (this.value.trim()) chatForm.dispatchEvent(new Event('submit'));
        }
    });

    // ══════════════════════════════════════════════════════════
    // HELPER
    // ══════════════════════════════════════════════════════════
    function scrollToBottom(smooth = false) {
        chatMessages.scrollTo({ top: chatMessages.scrollHeight, behavior: smooth ? 'smooth' : 'auto' });
    }

    function escapeHtml(text) {
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    function formatTime(dateStr) {
        return new Date(dateStr).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }

    function hideEmptyState() {
        if (emptyState) emptyState.classList.add('hidden');
    }

    function setStatusConnected() {
        statusDot.classList.remove('bg-red-500');
        statusDot.classList.add('bg-emerald-500');
    }

    function setStatusDisconnected() {
        statusDot.classList.remove('bg-emerald-500');
        statusDot.classList.add('bg-red-500');
        statusNormal.textContent = 'Koneksi terputus…';
        statusNormal.classList.remove('text-emerald-600');
        statusNormal.classList.add('text-red-600');
    }

    function showTyping() {
        // Tampilkan bubble typing
        typingIndicator.classList.remove('hidden');
        typingIndicator.classList.add('flex');

        // Ganti status label
        statusNormal.classList.add('hidden');
        statusTyping.classList.remove('hidden');

        scrollToBottom(true);

        // Auto hide setelah 4 detik kalau tidak ada event stop
        clearTimeout(hideTypingTimer);
        hideTypingTimer = setTimeout(() => hideTyping(), 4000);
    }

    function hideTyping() {
        // Sembunyikan bubble typing
        typingIndicator.classList.add('hidden');
        typingIndicator.classList.remove('flex');

        // Kembalikan status label
        statusNormal.classList.remove('hidden');
        statusTyping.classList.add('hidden');

        clearTimeout(hideTypingTimer);
    }

    // ══════════════════════════════════════════════════════════
    // SESI BERAKHIR
    // ══════════════════════════════════════════════════════════
    function showSessionEnded() {
        hideTyping();

        // Disable input
        messageInput.disabled = true;
        messageInput.placeholder = 'Sesi telah berakhir.';
        document.getElementById('sendButton').disabled = true;

        // Update status header
        statusDot.classList.remove('bg-emerald-500', 'bg-red-500');
        statusDot.classList.add('bg-slate-400');
        statusNormal.textContent = 'Sesi Telah Berakhir';
        statusNormal.classList.remove('text-emerald-600', 'text-red-600');
        statusNormal.classList.add('text-slate-500');
        statusNormal.classList.remove('hidden');
        statusTyping.classList.add('hidden');

        // Banner di dalam chat
        if (!document.getElementById('session-ended-banner')) {
            messagesList.insertAdjacentHTML('beforeend', `
                <div id="session-ended-banner" class="flex flex-col items-center py-8 mt-6 animate-pop-in">
                    <div class="w-px h-10 bg-slate-200 mb-6"></div>
                    <div class="bg-slate-100 border border-slate-200 rounded-[2rem] px-8 py-5 text-center max-w-sm">
                        <div class="w-12 h-12 bg-slate-200 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-lock text-slate-500 text-lg"></i>
                        </div>
                        <p class="text-sm font-black text-slate-700 mb-1">Sesi Telah Berakhir</p>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed">
                            Konselor telah mengakhiri sesi ini. Terima kasih sudah mau bercerita. 💚
                        </p>
                    </div>
                    <div class="w-px h-6 bg-slate-200 mt-6"></div>
                    <a href="{{ route('konsuli.konseling.index') }}"
                       class="mt-4 inline-flex items-center gap-2 px-6 py-3 bg-slate-900 hover:bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all duration-300">
                        <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                    </a>
                </div>
            `);
            chatMessages.scrollTo({ top: chatMessages.scrollHeight, behavior: 'smooth' });
        }
    }

    // ══════════════════════════════════════════════════════════
    // RENDER PESAN
    // ══════════════════════════════════════════════════════════
    function renderMessage(msg, isNewGroup = true, isOptimistic = false) {
        hideEmptyState();
        hideTyping();

        const el             = document.createElement('div');
        const marginClass    = isNewGroup ? 'mt-8' : 'mt-1';
        const time           = formatTime(msg.created_at || new Date().toISOString());
        const optimClass     = isOptimistic ? 'msg-optimistic' : '';
        const isFromKonselor = msg.sender_id == konselorId;

        if (!isFromKonselor) {
            const radiusClass = isNewGroup ? 'rounded-tr-none' : '';
            el.className = `flex items-end justify-end gap-3 animate-pop-in ${marginClass} ${optimClass}`;
            el.dataset.optimistic = isOptimistic ? '1' : '0';
            el.innerHTML = `
                <div class="flex flex-col items-end max-w-[85%] sm:max-w-md">
                    <div class="bg-slate-900 text-white rounded-[1.5rem] px-5 py-3.5 shadow-lg shadow-slate-200/50 ${radiusClass}">
                        <p class="text-sm leading-relaxed font-medium break-words">${escapeHtml(msg.message)}</p>
                    </div>
                    <div class="flex items-center gap-1.5 mt-1.5 mr-2">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">${time}</p>
                        <i class="fas fa-${isOptimistic ? 'clock' : 'check-double'} text-[9px] ${isOptimistic ? 'text-slate-300' : 'text-emerald-500'}"></i>
                    </div>
                </div>`;
        } else {
            const radiusClass = isNewGroup ? 'rounded-tl-none' : '';
            el.className = `flex items-end gap-3 animate-pop-in ${marginClass}`;
            el.innerHTML = `
                <div class="flex flex-col max-w-[85%] sm:max-w-md">
                    <div class="bg-white text-slate-700 rounded-[1.5rem] px-5 py-3.5 shadow-sm border border-slate-100 ${radiusClass}">
                        <p class="text-sm leading-relaxed font-medium break-words">${escapeHtml(msg.message)}</p>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 mt-1.5 ml-2 uppercase tracking-tighter">${time}</p>
                </div>`;
        }

        messagesList.appendChild(el);
        scrollToBottom(true);
        return el;
    }

    // ══════════════════════════════════════════════════════════
    // KIRIM PESAN — Optimistic UI
    // ══════════════════════════════════════════════════════════
    chatForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const message = messageInput.value.trim();
        if (!message) return;

        sendTypingEvent(false);

        messageInput.value = '';
        messageInput.style.height = 'auto';
        messageInput.focus();

        const isNewGroup    = lastSenderId !== currentUserId;
        const optimisticMsg = {
            id: null,
            sender_id: currentUserId,
            message,
            created_at: new Date().toISOString()
        };
        const optimisticEl = renderMessage(optimisticMsg, isNewGroup, true);
        lastSenderId = currentUserId;

        fetch(sendUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                lastMessageId = data.message.id;
                optimisticEl.classList.remove('msg-optimistic');
                optimisticEl.dataset.optimistic = '0';
                const icon = optimisticEl.querySelector('.fa-clock');
                if (icon) {
                    icon.classList.replace('fa-clock', 'fa-check-double');
                    icon.classList.replace('text-slate-300', 'text-emerald-500');
                }
            } else {
                markFailed(optimisticEl);
            }
        })
        .catch(() => markFailed(optimisticEl));
    });

    function markFailed(el) {
        const bubble = el.querySelector('div > div');
        if (bubble) bubble.classList.add('bg-red-800');
        el.classList.remove('msg-optimistic');
    }

    // ══════════════════════════════════════════════════════════
    // TYPING EVENT — Pusher Whisper
    // ══════════════════════════════════════════════════════════
    function sendTypingEvent(typing) {
        if (!channel) return;
        try {
            channel.whisper('typing', { typing, user_id: currentUserId });
        } catch (e) {}
    }

    messageInput.addEventListener('input', function () {
        if (!isTyping) {
            isTyping = true;
            sendTypingEvent(true);
        }
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            isTyping = false;
            sendTypingEvent(false);
        }, 2000);
    });

    // ══════════════════════════════════════════════════════════
    // PUSHER / LARAVEL ECHO — Real-time
    // ══════════════════════════════════════════════════════════
    if (typeof window.Echo !== 'undefined') {
        channel = window.Echo.private(`session.${konselingId}`);

        channel
            .listen('.message.sent', (e) => {
                const msg = e;

                if (msg.sender_id != currentUserId) {
                    const isNewGroup = lastSenderId != msg.sender_id;
                    renderMessage(msg, isNewGroup);
                    lastSenderId = msg.sender_id;
                }

                if (msg.id > lastMessageId) lastMessageId = msg.id;
            })
            .listenForWhisper('typing', (e) => {
                if (e.user_id !== currentUserId) {
                    if (e.typing) {
                        showTyping();
                    } else {
                        hideTyping();
                    }
                }
            })
            .listen('.session.updated', (e) => {
                if (e.status === 'completed') {
                    showSessionEnded();
                }
            })
            .subscribed(() => {
                setStatusConnected();
                console.info(`[Pusher] Terhubung ke channel: session.${konselingId}`);
            })
            .error(() => {
                setStatusDisconnected();
            });
    } else {
        setStatusDisconnected();
    }

    // Cek status awal dari server (jika halaman dibuka saat sesi sudah selesai)
    @if($konseling->status === 'completed')
        showSessionEnded();
    @endif

    scrollToBottom();
});
</script>
@endpush
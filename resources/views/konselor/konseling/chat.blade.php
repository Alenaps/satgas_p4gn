@extends('layouts.konselor')

@section('title', 'Ruang Obrolan - SATGAS P4GN UNILA')

@section('content')

<div class="flex flex-col -mx-4 -mt-4 md:mx-0 md:mt-0 h-[calc(100vh-4rem)] md:h-[calc(100vh-7rem)] bg-slate-50 md:rounded-3xl border-0 md:border md:border-gray-200 overflow-hidden font-sans shadow-sm relative">

    {{-- ====== HEADER ====== --}}
    <div class="bg-white border-b border-gray-100 p-4 shrink-0 z-20 shadow-sm relative">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3 sm:gap-4">
                <a href="{{ route('konselor.konseling.index') }}" class="text-gray-500 hover:text-teal-600 transition-colors py-2 px-1">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>

                <button type="button" id="btn-info-konsuli" title="Lihat Informasi Konsuli"
                        class="relative group cursor-pointer focus:outline-none">
                    @if($session->konseli->foto)
                        <img src="{{ asset('storage/' . $session->konseli->foto) }}"
                             alt="{{ $session->konseli->nama }}"
                             class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover shadow-sm ring-2 ring-teal-100 group-hover:ring-teal-400 transition-all duration-200">
                    @else
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-teal-100 to-slate-200 rounded-full flex items-center justify-center shadow-sm ring-2 ring-teal-100 group-hover:ring-teal-400 transition-all duration-200">
                            <span class="text-teal-700 font-bold text-xl">{{ strtoupper(substr($session->konseli->nama, 0, 1)) }}</span>
                        </div>
                    @endif
                    <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></span>
                    <span class="absolute -bottom-7 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-[10px] px-2 py-0.5 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-30">
                        Lihat profil
                    </span>
                </button>

                <div>
                    <button type="button" id="btn-info-konsuli-name"
                            class="text-left hover:text-teal-700 transition-colors group">
                        <h1 class="text-base sm:text-xl font-bold text-gray-800 leading-tight group-hover:text-teal-700 transition-colors">
                            {{ $session->konseli->nama }}
                        </h1>
                        <p id="user-status" class="text-[11px] sm:text-sm text-teal-600 font-medium flex items-center gap-1">
                            {{ $session->konseli->npm_nip ?? 'Mahasiswa / Konseli' }}
                            <i class="fas fa-chevron-right text-[9px] opacity-60"></i>
                        </p>
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <div id="realtime-indicator" class="hidden items-center gap-1.5 px-2.5 py-1.5 bg-green-50 border border-green-200 rounded-full">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-[10px] text-green-700 font-semibold hidden sm:inline">Live</span>
                </div>

                <a href="{{ route('konselor.konseling.end-form', $session->id) }}"
                   class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl font-medium transition-colors flex items-center gap-2 text-sm border border-red-100" title="Akhiri Sesi">
                    <i class="fas fa-power-off"></i>
                    <span class="hidden sm:inline">Akhiri Sesi</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ====== INFO BAR ====== --}}
    <div class="bg-blue-50 border-b border-blue-100 p-2.5 sm:px-6 sm:py-3 flex items-start gap-2 sm:gap-3 shrink-0">
        <i class="fas fa-info-circle text-blue-500 mt-0.5 text-sm sm:text-base"></i>
        <p class="text-[11px] sm:text-xs text-blue-700 leading-relaxed">
            Anda terhubung sebagai Konselor. Berikan panduan yang empatik dan suportif. Silakan akhiri sesi jika konseli sudah merasa terbantu.
        </p>
    </div>

    {{-- ====== AREA PESAN ====== --}}
    <div id="messages-container" class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4 bg-slate-50/50">
        <div id="empty-state" class="flex flex-col items-center justify-center h-full text-center {{ count($messages) > 0 ? 'hidden' : '' }}">
            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-teal-50 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-comments text-3xl sm:text-4xl text-teal-300"></i>
            </div>
            <h3 class="text-lg sm:text-xl font-bold text-gray-700 mb-2">Sesi Dimulai</h3>
            <p class="text-gray-500 text-xs sm:text-sm max-w-xs">Belum ada pesan. Sapalah konseli Anda untuk membuka percakapan.</p>
        </div>

        <div id="messages-list" class="space-y-2 pb-2">
            @php
                $messagesArray = is_array($messages) ? $messages : $messages->values()->all();
                $totalMessages = count($messagesArray);
            @endphp

            @foreach($messagesArray as $index => $msg)
                @php
                    $prevMsg  = $index > 0 ? $messagesArray[$index - 1] : null;
                    $nextMsg  = $index < $totalMessages - 1 ? $messagesArray[$index + 1] : null;
                    $showTime = !$nextMsg || $nextMsg->sender_id !== $msg->sender_id;
                    $isNewGroup = !$prevMsg || $prevMsg->sender_id !== $msg->sender_id;
                @endphp

                @if($msg->sender_id != auth()->id())
                    <div class="flex items-end gap-2 {{ $isNewGroup ? 'mt-4' : 'mt-1' }}">
                        <div class="flex flex-col max-w-[85%] sm:max-w-md">
                            <div class="bg-white text-gray-800 rounded-2xl px-4 py-2.5 sm:py-3 shadow-sm border border-gray-100 {{ $isNewGroup ? 'rounded-tl-sm' : '' }}">
                                <p class="text-[13px] sm:text-sm leading-relaxed break-words">{{ $msg->message }}</p>
                            </div>
                            @if($showTime)
                                <p class="text-[10px] sm:text-[11px] text-gray-400 mt-1 ml-1 chat-timestamp" data-timestamp="{{ $msg->created_at->toISOString() }}"></p>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="flex items-end justify-end gap-2 {{ $isNewGroup ? 'mt-4' : 'mt-1' }}">
                        <div class="flex flex-col items-end max-w-[85%] sm:max-w-md">
                            <div class="bg-teal-600 text-white rounded-2xl px-4 py-2.5 sm:py-3 shadow-sm {{ $isNewGroup ? 'rounded-tr-sm' : '' }}">
                                <p class="text-[13px] sm:text-sm leading-relaxed break-words">{{ $msg->message }}</p>
                            </div>
                            @if($showTime)
                                <div class="flex items-center gap-1 mt-1 mr-1">
                                    <p class="text-[10px] sm:text-[11px] text-gray-400 chat-timestamp" data-timestamp="{{ $msg->created_at->toISOString() }}"></p>
                                    <i class="fas fa-check-double text-[10px] text-teal-500"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Typing Indicator --}}
        <div id="typing-indicator" class="hidden flex items-end gap-2 mt-4">
            <div class="bg-white rounded-2xl rounded-tl-sm px-4 py-3 shadow-sm border border-gray-100">
                <div class="flex items-center gap-1">
                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                </div>
            </div>
        </div>
    </div>

    {{-- ====== INPUT AREA ====== --}}
    <div class="bg-white border-t border-gray-200 p-3 sm:p-4 shrink-0 z-20 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.02)]">
        <form id="chat-form" onsubmit="event.preventDefault();" class="flex gap-2 sm:gap-3 items-end max-w-5xl mx-auto">
            @csrf
            <div class="flex-1 bg-gray-50 border border-gray-200 rounded-2xl overflow-hidden focus-within:ring-2 focus-within:ring-teal-100 focus-within:border-teal-400 transition-all flex items-center">
                <textarea
                    id="message-input"
                    name="message"
                    rows="1"
                    placeholder="Ketik pesan..."
                    class="w-full px-4 py-3 bg-transparent border-none focus:ring-0 resize-none max-h-32 text-[13px] sm:text-sm text-gray-700 m-0"
                    required
                ></textarea>
            </div>
            <button
                type="submit"
                id="send-btn"
                class="h-11 w-11 sm:h-12 sm:w-auto sm:px-6 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-full sm:rounded-2xl shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2 disabled:opacity-50 flex-shrink-0"
            >
                <i class="fas fa-paper-plane text-[15px] sm:text-base sm:-ml-1"></i>
                <span class="hidden sm:inline">Kirim</span>
            </button>
        </form>
    </div>
</div>

{{-- MODAL INFO KONSULI --}}
<div id="modal-info-konsuli"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">

    <div id="modal-backdrop"
         class="absolute inset-0 bg-black/50 backdrop-blur-sm"
         onclick="closeModalInfo()"></div>

    <div id="modal-panel"
         class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-hidden flex flex-col transform scale-95 transition-transform duration-300">

        <div class="bg-gradient-to-br from-teal-600 to-teal-700 px-6 pt-8 pb-16 relative overflow-hidden shrink-0">
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="absolute bottom-0 left-0 w-28 h-28 bg-white/5 rounded-full translate-y-12 -translate-x-8"></div>

            <button onclick="closeModalInfo()"
                    class="absolute top-4 right-4 w-8 h-8 bg-white/20 hover:bg-white/30 text-white rounded-full flex items-center justify-center transition-colors">
                <i class="fas fa-times text-sm"></i>
            </button>

            <div class="flex flex-col items-center text-center relative z-10">
                <div class="mb-3">
                    @if($session->konseli->foto)
                        <img src="{{ asset('storage/' . $session->konseli->foto) }}"
                             alt="{{ $session->konseli->nama }}"
                             class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-xl">
                    @else
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-teal-200 to-teal-400 border-4 border-white shadow-xl flex items-center justify-center">
                            <span class="text-white font-bold text-4xl">{{ strtoupper(substr($session->konseli->nama, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>
                <h2 class="text-white font-bold text-xl leading-tight">{{ $session->konseli->nama }}</h2>
                <p class="text-teal-100 text-sm mt-1">{{ $session->konseli->npm_nip ?? '-' }}</p>
            </div>
        </div>

        <div class="px-6 -mt-6 flex flex-wrap justify-center gap-2 shrink-0 relative z-10">
            <span class="bg-white shadow-md border border-teal-100 text-teal-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                <i class="fas fa-user-graduate mr-1 text-[10px]"></i>
                @if($session->konseli->statusSivitas)
                    {{ $session->konseli->statusSivitas->nama }}
                @else
                    Konseli
                @endif
            </span>
            @if($session->konseli->unit)
                <span class="bg-white shadow-md border border-blue-100 text-blue-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                    <i class="fas fa-building mr-1 text-[10px]"></i>
                    {{ $session->konseli->unit->kategori_unit ?? 'Unit' }}
                </span>
            @endif
        </div>

        <div class="overflow-y-auto flex-1 px-6 py-5 space-y-3">
            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-2xl">
                <div class="w-8 h-8 bg-teal-50 rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fas fa-envelope text-teal-600 text-xs"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide">Email</p>
                    <p class="text-sm text-gray-800 font-medium break-all mt-0.5">{{ $session->konseli->email ?? '-' }}</p>
                </div>
            </div>

            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-2xl">
                <div class="w-8 h-8 bg-teal-50 rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fas fa-phone text-teal-600 text-xs"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide">No. Telepon</p>
                    <p class="text-sm text-gray-800 font-medium mt-0.5">{{ $session->konseli->no_telp ?? '-' }}</p>
                </div>
            </div>

            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-2xl">
                <div class="w-8 h-8 bg-teal-50 rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fas fa-venus-mars text-teal-600 text-xs"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide">Jenis Kelamin</p>
                    <p class="text-sm text-gray-800 font-medium mt-0.5">{{ $session->konseli->jenis_kelamin ?? '-' }}</p>
                </div>
            </div>

            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-2xl">
                <div class="w-8 h-8 bg-teal-50 rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fas fa-id-badge text-teal-600 text-xs"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide">Status Sivitas</p>
                    <p class="text-sm text-gray-800 font-medium mt-0.5">
                        @if($session->konseli->statusSivitas)
                            {{ $session->konseli->statusSivitas->nama }}
                        @else
                            <span class="text-gray-400 italic">Belum diisi</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-2xl">
                <div class="w-8 h-8 bg-teal-50 rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fas fa-layer-group text-teal-600 text-xs"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide">Kategori Unit</p>
                    <p class="text-sm text-gray-800 font-medium mt-0.5">
                        @if($session->konseli->unit && $session->konseli->unit->kategori_unit)
                            {{ $session->konseli->unit->kategori_unit }}
                        @else
                            <span class="text-gray-400 italic">Belum diisi</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-2xl">
                <div class="w-8 h-8 bg-teal-50 rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fas fa-university text-teal-600 text-xs"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide">Unit / Fakultas</p>
                    <p class="text-sm text-gray-800 font-medium mt-0.5">
                        @if($session->konseli->unit)
                            {{ $session->konseli->unit->nama_unit }}
                        @else
                            <span class="text-gray-400 italic">Belum diisi</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 shrink-0">
            <button onclick="closeModalInfo()"
                    class="w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-2xl transition-colors text-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

<style>
@keyframes popIn {
    0% { opacity: 0; transform: scale(0.95) translateY(10px); }
    100% { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-pop-in {
    animation: popIn 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
}
#messages-container p {
    word-break: break-all;
    overflow-wrap: anywhere;
}
#messages-container::-webkit-scrollbar { width: 6px; }
#messages-container::-webkit-scrollbar-track { background: transparent; }
#messages-container::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
#messages-container::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
textarea { scrollbar-width: none; }
textarea::-webkit-scrollbar { display: none; }
#modal-panel > div.overflow-y-auto::-webkit-scrollbar { width: 4px; }
#modal-panel > div.overflow-y-auto::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>

@endsection

@push('scripts')
<script>
    const sessionId     = {{ $session->id }};
    const sendUrl       = "{{ route('konselor.konseling.send', $session->id) }}";
    const currentUserId = {{ auth()->id() }};

    const messagesContainer = document.getElementById('messages-container');
    const messagesList      = document.getElementById('messages-list');
    const emptyState        = document.getElementById('empty-state');
    const chatForm          = document.getElementById('chat-form');
    const messageInput      = document.getElementById('message-input');
    const sendBtn           = document.getElementById('send-btn');
    const realtimeIndicator = document.getElementById('realtime-indicator');
    const typingIndicator   = document.getElementById('typing-indicator');
    const userStatus        = document.getElementById('user-status');

    let lastMessageId = {{ count($messagesArray ?? []) > 0 ? collect($messagesArray)->last()->id : 0 }};
    let lastSenderId  = {{ count($messagesArray ?? []) > 0 ? collect($messagesArray)->last()->sender_id : 'null' }};
    let typingTimer   = null;
    let isTyping      = false;
    let channel       = null;

    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    async function sendMessage() {
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
            message: message,
            created_at: new Date().toISOString()
        };
        const optimisticEl = addMessage(optimisticMsg, true, isNewGroup, true);
        lastSenderId = currentUserId;

        try {
            const response = await fetch(sendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message })
            });

            const data = await response.json();

            if (data.success) {
                lastMessageId = data.message.id;
                optimisticEl.classList.remove('opacity-60');
                const icon = optimisticEl.querySelector('.fa-clock');
                if (icon) {
                    icon.classList.replace('fa-clock', 'fa-check-double');
                    icon.classList.replace('text-gray-300', 'text-teal-500');
                }
            } else {
                markFailed(optimisticEl);
                messageInput.value = message;
            }
        } catch (error) {
            console.error('Error:', error);
            markFailed(optimisticEl);
            messageInput.value = message;
        }
    }

    function markFailed(el) {
        const bubble = el.querySelector('.bg-teal-600');
        if (bubble) bubble.classList.add('bg-red-600');
        el.classList.remove('opacity-60');
    }

    chatForm.addEventListener('submit', e => { e.preventDefault(); sendMessage(); });

    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (this.value.trim()) sendMessage();
        }
    });

    function sendTypingEvent(typing) {
        if (!channel) return;
        try {
            channel.whisper('typing', { typing, user_id: currentUserId });
        } catch (e) {}
    }

    messageInput.addEventListener('input', function() {
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

    function hideEmptyState() {
        if (emptyState) emptyState.classList.add('hidden');
    }

    function scrollToBottom(smooth = false) {
        messagesContainer.scrollTo({
            top: messagesContainer.scrollHeight,
            behavior: smooth ? 'smooth' : 'auto'
        });
    }

    function escapeHtml(text) {
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    function formatTime(dateStr) {
        return new Date(dateStr).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    }

    function showTyping() {
        typingIndicator.classList.remove('hidden');
        typingIndicator.classList.add('flex');
        userStatus.innerHTML = `<span class="text-teal-500 font-semibold animate-pulse">sedang mengetik...</span>`;
        scrollToBottom(true);
    }

    function hideTyping() {
        typingIndicator.classList.add('hidden');
        typingIndicator.classList.remove('flex');
        userStatus.innerHTML = `{{ $session->konseli->npm_nip ?? 'Mahasiswa / Konseli' }} <i class="fas fa-chevron-right text-[9px] opacity-60"></i>`;
    }

    function addMessage(msg, isOwn, isNewGroup = true, isOptimistic = false) {
        hideEmptyState();
        hideTyping();

        const messageDiv  = document.createElement('div');
        const time        = formatTime(msg.created_at);
        const marginClass = isNewGroup ? 'mt-4' : 'mt-1';
        const optimClass  = isOptimistic ? 'opacity-60' : '';

        if (isOwn) {
            const radiusClass = isNewGroup ? 'rounded-tr-sm' : '';
            messageDiv.className = `flex items-end justify-end gap-2 animate-pop-in ${marginClass} ${optimClass}`;
            messageDiv.innerHTML = `
                <div class="flex flex-col items-end max-w-[85%] sm:max-w-md">
                    <div class="bg-teal-600 text-white rounded-2xl px-4 py-2.5 sm:py-3 shadow-sm ${radiusClass}">
                        <p class="text-[13px] sm:text-sm leading-relaxed break-words">${escapeHtml(msg.message)}</p>
                    </div>
                    <div class="flex items-center gap-1 mt-1 mr-1">
                        <p class="text-[10px] sm:text-[11px] text-gray-400">${time}</p>
                        <i class="fas fa-${isOptimistic ? 'clock' : 'check-double'} text-[10px] ${isOptimistic ? 'text-gray-300' : 'text-teal-500'}"></i>
                    </div>
                </div>`;
        } else {
            const radiusClass = isNewGroup ? 'rounded-tl-sm' : '';
            messageDiv.className = `flex items-end gap-2 animate-pop-in ${marginClass}`;
            messageDiv.innerHTML = `
                <div class="flex flex-col max-w-[85%] sm:max-w-md">
                    <div class="bg-white text-gray-800 rounded-2xl px-4 py-2.5 sm:py-3 shadow-sm border border-gray-100 ${radiusClass}">
                        <p class="text-[13px] sm:text-sm leading-relaxed break-words">${escapeHtml(msg.message)}</p>
                    </div>
                    <p class="text-[10px] sm:text-[11px] text-gray-400 mt-1 ml-1">${time}</p>
                </div>`;
        }

        messagesList.appendChild(messageDiv);
        scrollToBottom(true);
        return messageDiv;
    }

    function initEchoChat() {
        if (typeof window.Echo === 'undefined') {
            setTimeout(initEchoChat, 200);
            return;
        }

        const channelName = `session.${sessionId}`;
        channel = window.Echo.private(channelName);

        channel
            .listen('.message.sent', (e) => {
                if (e.sender_id !== currentUserId) {
                    const isNewGroup = lastSenderId !== e.sender_id;
                    addMessage(e, false, isNewGroup);
                    lastSenderId = e.sender_id;
                }
                if (e.id > lastMessageId) lastMessageId = e.id;
            })
            .listenForWhisper('typing', (e) => {
                if (e.user_id !== currentUserId) {
                    e.typing ? showTyping() : hideTyping();
                }
            })
            .subscribed(() => {
                realtimeIndicator.classList.remove('hidden');
                realtimeIndicator.classList.add('flex');
                console.log(`[Pusher] Terhubung ke channel: ${channelName}`);
            })
            .error((err) => {
                realtimeIndicator.classList.add('hidden');
                realtimeIndicator.classList.remove('flex');
                console.error('[Pusher] Gagal terhubung:', err);
            });

        scrollToBottom();
    }

    initEchoChat();

    // Format timestamps from Blade
    document.querySelectorAll('.chat-timestamp').forEach(el => {
        if (el.dataset.timestamp) {
            el.textContent = formatTime(el.dataset.timestamp);
        }
    });

    function closeModalInfo() {
        const modal = document.getElementById('modal-info-konsuli');
        const panel = document.getElementById('modal-panel');
        modal.classList.add('opacity-0', 'pointer-events-none');
        panel.classList.add('scale-95');
    }

    document.getElementById('btn-info-konsuli').addEventListener('click', () => {
        const modal = document.getElementById('modal-info-konsuli');
        const panel = document.getElementById('modal-panel');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        panel.classList.remove('scale-95');
    });

    document.getElementById('btn-info-konsuli-name').addEventListener('click', () => {
        document.getElementById('btn-info-konsuli').click();
    });
</script>
@endpush
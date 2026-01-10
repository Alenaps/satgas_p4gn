@extends('layouts.konsuli')

@section('title', 'Chat Konseling - SATGAS P4GN UNILA')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-8">
    <div class="max-w-5xl mx-auto px-4">

        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    @if($konseling->konselor->foto_profil)
                        <img src="{{ asset('storage/' . $konseling->konselor->foto_profil) }}" 
                             alt="{{ $konseling->konselor->name }}"
                             class="w-16 h-16 rounded-full object-cover shadow-lg border-4 border-green-500">
                    @else
                        <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-2xl">{{ strtoupper(substr($konseling->konselor->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $konseling->konselor->name }}</h1>
                        <p class="text-sm text-gray-500 mt-1">Konselor Professional</p>
                    </div>
                </div>
                <a href="{{ route('konsuli.konseling.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-gray-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            
            <!-- Chat Messages Area -->
            <div id="chatMessages" class="h-[500px] overflow-y-auto p-6 bg-gradient-to-b from-white to-gray-50">
                <!-- Empty State -->
                <div id="emptyState" class="flex flex-col items-center justify-center h-full text-gray-400 {{ $konseling->messages->count() > 0 ? 'hidden' : '' }}">
                    <svg class="w-20 h-20 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-lg">Belum ada pesan</p>
                    <p class="text-sm">Mulai percakapan dengan mengirim pesan</p>
                </div>

                <!-- Messages List -->
                <div id="messagesList" class="space-y-1">
                    @php
                        $messagesArray = $konseling->messages->values()->all();
                        $totalMessages = count($messagesArray);
                    @endphp
                    
                    @foreach($messagesArray as $index => $msg)
                        @php
                            $prevMsg = $index > 0 ? $messagesArray[$index - 1] : null;
                            $nextMsg = $index < $totalMessages - 1 ? $messagesArray[$index + 1] : null;
                            
                            $showAvatar = !$nextMsg || $nextMsg->sender_id !== $msg->sender_id;
                            $showTime = $showAvatar;
                            $isNewGroup = !$prevMsg || $prevMsg->sender_id !== $msg->sender_id;
                        @endphp
                        
                        @if($msg->sender_id === $konseling->konselor_id)
                            <!-- Pesan dari Konselor (Kiri) -->
                            <div class="flex items-end gap-2 {{ $isNewGroup ? 'mt-3' : 'mt-0.5' }}">
                                <!-- Avatar / Spacer -->
                                <div class="w-8 h-8 flex-shrink-0">
                                    @if($showAvatar)
                                        <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-md">
                                            <span class="text-white font-semibold text-xs">{{ strtoupper(substr($konseling->konselor->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Message Bubble -->
                                <div class="flex flex-col max-w-md">
                                    <div class="bg-white text-gray-800 rounded-2xl px-4 py-2.5 shadow-sm border border-gray-200">
                                        <p class="text-sm leading-relaxed break-words">{{ $msg->message }}</p>
                                    </div>
                                    @if($showTime)
                                        <p class="text-xs text-gray-400 mt-0.5 ml-2">{{ $msg->created_at->format('H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <!-- Pesan dari User (Kanan) -->
                            <div class="flex items-end gap-2 justify-end {{ $isNewGroup ? 'mt-3' : 'mt-0.5' }}">
                                <!-- Message Bubble -->
                                <div class="flex flex-col items-end max-w-md">
                                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl px-4 py-2.5 shadow-md">
                                        <p class="text-sm leading-relaxed break-words">{{ $msg->message }}</p>
                                    </div>
                                    @if($showTime)
                                        <p class="text-xs text-gray-400 mt-0.5 mr-2">{{ $msg->created_at->format('H:i') }}</p>
                                    @endif
                                </div>
                                
                                <!-- Avatar / Spacer -->
                                <div class="w-8 h-8 flex-shrink-0">
                                    @if($showAvatar)
                                        <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center shadow-md">
                                            <span class="text-white font-semibold text-xs">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Input Area -->
            <div class="bg-white border-t border-gray-200 p-4">
                <form id="chatForm" class="flex gap-3">
                    @csrf
                    <input 
                        type="text" 
                        id="messageInput"
                        name="message" 
                        placeholder="Ketik pesan Anda..." 
                        class="flex-1 px-5 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                        autocomplete="off"
                        required
                    >
                    <button 
                        type="submit" 
                        id="sendButton"
                        class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Kirim
                    </button>
                </form>
            </div>

        </div>

        <!-- Info Card -->
        <div class="mt-6 bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm text-gray-700">
                    <p class="font-semibold text-yellow-800 mb-1">Informasi Penting:</p>
                    <ul class="space-y-1 text-gray-600">
                        <li>• Semua percakapan bersifat rahasia dan aman</li>
                        <li>• Pesan akan dibalas oleh konselor secepatnya</li>
                        <li>• Gunakan bahasa yang sopan dan jelas</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}

#chatMessages::-webkit-scrollbar {
    width: 8px;
}

#chatMessages::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#chatMessages::-webkit-scrollbar-thumb {
    background: #10b981;
    border-radius: 10px;
}

#chatMessages::-webkit-scrollbar-thumb:hover {
    background: #059669;
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatMessages = document.getElementById('chatMessages');
    const messagesList = document.getElementById('messagesList');
    const emptyState = document.getElementById('emptyState');
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const konselingId = {{ $konseling->id }};
    const konselorId = {{ $konseling->konselor_id }};
    const currentUserId = {{ auth()->id() }};
    const konselorName = "{{ $konseling->konselor->name }}";
    const userName = "{{ auth()->user()->name }}";

    let lastMessageCount = {{ $konseling->messages->count() }};

    // Hide empty state
    function hideEmptyState() {
        if (emptyState) {
            emptyState.classList.add('hidden');
        }
    }

    // Scroll ke bawah
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Initial scroll
    scrollToBottom();

    // Escape HTML
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    // Add single message to DOM
    function addMessage(msg, prevMsg, nextMsg) {
        hideEmptyState();

        const showAvatar = !nextMsg || nextMsg.sender_id !== msg.sender_id;
        const showTime = showAvatar;
        const isNewGroup = !prevMsg || prevMsg.sender_id !== msg.sender_id;
        
        const messageDiv = document.createElement('div');
        const time = new Date(msg.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        const marginClass = isNewGroup ? 'mt-3' : 'mt-0.5';
        
        if (msg.sender_id === konselorId) {
            // Pesan dari konselor
            const initial = konselorName.charAt(0).toUpperCase();
            
            messageDiv.className = `flex items-end gap-2 animate-fade-in ${marginClass}`;
            messageDiv.innerHTML = `
                <div class="w-8 h-8 flex-shrink-0">
                    ${showAvatar ? `
                        <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center shadow-md">
                            <span class="text-white font-semibold text-xs">${initial}</span>
                        </div>
                    ` : ''}
                </div>
                <div class="flex flex-col max-w-md">
                    <div class="bg-white text-gray-800 rounded-2xl px-4 py-2.5 shadow-sm border border-gray-200">
                        <p class="text-sm leading-relaxed break-words">${escapeHtml(msg.message)}</p>
                    </div>
                    ${showTime ? `<p class="text-xs text-gray-400 mt-0.5 ml-2">${time}</p>` : ''}
                </div>
            `;
        } else {
            // Pesan dari user
            const initial = userName.charAt(0).toUpperCase();
            
            messageDiv.className = `flex items-end gap-2 justify-end animate-fade-in ${marginClass}`;
            messageDiv.innerHTML = `
                <div class="flex flex-col items-end max-w-md">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-2xl px-4 py-2.5 shadow-md">
                        <p class="text-sm leading-relaxed break-words">${escapeHtml(msg.message)}</p>
                    </div>
                    ${showTime ? `<p class="text-xs text-gray-400 mt-0.5 mr-2">${time}</p>` : ''}
                </div>
                <div class="w-8 h-8 flex-shrink-0">
                    ${showAvatar ? `
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center shadow-md">
                            <span class="text-white font-semibold text-xs">${initial}</span>
                        </div>
                    ` : ''}
                </div>
            `;
        }
        
        messagesList.appendChild(messageDiv);
    }

    // Handle form submit
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;

        // Disable input sementara
        messageInput.disabled = true;
        sendButton.disabled = true;

        // Kirim pesan
        fetch(`/konsuli/konseling/${konselingId}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageInput.value = '';
                // Langsung tambahkan pesan tanpa reload semua
                addMessage(data.message, null, null);
                lastMessageCount++;
                scrollToBottom();
            } else {
                alert('Gagal mengirim pesan: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim pesan');
        })
        .finally(() => {
            messageInput.disabled = false;
            sendButton.disabled = false;
            messageInput.focus();
        });
    });

    // Check for new messages (hanya tambah yang baru, jangan reload semua)
    function checkNewMessages() {
        fetch(`/konsuli/konseling/${konselingId}/messages`)
            .then(response => response.json())
            .then(messages => {
                if (messages && Array.isArray(messages)) {
                    // Hanya tambah jika ada pesan baru
                    if (messages.length > lastMessageCount) {
                        const newMessages = messages.slice(lastMessageCount);
                        newMessages.forEach((msg, index) => {
                            const prevMsg = index > 0 ? newMessages[index - 1] : messages[lastMessageCount - 1];
                            const nextMsg = index < newMessages.length - 1 ? newMessages[index + 1] : null;
                            addMessage(msg, prevMsg, nextMsg);
                        });
                        lastMessageCount = messages.length;
                        scrollToBottom();
                    }
                }
            })
            .catch(error => console.error('Error loading messages:', error));
    }

    // Poll setiap 5 detik
    setInterval(checkNewMessages, 5000);

    // Focus input
    messageInput.focus();
});
</script>
@endpush
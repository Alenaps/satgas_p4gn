@extends('layouts.konselor')

@section('title', 'Chat Konseling')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    <!-- Header Chat -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('konselor.konseling.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                    @if($session->konseli->foto)
                        <img src="{{ asset('storage/' . $session->konseli->foto) }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-user text-gray-400 text-xl"></i>
                    @endif
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">{{ $session->konseli->nama }}</h3>
                    <p class="text-sm text-gray-600">{{ $session->konseli->npm_nip }}</p>
                </div>
            </div>
            <a href="{{ route('konselor.konseling.end-form', $session->id) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-check-circle mr-2"></i>Akhiri Sesi
            </a>
        </div>
    </div>

    <!-- Chat Container -->
    <div class="bg-white rounded-lg shadow-md flex flex-col h-[600px]">
        
        <!-- Messages Area -->
        <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4">
            <!-- Empty State -->
            <div id="empty-state" class="flex items-center justify-center h-full {{ count($messages) > 0 ? 'hidden' : '' }}">
                <div class="text-center text-gray-400">
                    <i class="fas fa-comments text-4xl mb-3"></i>
                    <p>Belum ada pesan. Mulai percakapan!</p>
                </div>
            </div>

            <!-- Messages List -->
            <div id="messages-list" class="space-y-4">
                @foreach($messages as $message)
                    @if($message->sender_id == auth()->id())
                        <!-- Pesan dari Konselor (Kanan) -->
                        <div class="flex justify-end">
                            <div class="max-w-[70%]">
                                <div class="bg-green-600 text-white rounded-lg px-4 py-3">
                                    <p class="text-sm">{{ $message->message }}</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 text-right">
                                    {{ $message->created_at->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    @else
                        <!-- Pesan dari Konseli (Kiri) -->
                        <div class="flex justify-start">
                            <div class="max-w-[70%]">
                                <div class="flex items-start gap-2">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden flex-shrink-0">
                                        @if($message->sender->foto)
                                            <img src="{{ asset('storage/' . $message->sender->foto) }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-user text-gray-400 text-xs"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="bg-gray-100 text-gray-800 rounded-lg px-4 py-3">
                                            <p class="text-sm">{{ $message->message }}</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $message->created_at->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Input Area -->
        <div class="border-t p-4">
            <form id="chat-form" action="{{ route('konselor.konseling.send', $session->id) }}" method="POST" class="flex gap-2">
                @csrf
                <input 
                    type="text" 
                    id="message-input" 
                    name="message"
                    placeholder="Ketik pesan Anda..." 
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    autocomplete="off"
                    required
                >
                <button 
                    type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    const sessionId = {{ $session->id }};
    const sendUrl = "{{ route('konselor.konseling.send', $session->id) }}";
    const messagesUrl = "{{ route('konselor.konseling.messages', $session->id) }}";
    const currentUserId = {{ auth()->id() }};
    
    const messagesContainer = document.getElementById('messages-container');
    const messagesList = document.getElementById('messages-list');
    const emptyState = document.getElementById('empty-state');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');

    // Hide empty state
    function hideEmptyState() {
        if (emptyState) {
            emptyState.classList.add('hidden');
        }
    }

    // Auto scroll to bottom
    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Add message to chat
    function addMessage(message, isOwn) {
        hideEmptyState();
        
        const messageDiv = document.createElement('div');
        messageDiv.className = isOwn ? 'flex justify-end' : 'flex justify-start';
        
        if (isOwn) {
            messageDiv.innerHTML = `
                <div class="max-w-[70%]">
                    <div class="bg-green-600 text-white rounded-lg px-4 py-3">
                        <p class="text-sm">${message.message}</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 text-right">
                        ${new Date(message.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}
                    </p>
                </div>
            `;
        } else {
            const fotoUrl = message.sender.foto 
                ? `/storage/${message.sender.foto}` 
                : '';
            const fotoHtml = message.sender.foto
                ? `<img src="${fotoUrl}" class="w-full h-full object-cover">`
                : `<i class="fas fa-user text-gray-400 text-xs"></i>`;
            
            messageDiv.innerHTML = `
                <div class="max-w-[70%]">
                    <div class="flex items-start gap-2">
                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden flex-shrink-0">
                            ${fotoHtml}
                        </div>
                        <div>
                            <div class="bg-gray-100 text-gray-800 rounded-lg px-4 py-3">
                                <p class="text-sm">${message.message}</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                ${new Date(message.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'})}
                            </p>
                        </div>
                    </div>
                </div>
            `;
        }
        
        messagesList.appendChild(messageDiv);
        scrollToBottom();
    }

    // Send message
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;

        try {
            const response = await fetch(sendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ message })
            });

            const data = await response.json();
            
            if (data.success) {
                addMessage(data.message, true);
                messageInput.value = '';
            }
        } catch (error) {
            console.error('Error sending message:', error);
            alert('Gagal mengirim pesan. Silakan coba lagi.');
        }
    });

    // Fetch new messages
    async function fetchMessages() {
        try {
            const response = await fetch(messagesUrl);
            const data = await response.json();
            
            // Response bisa berupa array langsung atau object dengan property messages
            const messages = Array.isArray(data) ? data : (data.messages || []);
            
            // Count existing messages in DOM
            const existingMessages = messagesList.querySelectorAll('.flex.justify-end, .flex.justify-start');
            const currentCount = existingMessages.length;
            
            if (messages.length > currentCount) {
                // Add only new messages
                messages.slice(currentCount).forEach(message => {
                    addMessage(message, message.sender_id === currentUserId);
                });
            }
        } catch (error) {
            console.error('Error fetching messages:', error);
        }
    }

    // Poll for new messages every 3 seconds
    setInterval(fetchMessages, 3000);

    // Initial scroll
    scrollToBottom();
</script>
@endsection
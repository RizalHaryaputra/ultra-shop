<div style="padding: 3rem 11rem" class="flex flex-col h-full bg-slate-200 shadow-lg rounded-lg">
    <!-- Bagian Header Chatbot -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold text-gray-700">UltraBot Assistant</h2>
        <div class="flex items-center">
            <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse mr-2"></div>
            @if ($messages) 
            <button 
                wire:click="clearHistory" 
                class="ml-2 px-4 py-2 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600 focus:outline-none focus:ring focus:ring-red-300">
                Hapus Riwayat
            </button>
            @endif
        </div>
    </div>
    <!-- Wrapper untuk Pesan Chat -->
    <div 
        class="bg-gray-100 rounded-lg p-4 mb-4 shadow-lg relative" 
        style="height: 500px; overflow-y: auto; word-wrap: break-word;"
    >
        <div id="chatMessages">
            @if (empty($messages))
                <!-- Tampilan Jika Tidak Ada Pesan -->
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <p class="text-gray-600">Halo! Saya UltraBot. Bagaimana saya bisa membantu Anda hari ini?</p>
                        <p class="text-sm text-gray-500">Ketik pertanyaan atau permintaan Anda di bawah.</p>
                    </div>
                </div>
            @else
                <!-- Tampilkan Pesan -->
                @foreach ($messages as $message)
                    <div class="mb-2 p-5 flex {{ $message['user'] === 'user' ? 'justify-end' : 'justify-start' }}">
                        <div
                            class="px-4 py-2 rounded-lg text-sm shadow 
                                {{ $message['user'] === 'user' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}"
                            style="max-width: 75%; overflow-wrap: break-word; text-align: {{ $message['user'] === 'ai' ? 'justify' : 'left' }};"
                        >
                            {!! $message['text'] !!}
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Animasi Loading -->
        <div 
            wire:loading 
            class="absolute inset-0 flex items-center justify-center bg-gray-100 bg-opacity-75 rounded-lg"
        >
            <div class="animate-spin rounded-full h-10 w-10 border-t-4 border-blue-500"></div>
        </div>
    </div>

    <!-- Bagian Input -->
    <div class="flex items-center mt-2">
        <input 
            type="text" 
            wire:model="userInput" 
            wire:keydown.enter="sendMessage"
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:outline-none focus:ring-blue-300"
            placeholder="Ketik pesan Anda di sini..."
        />
        <button 
            wire:click="sendMessage" 
            class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:ring focus:ring-blue-300">
            Kirim
        </button>
    </div>
</div>

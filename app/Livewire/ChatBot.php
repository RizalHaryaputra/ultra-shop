<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Parsedown;
use App\Models\Product;
use Livewire\Attributes\Title;

#[title("UltraBot")]

class ChatBot extends Component
{
    public $messages = [];
    public $userInput;

    public function mount()
    {
        // Muat riwayat pesan dari session jika tersedia
        $this->messages = session('chat_messages', []);
    }

    public function sendMessage()
    {
        $this->validate([
            'userInput' => 'required|string|min:1|max:500',
        ]);

        $apiKey = env('GEMINI_API');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=$apiKey";

        try {
            // Ekstrak kata kunci dari input pengguna
            $keywords = $this->extractKeywords($this->userInput);

            if ($keywords) {
                // Cari produk berdasarkan kata kunci yang diekstrak
                $products = Product::where('name', 'like', '%' . $keywords . '%')->get();
                $resultProduct = "Berikut adalah rekomendasi produk dari kami:\n\n";

                if ($products->isNotEmpty()) {
                    // Tampilkan informasi produk yang ditemukan
                    foreach ($products as $product) {
                        $resultProduct .= "**Nama Produk:** {$product->name}\n\n**Deskripsi:** {$product->description}\n\n**Harga:** Rp {$product->price}\n\n";
                    }
                } else {
                    $resultProduct .= "Tidak ada produk yang ditemukan dengan kata kunci tersebut.";
                }

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => 'Tolong berikan alasan mengapa harus memilih produk ini ' . $resultProduct],
                            ],
                        ],
                    ],
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $aiResponse = $resultProduct . "\n\n <br>" . $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response available.';
                    // Tambahkan tautan ke halaman produk
                    $aiResponse .= "<a href='" . url("products/{$product->slug}") . "' class='text-blue-500 underline hover:text-blue-700'>Beli Sekarang!!!</a><br><br>";
                } else {
                    $aiResponse = 'Failed to fetch response from AI.';
                }
            } else {
                // Jika tidak ada kata kunci yang ditemukan, minta AI memberikan penjelasan umum
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $this->userInput],
                            ],
                        ],
                    ],
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response available.';
                } else {
                    $aiResponse = 'Failed to fetch response from AI.';
                }
            }

            // Gunakan Parsedown untuk mengubah Markdown ke HTML
            $parsedown = new Parsedown();
            $formattedResponse = $parsedown->text($aiResponse); // Mengubah teks Markdown menjadi HTML

            // Menyimpan pesan pengguna dan respons AI
            $this->messages[] = ['user' => 'user', 'text' => $this->userInput];
            $this->messages[] = ['user' => 'ai', 'text' => $formattedResponse];

            // Simpan riwayat pesan ke session
            session(['chat_messages' => $this->messages]);
        } catch (\Exception $e) {
            $this->messages[] = ['user' => 'ai', 'text' => 'An error occurred: ' . $e->getMessage()];
        }

        // Reset input pengguna setelah mengirimkan pesan
        $this->userInput = '';
    }

    // Fungsi untuk mengekstrak kata kunci dari kalimat panjang
    private function extractKeywords($text)
    {
        // Misalkan kita menggunakan kata kunci yang sudah ditentukan (contoh: kategori, jenis produk, dll)
        $keywords = ['sepatu', 'topi', 'kemeja', 'kaos', 'celana', 'hoodie'];

        // Periksa apakah salah satu kata kunci ada dalam kalimat
        foreach ($keywords as $keyword) {
            if (stripos($text, $keyword) !== false) {
                return $keyword;  // Mengembalikan kata kunci pertama yang ditemukan
            }
        }

        return null;  // Jika tidak ada kata kunci yang ditemukan
    }

    public function clearHistory()
    {
        // Hapus riwayat chat dari session dan properti messages
        session()->forget('chat_messages');
        $this->messages = [];
    }


    public function render()
    {
        return view('livewire.chat-bot');
    }
}

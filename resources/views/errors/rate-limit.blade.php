<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terlalu Banyak Permintaan - IBEKAMI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-[#fff2e0] min-h-screen flex items-center justify-center px-4 font-['Inter']">
    
    <div class="max-w-md w-full">
        <!-- Card Container -->
        <div class="bg-white rounded-[32px] p-8 shadow-[0_20px_50px_rgba(166,78,47,0.15)] border border-[#ff9100]/10 text-center">
            
            <!-- Icon -->
            <div class="w-20 h-20 mx-auto mb-6 bg-[#ff9100]/10 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-[#ff9100]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <!-- Error Code -->
            <div class="mb-4">
                <span class="inline-block px-4 py-1.5 bg-[#ff9100]/10 text-[#ff9100] text-xs font-bold uppercase tracking-wider rounded-full border border-[#ff9100]/20">
                    Error 429
                </span>
            </div>

            <!-- Title -->
            <h1 class="font-['Playfair_Display'] text-3xl font-bold text-[#3d2b1f] mb-3">
                Sebentar Ya! 🙏
            </h1>

            <!-- Message -->
            <p class="text-[#7a6452] text-[15px] leading-relaxed mb-6">
                {{ $message ?? 'Terlalu banyak permintaan dalam waktu singkat. Silakan tunggu sebentar sebelum mencoba lagi.' }}
            </p>

            <!-- Countdown Timer -->
            <div x-data="{ 
                timeLeft: {{ $retry_after ?? 60 }},
                init() {
                    this.countdown();
                },
                countdown() {
                    if (this.timeLeft > 0) {
                        setTimeout(() => {
                            this.timeLeft--;
                            this.countdown();
                        }, 1000);
                    } else {
                        window.location.reload();
                    }
                }
            }" class="mb-6">
                <div class="bg-[#fff2e0] rounded-2xl p-4 border border-[#ff9100]/10">
                    <p class="text-[#8A6A54] text-sm mb-2">Halaman akan dimuat ulang dalam:</p>
                    <div class="text-4xl font-bold text-[#ff9100]" x-text="timeLeft + 's'"></div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col gap-3">
                <a href="/" 
                   class="w-full bg-[#ff9100] text-white py-3.5 rounded-xl font-bold hover:bg-[#e68200] transition-all shadow-md">
                    Kembali ke Beranda
                </a>
                <button onclick="window.history.back()" 
                        class="w-full bg-white border-2 border-[#ff9100]/30 text-[#ff9100] py-3.5 rounded-xl font-bold hover:bg-[#fff2e0] transition-all">
                    Halaman Sebelumnya
                </button>
            </div>

            <!-- Info -->
            <div class="mt-6 pt-6 border-t border-[#ff9100]/10">
                <p class="text-[#8A6A54] text-xs leading-relaxed">
                    💡 <strong>Tips:</strong> Tunggu beberapa saat sebelum melanjutkan browsing. 
                    Ini membantu kami menjaga performa website tetap optimal untuk semua pengunjung.
                </p>
            </div>

        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-[#8A6A54] text-sm">
                Butuh bantuan? 
                <a href="https://wa.me/6281707699999" target="_blank" class="text-[#ff9100] font-semibold hover:underline">
                    Hubungi Admin
                </a>
            </p>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>

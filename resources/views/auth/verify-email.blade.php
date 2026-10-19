<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12 bg-gray-50">
        <div class="w-full max-w-md bg-white p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="text-center">
                <a href="{{ route('home') }}" class="inline-block mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="IMST Logo" class="h-9 w-auto mx-auto object-contain">
                </a>
                <h2 class="text-xl font-bold text-gray-900">Verifikasi Alamat Email</h2>
                <p class="text-xs text-gray-500 mt-1">
                    Silakan periksa kotak masuk email Anda dan klik tautan verifikasi yang kami kirimkan.
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="p-3 bg-green-50 border border-green-200 text-xs text-green-700 rounded-xl">
                    Tautan verifikasi baru telah berhasil dikirim ke alamat email yang Anda daftarkan.
                </div>
            @endif

            <div class="pt-2 flex items-center justify-between">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="py-2.5 px-4 rounded-xl bg-[#1044D1] text-white font-semibold text-xs hover:bg-[#0d36a8] focus:ring-4 focus:ring-[#1044D1]/20 focus:outline-none transition-colors duration-150 shadow-xs">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-red-600 hover:underline">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

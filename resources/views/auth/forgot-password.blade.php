<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12 bg-gray-50">
        <div class="w-full max-w-md bg-white p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="text-center">
                <a href="{{ route('login') }}" class="inline-block mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="IMST Logo" class="h-9 w-auto mx-auto object-contain">
                </a>
                <h2 class="text-xl font-bold text-gray-900">Lupa Kata Sandi</h2>
                <p class="text-xs text-gray-500 mt-1">
                    Masukkan alamat email Anda untuk menerima tautan reset kata sandi.
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Alamat Email
                    </label>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus 
                           placeholder="nama@imst.com"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-900 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-[#1044D1] focus:border-[#1044D1] transition placeholder-gray-400">
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-600" />
                </div>

                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('login') }}" class="text-xs text-gray-500 hover:text-gray-800 transition">
                        &larr; Kembali ke Login
                    </a>
                    <button type="submit" class="py-2.5 px-4 rounded-xl bg-[#1044D1] text-white font-semibold text-xs sm:text-sm hover:bg-[#0d36a8] focus:ring-4 focus:ring-[#1044D1]/20 focus:outline-none transition-colors duration-150 shadow-xs">
                        Kirim Tautan Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>

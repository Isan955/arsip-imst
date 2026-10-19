<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12 bg-gray-50">
        <div class="w-full max-w-md bg-white p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="text-center">
                <a href="{{ route('home') }}" class="inline-block mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="IMST Logo" class="h-9 w-auto mx-auto object-contain">
                </a>
                <h2 class="text-xl font-bold text-gray-900">Konfirmasi Kata Sandi</h2>
                <p class="text-xs text-gray-500 mt-1">
                    Area ini aman dan terlindungi. Silakan konfirmasi kata sandi Anda sebelum melanjutkan.
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
                @csrf

                <!-- Password -->
                <div class="space-y-1.5">
                    <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Kata Sandi
                    </label>
                    <input id="password" 
                           type="password" 
                           name="password" 
                           required 
                           autocomplete="current-password"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-900 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-[#1044D1] focus:border-[#1044D1] transition">
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-600" />
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-[#1044D1] text-white font-semibold text-xs sm:text-sm hover:bg-[#0d36a8] focus:ring-4 focus:ring-[#1044D1]/20 focus:outline-none transition-colors duration-150 shadow-xs">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>

<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12 bg-gray-50">
        <div class="w-full max-w-md bg-white p-8 rounded-2xl border border-gray-200 shadow-sm space-y-5">
            <div class="text-center">
                <a href="{{ route('login') }}" class="inline-block mb-3">
                    <img src="{{ asset('images/logo.png') }}" alt="IMST Logo" class="h-9 w-auto mx-auto object-contain">
                </a>
                <h2 class="text-xl font-bold text-gray-900">Atur Ulang Kata Sandi</h2>
                <p class="text-xs text-gray-500 mt-1">
                    Silakan masukkan kata sandi baru untuk akun Anda.
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Alamat Email
                    </label>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email', $request->email) }}" 
                           required 
                           autofocus 
                           autocomplete="username"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-900 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-[#1044D1] focus:border-[#1044D1] transition">
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-600" />
                </div>

                <!-- Password -->
                <div class="space-y-1.5">
                    <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Kata Sandi Baru
                    </label>
                    <input id="password" 
                           type="password" 
                           name="password" 
                           required 
                           autocomplete="new-password"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-900 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-[#1044D1] focus:border-[#1044D1] transition">
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-600" />
                </div>

                <!-- Confirm Password -->
                <div class="space-y-1.5">
                    <label for="password_confirmation" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        Konfirmasi Kata Sandi Baru
                    </label>
                    <input id="password_confirmation" 
                           type="password" 
                           name="password_confirmation" 
                           required 
                           autocomplete="new-password"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-900 text-xs sm:text-sm focus:outline-none focus:ring-1 focus:ring-[#1044D1] focus:border-[#1044D1] transition">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-red-600" />
                </div>

                <div class="flex items-center justify-end pt-2">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-[#1044D1] text-white font-semibold text-xs sm:text-sm hover:bg-[#0d36a8] focus:ring-4 focus:ring-[#1044D1]/20 focus:outline-none transition-colors duration-150 shadow-xs">
                        Atur Ulang Kata Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>

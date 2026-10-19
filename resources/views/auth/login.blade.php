<x-guest-layout>
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2 bg-white selection:bg-primary selection:text-white">
        
        <!-- Bagian Kiri: Visual Perusahaan & InfoHub-IMST -->
        <div class="relative overflow-hidden bg-slate-50 flex flex-col justify-between p-8 sm:p-12 lg:p-14 min-h-[460px] lg:min-h-screen border-b lg:border-b-0 lg:border-r border-gray-200">
            <!-- Background Foto Kantor Baru -->
            <img src="{{ asset('images/fotokantorbaru.png') }}" 
                 alt="Gedung PT IMST" 
                 class="absolute inset-0 w-full h-full object-cover object-bottom lg:object-center z-0">
            
            <!-- Soft White Gradient Overlay for Text Readability -->
            <div class="absolute inset-0 bg-gradient-to-b from-white/95 via-white/80 to-white/20 lg:via-white/75 lg:to-transparent z-1"></div>

            <!-- Top-Left Geometric Blue Accent Shapes -->
            <div class="absolute top-0 left-0 pointer-events-none z-2">
                <svg class="w-36 h-36 sm:w-48 sm:h-48 lg:w-56 lg:h-56" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="0,0 150,0 0,150" fill="#1044D1" />
                    <polygon points="0,0 80,0 0,80" fill="#2563EB" opacity="0.9" />
                </svg>
            </div>

            <!-- Bottom-Right Geometric Soft Accent -->
            <div class="absolute bottom-0 right-0 pointer-events-none z-2 hidden lg:block">
                <svg class="w-48 h-48 lg:w-64 lg:h-64" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="200,200 200,70 70,200" fill="#BFDBFE" fill-opacity="0.45" />
                </svg>
            </div>

            <!-- Content Area (Relative z-10) -->
            <div class="relative z-10 space-y-8 max-w-lg">
                <!-- Logo & Company Name Header -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="inline-block">
                        <img src="{{ asset('images/logo.png') }}" alt="IMST Logo" class="h-9 sm:h-10 w-auto object-contain">
                    </a>
                    <div class="h-8 w-px bg-gray-300"></div>
                    <div class="text-left leading-tight">
                        <p class="text-xs font-semibold text-gray-800 tracking-wide">PT INKA Multi Solusi</p>
                        <p class="text-[11px] font-medium text-gray-500 tracking-wide">Trading</p>
                    </div>
                </div>

                <!-- Main Greeting & Headline -->
                <div class="space-y-2 text-left pt-2">
                    <h1 class="text-3xl sm:text-4xl lg:text-[40px] font-bold text-gray-900 tracking-tight leading-tight">
                        Selamat Datang <br>
                        <span class="text-[#1044D1] font-extrabold">di InfoHub-IMST</span>
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-600 leading-relaxed pt-1">
                        Akses informasi perusahaan dengan mudah, cepat, dan aman dalam satu portal.
                    </p>
                </div>
            </div>

            <!-- Bottom spacer to let the building photo show cleanly at bottom -->
            <div class="relative z-10 hidden lg:block pt-32"></div>
        </div>

        <!-- Bagian Kanan: Form Login -->
        <div class="bg-white p-8 sm:p-12 lg:p-16 flex flex-col justify-center items-center">
            
            <div class="w-full max-w-md mx-auto">

                <!-- Header Title & Subtitle -->
                <div class="mb-8 text-left">
                    <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">
                        Login to Your Account
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-2">
                        Masuk ke akun Anda untuk mengakses portal informasi perusahaan.
                    </p>
                </div>

                <!-- Session Status Alert -->
                <x-auth-session-status class="mb-5" :status="session('status')" />

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4" x-data="{ showPassword: false }">
                    @csrf

                    <!-- Email Address Field -->
                    <div class="space-y-1 text-left">
                        <div class="relative rounded-xl border border-gray-200 bg-white focus-within:border-[#1044D1] focus-within:ring-2 focus-within:ring-[#1044D1]/20 transition-all">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <!-- Mail Icon -->
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>  
                            </div>
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="username" 
                                   placeholder="Email Address"
                                   class="w-full pl-11 pr-4 py-3 rounded-xl border-0 bg-transparent text-gray-900 text-sm placeholder-gray-400 focus:outline-none focus:ring-0">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-600" />
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1 text-left">
                        <div class="relative rounded-xl border border-gray-200 bg-white focus-within:border-[#1044D1] focus-within:ring-2 focus-within:ring-[#1044D1]/20 transition-all">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <!-- Lock Icon -->
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" 
                                   :type="showPassword ? 'text' : 'password'" 
                                   name="password" 
                                   required 
                                   autocomplete="current-password" 
                                   placeholder="Password"
                                   class="w-full pl-11 pr-11 py-3 rounded-xl border-0 bg-transparent text-gray-900 text-sm placeholder-gray-400 focus:outline-none focus:ring-0">
                            <!-- Toggle Visibility Button -->
                            <button type="button" 
                                    @click="showPassword = !showPassword" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition focus:outline-none">
                                <template x-if="!showPassword">
                                    <!-- Eye-Off / Slashed icon -->
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </template>
                                <template x-if="showPassword">
                                    <!-- Regular Eye icon -->
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </template>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-600" />
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer space-x-2.5">
                            <input id="remember_me" 
                                   type="checkbox" 
                                   name="remember" 
                                   class="w-4 h-4 text-[#1044D1] border-gray-300 rounded focus:ring-[#1044D1]/20 focus:ring-2 transition cursor-pointer">
                            <span class="text-xs sm:text-sm text-gray-700 select-none">
                                Ingat saya
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-3">
                        <button type="submit" 
                                class="w-full py-3 px-6 rounded-xl bg-[#1044D1] hover:bg-[#0c36a8] text-white font-semibold text-sm transition-colors duration-150 shadow-sm flex items-center justify-center space-x-2">
                            <span>Login</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Divider 'atau' -->
                    <div class="relative flex py-2 items-center">
                        <div class="flex-grow border-t border-gray-200"></div>
                        <span class="flex-shrink mx-4 text-xs text-gray-400 font-normal">atau</span>
                        <div class="flex-grow border-t border-gray-200"></div>
                    </div>

                    <!-- Forgot Password Link -->
                    @if (Route::has('password.request'))
                        <div class="text-center pt-1">
                            <a href="{{ route('password.request') }}" class="text-xs sm:text-sm font-medium text-[#1044D1] hover:underline transition">
                                Lupa password?
                            </a>
                        </div>
                    @endif
                </form>

            </div>

        </div>

    </div>
</x-guest-layout>

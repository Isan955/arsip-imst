<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \App\Models\Setting::getValue('site_name', 'IMST Corporate Portal') }}</title>
    <meta name="description" content="{{ \App\Models\Setting::getValue('site_description', 'Portal Informasi Terpusat PT IMST') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS / JS Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen selection:bg-primary selection:text-white flex flex-col justify-between">

    <!-- Clean Corporate Navbar -->
    <header class="sticky top-0 z-40 bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Left: Logo & Portal Title -->
                <div class="flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo.png') }}" alt="IMST Logo" class="h-8 w-auto object-contain">
                    </a>

                    <!-- Navigation Links (Desktop) -->
                    <nav class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('home') }}" 
                           class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request()->routeIs('home') ? 'bg-blue-50 text-[#1044D1] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100/70' }}">
                            Beranda
                        </a>
                        <a href="{{ route('bookmarks.index') }}" 
                           class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ request()->routeIs('bookmarks.*') ? 'bg-blue-50 text-[#1044D1] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100/70' }}">
                            Bookmark
                        </a>
                    </nav>
                </div>

                <!-- Right: Actions & User Dropdown -->
                <div class="flex items-center space-x-3">
                    @hasanyrole('Super Admin|Admin Kesekretariatan')
                        <a href="{{ route('admin.dashboard') }}" 
                           class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-blue-50 text-[#1044D1] border border-blue-200 hover:bg-blue-100 transition">
                            <svg class="w-3.5 h-3.5 text-[#1044D1]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Panel Admin</span>
                        </a>
                    @endhasanyrole

                    <div class="h-4 w-px bg-gray-200"></div>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" 
                                class="flex items-center space-x-2 p-1 rounded-lg hover:bg-gray-50 transition focus:outline-none">
                            <div class="w-7 h-7 rounded-full bg-gray-100 text-gray-700 font-semibold flex items-center justify-center text-xs border border-gray-200">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-xs font-medium text-gray-700 hidden sm:inline">{{ Auth::user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 rounded-xl shadow-lg bg-white border border-gray-200 py-1 z-50 text-left" 
                             style="display: none;">
                            
                            <div class="px-3.5 py-2 border-b border-gray-100">
                                <p class="text-xs font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <a href="{{ route('profile.edit') }}" 
                               class="flex items-center space-x-2 px-3.5 py-2 text-xs text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Profil Saya</span>
                            </a>

                            <div class="border-t border-gray-100 my-0.5"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center space-x-2 text-left px-3.5 py-2 text-xs text-red-600 hover:bg-red-50 transition">
                                    <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Strip -->
        <div class="md:hidden flex items-center justify-around border-t border-gray-100 px-4 py-2 bg-gray-50/50">
            <a href="{{ route('home') }}" 
               class="px-3 py-1 rounded-md text-xs font-medium {{ request()->routeIs('home') ? 'text-[#1044D1] font-semibold' : 'text-gray-600' }}">
                Beranda
            </a>
            <a href="{{ route('bookmarks.index') }}" 
               class="px-3 py-1 rounded-md text-xs font-medium {{ request()->routeIs('bookmarks.*') ? 'text-[#1044D1] font-semibold' : 'text-gray-600' }}">
                Bookmark
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Clean Corporate Footer -->
    <footer class="border-t border-gray-200 bg-white py-6 text-center text-xs text-gray-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p>&copy; {{ date('Y') }} {{ \App\Models\CompanyProfile::getValue('name', 'PT IMST') }}. All rights reserved.</p>
            <p class="text-[11px] text-gray-400">Portal Informasi Terpusat</p>
        </div>
    </footer>

    <!-- SweetAlert Toast Handler -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if(session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            @if(session('error'))
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            @endif
        });
    </script>
</body>
</html>

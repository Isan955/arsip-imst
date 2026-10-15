<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ \App\Models\Setting::getValue('site_name', 'IMST Corporate Portal') }}</title>
    <meta name="description" content="Panel Administrator Portal Informasi PT IMST">

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
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen selection:bg-primary selection:text-white" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
               class="fixed inset-y-0 left-0 z-40 w-60 bg-white border-r border-gray-200 flex flex-col justify-between transition-transform duration-200 ease-in-out md:translate-x-0 md:static md:inset-auto md:z-auto">
            
            <!-- Brand & Navigation Wrapper -->
            <div class="flex-1 flex flex-col min-h-0">
                <!-- Sidebar Header -->
                <div class="flex items-center justify-between h-16 px-5 border-b border-gray-200">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo.png') }}" alt="IMST Logo" class="h-7 w-auto object-contain">
                    </a>
                    
                    <!-- Close sidebar (mobile only) -->
                    <button @click="sidebarOpen = false" class="md:hidden p-1 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links List -->
                <nav class="flex-1 px-3 py-4 space-y-5 overflow-y-auto">
                    
                    <!-- Group 1: Content Management -->
                    <div class="space-y-0.5">
                        <span class="px-2.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wider block mb-1">
                            Konten
                        </span>

                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center space-x-2.5 px-2.5 py-2 rounded-lg text-[13px] transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-[#1044D1] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100/80 font-medium' }}">
                            <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-[#1044D1]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <!-- Kelola Kategori -->
                        <a href="{{ route('admin.categories.index') }}" 
                           class="flex items-center space-x-2.5 px-2.5 py-2 rounded-lg text-[13px] transition {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-[#1044D1] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100/80 font-medium' }}">
                            <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.categories.*') ? 'text-[#1044D1]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>Kategori</span>
                        </a>

                        <!-- Kelola Informasi -->
                        <a href="{{ route('admin.informations.index') }}" 
                           class="flex items-center space-x-2.5 px-2.5 py-2 rounded-lg text-[13px] transition {{ request()->routeIs('admin.informations.*') ? 'bg-blue-50 text-[#1044D1] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100/80 font-medium' }}">
                            <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.informations.*') ? 'text-[#1044D1]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Informasi Dokumen</span>
                        </a>
                    </div>

                    <!-- Group 2: System & Administration (Super Admin Only) -->
                    @role('Super Admin')
                        <div class="space-y-0.5 pt-4 border-t border-gray-100">
                            <span class="px-2.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wider block mb-1.5">
                                Administrasi
                            </span>

                            <!-- Kelola Pengguna -->
                            <a href="{{ route('admin.users.index') }}" 
                               class="flex items-center space-x-2.5 px-2.5 py-2 rounded-lg text-[13px] transition {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-[#1044D1] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100/80 font-medium' }}">
                                <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-[#1044D1]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <span>Pengguna</span>
                            </a>

                            <!-- Kelola Group -->
                            <a href="{{ route('admin.groups.index') }}" 
                               class="flex items-center space-x-2.5 px-2.5 py-2 rounded-lg text-[13px] transition {{ request()->routeIs('admin.groups.*') ? 'bg-blue-50 text-[#1044D1] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100/80 font-medium' }}">
                                <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.groups.*') ? 'text-[#1044D1]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span>Group Akses</span>
                            </a>

                            <!-- Kelola Banner -->
                            <a href="{{ route('admin.banners.index') }}" 
                               class="flex items-center space-x-2.5 px-2.5 py-2 rounded-lg text-[13px] transition {{ request()->routeIs('admin.banners.*') ? 'bg-blue-50 text-[#1044D1] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100/80 font-medium' }}">
                                <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.banners.*') ? 'text-[#1044D1]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Banner</span>
                            </a>

                            <!-- Pengaturan Web -->
                            <a href="{{ route('admin.settings.index') }}" 
                               class="flex items-center space-x-2.5 px-2.5 py-2 rounded-lg text-[13px] transition {{ request()->routeIs('admin.settings.*') ? 'bg-blue-50 text-[#1044D1] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100/80 font-medium' }}">
                                <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.settings.*') ? 'text-[#1044D1]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Pengaturan</span>
                            </a>

                            <!-- Log Aktivitas -->
                            <a href="{{ route('admin.logs.activity') }}" 
                               class="flex items-center space-x-2.5 px-2.5 py-2 rounded-lg text-[13px] transition {{ request()->routeIs('admin.logs.activity') ? 'bg-blue-50 text-[#1044D1] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100/80 font-medium' }}">
                                <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.logs.activity') ? 'text-[#1044D1]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                                <span>Log Aktivitas</span>
                            </a>

                            <!-- Log Login -->
                            <a href="{{ route('admin.logs.login') }}" 
                               class="flex items-center space-x-2.5 px-2.5 py-2 rounded-lg text-[13px] transition {{ request()->routeIs('admin.logs.login') ? 'bg-blue-50 text-[#1044D1] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100/80 font-medium' }}">
                                <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.logs.login') ? 'text-[#1044D1]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                <span>Log Login</span>
                            </a>

                            <!-- Backup/Restore DB -->
                            <a href="{{ route('admin.logs.database') }}" 
                               class="flex items-center space-x-2.5 px-2.5 py-2 rounded-lg text-[13px] transition {{ request()->routeIs('admin.logs.database') ? 'bg-blue-50 text-[#1044D1] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100/80 font-medium' }}">
                                <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.logs.database') ? 'text-[#1044D1]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                                </svg>
                                <span>Database</span>
                            </a>
                        </div>
                    @endrole

                </nav>
            </div>

            <!-- Sidebar Bottom: System Info -->
            <div class="px-4 py-3 border-t border-gray-200 text-xs text-gray-500 flex items-center justify-between">
                <span>Portal IMST</span>
                <span class="text-[11px] text-gray-400">v1.2</span>
            </div>
        </aside>

        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             x-transition:enter="transition-opacity ease-linear duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-30 bg-black/30 md:hidden" 
             style="display: none;"></div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-gray-50">
            
            <!-- Top Navbar -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-20">
                
                <!-- Left: Hamburger & Breadcrumb -->
                <div class="flex items-center space-x-3">
                    <button @click="sidebarOpen = true" class="md:hidden p-1.5 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    
                    <div class="hidden sm:flex items-center space-x-2 text-xs text-gray-500">
                        <span>Portal IMST</span>
                        <span class="text-gray-300">/</span>
                        <span class="text-gray-800 font-medium">Administrator</span>
                    </div>
                </div>

                <!-- Right: Actions & User Dropdown -->
                <div class="flex items-center space-x-3">
                    
                    <!-- View Portal Button -->
                    <a href="{{ route('home') }}" 
                       class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-xs font-medium text-gray-700 transition">
                        <span>Lihat Portal</span>
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>

                    <div class="h-4 w-px bg-gray-200 hidden sm:block"></div>

                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" 
                                class="flex items-center space-x-2 p-1 rounded-lg hover:bg-gray-50 transition focus:outline-none">
                            <div class="w-7 h-7 rounded-full bg-gray-100 text-gray-700 font-semibold flex items-center justify-center text-xs border border-gray-200">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="text-xs font-medium text-gray-700 hidden md:inline">{{ Auth::user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
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
            </header>

            <!-- Main Scrollable Section -->
            <main class="flex-1 overflow-y-auto p-6 lg:p-8">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

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

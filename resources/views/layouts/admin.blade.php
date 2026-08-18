<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('dark') === 'true', sidebarOpen: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ \App\Models\Setting::getValue('site_name', 'IMST Corporate Portal') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS / JS Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>
<body class="bg-[#F5F7FB] dark:bg-gray-900 text-gray-800 dark:text-gray-200 min-h-screen transition-colors duration-200">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
               class="fixed inset-y-0 left-0 z-40 w-64 bg-white dark:bg-gray-850 border-r border-gray-200/60 dark:border-gray-800/60 transition-transform duration-300 transform md:translate-x-0 md:static md:inset-auto md:z-auto">
            <div class="flex items-center justify-between h-16 px-6 border-b border-gray-200/60 dark:border-gray-800/60">
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center text-white font-bold shadow-md shadow-primary/20">
                        IM
                    </div>
                    <span class="font-bold text-base tracking-tight text-gray-900 dark:text-white">IMST Admin</span>
                </div>
                <!-- Close sidebar (mobile only) -->
                <button @click="sidebarOpen = false" class="md:hidden p-1 text-gray-500 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-lg shadow-primary/10' : 'text-gray-600 dark:text-gray-450 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                    Dashboard
                </a>

                <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition {{ request()->routeIs('admin.categories.*') ? 'bg-primary text-white shadow-lg shadow-primary/10' : 'text-gray-600 dark:text-gray-450 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                    Kelola Kategori
                </a>

                <a href="{{ route('admin.informations.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition {{ request()->routeIs('admin.informations.*') ? 'bg-primary text-white shadow-lg shadow-primary/10' : 'text-gray-600 dark:text-gray-450 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                    Kelola Informasi
                </a>

                @role('Super Admin')
                    <div class="h-px bg-gray-200/50 dark:bg-gray-800/50 my-4"></div>
                    <span class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-2">Super Admin Only</span>

                    <a href="{{ route('admin.banners.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition {{ request()->routeIs('admin.banners.*') ? 'bg-primary text-white shadow-lg shadow-primary/10' : 'text-gray-600 dark:text-gray-450 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        Kelola Banner
                    </a>

                    <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition {{ request()->routeIs('admin.settings.*') ? 'bg-primary text-white shadow-lg shadow-primary/10' : 'text-gray-600 dark:text-gray-450 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        Pengaturan Web
                    </a>

                    <a href="{{ route('admin.logs.activity') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition {{ request()->routeIs('admin.logs.activity') ? 'bg-primary text-white shadow-lg shadow-primary/10' : 'text-gray-600 dark:text-gray-450 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        Log Aktifitas
                    </a>

                    <a href="{{ route('admin.logs.login') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition {{ request()->routeIs('admin.logs.login') ? 'bg-primary text-white shadow-lg shadow-primary/10' : 'text-gray-600 dark:text-gray-450 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        Log Login
                    </a>

                    <a href="{{ route('admin.logs.database') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition {{ request()->routeIs('admin.logs.database') ? 'bg-primary text-white shadow-lg shadow-primary/10' : 'text-gray-600 dark:text-gray-450 hover:bg-gray-50 dark:hover:bg-gray-800' }}">
                        Backup/Restore DB
                    </a>
                @endrole
            </nav>
        </aside>

        <!-- Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black/40 md:hidden" style="display: none;"></div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="h-16 bg-white dark:bg-gray-850 border-b border-gray-200/60 dark:border-gray-800/60 flex items-center justify-between px-6 z-20">
                <div class="flex items-center space-x-3">
                    <button @click="sidebarOpen = true" class="md:hidden p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <span class="text-sm text-gray-500 font-medium">Halaman Administrator Portal IMST</span>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-800 text-xs font-semibold hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        Lihat Portal (Viewer)
                    </a>

                    <!-- Dark Mode Toggle -->
                    <button @click="darkMode = !darkMode; localStorage.setItem('dark', darkMode)" class="p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 transition">
                        <template x-if="!darkMode">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </template>
                        <template x-if="darkMode">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M14 12a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </template>
                    </button>

                    <!-- Profile link -->
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center font-bold text-gray-700 dark:text-gray-300">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 hidden md:inline">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </header>

            <!-- Main Scrollable Section -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 bg-[#F5F7FB] dark:bg-gray-900 transition-colors duration-200">
                @yield('content')
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

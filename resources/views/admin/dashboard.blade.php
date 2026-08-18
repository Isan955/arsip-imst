@extends('layouts.admin')

@section('content')
<div class="space-y-8 text-left">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Dashboard Statistik</h1>
        <p class="text-xs text-gray-500">Ringkasan status portal informasi korporat PT IMST.</p>
    </div>

    <!-- Stats Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Widget 1: Total Info -->
        <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Total Informasi</span>
                <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalInfo }}</span>
            </div>
            <div class="p-3 bg-blue-50 dark:bg-blue-950/20 text-primary dark:text-blue-400 rounded-xl">
                <!-- Document icon -->
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>

        <!-- Widget 2: Categories -->
        <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Kategori</span>
                <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalCategories }}</span>
            </div>
            <div class="p-3 bg-purple-50 dark:bg-purple-950/20 text-purple-600 dark:text-purple-400 rounded-xl">
                <!-- Tag Icon -->
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>

        <!-- Widget 3: Users -->
        <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Pengguna</span>
                <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</span>
            </div>
            <div class="p-3 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 rounded-xl">
                <!-- User Icon -->
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>

        <!-- Widget 4: Draft vs Published -->
        <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block">Status Publikasi</span>
                <span class="text-sm font-semibold text-gray-750">
                    <span class="text-emerald-500">{{ $publishedCount }} Publish</span>
                    <span class="text-gray-400"> / </span>
                    <span class="text-amber-500">{{ $draftCount }} Draft</span>
                </span>
            </div>
            <div class="p-3 bg-amber-50 dark:bg-amber-950/20 text-amber-600 dark:text-amber-450 rounded-xl">
                <!-- Pie Icon -->
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Chart & Activity Log Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Monthly update chart -->
        <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm lg:col-span-2 space-y-4">
            <h3 class="font-bold text-gray-900 dark:text-white text-base">Grafik Update Bulanan</h3>
            <div class="h-64 relative">
                <canvas id="updatesChart"></canvas>
            </div>
        </div>

        <!-- Recent Logs Activity -->
        <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm space-y-4 flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white text-base mb-4">Log Aktivitas Terbaru</h3>
                <div class="space-y-3.5 max-h-72 overflow-y-auto">
                    @forelse($recentLogs as $log)
                        <div class="flex space-x-3 items-start text-xs border-b border-gray-50 dark:border-gray-800/60 pb-2">
                            <div class="w-7 h-7 rounded bg-gray-100 dark:bg-gray-800 flex items-center justify-center font-bold text-gray-500">
                                {{ strtoupper(substr($log->user ? $log->user->name : 'S', 0, 1)) }}
                            </div>
                            <div class="flex-1 space-y-0.5">
                                <p class="text-gray-900 dark:text-white font-semibold">{{ $log->action }}</p>
                                <p class="text-gray-500">{{ $log->description }}</p>
                                <p class="text-[10px] text-gray-400">{{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 italic">Belum ada catatan aktivitas.</p>
                    @endforelse
                </div>
            </div>
            
            <a href="{{ route('admin.logs.activity') }}" class="text-xs font-semibold text-primary hover:underline mt-4 inline-block text-left">
                Lihat semua log aktivitas &rarr;
            </a>
        </div>

    </div>
</div>

<!-- Load Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('updatesChart').getContext('2d');
        
        // Extract chart data from Laravel variable
        const rawData = [
            @foreach($monthlyUpdates as $u)
                {
                    label: "{{ \Carbon\Carbon::create($u->year, $u->month, 1)->translatedFormat('M Y') }}",
                    total: {{ $u->total }}
                },
            @endforeach
        ];

        const labels = rawData.map(item => item.label);
        const dataValues = rawData.map(item => item.total);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels.length ? labels : ['No Data'],
                datasets: [{
                    label: 'Jumlah Update Dokumen',
                    data: dataValues.length ? dataValues : [0],
                    borderColor: '#1044D1',
                    backgroundColor: 'rgba(16, 68, 209, 0.05)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>
@endsection

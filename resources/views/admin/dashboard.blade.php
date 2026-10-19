@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-left">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Dashboard</h1>
            <p class="text-sm text-gray-500 mt-0.5">
                Selamat datang kembali, <span class="font-medium text-gray-700">{{ Auth::user()->name }}</span>.
            </p>
        </div>

        <div>
            <a href="{{ route('admin.informations.create') }}" 
               class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-lg bg-[#1044D1] hover:bg-blue-700 text-white text-xs font-semibold shadow-xs transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Informasi</span>
            </a>
        </div>
    </div>

    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 {{ Auth::user()->hasRole('Super Admin') ? 'lg:grid-cols-4' : 'lg:grid-cols-3' }} gap-4">
        
        <!-- Total Informasi -->
        <div class="bg-white p-5 rounded-xl border border-gray-200">
            <span class="text-xs font-medium text-gray-500 block">Total Informasi</span>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalInfo }}</p>
            <p class="text-xs text-gray-400 mt-1">
                <span class="text-emerald-600 font-medium">{{ $publishedCount }} Publish</span> · <span>{{ $draftCount }} Draft</span>
            </p>
        </div>

        <!-- Kategori -->
        <div class="bg-white p-5 rounded-xl border border-gray-200">
            <span class="text-xs font-medium text-gray-500 block">Kategori</span>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalCategories }}</p>
            <p class="text-xs text-gray-400 mt-1">Kategori aktif</p>
        </div>

        <!-- Versi Dokumen -->
        <div class="bg-white p-5 rounded-xl border border-gray-200">
            <span class="text-xs font-medium text-gray-500 block">Versi Dokumen</span>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalVersions }}</p>
            <p class="text-xs text-gray-400 mt-1">Riwayat arsip bulanan</p>
        </div>

        <!-- Super Admin Only: Pengguna -->
        @role('Super Admin')
            <div class="bg-white p-5 rounded-xl border border-gray-200">
                <span class="text-xs font-medium text-gray-500 block">Pengguna</span>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalUsers }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $totalGroups }} group terdaftar</p>
            </div>
        @endrole

    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Dokumen Terbaru -->
        <div class="bg-white rounded-xl border border-gray-200 lg:col-span-2 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-sm font-bold text-gray-900">Dokumen Terbaru</h2>
                <a href="{{ route('admin.informations.index') }}" class="text-xs font-medium text-[#1044D1] hover:underline">
                    Kelola Semua &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-gray-400 uppercase text-[10px] tracking-wider border-b border-gray-100 bg-gray-50/50">
                            <th class="px-5 py-3 font-semibold">Judul Dokumen</th>
                            <th class="px-5 py-3 font-semibold">Kategori</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 font-semibold text-right">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse($recentInformations as $info)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-5 py-3 font-medium text-gray-900">
                                    <a href="{{ route('admin.informations.edit', $info->id) }}" class="hover:text-[#1044D1] transition">
                                        {{ $info->title }}
                                    </a>
                                </td>
                                <td class="px-5 py-3 text-gray-500">
                                    {{ $info->category?->name ?? '—' }}
                                </td>
                                <td class="px-5 py-3">
                                    @if($info->status === 'published')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Publish
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-gray-100 text-gray-600 border border-gray-200">
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right text-gray-400">
                                    {{ $info->created_at->translatedFormat('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-6 text-center text-gray-400 italic">
                                    Belum ada data dokumen.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Column: Log Aktivitas (Super Admin) OR Monthly Chart (Admin Kesekretariatan) -->
        @role('Super Admin')
            <div class="bg-white rounded-xl border border-gray-200 p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-3">
                        <h2 class="text-sm font-bold text-gray-900">Aktivitas Sistem</h2>
                        <a href="{{ route('admin.logs.activity') }}" class="text-xs font-medium text-[#1044D1] hover:underline">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($recentLogs as $log)
                            <div class="flex items-start space-x-2.5 text-xs">
                                <div class="w-6 h-6 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center font-semibold text-[10px] flex-shrink-0 mt-0.5 border border-gray-200">
                                    {{ strtoupper(substr($log->user ? $log->user->name : 'S', 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 leading-snug truncate">{{ $log->action }}</p>
                                    <p class="text-gray-500 text-[11px] truncate">{{ $log->description }}</p>
                                    <span class="text-[10px] text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 italic py-3">Belum ada aktivitas tercatat.</p>
                        @endforelse
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 mt-4">
                    <a href="{{ route('admin.logs.activity') }}" class="text-xs text-gray-500 hover:text-[#1044D1] font-medium flex items-center justify-between">
                        <span>Buka Audit Trail</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-3">
                <div class="pb-3 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-900">Pembaruan Dokumen Bulanan</h2>
                    <p class="text-xs text-gray-400">Frekuensi upload histori versi bulanan</p>
                </div>

                <div class="h-56 relative pt-2">
                    <canvas id="updatesChart"></canvas>
                </div>
            </div>

            <!-- Chart.js for Admin Kesekretariatan -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const ctx = document.getElementById('updatesChart').getContext('2d');
                    
                    const rawData = [
                        @foreach($monthlyUpdates as $u)
                            {
                                label: "{{ \Carbon\Carbon::create($u->year, $u->month, 1)->translatedFormat('M Y') }}",
                                total: {{ $u->total }}
                            },
                        @endforeach
                    ];

                    const labels = rawData.length ? rawData.map(item => item.label) : ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
                    const dataValues = rawData.length ? rawData.map(item => item.total) : [0, 0, 0, 0, 0, 0];

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Versi Diperbarui',
                                data: dataValues,
                                borderColor: '#1044D1',
                                borderWidth: 2,
                                backgroundColor: 'rgba(16, 68, 209, 0.05)',
                                pointBackgroundColor: '#FFFFFF',
                                pointBorderColor: '#1044D1',
                                pointRadius: 3,
                                tension: 0.2,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { size: 10 }, color: '#94A3B8' }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: { color: '#F1F5F9' },
                                    ticks: { font: { size: 10 }, color: '#94A3B8', stepSize: 1, precision: 0 }
                                }
                            }
                        }
                    });
                });
            </script>
        @endrole

    </div>

</div>
@endsection

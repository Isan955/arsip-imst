@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-left">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Log Aktivitas Pengguna</h1>
        <p class="text-xs text-gray-500">Mencatat seluruh aksi administratif dan audit trail dalam sistem.</p>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-850 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Pengguna</th>
                        <th class="px-6 py-4">Aksi</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4">IP Address</th>
                        <th class="px-6 py-4">User Agent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/65 dark:divide-gray-800/60 text-xs">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                            <td class="px-6 py-4 font-mono whitespace-nowrap">{{ $log->created_at->translatedFormat('d M Y, H:i:s') }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $log->user ? $log->user->name : 'System/Guest' }}</td>
                            <td class="px-6 py-4 font-mono font-bold text-primary dark:text-blue-400">{{ $log->action }}</td>
                            <td class="px-6 py-4 max-w-xs">{{ $log->description ?: '-' }}</td>
                            <td class="px-6 py-4 font-mono">{{ $log->ip_address }}</td>
                            <td class="px-6 py-4 max-w-xxs truncate" title="{{ $log->user_agent }}">{{ $log->user_agent }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">Belum ada log aktivitas tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-250/20 dark:border-gray-800/80">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

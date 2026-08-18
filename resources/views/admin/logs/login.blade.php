@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-left">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Log Masuk/Keluar Pengguna</h1>
        <p class="text-xs text-gray-500">Mencatat riwayat login dan logout pengguna ke sistem.</p>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-850 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-4">Pengguna</th>
                        <th class="px-6 py-4">IP Address</th>
                        <th class="px-6 py-4">Waktu Masuk</th>
                        <th class="px-6 py-4">Waktu Keluar</th>
                        <th class="px-6 py-4">User Agent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/65 dark:divide-gray-800/60 text-xs">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                <p>{{ $log->user->name }}</p>
                                <p class="text-[10px] text-gray-400 font-mono font-normal">{{ $log->user->email }}</p>
                            </td>
                            <td class="px-6 py-4 font-mono">{{ $log->ip_address }}</td>
                            <td class="px-6 py-4 font-mono">{{ $log->login_at ? $log->login_at->translatedFormat('d M Y, H:i:s') : '-' }}</td>
                            <td class="px-6 py-4 font-mono">
                                @if($log->logout_at)
                                    {{ $log->logout_at->translatedFormat('d M Y, H:i:s') }}
                                @else
                                    <span class="text-emerald-500 font-semibold">Aktif (Sesi Berjalan)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 max-w-xxs truncate" title="{{ $log->user_agent }}">{{ $log->user_agent }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 italic">Belum ada log masuk tercatat.</td>
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

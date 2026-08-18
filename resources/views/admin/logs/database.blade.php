@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-left max-w-xl">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Backup & Pemulihan Database</h1>
        <p class="text-xs text-gray-500">Mencadangkan data sistem atau memulihkan basis data dari cadangan berkas SQL.</p>
    </div>

    <!-- Backup Card -->
    <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm space-y-4">
        <h3 class="font-bold text-gray-900 dark:text-white text-base">Cadangkan Basis Data (Backup)</h3>
        <p class="text-xs text-gray-500">Menghasilkan berkas sql yang berisi seluruh tabel, data, skema, dan hak akses. Berkas akan langsung diunduh secara otomatis.</p>
        
        <form action="{{ route('admin.backup') }}" method="POST">
            @csrf
            <button type="submit" class="bg-primary text-white text-xs font-semibold px-5 py-2.5 rounded-xl hover:bg-blue-700 transition flex items-center space-x-2 shadow-md shadow-primary/10">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Unduh Cadangan SQL Sekarang</span>
            </button>
        </form>
    </div>

    <!-- Restore Card -->
    <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm space-y-4">
        <h3 class="font-bold text-gray-900 dark:text-white text-base">Pulihkan Basis Data (Restore)</h3>
        <p class="text-xs text-red-500 font-medium">⚠️ PENTING: Proses pemulihan akan menimpa seluruh data saat ini! Pastikan berkas cadangan valid dan sesuai.</p>
        
        <form action="{{ route('admin.restore') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div class="space-y-1.5">
                <label for="backup_file" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Pilih Berkas Cadangan (.sql)</label>
                <input type="file" id="backup_file" name="backup_file" accept=".sql" required class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-red-50 dark:file:bg-red-950/20 file:text-red-600 hover:file:bg-red-100">
                @error('backup_file') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <button type="submit" onclick="return confirmRestore()" class="bg-red-600 text-white text-xs font-semibold px-5 py-2.5 rounded-xl hover:bg-red-700 transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 6H16"/>
                </svg>
                <span>Pulihkan Database</span>
            </button>
        </form>
    </div>
</div>

<script>
    function confirmRestore() {
        return confirm('Apakah Anda benar-benar yakin ingin memulihkan database? Seluruh data yang ada saat ini akan diganti permanen!');
    }
</script>
@endsection

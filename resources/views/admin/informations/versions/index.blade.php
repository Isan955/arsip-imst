@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-left">
    <div class="flex items-center justify-between">
        <div>
            <nav class="flex text-[10px] text-gray-500 space-x-2 mb-2">
                <a href="{{ route('admin.informations.index') }}" class="hover:text-primary transition">Daftar Informasi</a>
                <span>/</span>
                <span class="text-gray-400">Histori Bulanan</span>
            </nav>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Histori Versi Bulanan</h1>
            <p class="text-xs text-gray-500">Mengelola isi konten tiap bulan untuk: <span class="font-bold text-primary dark:text-blue-400">{{ $information->title }}</span></p>
        </div>
        <a href="{{ route('admin.informations.versions.create', $information->id) }}" class="bg-primary text-white text-xs font-semibold px-4 py-2.5 rounded-xl hover:bg-blue-700 transition shadow-md shadow-primary/10">
            Tambah Versi Bulan
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-850 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-4">Versi (Bulan/Tahun)</th>
                        <th class="px-6 py-4">Lampiran PDF</th>
                        <th class="px-6 py-4">Pengubah</th>
                        <th class="px-6 py-4">Tanggal Diperbarui</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/65 dark:divide-gray-800/60">
                    @forelse($versions as $ver)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::create(null, $ver->month, 1)->translatedFormat('F') }} / {{ $ver->year }}
                            </td>
                            <td class="px-6 py-4">
                                @if($ver->attachments->count() > 0)
                                    @foreach($ver->attachments as $att)
                                        <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank" class="inline-flex items-center space-x-1.5 bg-red-50 dark:bg-red-950/20 text-red-650 dark:text-red-400 px-2.5 py-1 rounded-lg text-xs hover:underline font-semibold">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                            <span class="truncate max-w-[150px]">{{ $att->file_name }}</span>
                                        </a>
                                    @endforeach
                                @else
                                    <span class="text-xs text-gray-400 italic">Tidak ada lampiran</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs">{{ $ver->creator ? $ver->creator->name : '-' }}</td>
                            <td class="px-6 py-4 text-xs">{{ $ver->updated_at->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.informations.versions.edit', [$information->id, $ver->id]) }}" class="text-xs font-semibold text-primary hover:underline">Edit Konten</a>
                                    <span class="text-gray-300 dark:text-gray-700">|</span>
                                    <form action="{{ route('admin.informations.versions.destroy', [$information->id, $ver->id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete(this)" class="text-xs font-semibold text-red-650 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 italic">Belum ada versi dokumen bulanan yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($versions->hasPages())
            <div class="px-6 py-4 border-t border-gray-250/20 dark:border-gray-800/80">
                {{ $versions->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function confirmDelete(button) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Konten dan berkas PDF lampiran pada versi bulan ini akan terhapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }
</script>
@endsection

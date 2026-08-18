@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-left">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Kategori Informasi</h1>
            <p class="text-xs text-gray-500">Kelola kategori untuk mengelompokkan dokumen informasi perusahaan.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="bg-primary text-white text-xs font-semibold px-4 py-2.5 rounded-xl hover:bg-blue-700 transition shadow-md shadow-primary/10">
            Tambah Kategori
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-850 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Slug</th>
                        <th class="px-6 py-4">Deskripsi</th>
                        <th class="px-6 py-4 text-center">Jumlah Dokumen</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/65 dark:divide-gray-800/60">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $cat->name }}</td>
                            <td class="px-6 py-4 font-mono text-xs">{{ $cat->slug }}</td>
                            <td class="px-6 py-4 text-xs max-w-xs truncate">{{ $cat->description ?: '-' }}</td>
                            <td class="px-6 py-4 text-center font-semibold text-gray-700 dark:text-gray-300">{{ $cat->informations_count }}</td>
                            <td class="px-6 py-4 flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.categories.edit', $cat->id) }}" class="text-xs font-semibold text-primary hover:underline">Edit</a>
                                <span class="text-gray-300 dark:text-gray-700">|</span>
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this)" class="text-xs font-semibold text-red-650 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 italic">Belum ada kategori yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-250/20 dark:border-gray-800/80">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function confirmDelete(button) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Kategori yang dihapus akan menghapus relasi dokumen di dalamnya!",
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

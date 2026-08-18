@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-left">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Dokumen Informasi</h1>
            <p class="text-xs text-gray-500">Kelola dokumen informasi perusahaan dan kelola versi histori bulanan di dalamnya.</p>
        </div>
        <a href="{{ route('admin.informations.create') }}" class="bg-primary text-white text-xs font-semibold px-4 py-2.5 rounded-xl hover:bg-blue-700 transition shadow-md shadow-primary/10">
            Tambah Informasi Baru
        </a>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white dark:bg-gray-850 p-4 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm">
        <form action="{{ route('admin.informations.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
            <div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari judul..." 
                       class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 text-xs focus:ring-primary/20 focus:border-primary">
            </div>
            <div>
                <select name="category_id" class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 text-xs focus:ring-primary/20 focus:border-primary">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 text-xs focus:ring-primary/20 focus:border-primary">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-xs font-semibold px-4 py-2.5 rounded-xl transition w-full">
                    Saring
                </button>
                <a href="{{ route('admin.informations.index') }}" class="text-center bg-transparent border border-gray-250 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 text-xs font-semibold px-4 py-2.5 rounded-xl transition w-full">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Informations List Card -->
    <div class="bg-white dark:bg-gray-850 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-4">Cover</th>
                        <th class="px-6 py-4">Informasi</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Histori Bulanan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/65 dark:divide-gray-800/60">
                    @forelse($informations as $info)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                            <!-- Cover thumbnail -->
                            <td class="px-6 py-4">
                                <div class="w-14 h-10 rounded-lg bg-cover bg-center border border-gray-100 dark:border-gray-800/80 shadow-sm"
                                     style="background-image: url('{{ $info->thumbnail ? asset('storage/' . $info->thumbnail) : 'https://images.unsplash.com/photo-1457369804613-52c61a468e7d?q=80&w=200&auto=format&fit=crop' }}')"></div>
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <p class="font-semibold text-gray-900 dark:text-white leading-tight truncate">{{ $info->title }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5 truncate">Oleh: {{ $info->author->name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-350 text-xs px-2.5 py-1 rounded-lg">
                                    {{ $info->category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($info->status === 'published')
                                    <span class="bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 text-xs px-2.5 py-1 rounded-lg font-medium">Published</span>
                                @else
                                    <span class="bg-amber-50 dark:bg-amber-950/20 text-amber-600 dark:text-amber-450 text-xs px-2.5 py-1 rounded-lg font-medium">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.informations.versions', $info->id) }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-primary/10 hover:bg-primary/20 text-primary dark:text-blue-400 rounded-xl text-xs font-semibold transition">
                                    <span>Lihat Versi ({{ $info->versions()->count() }})</span>
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.informations.edit', $info->id) }}" class="text-xs font-semibold text-primary hover:underline">Edit</a>
                                    <span class="text-gray-300 dark:text-gray-700">|</span>
                                    <form action="{{ route('admin.informations.destroy', $info->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete(this)" class="text-xs font-semibold text-red-650 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">Belum ada informasi yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($informations->hasPages())
            <div class="px-6 py-4 border-t border-gray-250/20 dark:border-gray-800/80">
                {{ $informations->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function confirmDelete(button) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Semua histori versi bulanan dan lampirannya juga akan terhapus secara permanen!",
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

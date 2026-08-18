@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-left">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Kelola Banner Carousel</h1>
            <p class="text-xs text-gray-500">Mengelola gambar promosi/informasi utama di beranda depan viewer.</p>
        </div>
        <a href="{{ route('admin.banners.create') }}" class="bg-primary text-white text-xs font-semibold px-4 py-2.5 rounded-xl hover:bg-blue-700 transition shadow-md shadow-primary/10">
            Tambah Banner
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-850 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-4">Gambar</th>
                        <th class="px-6 py-4">Judul</th>
                        <th class="px-6 py-4 text-center">Urutan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/65 dark:divide-gray-800/60">
                    @forelse($banners as $banner)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                            <td class="px-6 py-4">
                                <div class="w-20 h-12 bg-cover bg-center rounded-lg border dark:border-gray-800" style="background-image: url('{{ asset('storage/' . $banner->image_path) }}')"></div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900 dark:text-white leading-tight">{{ $banner->title }}</p>
                                <p class="text-[10px] text-gray-450 truncate max-w-xs mt-0.5">{{ $banner->description ?: '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center font-mono">{{ $banner->order }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($banner->is_active)
                                    <span class="bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 text-xs px-2.5 py-1 rounded-lg font-medium">Aktif</span>
                                @else
                                    <span class="bg-gray-100 dark:bg-gray-800 text-gray-400 text-xs px-2.5 py-1 rounded-lg font-medium">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="text-xs font-semibold text-primary hover:underline">Edit</a>
                                    <span class="text-gray-300 dark:text-gray-700">|</span>
                                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete(this)" class="text-xs font-semibold text-red-650 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 italic">Belum ada banner yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function confirmDelete(button) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Banner ini akan dihapus secara permanen dari beranda depan!",
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

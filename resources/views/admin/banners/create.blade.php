@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-left max-w-xl">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Tambah Banner</h1>
        <p class="text-xs text-gray-500">Mendaftarkan banner carousel baru di beranda depan viewer.</p>
    </div>

    <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm">
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Title -->
            <div class="space-y-1.5">
                <label for="title" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Judul Banner</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Description -->
            <div class="space-y-1.5">
                <label for="description" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Deskripsi / Penjelasan Singkat</label>
                <textarea id="description" name="description" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">{{ old('description') }}</textarea>
                @error('description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Image File -->
            <div class="space-y-1.5">
                <label for="image" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Berkas Gambar</label>
                <input type="file" id="image" name="image" required accept="image/*" class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                <p class="text-[10px] text-gray-400">File format image (max 3MB).</p>
                @error('image') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Link URL -->
            <div class="space-y-1.5">
                <label for="link_url" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Link URL (Opsional)</label>
                <input type="url" id="link_url" name="link_url" value="{{ old('link_url') }}" placeholder="https://example.com" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                @error('link_url') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Order -->
                <div class="space-y-1.5">
                    <label for="order" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Urutan Tampil</label>
                    <input type="number" id="order" name="order" value="{{ old('order', 0) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm font-mono">
                    @error('order') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Active -->
                <div class="flex items-center space-x-2 pt-6">
                    <input type="checkbox" id="is_active" name="is_active" value="1" checked class="w-4.5 h-4.5 text-primary border-gray-300 rounded focus:ring-primary/20">
                    <label for="is_active" class="text-xs font-semibold text-gray-700 dark:text-gray-300 cursor-pointer">Langsung Aktif</label>
                </div>
            </div>

            <!-- Submit buttons -->
            <div class="flex items-center space-x-3 pt-2">
                <button type="submit" class="bg-primary text-white text-xs font-semibold px-5 py-2.5 rounded-xl hover:bg-blue-700 transition">
                    Simpan Banner
                </button>
                <a href="{{ route('admin.banners.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 text-xs font-semibold transition text-gray-600 dark:text-gray-350">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

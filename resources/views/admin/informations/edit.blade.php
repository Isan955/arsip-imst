@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-left max-w-xl">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Ubah Informasi</h1>
        <p class="text-xs text-gray-500">Edit metadata utama dokumen informasi.</p>
    </div>

    <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm">
        <form action="{{ route('admin.informations.update', $information->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="space-y-1.5">
                <label for="title" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Judul Informasi</label>
                <input type="text" id="title" name="title" value="{{ old('title', $information->title) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Category -->
            <div class="space-y-1.5">
                <label for="category_id" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Kategori</label>
                <select id="category_id" name="category_id" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $information->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Status -->
            <div class="space-y-1.5">
                <label for="status" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</label>
                <select id="status" name="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                    <option value="draft" {{ old('status', $information->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $information->status) === 'published' ? 'selected' : '' }}>Published</option>
                </select>
                @error('status') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Thumbnail -->
            <div class="space-y-3">
                <label for="thumbnail" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Cover / Thumbnail Gambar</label>
                @if($information->thumbnail)
                    <div class="w-28 h-20 bg-cover bg-center rounded-xl border" style="background-image: url('{{ asset('storage/' . $information->thumbnail) }}')"></div>
                @endif
                <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                <p class="text-[10px] text-gray-400">Biarkan kosong jika tidak ingin mengganti cover. Max 2MB.</p>
                @error('thumbnail') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Summary -->
            <div class="space-y-1.5">
                <label for="summary" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Ringkasan Singkat</label>
                <textarea id="summary" name="summary" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">{{ old('summary', $information->summary) }}</textarea>
                @error('summary') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Submit buttons -->
            <div class="flex items-center space-x-3 pt-2">
                <button type="submit" class="bg-primary text-white text-xs font-semibold px-5 py-2.5 rounded-xl hover:bg-blue-700 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.informations.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 text-xs font-semibold transition text-gray-600 dark:text-gray-350">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

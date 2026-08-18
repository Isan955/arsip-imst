@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-left max-w-4xl">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Tambah Dokumen Informasi</h1>
        <p class="text-xs text-gray-500">Mendaftarkan entitas informasi baru beserta versi bulan pertamanya (opsional).</p>
    </div>

    <form action="{{ route('admin.informations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Metadata Card -->
        <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm space-y-4">
            <h3 class="font-bold text-gray-900 dark:text-white text-base">Metadata Utama</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Title -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="title" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Judul Informasi</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                    @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Category -->
                <div class="space-y-1.5">
                    <label for="category_id" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Kategori</label>
                    <select id="category_id" name="category_id" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Status -->
                <div class="space-y-1.5">
                    <label for="status" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status Awal</label>
                    <select id="status" name="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                    @error('status') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Thumbnail -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="thumbnail" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Cover / Thumbnail Gambar</label>
                    <input type="file" id="thumbnail" name="thumbnail" accept="image/*" class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                    <p class="text-[10px] text-gray-400">File format image (max 2MB).</p>
                    @error('thumbnail') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Summary -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="summary" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Ringkasan Singkat</label>
                    <textarea id="summary" name="summary" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">{{ old('summary') }}</textarea>
                    @error('summary') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Initial Monthly Version Card (Optional) -->
        <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="font-bold text-gray-900 dark:text-white text-base">Konten & Histori Bulan Pertama</h3>
                <span class="text-[10px] bg-blue-50 dark:bg-blue-950/20 text-primary dark:text-blue-400 px-2 py-0.5 rounded-md font-semibold">Opsional</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Month -->
                <div class="space-y-1.5">
                    <label for="month" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Bulan</label>
                    <select id="month" name="month" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                        <option value="">Pilih Bulan</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ old('month', date('n')) == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null, $i, 1)->translatedFormat('F') }}</option>
                        @endfor
                    </select>
                    @error('month') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Year -->
                <div class="space-y-1.5">
                    <label for="year" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Tahun</label>
                    <input type="number" id="year" name="year" value="{{ old('year', date('Y')) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                    @error('year') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Rich Content -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="rich_content" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Konten Utama (Rich Text)</label>
                    <textarea id="rich_content" name="rich_content" rows="8">{{ old('rich_content') }}</textarea>
                    @error('rich_content') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Attachment PDF -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="attachment_file" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Lampiran Pendukung (PDF)</label>
                    <input type="file" id="attachment_file" name="attachment_file" accept=".pdf" class="w-full px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                    <p class="text-[10px] text-gray-400">Hanya berkas berekstensi PDF (max 10MB).</p>
                    @error('attachment_file') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Submit buttons -->
        <div class="flex items-center space-x-3">
            <button type="submit" class="bg-primary text-white text-xs font-semibold px-5 py-2.5 rounded-xl hover:bg-blue-700 transition">
                Simpan Informasi
            </button>
            <a href="{{ route('admin.informations.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 text-xs font-semibold transition text-gray-600 dark:text-gray-355">
                Batal
            </a>
        </div>
    </form>
</div>

<!-- Load CKEditor via CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#rich_content'), {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo' ]
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endsection

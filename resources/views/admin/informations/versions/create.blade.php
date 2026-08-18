@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-left max-w-4xl">
    <div>
        <nav class="flex text-[10px] text-gray-500 space-x-2 mb-2">
            <a href="{{ route('admin.informations.index') }}" class="hover:text-primary transition">Daftar Informasi</a>
            <span>/</span>
            <a href="{{ route('admin.informations.versions', $information->id) }}" class="hover:text-primary transition">Histori Bulanan</a>
            <span>/</span>
            <span class="text-gray-400">Tambah Versi</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Tambah Versi Bulanan</h1>
        <p class="text-xs text-gray-500">Membuat riwayat/konten versi bulan baru untuk: <span class="font-semibold text-primary dark:text-blue-400">{{ $information->title }}</span></p>
    </div>

    <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm">
        <form action="{{ route('admin.informations.versions.store', $information->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Month -->
                <div class="space-y-1.5">
                    <label for="month" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Bulan</label>
                    <select id="month" name="month" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
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
                    <input type="number" id="year" name="year" value="{{ old('year', date('Y')) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                    @error('year') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Rich Content -->
                <div class="space-y-1.5 md:col-span-2">
                    <label for="rich_content" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Konten Utama (Rich Text)</label>
                    <textarea id="rich_content" name="rich_content" rows="10">{{ old('rich_content') }}</textarea>
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

            <!-- Submit buttons -->
            <div class="flex items-center space-x-3 pt-2">
                <button type="submit" class="bg-primary text-white text-xs font-semibold px-5 py-2.5 rounded-xl hover:bg-blue-700 transition">
                    Simpan Versi
                </button>
                <a href="{{ route('admin.informations.versions', $information->id) }}" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 text-xs font-semibold transition text-gray-600 dark:text-gray-350">
                    Batal
                </a>
            </div>
        </form>
    </div>
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

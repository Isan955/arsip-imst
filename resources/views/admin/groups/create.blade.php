@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-left max-w-2xl">
    <div>
        <nav class="flex text-[10px] text-gray-500 space-x-2 mb-2">
            <a href="{{ route('admin.groups.index') }}" class="hover:text-primary transition">Kelola Group User</a>
            <span>/</span>
            <span class="text-gray-400">Tambah Group</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Tambah Group User Baru</h1>
        <p class="text-xs text-gray-500">Mendaftarkan kelompok pengguna baru dan menentukan anggotanya.</p>
    </div>

    <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm">
        <form action="{{ route('admin.groups.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Name -->
            <div class="space-y-1.5">
                <label for="name" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Nama Group</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Kesekretariatan, Divisi Keuangan" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Description -->
            <div class="space-y-1.5">
                <label for="description" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Deskripsi (Opsional)</label>
                <textarea id="description" name="description" rows="3" placeholder="Keterangan singkat mengenai group ini..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">{{ old('description') }}</textarea>
                @error('description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Access Mode Selection -->
            <div class="space-y-1.5">
                <label for="access_mode" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Mode Akses Data Default</label>
                <select id="access_mode" name="access_mode" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm font-semibold">
                    <option value="custom" {{ old('access_mode', 'custom') === 'custom' ? 'selected' : '' }}>Custom (Tree-view Checkbox)</option>
                    <option value="categories" {{ old('access_mode') === 'categories' ? 'selected' : '' }}>Berdasarkan Kategori</option>
                    <option value="all" {{ old('access_mode') === 'all' ? 'selected' : '' }}>Semua Data (Waris Penuh)</option>
                </select>
                <p class="text-[10px] text-gray-400">Peta akses detail (kategori, dokumen, dan versi bulanan) dapat diatur setelah group dibuat.</p>
                @error('access_mode') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Members Selection -->
            <div class="space-y-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                <label class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider block">Pilih Anggota Viewer</label>
                <p class="text-[10px] text-gray-400 mb-2">Pilih user dengan role Viewer yang akan dimasukkan ke dalam group ini:</p>

                <div class="max-h-56 overflow-y-auto border border-gray-200 dark:border-gray-800 rounded-xl p-3 space-y-2 bg-gray-50/50 dark:bg-gray-900/40">
                    @forelse($viewers as $viewer)
                        <label class="flex items-center space-x-3 p-2 rounded-lg hover:bg-white dark:hover:bg-gray-800 transition cursor-pointer">
                            <input type="checkbox" name="members[]" value="{{ $viewer->id }}" {{ in_array($viewer->id, old('members', [])) ? 'checked' : '' }} class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary/20">
                            <div>
                                <p class="text-xs font-semibold text-gray-900 dark:text-white">{{ $viewer->name }}</p>
                                <p class="text-[10px] text-gray-400">{{ $viewer->email }}</p>
                            </div>
                        </label>
                    @empty
                        <p class="text-xs text-gray-400 italic py-2">Belum ada user dengan role Viewer.</p>
                    @endforelse
                </div>
                @error('members') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Submit buttons -->
            <div class="flex items-center space-x-3 pt-4">
                <button type="submit" class="bg-primary text-white text-xs font-semibold px-5 py-2.5 rounded-xl hover:bg-blue-700 transition">
                    Simpan Group
                </button>
                <a href="{{ route('admin.groups.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 text-xs font-semibold transition text-gray-600 dark:text-gray-350">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

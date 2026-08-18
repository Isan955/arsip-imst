@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-left max-w-xl">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Tambah Kategori</h1>
        <p class="text-xs text-gray-500">Buat kategori baru untuk dokumen informasi.</p>
    </div>

    <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-5" x-data="{ name: '', slug: '' }">
            @csrf

            <!-- Name -->
            <div class="space-y-1.5">
                <label for="name" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Nama Kategori</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       x-model="name" 
                       @input="slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '')"
                       value="{{ old('name') }}" 
                       required 
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                @error('name')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Slug -->
            <div class="space-y-1.5">
                <label for="slug" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Slug (URL)</label>
                <input type="text" 
                       id="slug" 
                       name="slug" 
                       x-model="slug" 
                       value="{{ old('slug') }}" 
                       required 
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm font-mono">
                @error('slug')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Description -->
            <div class="space-y-1.5">
                <label for="description" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Deskripsi</label>
                <textarea id="description" 
                          name="description" 
                          rows="4" 
                          class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit buttons -->
            <div class="flex items-center space-x-3 pt-2">
                <button type="submit" class="bg-primary text-white text-xs font-semibold px-5 py-2.5 rounded-xl hover:bg-blue-700 transition">
                    Simpan Kategori
                </button>
                <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 text-xs font-semibold transition text-gray-600 dark:text-gray-350">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

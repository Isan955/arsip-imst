@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-left" x-data="accessManager({{ $user->access_mode === 'all' ? "'all'" : ($user->access_mode === 'categories' ? "'categories'" : "'custom'") }})">
    <div>
        <nav class="flex text-[10px] text-gray-500 space-x-2 mb-2">
            <a href="{{ route('admin.users.index') }}" class="hover:text-primary transition">Kelola Pengguna</a>
            <span>/</span>
            <span class="text-gray-400">Atur Hak Akses Granular</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Hak Akses Granular</h1>
        <p class="text-xs text-gray-500">Membatasi akses pembacaan dokumen untuk user Viewer: <span class="font-bold text-primary dark:text-blue-400">{{ $user->name }}</span> ({{ $user->email }})</p>
    </div>

    <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm">
        <form action="{{ route('admin.users.access.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf

            <!-- Mode Akses Selection -->
            <div class="space-y-2.5 max-w-md">
                <label for="access_mode" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Mode Akses Data</label>
                <select id="access_mode" 
                        name="access_mode" 
                        x-model="mode" 
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm font-semibold">
                    <option value="all">Semua Data (Waris Penuh)</option>
                    <option value="categories">Berdasarkan Kategori</option>
                    <option value="custom">Custom (Tree-view Checkbox)</option>
                </select>
                <p class="text-[10px] text-gray-450 mt-1" x-text="getExplanation()"></p>
            </div>

            <!-- Category-only Selection Wrapper -->
            <div x-show="mode === 'categories'" class="border-t border-gray-100 dark:border-gray-800 pt-6 space-y-4" style="display: none;">
                <h3 class="font-bold text-sm text-gray-905 dark:text-white">Pilih Kategori yang Diizinkan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($categories as $category)
                        <label class="flex items-start space-x-3 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border dark:border-gray-800 hover:border-primary/55 cursor-pointer transition">
                            <input type="checkbox" 
                                   name="categories[]" 
                                   value="{{ $category->id }}"
                                   {{ in_array($category->id, $assignedCategories) ? 'checked' : '' }}
                                   class="mt-1 w-4.5 h-4.5 text-primary border-gray-300 rounded focus:ring-primary/20">
                            <div class="text-left">
                                <p class="text-xs font-semibold text-gray-900 dark:text-white">{{ $category->name }}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5 line-clamp-2">{{ $category->description ?: 'Tanpa deskripsi.' }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Custom Tree-View Checkbox Wrapper -->
            <div x-show="mode === 'custom'" class="border-t border-gray-100 dark:border-gray-800 pt-6 space-y-4" style="display: none;">
                <h3 class="font-bold text-sm text-gray-905 dark:text-white">Peta Akses Kategori &rarr; Informasi &rarr; Versi Bulanan</h3>
                
                <div class="space-y-6">
                    @foreach($categories as $category)
                        <!-- Category level -->
                        <div class="border border-gray-100 dark:border-gray-800/80 rounded-2xl p-4 bg-gray-50/50 dark:bg-gray-800/20 space-y-4">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" 
                                       name="categories[]" 
                                       value="{{ $category->id }}"
                                       {{ in_array($category->id, $assignedCategories) ? 'checked' : '' }}
                                       class="w-4.5 h-4.5 text-purple-600 border-gray-300 rounded focus:ring-purple-500/20">
                                <span class="text-xs font-bold text-purple-650 dark:text-purple-400">Kategori: {{ $category->name }}</span>
                            </label>

                            <!-- Information level (indented) -->
                            <div class="ml-6 pl-2 border-l border-dashed border-gray-250 dark:border-gray-800 space-y-4">
                                @forelse($category->informations as $info)
                                    <div class="space-y-2">
                                        <label class="flex items-center space-x-3 cursor-pointer">
                                            <input type="checkbox" 
                                                   name="informations[]" 
                                                   value="{{ $info->id }}"
                                                   {{ in_array($info->id, $assignedInformations) ? 'checked' : '' }}
                                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500/20">
                                            <span class="text-xs font-semibold text-gray-900 dark:text-white">Dokumen: {{ $info->title }}</span>
                                        </label>

                                        <!-- Monthly Version level (indented again) -->
                                        <div class="ml-6 flex flex-wrap gap-2">
                                            @forelse($info->versions as $version)
                                                @php
                                                    $monthName = \Carbon\Carbon::create(null, $version->month, 1)->translatedFormat('F');
                                                @endphp
                                                <label class="flex items-center space-x-1.5 px-3 py-1.5 bg-white dark:bg-gray-800 rounded-lg border dark:border-gray-700 cursor-pointer text-xs transition hover:border-primary/50">
                                                    <input type="checkbox" 
                                                           name="versions[]" 
                                                           value="{{ $version->id }}"
                                                           {{ in_array($version->id, $assignedVersions) ? 'checked' : '' }}
                                                           class="w-3.5 h-3.5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500/20">
                                                    <span>{{ $monthName }} {{ $version->year }}</span>
                                                </label>
                                            @empty
                                                <span class="text-[10px] text-gray-400 italic">Belum ada versi bulanan untuk dokumen ini.</span>
                                            @endforelse
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-[10px] text-gray-400 italic">Belum ada dokumen terdaftar dalam kategori ini.</p>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Submit buttons -->
            <div class="flex items-center space-x-3 pt-4">
                <button type="submit" class="bg-primary text-white text-xs font-semibold px-5 py-2.5 rounded-xl hover:bg-blue-700 transition">
                    Simpan Hak Akses
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 text-xs font-semibold transition text-gray-600 dark:text-gray-350">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function accessManager(initialMode) {
        return {
            mode: initialMode,
            getExplanation() {
                if (this.mode === 'all') {
                    return 'Mode ini memberikan izin akses membaca untuk seluruh kategori, dokumen informasi, dan riwayat dokumen tanpa terkecuali.';
                } else if (this.mode === 'categories') {
                    return 'Membatasi akses Viewer hanya pada dokumen yang masuk di dalam Kategori yang dicentang saja.';
                } else {
                    return 'Kontrol penuh hirarki. Viewer hanya dapat melihat kategori, informasi, atau versi bulanan spesifik yang dipilih dalam checklist tree di bawah.';
                }
            }
        }
    }
</script>
@endsection

@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-left">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Kelola Group User</h1>
            <p class="text-xs text-gray-500">Kelola kelompok pengguna Viewer dan distribusi hak akses data terpusat.</p>
        </div>
        <a href="{{ route('admin.groups.create') }}" class="bg-primary text-white text-xs font-semibold px-4 py-2.5 rounded-xl hover:bg-blue-700 transition shadow-md shadow-primary/10">
            Tambah Group Baru
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-850 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-4">Nama Group</th>
                        <th class="px-6 py-4">Deskripsi</th>
                        <th class="px-6 py-4 text-center">Jumlah Anggota</th>
                        <th class="px-6 py-4 text-center">Mode Akses</th>
                        <th class="px-6 py-4 text-center">Hak Akses</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/65 dark:divide-gray-800/60">
                    @forelse($groups as $group)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $group->name }}</td>
                            <td class="px-6 py-4 text-xs text-gray-500 max-w-xs truncate">{{ $group->description ?: '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-blue-50 dark:bg-blue-950/20 text-primary dark:text-blue-400 text-xs px-2.5 py-1 rounded-lg font-semibold">
                                    {{ $group->users_count }} Viewer
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs uppercase font-semibold px-2.5 py-1 rounded-lg {{ $group->access_mode === 'all' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/20 dark:text-emerald-400' : ($group->access_mode === 'categories' ? 'bg-amber-50 text-amber-600 dark:bg-amber-950/20 dark:text-amber-400' : 'bg-purple-50 text-purple-600 dark:bg-purple-950/20 dark:text-purple-400') }}">
                                    {{ $group->access_mode }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.groups.access', $group->id) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-purple-50 dark:bg-purple-950/20 hover:bg-purple-100 text-purple-650 dark:text-purple-400 rounded-xl text-xs font-semibold transition">
                                    <span>Kelola Akses ({{ $group->access_rules_count }} aturan)</span>
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.groups.edit', $group->id) }}" class="text-xs font-semibold text-primary hover:underline">Edit</a>
                                    <span class="text-gray-300 dark:text-gray-700">|</span>
                                    <form action="{{ route('admin.groups.destroy', $group->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete(this)" class="text-xs font-semibold text-red-650 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">Belum ada group user yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($groups->hasPages())
            <div class="px-6 py-4 border-t border-gray-250/20 dark:border-gray-800/80">
                {{ $groups->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function confirmDelete(button) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Group ini akan dihapus. Anggota group akan kehilangan akses yang berasal dari group ini!",
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

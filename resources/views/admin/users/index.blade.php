@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-left">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Kelola Pengguna</h1>
            <p class="text-xs text-gray-500">Daftar pengguna portal, peran, status aktivasi, dan akses data granular.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-primary text-white text-xs font-semibold px-4 py-2.5 rounded-xl hover:bg-blue-700 transition shadow-md shadow-primary/10">
            Tambah User Baru
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-850 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-800 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Peran (Role)</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Akses Granular</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/65 dark:divide-gray-800/60">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $user->name }}</td>
                            <td class="px-6 py-4 font-mono text-xs">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                @foreach($user->roles as $role)
                                    <span class="bg-primary/10 text-primary dark:text-blue-400 text-xs px-2.5 py-1 rounded-lg font-semibold">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($user->is_active)
                                    <span class="bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 text-xs px-2.5 py-1 rounded-lg font-medium">Aktif</span>
                                @else
                                    <span class="bg-red-50 dark:bg-red-950/20 text-red-650 dark:text-red-400 text-xs px-2.5 py-1 rounded-lg font-medium">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($user->hasRole('Viewer'))
                                    <a href="{{ route('admin.users.access', $user->id) }}" class="inline-flex items-center space-x-1 px-3 py-1.5 bg-purple-50 dark:bg-purple-950/20 hover:bg-purple-100 text-purple-650 dark:text-purple-400 rounded-xl text-xs font-semibold transition">
                                        <span>Kelola Akses ({{ strtoupper($user->access_mode) }})</span>
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 italic">Akses Penuh (Admin)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-xs font-semibold text-primary hover:underline">Edit</a>
                                    
                                    @if($user->id !== auth()->id())
                                        <span class="text-gray-300 dark:text-gray-700">|</span>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(this)" class="text-xs font-semibold text-red-650 hover:underline">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">Belum ada pengguna terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-250/20 dark:border-gray-800/80">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function confirmDelete(button) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Pengguna ini akan dihapus secara permanen beserta data relasinya!",
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

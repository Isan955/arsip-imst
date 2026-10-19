@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-left max-w-xl">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Ubah Data User</h1>
        <p class="text-xs text-gray-500">Edit informasi profil, peran, dan reset sandi pengguna.</p>
    </div>

    <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="space-y-1.5">
                <label for="name" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Email -->
            <div class="space-y-1.5">
                <label for="email" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Alamat Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <!-- Role Selection -->
            <div class="space-y-1.5">
                <label for="role" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Peran (Role)</label>
                <select id="role" name="role" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                    <option value="">Pilih Peran</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role', $user->roles->first()->name ?? '') === $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="border-t border-gray-100 dark:border-gray-800 my-4 pt-4 space-y-4">
                <h3 class="font-bold text-xs text-gray-900 dark:text-white uppercase tracking-wider">Reset Kata Sandi (Kosongkan jika tidak diubah)</h3>
                
                <!-- Password -->
                <div class="space-y-1.5">
                    <label for="password" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Kata Sandi Baru</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                    @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="space-y-1.5">
                    <label for="password_confirmation" class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Konfirmasi Kata Sandi</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                </div>
            </div>

            <!-- Active Checkbox -->
            <div class="flex items-center space-x-2 pt-2">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="w-4.5 h-4.5 text-primary border-gray-300 rounded focus:ring-primary/20">
                <label for="is_active" class="text-xs font-semibold text-gray-700 dark:text-gray-300 cursor-pointer">Aktif (User bisa masuk)</label>
            </div>

            <!-- Submit buttons -->
            <div class="flex items-center space-x-3 pt-4">
                <button type="submit" class="bg-primary text-white text-xs font-semibold px-5 py-2.5 rounded-xl hover:bg-blue-700 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 text-xs font-semibold transition text-gray-600 dark:text-gray-350">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

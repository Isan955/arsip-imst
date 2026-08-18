@extends('layouts.admin')

@section('content')
<div class="space-y-6 text-left max-w-2xl">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Pengaturan Website & Perusahaan</h1>
        <p class="text-xs text-gray-500">Mengelola konfigurasi sistem portal informasi dan profil perusahaan.</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Website Settings -->
        <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm space-y-4">
            <h3 class="font-bold text-gray-900 dark:text-white text-base">Konfigurasi Portal</h3>
            
            <div class="space-y-4">
                @foreach($settings as $setting)
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ $setting->label ?: $setting->key }}</label>
                        
                        @if($setting->type === 'textarea')
                            <textarea name="settings[{{ $setting->key }}]" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">{{ $setting->value }}</textarea>
                        @elseif($setting->type === 'boolean')
                            <select name="settings[{{ $setting->key }}]" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                                <option value="true" {{ $setting->value === 'true' ? 'selected' : '' }}>Aktif (True)</option>
                                <option value="false" {{ $setting->value === 'false' ? 'selected' : '' }}>Nonaktif (False)</option>
                            </select>
                        @else
                            <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Company Profiles -->
        <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-200/60 dark:border-gray-800/60 shadow-sm space-y-4">
            <h3 class="font-bold text-gray-900 dark:text-white text-base">Profil Perusahaan</h3>
            
            <div class="space-y-4">
                @foreach($profiles as $profile)
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ $profile->label ?: $profile->key }}</label>
                        @if($profile->key === 'address')
                            <textarea name="profiles[{{ $profile->key }}]" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">{{ $profile->value }}</textarea>
                        @else
                            <input type="text" name="profiles[{{ $profile->key }}]" value="{{ $profile->value }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 dark:bg-gray-900 focus:ring-primary/20 focus:border-primary text-sm">
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center">
            <button type="submit" class="bg-primary text-white text-xs font-semibold px-5 py-2.5 rounded-xl hover:bg-blue-700 transition shadow-md shadow-primary/10">
                Simpan Semua Konfigurasi
            </button>
        </div>
    </form>
</div>
@endsection

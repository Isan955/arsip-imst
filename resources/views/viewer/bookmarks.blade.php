@extends('layouts.viewer')

@section('content')
<div class="space-y-6 text-left">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Bookmark Saya</h1>
            <p class="text-sm text-gray-500">Daftar dokumen informasi yang Anda tandai sebagai favorit.</p>
        </div>
    </div>

    @if($bookmarks->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($bookmarks as $info)
                <div id="bookmark-card-{{ $info->id }}" 
                     class="bg-white dark:bg-gray-850 rounded-2xl border border-gray-100 dark:border-gray-800/80 shadow-sm overflow-hidden flex flex-col group hover:shadow-md transition duration-300">
                    
                    <!-- Cover image -->
                    <div class="h-40 w-full overflow-hidden relative bg-gray-100 dark:bg-gray-800">
                        <img src="{{ $info->thumbnail ? asset('storage/' . $info->thumbnail) : 'https://images.unsplash.com/photo-1457369804613-52c61a468e7d?q=80&w=600&auto=format&fit=crop' }}" 
                             alt="{{ $info->title }}" 
                             class="w-full h-full object-cover">
                        <span class="absolute top-3 left-3 bg-white/95 dark:bg-gray-900/90 text-primary dark:text-blue-400 text-xs font-semibold px-2.5 py-1 rounded-lg">
                            {{ $info->category->name }}
                        </span>
                        
                        <!-- Toggle button -->
                        <button onclick="removeBookmark({{ $info->id }})" 
                                class="absolute top-3 right-3 p-1.5 rounded-lg bg-white/95 dark:bg-gray-900/90 text-red-500 hover:bg-red-50 transition">
                            <svg class="w-5 h-5 fill-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <a href="{{ route('info.show', $info->slug) }}" class="block">
                                <h3 class="font-bold text-gray-900 dark:text-white text-base leading-snug group-hover:text-primary transition line-clamp-2">
                                    {{ $info->title }}
                                </h3>
                            </a>
                            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2">
                                {{ $info->summary ?: 'Tidak ada ringkasan tersedia.' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-850 border border-gray-100 dark:border-gray-800/85 rounded-2xl p-16 text-center shadow-sm space-y-4 max-w-xl mx-auto">
            <div class="w-16 h-16 bg-gray-105 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto text-gray-400">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <div class="space-y-1">
                <h3 class="font-bold text-gray-900 dark:text-white text-base">Bookmark Kosong</h3>
                <p class="text-xs text-gray-500">Anda belum menyimpan dokumen apa pun. Cari dokumen di halaman depan lalu klik tombol hati untuk menyimpannya.</p>
            </div>
            <a href="{{ route('home') }}" class="inline-flex items-center space-x-2 bg-primary text-white px-5 py-2.5 rounded-xl text-xs font-semibold hover:bg-blue-700 transition shadow-md shadow-primary/10">
                Kembali ke Dashboard
            </a>
        </div>
    @endif
</div>

<script>
    function removeBookmark(infoId) {
        fetch(`/bookmarks/toggle/${infoId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            const card = document.getElementById(`bookmark-card-${infoId}`);
            if (card) {
                card.remove();
            }
            
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'success',
                title: 'Dihapus dari bookmark.'
            });

            // Refresh page if no cards left to display empty state
            if (document.querySelectorAll('[id^="bookmark-card-"]').length === 0) {
                setTimeout(() => window.location.reload(), 1000);
            }
        })
        .catch(err => console.error(err));
    }
</script>
@endsection

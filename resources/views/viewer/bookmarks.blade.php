@extends('layouts.viewer')

@section('content')
<div class="space-y-6 text-left">
    <!-- Header -->
    <div class="flex items-center justify-between pb-4 border-b border-gray-200">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-gray-900">Bookmark Saya</h1>
            <p class="text-xs text-gray-500 mt-0.5">Daftar dokumen informasi yang Anda tandai sebagai favorit.</p>
        </div>
        <a href="{{ route('home') }}" 
           class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-xs font-medium text-gray-700 transition">
            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Beranda</span>
        </a>
    </div>

    @if($bookmarks->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($bookmarks as $info)
                <div id="bookmark-card-{{ $info->id }}" 
                     class="bg-white rounded-xl border border-gray-200 hover:border-gray-300 hover:shadow-xs transition duration-200 flex flex-col overflow-hidden group">
                    
                    <!-- Cover image -->
                    <div class="h-36 w-full overflow-hidden relative bg-gray-100 flex-shrink-0">
                        <img src="{{ $info->thumbnail ? asset('storage/' . $info->thumbnail) : 'https://images.unsplash.com/photo-1457369804613-52c61a468e7d?q=80&w=600&auto=format&fit=crop' }}" 
                             alt="{{ $info->title }}" 
                             class="w-full h-full object-cover group-hover:scale-102 transition duration-300">
                        <span class="absolute top-2.5 left-2.5 bg-white/95 text-[#1044D1] text-[11px] font-semibold px-2 py-0.5 rounded-md border border-gray-200/60 shadow-2xs">
                            {{ $info->category->name }}
                        </span>
                        
                        <!-- Toggle button -->
                        <button onclick="removeBookmark({{ $info->id }})" 
                                title="Hapus dari bookmark"
                                class="absolute top-2.5 right-2.5 p-1.5 rounded-md bg-white/95 hover:bg-red-50 text-red-500 border border-gray-200/60 shadow-2xs transition">
                            <svg class="w-4 h-4 fill-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                        <div class="space-y-1.5">
                            <a href="{{ route('info.show', $info->slug) }}" class="block">
                                <h3 class="font-semibold text-gray-900 text-sm leading-snug group-hover:text-[#1044D1] transition line-clamp-2">
                                    {{ $info->title }}
                                </h3>
                            </a>
                            <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">
                                {{ $info->summary ?: 'Tidak ada ringkasan tersedia.' }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white border border-gray-200 rounded-xl p-12 text-center shadow-2xs space-y-3 max-w-md mx-auto">
            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto text-gray-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <div class="space-y-1">
                <h3 class="font-semibold text-gray-900 text-sm">Belum Ada Bookmark</h3>
                <p class="text-xs text-gray-500">Anda belum menyimpan dokumen apapun. Klik ikon hati pada kartu dokumen di beranda untuk menyimpannya di sini.</p>
            </div>
            <div class="pt-2">
                <a href="{{ route('home') }}" class="inline-flex items-center space-x-1.5 bg-[#1044D1] text-white px-4 py-2 rounded-lg text-xs font-semibold hover:bg-blue-700 transition shadow-2xs">
                    <span>Jelajahi Dokumen</span>
                </a>
            </div>
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

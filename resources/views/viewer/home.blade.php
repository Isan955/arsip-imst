@extends('layouts.viewer')

@section('content')
<div x-data="homeSearch()" class="space-y-10">
    
    <!-- Banner Carousel -->
    @if($banners->count() > 0)
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-primary to-blue-700 text-white shadow-xl shadow-primary/10">
            <!-- Carousel items wrapper -->
            <div class="relative h-64 md:h-80 lg:h-96">
                @foreach($banners as $index => $banner)
                    <div x-show="activeSlide === {{ $index }}" 
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 translate-x-8"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0 -translate-x-8"
                         class="absolute inset-0 flex flex-col md:flex-row justify-between items-center p-8 md:p-12 space-y-4 md:space-y-0"
                         style="display: none;">
                        <div class="flex-1 space-y-4 max-w-xl text-left">
                            <span class="bg-white/20 text-white text-xs font-semibold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                Info Terkini
                            </span>
                            <h2 class="text-2xl md:text-4xl font-bold tracking-tight">{{ $banner->title }}</h2>
                            <p class="text-sm md:text-base text-blue-100/90 line-clamp-3">{{ $banner->description }}</p>
                            @if($banner->link_url)
                                <a href="{{ $banner->link_url }}" class="inline-flex items-center space-x-2 bg-white text-primary px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-50 transition shadow-sm">
                                    <span>Selengkapnya</span>
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                        <div class="hidden md:block w-72 h-48 lg:w-96 lg:h-64 rounded-xl overflow-hidden shadow-lg bg-white/10 backdrop-blur-sm p-2">
                            <div class="w-full h-full bg-cover bg-center rounded-lg" style="background-image: url('{{ asset('storage/' . $banner->image_path) }}')"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Indicators -->
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                @foreach($banners as $index => $banner)
                    <button @click="activeSlide = {{ $index }}" 
                            :class="{ 'bg-white scale-110': activeSlide === {{ $index }}, 'bg-white/40': activeSlide !== {{ $index }} }"
                            class="w-2.5 h-2.5 rounded-full transition duration-300"></button>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Live Search & Categories Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Categories & Quick Info -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-100 dark:border-gray-800/80 shadow-sm space-y-4 text-left">
                <h3 class="font-bold text-gray-900 dark:text-white text-base">Kategori Informasi</h3>
                <div class="space-y-1">
                    <button @click="setCategory(null)" 
                            :class="{ 'bg-primary/10 text-primary font-semibold': activeCategory === null, 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }"
                            class="w-full text-left px-4 py-2.5 rounded-xl text-sm transition flex justify-between items-center">
                        <span>Semua Kategori</span>
                        <span class="text-xs bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded-full text-gray-500">{{ \App\Models\Information::published()->count() }}</span>
                    </button>
                    @foreach($categories as $category)
                        <button @click="setCategory({{ $category->id }})" 
                                :class="{ 'bg-primary/10 text-primary font-semibold': activeCategory === {{ $category->id }}, 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800' }"
                                class="w-full text-left px-4 py-2.5 rounded-xl text-sm transition flex justify-between items-center">
                            <span class="truncate">{{ $category->name }}</span>
                            <span class="text-xs bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded-full text-gray-500">{{ $category->informations_count }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Popular Updates Panel -->
            <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-100 dark:border-gray-800/80 shadow-sm space-y-4 text-left">
                <h3 class="font-bold text-gray-900 dark:text-white text-base">Rekomendasi Populer</h3>
                <div class="space-y-4">
                    @forelse($popularUpdates as $pop)
                        <a href="{{ route('info.show', $pop->slug) }}" class="group flex space-x-3 items-start">
                            <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-800 flex-shrink-0 bg-cover bg-center" style="background-image: url('{{ $pop->thumbnail ? asset('storage/' . $pop->thumbnail) : 'https://images.unsplash.com/photo-1586281380349-632531db7ed4?q=80&w=200&auto=format&fit=crop' }}')"></div>
                            <div class="flex-1 space-y-0.5 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-primary transition truncate">{{ $pop->title }}</h4>
                                <p class="text-xs text-gray-500 truncate">{{ $pop->category->name }}</p>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-gray-500">Belum ada konten populer.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Main Feed (Live Search, Cards) -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Search & Info Bar -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="relative flex-1 max-w-md">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" 
                           x-model="searchQuery" 
                           @input.debounce.300ms="fetchResults()"
                           placeholder="Cari judul, ringkasan, konten, atau tag..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-850 dark:bg-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition text-sm">
                </div>
            </div>

            <!-- Loader / Empty / Content Grid -->
            <div>
                <!-- Skeleton Loader -->
                <div x-show="loading" class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: none;">
                    @for($i = 0; $i < 4; $i++)
                        <div class="bg-white dark:bg-gray-850 border border-gray-100 dark:border-gray-800/80 p-5 rounded-2xl space-y-4 animate-pulse">
                            <div class="h-40 bg-gray-200 dark:bg-gray-800 rounded-xl"></div>
                            <div class="space-y-2">
                                <div class="h-4 bg-gray-200 dark:bg-gray-800 rounded-md w-2/3"></div>
                                <div class="h-3 bg-gray-200 dark:bg-gray-800 rounded-md w-full"></div>
                                <div class="h-3 bg-gray-200 dark:bg-gray-800 rounded-md w-4/5"></div>
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- Content Grid Wrapper -->
                <div x-show="!loading" id="information-list-container">
                    @include('components.information-cards', ['informations' => $recentUpdates, 'bookmarkedIds' => $bookmarkedIds])
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function homeSearch() {
        return {
            activeSlide: 0,
            searchQuery: '',
            activeCategory: null,
            loading: false,
            // Slide interval
            init() {
                setInterval(() => {
                    this.activeSlide = (this.activeSlide + 1) % {{ $banners->count() ?: 1 }};
                }, 6000);
            },
            setCategory(catId) {
                this.activeCategory = catId;
                this.fetchResults();
            },
            fetchResults() {
                this.loading = true;
                const url = new URL('{{ route('home') }}');
                if (this.searchQuery) url.searchParams.set('search', this.searchQuery);
                if (this.activeCategory) url.searchParams.set('category', this.activeCategory);

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    document.getElementById('information-list-container').innerHTML = html;
                    this.loading = false;
                })
                .catch(err => {
                    console.error('Error fetching results:', err);
                    this.loading = false;
                });
            },
            toggleBookmark(infoId) {
                fetch(`/bookmarks/toggle/${infoId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    const btn = document.getElementById(`bookmark-btn-${infoId}`);
                    if (data.bookmarked) {
                        btn.innerHTML = `<svg class="w-5 h-5 text-red-500 fill-current" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>`;
                    } else {
                        btn.innerHTML = `<svg class="w-5 h-5 text-gray-400 group-hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>`;
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
                        title: data.message
                    });
                })
                .catch(err => console.error(err));
            }
        }
    }
</script>
@endsection

@extends('layouts.viewer')

@section('content')
<div x-data="homeSearch()" class="space-y-8">
    
    <!-- Banner Carousel -->
    @if($banners->count() > 0)
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-r from-[#1044D1] to-[#1e40af] text-white border border-blue-900/10 shadow-xs">
            <!-- Carousel items wrapper -->
            <div class="relative h-64 sm:h-72 md:h-80">
                @foreach($banners as $index => $banner)
                    <div x-show="activeSlide === {{ $index }}" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-x-4"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0 -translate-x-4"
                         class="absolute inset-0 flex flex-col md:flex-row justify-between items-center p-6 sm:p-8 md:p-10 gap-6"
                         style="display: none;">
                        <div class="flex-1 space-y-3 text-left">
                            <span class="inline-block bg-white/20 text-white text-[11px] font-semibold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                                Informasi Terkini
                            </span>
                            <h2 class="text-xl sm:text-2xl md:text-3xl font-bold tracking-tight text-white line-clamp-2">{{ $banner->title }}</h2>
                            <p class="text-xs sm:text-sm text-blue-100/90 line-clamp-2 md:line-clamp-3 leading-relaxed">{{ $banner->description }}</p>
                            @if($banner->link_url)
                                <div class="pt-1">
                                    <a href="{{ $banner->link_url }}" class="inline-flex items-center space-x-1.5 bg-white text-[#1044D1] px-4 py-2 rounded-lg text-xs font-semibold hover:bg-blue-50 transition shadow-xs">
                                        <span>Selengkapnya</span>
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="hidden md:block w-64 h-44 lg:w-80 lg:h-52 rounded-lg overflow-hidden border border-white/20 bg-white/10 p-1 flex-shrink-0">
                            <div class="w-full h-full bg-cover bg-center rounded" style="background-image: url('{{ asset('storage/' . $banner->image_path) }}')"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Indicators -->
            @if($banners->count() > 1)
                <div class="absolute bottom-3 left-1/2 transform -translate-x-1/2 flex space-x-1.5">
                    @foreach($banners as $index => $banner)
                        <button @click="activeSlide = {{ $index }}" 
                                :class="{ 'bg-white w-5': activeSlide === {{ $index }}, 'bg-white/40 w-2': activeSlide !== {{ $index }} }"
                                class="h-1.5 rounded-full transition-all duration-300"></button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <!-- Content Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <!-- Left Sidebar: Categories & Popular Updates -->
        <div class="space-y-6">
            <!-- Categories Panel -->
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-2xs space-y-3 text-left">
                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Kategori</h3>
                    <span class="text-[11px] text-gray-400 font-medium">{{ $totalAccessibleInformations }} Dokumen</span>
                </div>
                <div class="space-y-1">
                    <button @click="setCategory(null)" 
                            :class="{ 'bg-blue-50 text-[#1044D1] font-semibold': activeCategory === null, 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium': activeCategory !== null }"
                            class="w-full text-left px-3 py-2 rounded-lg text-xs transition flex justify-between items-center">
                        <span>Semua Kategori</span>
                        <span class="text-[11px] px-1.5 py-0.5 rounded-md"
                              :class="{ 'bg-blue-100 text-[#1044D1] font-semibold': activeCategory === null, 'bg-gray-100 text-gray-500': activeCategory !== null }">
                            {{ $totalAccessibleInformations }}
                        </span>
                    </button>
                    @forelse($categories as $category)
                        <button @click="setCategory({{ $category->id }})" 
                                :class="{ 'bg-blue-50 text-[#1044D1] font-semibold': activeCategory === {{ $category->id }}, 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium': activeCategory !== {{ $category->id }} }"
                                class="w-full text-left px-3 py-2 rounded-lg text-xs transition flex justify-between items-center">
                            <span class="truncate">{{ $category->name }}</span>
                            <span class="text-[11px] px-1.5 py-0.5 rounded-md"
                                  :class="{ 'bg-blue-100 text-[#1044D1] font-semibold': activeCategory === {{ $category->id }}, 'bg-gray-100 text-gray-500': activeCategory !== {{ $category->id }} }">
                                {{ $category->informations_count }}
                            </span>
                        </button>
                    @empty
                        <p class="text-xs text-gray-400 italic py-2 px-3">Tidak ada kategori tersedia.</p>
                    @endforelse
                </div>
            </div>

            <!-- Popular Updates Panel -->
            @if($popularUpdates->count() > 0)
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-2xs space-y-3 text-left">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-100">
                        Populer & Favorit
                    </h3>
                    <div class="space-y-3">
                        @foreach($popularUpdates as $pop)
                            <a href="{{ route('info.show', $pop->slug) }}" class="group flex space-x-3 items-center">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex-shrink-0 bg-cover bg-center border border-gray-100" 
                                     style="background-image: url('{{ $pop->thumbnail ? asset('storage/' . $pop->thumbnail) : 'https://images.unsplash.com/photo-1586281380349-632531db7ed4?q=80&w=200&auto=format&fit=crop' }}')"></div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-semibold text-gray-800 group-hover:text-[#1044D1] transition truncate">{{ $pop->title }}</h4>
                                    <p class="text-[11px] text-gray-400 truncate">{{ $pop->category->name }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Main Content Area: Search & Cards -->
        <div class="lg:col-span-3 space-y-5">
            <!-- Search Bar -->
            <div class="flex items-center justify-between">
                <div class="relative flex-1 max-w-md">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" 
                           x-model="searchQuery" 
                           @input.debounce.300ms="fetchResults()"
                           placeholder="Cari judul atau ringkasan dokumen..." 
                           class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-1 focus:ring-[#1044D1] focus:border-[#1044D1] transition text-xs placeholder-gray-400">
                </div>
            </div>

            <!-- Loader / Cards Grid -->
            <div>
                <!-- Skeleton Loader -->
                <div x-show="loading" class="grid grid-cols-1 md:grid-cols-2 gap-5" style="display: none;">
                    @for($i = 0; $i < 4; $i++)
                        <div class="bg-white border border-gray-200 p-4 rounded-xl space-y-3 animate-pulse">
                            <div class="h-36 bg-gray-100 rounded-lg"></div>
                            <div class="space-y-2">
                                <div class="h-4 bg-gray-100 rounded w-2/3"></div>
                                <div class="h-3 bg-gray-100 rounded w-full"></div>
                                <div class="h-3 bg-gray-100 rounded w-4/5"></div>
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
            init() {
                @if($banners->count() > 1)
                    setInterval(() => {
                        this.activeSlide = (this.activeSlide + 1) % {{ $banners->count() }};
                    }, 6000);
                @endif
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
                        btn.innerHTML = `<svg class="w-4 h-4 text-red-500 fill-current" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>`;
                    } else {
                        btn.innerHTML = `<svg class="w-4 h-4 text-gray-400 group-hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>`;
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

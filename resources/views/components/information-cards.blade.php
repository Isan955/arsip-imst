@if($informations->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
        @foreach($informations as $info)
            <div class="bg-white dark:bg-gray-850 rounded-2xl border border-gray-100 dark:border-gray-800/80 shadow-sm overflow-hidden flex flex-col group hover:shadow-md hover:-translate-y-0.5 transition duration-300">
                <!-- Thumbnail -->
                <div class="h-44 w-full overflow-hidden relative bg-gray-100 dark:bg-gray-800 flex-shrink-0">
                    <img src="{{ $info->thumbnail ? asset('storage/' . $info->thumbnail) : 'https://images.unsplash.com/photo-1457369804613-52c61a468e7d?q=80&w=600&auto=format&fit=crop' }}" 
                         alt="{{ $info->title }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-3 left-3 bg-white/95 dark:bg-gray-900/90 text-primary dark:text-blue-400 text-xs font-semibold px-2.5 py-1 rounded-lg shadow-sm">
                        {{ $info->category->name }}
                    </span>
                    
                    @auth
                        <!-- Bookmark Button -->
                        <button id="bookmark-btn-{{ $info->id }}" 
                                @click="toggleBookmark({{ $info->id }})" 
                                class="absolute top-3 right-3 p-1.5 rounded-lg bg-white/95 dark:bg-gray-900/90 hover:bg-red-50 dark:hover:bg-red-950/20 text-gray-400 transition group/bookmark">
                            @if(in_array($info->id, $bookmarkedIds))
                                <!-- Bookmarked -->
                                <svg class="w-5 h-5 text-red-500 fill-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            @else
                                <!-- Not Bookmarked -->
                                <svg class="w-5 h-5 text-gray-400 group-hover/bookmark:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            @endif
                        </button>
                    @endauth
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

                    <!-- Footer meta -->
                    <div class="flex items-center justify-between text-xs text-gray-400 border-t border-gray-100 dark:border-gray-800/80 pt-3">
                        <span class="truncate">Oleh: {{ $info->author->name }}</span>
                        <span>{{ $info->published_at ? $info->published_at->translatedFormat('d M Y') : $info->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $informations->links() }}
    </div>
@else
    <!-- Empty State -->
    <div class="bg-white dark:bg-gray-850 border border-gray-100 dark:border-gray-800/85 rounded-2xl p-12 text-center shadow-sm space-y-4">
        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto text-gray-400">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="space-y-1 max-w-sm mx-auto">
            <h3 class="font-bold text-gray-900 dark:text-white text-base">Tidak ada hasil ditemukan</h3>
            <p class="text-xs text-gray-500">Coba ubah kata kunci pencarian Anda atau pilih kategori lain.</p>
        </div>
    </div>
@endif

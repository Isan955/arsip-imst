@if($informations->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-left">
        @foreach($informations as $info)
            <div class="bg-white rounded-xl border border-gray-200 hover:border-gray-300 hover:shadow-xs transition duration-200 flex flex-col overflow-hidden group">
                <!-- Thumbnail -->
                <div class="h-40 w-full overflow-hidden relative bg-gray-100 flex-shrink-0">
                    <img src="{{ $info->thumbnail ? asset('storage/' . $info->thumbnail) : 'https://images.unsplash.com/photo-1457369804613-52c61a468e7d?q=80&w=600&auto=format&fit=crop' }}" 
                         alt="{{ $info->title }}" 
                         class="w-full h-full object-cover group-hover:scale-102 transition duration-300">
                    
                    <span class="absolute top-2.5 left-2.5 bg-white/95 text-[#1044D1] text-[11px] font-semibold px-2 py-0.5 rounded-md border border-gray-200/60 shadow-2xs">
                        {{ $info->category->name }}
                    </span>
                    
                    @auth
                        <!-- Bookmark Button -->
                        <button id="bookmark-btn-{{ $info->id }}" 
                                @click="toggleBookmark({{ $info->id }})" 
                                class="absolute top-2.5 right-2.5 p-1.5 rounded-md bg-white/95 hover:bg-red-50 text-gray-400 border border-gray-200/60 shadow-2xs transition group/bookmark">
                            @if(in_array($info->id, $bookmarkedIds))
                                <!-- Bookmarked -->
                                <svg class="w-4 h-4 text-red-500 fill-current" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            @else
                                <!-- Not Bookmarked -->
                                <svg class="w-4 h-4 text-gray-400 group-hover/bookmark:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            @endif
                        </button>
                    @endauth
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

                    <!-- Footer meta -->
                    <div class="flex items-center justify-between text-[11px] text-gray-400 border-t border-gray-100 pt-2.5">
                        <span class="truncate">Oleh: {{ $info->author->name }}</span>
                        <span>{{ $info->published_at ? $info->published_at->translatedFormat('d M Y') : $info->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $informations->links() }}
    </div>
@else
    <!-- Empty State -->
    <div class="bg-white border border-gray-200 rounded-xl p-10 text-center shadow-2xs space-y-3">
        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto text-gray-400">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="space-y-1 max-w-sm mx-auto">
            <h3 class="font-semibold text-gray-900 text-sm">Tidak ada dokumen ditemukan</h3>
            <p class="text-xs text-gray-500">Coba ubah kata kunci pencarian Anda atau pilih kategori lain.</p>
        </div>
    </div>
@endif

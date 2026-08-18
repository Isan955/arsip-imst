@extends('layouts.viewer')

@section('content')
<div class="space-y-6 text-left" 
     x-data="versionSwitcher('{{ $information->slug }}', {{ $activeVersion ? $activeVersion->month : 0 }}, {{ $activeVersion ? $activeVersion->year : 0 }})">
    
    <!-- Header Navigation Breadcrumb -->
    <nav class="flex text-xs text-gray-500 space-x-2">
        <a href="{{ route('home') }}" class="hover:text-primary transition">Dashboard</a>
        <span>/</span>
        <span class="text-gray-400">Informasi</span>
        <span>/</span>
        <span class="text-gray-900 dark:text-white font-semibold truncate">{{ $information->title }}</span>
    </nav>

    <!-- Main Detail Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Sidebar Version Switcher -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-100 dark:border-gray-800/80 shadow-sm space-y-4">
                <h3 class="font-bold text-gray-900 dark:text-white text-base">Histori Bulanan</h3>
                <p class="text-xs text-gray-500">Pilih bulan di bawah untuk melihat histori versi dokumen secara langsung.</p>
                
                @if($availableVersions->count() > 0)
                    <!-- Versions grouped by Year in UI -->
                    <div class="space-y-4">
                        @php
                            $grouped = $availableVersions->groupBy('year');
                        @endphp
                        @foreach($grouped as $year => $versions)
                            <div class="space-y-2">
                                <h4 class="text-xs font-bold text-primary dark:text-blue-400 uppercase tracking-wider">{{ $year }}</h4>
                                <div class="grid grid-cols-3 gap-2">
                                    @for($m = 1; $m <= 12; $m++)
                                        @php
                                            $hasVer = $versions->firstWhere('month', $m);
                                            $monthName = \Carbon\Carbon::create(null, $m, 1)->translatedFormat('M');
                                        @endphp
                                        @if($hasVer)
                                            <button @click="switchVersion({{ $m }}, {{ $year }})"
                                                    :class="{ 'bg-primary text-white border-primary shadow-sm': activeMonth === {{ $m }} && activeYear === {{ $year }}, 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }"
                                                    class="py-2 px-1 text-center rounded-lg border text-xs font-semibold transition">
                                                {{ $monthName }}
                                            </button>
                                        @else
                                            <button disabled 
                                                    class="py-2 px-1 text-center rounded-lg border border-dashed border-gray-150 dark:border-gray-800 text-gray-300 dark:text-gray-700 text-xs cursor-not-allowed">
                                                {{ $monthName }}
                                            </button>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-500 bg-gray-50 dark:bg-gray-800 p-3 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">Belum ada histori versi bulanan.</p>
                @endif
            </div>

            <!-- Meta Information Panel -->
            <div class="bg-white dark:bg-gray-850 p-6 rounded-2xl border border-gray-100 dark:border-gray-800/80 shadow-sm space-y-4">
                <h3 class="font-bold text-gray-900 dark:text-white text-sm">Metadata</h3>
                <div class="space-y-3 text-xs text-gray-600 dark:text-gray-400">
                    <div class="flex justify-between">
                        <span>Kategori:</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $information->category->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Pembuat:</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $information->author->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tanggal Publish:</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $information->published_at ? $information->published_at->translatedFormat('d M Y') : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document / Content View -->
        <div class="lg:col-span-3 space-y-6">
            
            <!-- Document Glass Card -->
            <div class="bg-white dark:bg-gray-850 rounded-2xl border border-gray-100 dark:border-gray-800/80 shadow-sm overflow-hidden flex flex-col">
                <!-- Thumbnail Cover -->
                @if($information->thumbnail)
                    <div class="h-64 md:h-80 w-full overflow-hidden bg-gray-100 dark:bg-gray-850 relative">
                        <img src="{{ asset('storage/' . $information->thumbnail) }}" alt="{{ $information->title }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-6 left-6 right-6 text-white space-y-2">
                            <span class="bg-primary text-white text-xs font-semibold px-2.5 py-1 rounded-lg">
                                {{ $information->category->name }}
                            </span>
                            <h1 class="text-xl md:text-3xl font-bold tracking-tight leading-tight">{{ $information->title }}</h1>
                        </div>
                    </div>
                @else
                    <div class="p-6 md:p-8 border-b border-gray-100 dark:border-gray-800/80 space-y-3">
                        <span class="bg-primary/10 text-primary dark:text-blue-400 text-xs font-semibold px-2.5 py-1 rounded-lg">
                            {{ $information->category->name }}
                        </span>
                        <h1 class="text-2xl md:text-4xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $information->title }}</h1>
                    </div>
                @endif

                <!-- Document Body -->
                <div class="p-6 md:p-8 space-y-6">
                    
                    <!-- Version Badge/Indicator -->
                    <div class="flex justify-between items-center py-2 px-4 bg-gray-50 dark:bg-gray-800/60 rounded-xl border border-gray-250/20 dark:border-gray-700/60 text-xs">
                        <div class="flex items-center space-x-1.5 text-gray-600 dark:text-gray-300">
                            <span>Menampilkan Versi:</span>
                            <span class="font-bold text-primary dark:text-blue-400" x-text="getVersionLabel()"></span>
                        </div>
                        <div x-show="loading" class="flex items-center space-x-1 text-gray-500" style="display: none;">
                            <svg class="animate-spin h-4 w-4 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Loading...</span>
                        </div>
                    </div>

                    <!-- Summary (if visible) -->
                    @if($information->summary)
                        <div class="p-4 bg-blue-50/50 dark:bg-blue-950/10 rounded-xl border border-blue-100/50 dark:border-blue-950/20">
                            <h4 class="text-xs font-bold text-primary dark:text-blue-400 uppercase tracking-wider mb-1">Ringkasan Dokumen</h4>
                            <p class="text-sm text-gray-700 dark:text-gray-350 leading-relaxed">{{ $information->summary }}</p>
                        </div>
                    @endif

                    <!-- Rich Content Area (Interactive & Styled) -->
                    <article class="prose max-w-none dark:prose-invert text-gray-800 dark:text-gray-200 leading-relaxed" 
                             x-html="richContent">
                        <!-- Loaded via Alpine initial or ajax -->
                        @if($activeVersion)
                            {!! $activeVersion->rich_content !!}
                        @else
                            <p class="text-gray-500 italic">Konten versi belum ditambahkan.</p>
                        @endif
                    </article>

                    <!-- Attachments Section -->
                    <div class="space-y-3 pt-6 border-t border-gray-100 dark:border-gray-800/80">
                        <h4 class="font-bold text-gray-900 dark:text-white text-sm">Lampiran Pendukung (PDF)</h4>
                        
                        <!-- Attachments dynamic list -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3" x-show="attachments.length > 0">
                            <template x-for="att in attachments" :key="att.id">
                                <a :href="att.url" target="_blank" 
                                   class="flex items-center justify-between p-3.5 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/40 hover:bg-gray-100/80 dark:hover:bg-gray-800 transition">
                                    <div class="flex items-center space-x-3 min-w-0">
                                        <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-950/30 flex items-center justify-center text-red-600 flex-shrink-0">
                                            <!-- PDF icon -->
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div class="text-left min-w-0">
                                            <p class="text-xs font-semibold text-gray-900 dark:text-white truncate" x-text="att.name"></p>
                                            <p class="text-[10px] text-gray-500" x-text="att.size"></p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-primary font-medium hover:underline flex-shrink-0">Unduh</span>
                                </a>
                            </template>
                        </div>
                        
                        <div x-show="attachments.length === 0" 
                             class="text-xs text-gray-500 italic p-3 bg-gray-50/50 dark:bg-gray-800/30 rounded-xl border border-gray-150 dark:border-gray-800">
                            Tidak ada lampiran PDF pendukung untuk versi bulan ini.
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function versionSwitcher(slug, initialMonth, initialYear) {
        return {
            slug: slug,
            activeMonth: initialMonth,
            activeYear: initialYear,
            loading: false,
            richContent: '',
            attachments: [],
            
            init() {
                // Initialize attachments array with server-side rendered array if available
                @if($activeVersion && $activeVersion->attachments->count() > 0)
                    this.attachments = [
                        @foreach($activeVersion->attachments as $att)
                            {
                                id: {{ $att->id }},
                                name: "{{ $att->file_name }}",
                                url: "{{ asset('storage/' . $att->file_path) }}",
                                size: "{{ round($att->file_size / 1024, 2) }} KB"
                            },
                        @endforeach
                    ];
                @endif
            },

            getVersionLabel() {
                const months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                return `${months[this.activeMonth]} ${this.activeYear}`;
            },

            switchVersion(month, year) {
                if (this.activeMonth === month && this.activeYear === year) return;
                this.loading = true;
                this.activeMonth = month;
                this.activeYear = year;

                fetch(`/info/${this.slug}/version?month=${month}&year=${year}`)
                    .then(res => {
                        if (!res.ok) throw new Error('Failed to fetch version content');
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            this.richContent = data.rich_content;
                            this.attachments = data.attachments;
                        }
                        this.loading = false;
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal mengambil data versi bulanan.'
                        });
                        this.loading = false;
                    });
            }
        }
    }
</script>
@endsection

@extends('layouts.viewer')

@section('content')
<div class="space-y-6 text-left" 
     x-data="versionSwitcher('{{ $information->slug }}', {{ $activeVersion ? $activeVersion->month : 0 }}, {{ $activeVersion ? $activeVersion->year : 0 }})">
    
    <!-- Header Navigation Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-[#1044D1] transition">Beranda</a>
        <span class="text-gray-300">/</span>
        <span class="text-gray-400">Informasi</span>
        <span class="text-gray-300">/</span>
        <span class="text-gray-800 font-medium truncate max-w-md">{{ $information->title }}</span>
    </nav>

    <!-- Main Detail Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <!-- Left Sidebar: Version Switcher & Metadata -->
        <div class="lg:col-span-1 space-y-5">
            <!-- Monthly Version Switcher -->
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-2xs space-y-3">
                <div class="pb-2 border-b border-gray-100">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Histori Bulanan</h3>
                    <p class="text-[11px] text-gray-400 mt-0.5">Pilih bulan untuk melihat arsip dokumen.</p>
                </div>
                
                @if($availableVersions->count() > 0)
                    <!-- Versions grouped by Year -->
                    <div class="space-y-3">
                        @php
                            $grouped = $availableVersions->groupBy('year');
                        @endphp
                        @foreach($grouped as $year => $versions)
                            <div class="space-y-1.5">
                                <h4 class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">{{ $year }}</h4>
                                <div class="grid grid-cols-3 gap-1.5">
                                    @for($m = 1; $m <= 12; $m++)
                                        @php
                                            $hasVer = $versions->firstWhere('month', $m);
                                            $monthName = \Carbon\Carbon::create(null, $m, 1)->translatedFormat('M');
                                        @endphp
                                        @if($hasVer)
                                            <button @click="switchVersion({{ $m }}, {{ $year }})"
                                                    :class="{ 'bg-[#1044D1] text-white border-[#1044D1] shadow-2xs font-semibold': activeMonth === {{ $m }} && activeYear === {{ $year }}, 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50 font-medium': !(activeMonth === {{ $m }} && activeYear === {{ $year }}) }"
                                                    class="py-1.5 px-1 text-center rounded-lg border text-xs transition focus:outline-none">
                                                {{ $monthName }}
                                            </button>
                                        @else
                                            <button disabled 
                                                    class="py-1.5 px-1 text-center rounded-lg border border-dashed border-gray-200 text-gray-300 text-xs cursor-not-allowed">
                                                {{ $monthName }}
                                            </button>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 italic bg-gray-50 p-3 rounded-lg border border-dashed border-gray-200">Belum ada arsip versi bulanan.</p>
                @endif
            </div>

            <!-- Metadata Panel -->
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-2xs space-y-3">
                <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider pb-2 border-b border-gray-100">Informasi Dokumen</h3>
                <div class="space-y-2.5 text-xs text-gray-600">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Kategori:</span>
                        <span class="font-medium text-gray-800">{{ $information->category->name }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Penulis:</span>
                        <span class="font-medium text-gray-800">{{ $information->author->name }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Publikasi:</span>
                        <span class="font-medium text-gray-800">{{ $information->published_at ? $information->published_at->translatedFormat('d F Y') : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Document View -->
        <div class="lg:col-span-3 space-y-5">
            
            <div class="bg-white rounded-xl border border-gray-200 shadow-2xs overflow-hidden flex flex-col">
                <!-- Cover Image (if present) -->
                @if($information->thumbnail)
                    <div class="h-56 sm:h-72 w-full overflow-hidden bg-gray-100 relative">
                        <img src="{{ asset('storage/' . $information->thumbnail) }}" alt="{{ $information->title }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-5 left-5 right-5 text-white space-y-1.5">
                            <span class="inline-block bg-white/95 text-[#1044D1] text-[11px] font-semibold px-2 py-0.5 rounded-md shadow-2xs">
                                {{ $information->category->name }}
                            </span>
                            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white leading-snug">{{ $information->title }}</h1>
                        </div>
                    </div>
                @else
                    <div class="p-6 border-b border-gray-100 space-y-2">
                        <span class="inline-block bg-blue-50 text-[#1044D1] text-[11px] font-semibold px-2 py-0.5 rounded-md border border-blue-100">
                            {{ $information->category->name }}
                        </span>
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 leading-snug">{{ $information->title }}</h1>
                    </div>
                @endif

                <!-- Document Body -->
                <div class="p-6 space-y-5">
                    
                    <!-- Version Indicator -->
                    <div class="flex items-center justify-between py-2 px-3.5 bg-gray-50 rounded-lg border border-gray-200/60 text-xs">
                        <div class="flex items-center space-x-1.5 text-gray-600">
                            <span>Menampilkan Dokumen Versi:</span>
                            <span class="font-semibold text-[#1044D1]" x-text="getVersionLabel()"></span>
                        </div>
                        <div x-show="loading" class="flex items-center space-x-1.5 text-gray-500" style="display: none;">
                            <svg class="animate-spin h-3.5 w-3.5 text-[#1044D1]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-[11px]">Memuat...</span>
                        </div>
                    </div>

                    <!-- Summary Box -->
                    @if($information->summary)
                        <div class="p-3.5 bg-blue-50/60 rounded-lg border border-blue-100 text-xs text-gray-700 leading-relaxed">
                            <h4 class="font-bold text-[#1044D1] uppercase tracking-wider text-[10px] mb-1">Ringkasan Dokumen</h4>
                            <p>{{ $information->summary }}</p>
                        </div>
                    @endif

                    <!-- Rich Content Area -->
                    <article class="prose prose-slate max-w-none text-gray-800 text-xs sm:text-sm leading-relaxed" 
                             x-html="richContent">
                        @if($activeVersion)
                            {!! $activeVersion->rich_content !!}
                        @else
                            <p class="text-gray-400 italic">Konten dokumen belum tersedia untuk versi ini.</p>
                        @endif
                    </article>

                    <!-- Attachments Section -->
                    <div class="space-y-3 pt-5 border-t border-gray-100">
                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Lampiran Pendukung (PDF)</h4>
                        
                        <!-- Attachments dynamic list -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" x-show="attachments.length > 0">
                            <template x-for="att in attachments" :key="att.id">
                                <button type="button" 
                                        @click="openPdf(att.url, att.name)"
                                        class="flex items-center justify-between p-3 w-full rounded-lg border border-gray-200 bg-gray-50/50 hover:bg-gray-100/70 transition text-left group">
                                    <div class="flex items-center space-x-2.5 min-w-0">
                                        <div class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center flex-shrink-0 border border-red-100">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-medium text-gray-900 truncate" x-text="att.name"></p>
                                            <p class="text-[10px] text-gray-400" x-text="att.size"></p>
                                        </div>
                                    </div>
                                    <span class="text-xs text-[#1044D1] font-medium group-hover:underline flex-shrink-0 ml-2">Pratinjau</span>
                                </button>
                            </template>
                        </div>
                        
                        <div x-show="attachments.length === 0" 
                             class="text-xs text-gray-400 italic p-3 bg-gray-50 rounded-lg border border-dashed border-gray-200">
                            Tidak ada lampiran PDF pendukung untuk versi bulan ini.
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Secure Modal PDF Viewer -->
    <div x-show="showPdfModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-2xs" @click="showPdfModal = false"></div>

        <!-- Modal Content -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-xl max-w-4xl w-full overflow-hidden shadow-2xl flex flex-col h-[85vh] border border-gray-200">
                <!-- Header -->
                <div class="px-5 py-3.5 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <div class="flex items-center space-x-2 min-w-0">
                        <span class="p-1 rounded bg-red-100 text-red-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <h3 class="text-xs font-bold text-gray-900 truncate max-w-md" x-text="activePdfName"></h3>
                    </div>
                    <button @click="showPdfModal = false" class="p-1 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- PDF Viewer Area -->
                <div class="flex-1 bg-gray-100 p-2 relative">
                    <iframe :src="activePdfUrl + '#toolbar=0&navpanes=0'" class="w-full h-full rounded border border-gray-200" frameborder="0"></iframe>
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
            showPdfModal: false,
            activePdfUrl: '',
            activePdfName: '',
            
            init() {
                @if($activeVersion && $activeVersion->attachments->count() > 0)
                    this.attachments = [
                        @foreach($activeVersion->attachments as $att)
                            {
                                id: {{ $att->id }},
                                name: "{{ $att->file_name }}",
                                url: "{{ route('attachments.stream', $att->id) }}",
                                size: "{{ round($att->file_size / 1024, 2) }} KB"
                            },
                        @endforeach
                    ];
                @endif
            },

            openPdf(url, name) {
                this.activePdfUrl = url;
                this.activePdfName = name;
                this.showPdfModal = true;
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

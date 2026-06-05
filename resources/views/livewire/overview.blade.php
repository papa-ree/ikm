<div class="space-y-8">
    <!-- Hero Section (Guideline 6.1) -->
    <div class="relative overflow-hidden p-8 mb-8 text-white rounded-2xl shadow-xl"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);"
        data-aos="fade-up">

        <!-- Dekorasi bulat kanan-atas -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <!-- Dekorasi bulat kiri-bawah -->
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-3 bg-white/20 backdrop-blur-md rounded-xl">
                        <x-lucide-award class="w-8 h-8 text-white" />
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold text-white tracking-tight">
                        {{ __('Indeks Kepuasan Masyarakat') }}
                    </h1>
                </div>
                <p class="max-w-2xl text-white/90 text-lg">
                    {{ __('Visualisasi performa pelayanan publik Kabupaten/Kota berdasarkan akumulasi data feedback masyarakat secara objektif.') }}
                </p>
            </div>

            <div class="shrink-0">
                <div class="inline-flex flex-wrap items-center p-1.5 bg-white/20 backdrop-blur-md border border-white/30 rounded-xl shadow-lg">
                    <!-- Triwulan Select -->
                    <div class="relative flex items-center group">
                        <x-lucide-calendar class="w-4 h-4 ml-3 text-white/70 group-hover:text-white transition-colors" />
                        <select wire:model.live="triwulan"
                            class="bg-transparent border-none text-sm font-bold focus:ring-0 focus:outline-none cursor-pointer pl-2 pr-10 text-white appearance-none py-2 [&>option]:text-slate-900">
                            <option value="1">TW 1</option>
                            <option value="2">TW 2</option>
                            <option value="3">TW 3</option>
                            <option value="4">TW 4</option>
                        </select>
                        <x-lucide-chevron-down class="w-3.5 h-3.5 absolute right-3 text-white/50 pointer-events-none" />
                    </div>

                    <div class="hidden sm:block w-px h-6 bg-white/30 mx-1"></div>

                    <!-- Tahun Select -->
                    <div class="relative flex items-center group">
                        <x-lucide-layers class="w-4 h-4 ml-3 text-white/70 group-hover:text-white transition-colors" />
                        <select wire:model.live="tahun"
                            class="bg-transparent border-none text-sm font-bold focus:ring-0 focus:outline-none cursor-pointer pl-2 pr-10 text-white appearance-none py-2 [&>option]:text-slate-900">
                            @foreach($tahunOptions as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                        <x-lucide-chevron-down class="w-3.5 h-3.5 absolute right-3 text-white/50 pointer-events-none" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid (Guideline 7.1) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6" data-aos="fade-up" data-aos-delay="100">
        {{-- Rerata Kota --}}
        <div class="group p-6 transition-all duration-300 bg-white border border-gray-100 shadow-md dark:bg-gray-800 rounded-2xl hover:shadow-xl dark:border-gray-700 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-linear-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg group-hover:from-indigo-600 group-hover:to-indigo-700 transition-all">
                    <x-lucide-bar-chart-3 class="w-6 h-6 text-white" />
                </div>
                <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-indigo-700 bg-indigo-50 rounded-full dark:bg-indigo-900/50 dark:text-indigo-300">
                    City Average
                </span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rerata Indeks</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                        {{ number_format($rataRata ?? 0, 2) }}
                    </h3>
                    <span class="text-xs font-medium text-gray-400">/ 100</span>
                </div>
            </div>
        </div>

        {{-- Sangat Baik --}}
        <div class="group p-6 transition-all duration-300 bg-white border border-gray-100 shadow-md dark:bg-gray-800 rounded-2xl hover:shadow-xl dark:border-gray-700 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-linear-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg group-hover:from-emerald-600 group-hover:to-emerald-700 transition-all">
                    <x-lucide-shield-check class="w-6 h-6 text-white" />
                </div>
                <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-emerald-700 bg-emerald-50 rounded-full dark:bg-emerald-900/50 dark:text-emerald-300">
                    Grade A
                </span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Unit Sangat Baik</p>
                <h3 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $jumlahA }}</h3>
            </div>
        </div>

        {{-- Baik --}}
        <div class="group p-6 transition-all duration-300 bg-white border border-gray-100 shadow-md dark:bg-gray-800 rounded-2xl hover:shadow-xl dark:border-gray-700 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-linear-to-br from-sky-500 to-sky-600 rounded-xl shadow-lg group-hover:from-sky-600 group-hover:to-sky-700 transition-all">
                    <x-lucide-smile class="w-6 h-6 text-white" />
                </div>
                <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest text-sky-700 bg-sky-50 rounded-full dark:bg-sky-900/50 dark:text-sky-300">
                    Grade B
                </span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Unit Baik</p>
                <h3 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $jumlahB }}</h3>
            </div>
        </div>

        {{-- Kurang --}}
        <div class="group p-6 transition-all duration-300 bg-white border border-gray-100 shadow-md dark:bg-gray-800 rounded-2xl hover:shadow-xl dark:border-gray-700 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-linear-to-br from-rose-500 to-rose-600 rounded-xl shadow-lg group-hover:from-rose-600 group-hover:to-rose-700 transition-all">
                    <x-lucide-alert-circle class="w-6 h-6 text-white" />
                </div>
                <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest text-rose-700 bg-rose-50 rounded-full dark:bg-rose-900/50 dark:text-rose-300">
                    Grade C/D
                </span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Perlu Evaluasi</p>
                <h3 class="mt-1 text-3xl font-bold text-gray-900 dark:text-white tracking-tight">{{ $dibawahB }}</h3>
            </div>
        </div>

        {{-- Pending --}}
        <a href="{{ route('ikm.list') }}" wire:navigate.hover class="group p-6 transition-all duration-300 {{ $pendingBatches > 0 ? 'bg-linear-to-br from-amber-100 to-orange-200 text-black shadow-xl shadow-amber-500/20 ring-1 ring-amber-400' : 'bg-white dark:bg-gray-800' }} border border-gray-100 dark:border-gray-700 rounded-2xl hover:shadow-xl hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 {{ $pendingBatches > 0 ? 'bg-white/20' : 'bg-linear-to-br from-amber-500 to-amber-600' }} rounded-xl shadow-lg">
                    <x-lucide-file-clock class="w-6 h-6 {{ $pendingBatches > 0 ? 'text-amber-800' : 'text-amber-800' }}" />
                </div>
                <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest {{ $pendingBatches > 0 ? 'bg-white/50 text-amber-800' : 'text-amber-700 bg-amber-50 dark:bg-amber-900/50 dark:text-amber-300' }} rounded-full">
                    Pending
                </span>
            </div>
            <div>
                <p class="text-sm font-medium {{ $pendingBatches > 0 ? 'text-amber-800' : 'text-gray-500 dark:text-gray-400' }}">Menunggu Verifikasi</p>
                <h3 class="mt-1 text-3xl font-bold tracking-tight text-amber-800">{{ $pendingBatches }}</h3>
                @if($pendingBatches > 0)
                <div class="inline-flex text-amber-800 mt-3 text-xs font-bold uppercase tracking-tighter hover:underline gap-1 items-center">
                    Tinjau <x-lucide-arrow-right class="w-3 h-3" />
                </div>
                @endif
            </div>
        </a>
    </div>

    <!-- Ranking Section (Guideline 7.3) -->
    @if($records->isNotEmpty())
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" data-aos="fade-up" data-aos-delay="200">
            <!-- Top 5 Content Card -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-50 dark:border-gray-700/50">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                            {{ __('Top 5 Layanan') }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                            {{ __('Unit layanan dengan kinerja tertinggi') }}
                        </p>
                    </div>
                    <div class="p-3 bg-linear-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg">
                        <x-lucide-trophy class="w-6 h-6 text-white" />
                    </div>
                </div>
                
                <div class="space-y-4">
                    @foreach($top5 as $index => $record)
                        <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-slate-900/50 border border-transparent hover:border-emerald-200 dark:hover:border-emerald-800/50 transition-all group">
                            <div class="flex items-center gap-4">
                                <span class="shrink-0 w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 font-bold text-sm dark:bg-emerald-900/30 dark:text-emerald-400">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white group-hover:text-emerald-600 transition-colors text-sm">{{ $record->nama_opd }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ $record->label_kategori }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-gray-900 dark:text-white tracking-tighter">{{ number_format($record->nilai_ikm, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Bottom 5 Content Card -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-50 dark:border-gray-700/50">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">
                            {{ __('Perlu Atensi') }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                            {{ __('Unit layanan prioritas perbaikan') }}
                        </p>
                    </div>
                    <div class="p-3 bg-linear-to-br from-rose-500 to-rose-600 rounded-xl shadow-lg">
                        <x-lucide-trending-up class="w-6 h-6 text-white rotate-180" />
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($bottom5 as $index => $record)
                        <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-slate-900/50 border border-transparent hover:border-rose-200 dark:hover:border-rose-800/50 transition-all group">
                            <div class="flex items-center gap-4">
                                <span class="shrink-0 w-8 h-8 flex items-center justify-center rounded-lg bg-rose-100 text-rose-700 font-bold text-sm dark:bg-rose-900/30 dark:text-rose-400">
                                    {{ count($records) - $index }}
                                </span>
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white group-hover:text-rose-600 transition-colors text-sm">
                                        {{ $record->nama_opd }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">
                                        {{ $record->label_kategori }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-gray-900 dark:text-white tracking-tighter">{{ number_format($record->nilai_ikm, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <!-- Empty State (Guideline 13) -->
        <div class="p-12 text-center bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700" data-aos="fade-up" data-aos-delay="200">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-50 dark:bg-gray-700 flex items-center justify-center">
                <x-lucide-inbox class="w-8 h-8 text-gray-300" />
            </div>
            <h3 class="text-xl font-bold text-gray-600 dark:text-white">
                {{ __('Data Belum Tersedia') }}
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                {{ __('Laporan IKM untuk periode ini belum diunggah atau belum mendapatkan persetujuan final.') }}
            </p>
            
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <x-core::button link href="{{ route('ikm.upload') }}" label="Unggah Data IKM">
                    <x-slot name="icon"><x-lucide-upload-cloud class="w-4 h-4" /></x-slot>
                </x-core::button>
                <x-core::secondary-button link href="{{ route('ikm.list') }}" label="Riwayat Batch" />
            </div>
        </div>
    @endif
</div>
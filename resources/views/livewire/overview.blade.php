<div class="space-y-6">
    {{-- Hero Header --}}
    <div class="relative overflow-hidden p-8 text-white rounded-2xl shadow-xl"
         style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-3 bg-white/20 backdrop-blur-md rounded-xl">
                        <x-lucide-bar-chart-3 class="w-8 h-8 text-white" />
                    </div>
                    <h1 class="text-3xl font-bold text-white md:text-4xl">Indeks Kepuasan Masyarakat</h1>
                </div>
                <p class="max-w-2xl text-white/90 text-lg">Ikhtisar performa layanan publik berdasarkan data IKM.</p>
            </div>

            {{-- Filter Periode --}}
            <div class="shrink-0">
                <div class="flex items-center bg-white/20 backdrop-blur-md rounded-xl border border-white/30 p-1">
                    <select wire:model.live="triwulan" class="bg-transparent border-none text-sm font-semibold text-white focus:ring-0 cursor-pointer px-4 [&>option]:text-gray-900">
                        <option value="1">TW 1</option>
                        <option value="2">TW 2</option>
                        <option value="3">TW 3</option>
                        <option value="4">TW 4</option>
                    </select>
                    <div class="w-px h-4 bg-white/30 mx-1"></div>
                    <select wire:model.live="tahun" class="bg-transparent border-none text-sm font-semibold text-white focus:ring-0 cursor-pointer px-4 [&>option]:text-gray-900">
                        @foreach($tahunOptions as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" data-aos="fade-up" data-aos-delay="100">
        {{-- Rerata --}}
        <div class="group p-6 transition-all duration-300 bg-white border border-gray-100 shadow-md dark:bg-gray-800 rounded-2xl hover:shadow-xl dark:border-gray-700 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-linear-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg">
                    <x-lucide-bar-chart-3 class="w-6 h-6 text-white" />
                </div>
                <span class="px-3 py-1 text-xs font-semibold text-indigo-700 bg-indigo-100 rounded-full dark:bg-indigo-900/50 dark:text-indigo-300">
                    Rerata Kota
                </span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nilai IKM</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($rataRata ?? 0, 2) }}</p>
                    <span class="text-sm font-medium text-gray-400">/ 100</span>
                </div>
            </div>
        </div>

        {{-- Sangat Baik --}}
        <div class="group p-6 transition-all duration-300 bg-white border border-gray-100 shadow-md dark:bg-gray-800 rounded-2xl hover:shadow-xl dark:border-gray-700 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-linear-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg">
                    <x-lucide-check-circle-2 class="w-6 h-6 text-white" />
                </div>
                <span class="px-3 py-1 text-xs font-semibold text-emerald-700 bg-emerald-100 rounded-full dark:bg-emerald-900/50 dark:text-emerald-300">
                    Sangat Baik (A)
                </span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Unit Layanan</p>
                <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $jumlahA }}</p>
            </div>
        </div>

        {{-- Baik --}}
        <div class="group p-6 transition-all duration-300 bg-white border border-gray-100 shadow-md dark:bg-gray-800 rounded-2xl hover:shadow-xl dark:border-gray-700 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-linear-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                    <x-lucide-thumbs-up class="w-6 h-6 text-white" />
                </div>
                <span class="px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900/50 dark:text-blue-300">
                    Baik (B)
                </span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Unit Layanan</p>
                <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $jumlahB }}</p>
            </div>
        </div>

        {{-- Perlu Evaluasi --}}
        <div class="group p-6 transition-all duration-300 bg-white border border-gray-100 shadow-md dark:bg-gray-800 rounded-2xl hover:shadow-xl dark:border-gray-700 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-linear-to-br from-red-500 to-rose-600 rounded-xl shadow-lg">
                    <x-lucide-alert-triangle class="w-6 h-6 text-white" />
                </div>
                <span class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full dark:bg-red-900/50 dark:text-red-300">
                    Perlu Evaluasi
                </span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Unit Layanan (C/D)</p>
                <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $dibawahB }}</p>
            </div>
        </div>
    </div>

    <!-- Ranking Section -->
    @if($records->isNotEmpty())
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" data-aos="fade-up" data-aos-delay="150">
        <!-- Top 5 -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-linear-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg">
                        <x-lucide-trophy class="w-5 h-5 text-white" />
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Top 5 Layanan</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kinerja tertinggi</p>
                    </div>
                </div>
                <span class="px-3 py-1 text-xs font-semibold text-emerald-700 bg-emerald-100 rounded-full dark:bg-emerald-900/50 dark:text-emerald-300">
                    Kinerja Tinggi
                </span>
            </div>
            <div class="space-y-3">
                @foreach($top5 as $index => $record)
                <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 group">
                    <div class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-lg bg-linear-to-br from-emerald-500 to-emerald-600 text-white font-bold text-sm shadow-sm">
                            #{{ $index + 1 }}
                        </span>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $record->nama_opd }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $record->label_kategori }}</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($record->nilai_ikm, 2) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Bottom 5 / Perlu Atensi -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-linear-to-br from-red-500 to-rose-600 rounded-xl shadow-lg">
                        <x-lucide-trending-down class="w-5 h-5 text-white" />
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Perlu Atensi</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Prioritas perbaikan</p>
                    </div>
                </div>
                <span class="px-3 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full dark:bg-red-900/50 dark:text-red-300">
                    Prioritas
                </span>
            </div>
            <div class="space-y-3">
                @foreach($bottom5 as $index => $record)
                <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 group">
                    <div class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-lg bg-linear-to-br from-red-500 to-rose-600 text-white font-bold text-sm shadow-sm">
                            #{{ count($records) - $index }}
                        </span>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $record->nama_opd }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $record->label_kategori }}</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($record->nilai_ikm, 2) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 p-12" data-aos="fade-up" data-aos-delay="150">
        <div class="grid items-center justify-center place-items-center gap-y-4">
            <div class="w-20 h-20 mx-auto rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                <x-lucide-frown class="w-10 h-10 text-gray-400" />
            </div>
            <div class="text-center">
                <p class="text-xl font-bold text-gray-600 dark:text-white">Data Belum Tersedia</p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                    Laporan IKM untuk Triwulan {{ $triwulan }} {{ $tahun }} belum diunggah atau belum mendapatkan persetujuan.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-3 mt-4">
                <a href="{{ route('ikm.upload') }}" wire:navigate class="px-6 py-3 justify-center gap-x-2 text-sm font-semibold transition-all duration-300 rounded-lg bg-linear-to-r from-purple-600 to-purple-700 text-white hover:from-purple-700 hover:to-purple-800 shadow-md hover:shadow-lg flex items-center gap-2">
                    <x-lucide-upload-cloud class="w-4 h-4" />
                    Unggah Data IKM
                </a>
                <a href="{{ route('ikm.list') }}" wire:navigate class="px-6 py-3 justify-center gap-x-2 text-sm font-semibold transition-all duration-300 rounded-lg bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 shadow-sm flex items-center gap-2">
                    Lihat Riwayat
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white uppercase italic">Indeks Kepuasan Masyarakat</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Ikhtisar performa layanan publik berdasarkan data IKM.</p>
        </div>

        <div class="flex items-center gap-3">
            <div class="flex items-center bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-1 shadow-sm">
                <select wire:model.live="triwulan" class="bg-transparent border-none text-sm font-black focus:ring-0 cursor-pointer px-4">
                    <option value="1">TW 1</option>
                    <option value="2">TW 2</option>
                    <option value="3">TW 3</option>
                    <option value="4">TW 4</option>
                </select>
                <div class="w-px h-4 bg-slate-200 dark:bg-slate-800 mx-1"></div>
                <select wire:model.live="tahun" class="bg-transparent border-none text-sm font-black focus:ring-0 cursor-pointer px-4">
                    @foreach($tahunOptions as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="p-6 rounded-[32px] bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-xl hover:shadow-primary-500/5 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">
                    <x-lucide-bar-chart-3 class="w-6 h-6" />
                </div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Rerata Kota</span>
            </div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black tracking-tighter text-slate-900 dark:text-white">{{ number_format($rataRata ?? 0, 2) }}</h3>
                <span class="text-xs font-bold text-slate-400">/ 100</span>
            </div>
        </div>

        <div class="p-6 rounded-[32px] bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-xl hover:shadow-emerald-500/5 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                    <x-lucide-check-circle-2 class="w-6 h-6" />
                </div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sangat Baik (A)</span>
            </div>
            <h3 class="text-4xl font-black tracking-tighter text-slate-900 dark:text-white">{{ $jumlahA }}</h3>
            <p class="mt-1 text-xs font-bold text-slate-500 uppercase">Unit Layanan</p>
        </div>

        <div class="p-6 rounded-[32px] bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-xl hover:shadow-sky-500/5 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-2xl bg-sky-50 dark:bg-sky-900/20 text-sky-600 dark:text-sky-400 group-hover:scale-110 transition-transform">
                    <x-lucide-thumbs-up class="w-6 h-6" />
                </div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Baik (B)</span>
            </div>
            <h3 class="text-4xl font-black tracking-tighter text-slate-900 dark:text-white">{{ $jumlahB }}</h3>
            <p class="mt-1 text-xs font-bold text-slate-500 uppercase">Unit Layanan</p>
        </div>

        <div class="p-6 rounded-[32px] bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-xl hover:shadow-rose-500/5 group">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 rounded-2xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 group-hover:scale-110 transition-transform">
                    <x-lucide-alert-triangle class="w-6 h-6" />
                </div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Perlu Evaluasi</span>
            </div>
            <h3 class="text-4xl font-black tracking-tighter text-slate-900 dark:text-white">{{ $dibawahB }}</h3>
            <p class="mt-1 text-xs font-bold text-slate-500 uppercase">Unit Layanan (C/D)</p>
        </div>
    </div>

    <!-- Ranking Section -->
    @if($records->isNotEmpty())
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top 5 -->
        <div class="p-8 rounded-[32px] bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-xl bg-emerald-500 text-white shadow-lg shadow-emerald-500/20">
                        <x-lucide-trophy class="w-5 h-5" />
                    </div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">Top 5 Layanan</h3>
                </div>
                <span class="px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest">Kinerja Tinggi</span>
            </div>
            <div class="space-y-4">
                @foreach($top5 as $index => $record)
                <div class="flex items-center justify-between p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-transparent hover:border-emerald-200 dark:hover:border-emerald-800/50 hover:bg-emerald-50/30 dark:hover:bg-emerald-900/10 transition-all group">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-xl bg-white dark:bg-slate-900 shadow-sm text-emerald-600 font-black text-sm border border-slate-100 dark:border-slate-800">
                            #{{ $index + 1 }}
                        </span>
                        <div>
                            <p class="font-black text-slate-900 dark:text-white group-hover:text-emerald-700 dark:group-hover:text-emerald-400 transition-colors">{{ $record->nama_opd }}</p>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">{{ $record->label_kategori }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xl font-black text-slate-900 dark:text-white tracking-tighter">{{ number_format($record->nilai_ikm, 2) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Bottom 5 -->
        <div class="p-8 rounded-[32px] bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-xl bg-rose-500 text-white shadow-lg shadow-rose-500/20">
                        <x-lucide-trending-down class="w-5 h-5" />
                    </div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">Perlu Atensi</h3>
                </div>
                <span class="px-3 py-1 rounded-full bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 text-[10px] font-black uppercase tracking-widest">Prioritas Perbaikan</span>
            </div>
            <div class="space-y-4">
                @foreach($bottom5 as $index => $record)
                <div class="flex items-center justify-between p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-transparent hover:border-rose-200 dark:hover:border-rose-800/50 hover:bg-rose-50/30 dark:hover:bg-rose-900/10 transition-all group">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-xl bg-white dark:bg-slate-900 shadow-sm text-rose-600 font-black text-sm border border-slate-100 dark:border-slate-800">
                            #{{ count($records) - $index }}
                        </span>
                        <div>
                            <p class="font-black text-slate-900 dark:text-white group-hover:text-rose-700 dark:group-hover:text-rose-400 transition-colors">{{ $record->nama_opd }}</p>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">{{ $record->label_kategori }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xl font-black text-slate-900 dark:text-white tracking-tighter">{{ number_format($record->nilai_ikm, 2) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="p-20 rounded-[40px] bg-white dark:bg-slate-900 border border-dashed border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-center shadow-inner">
        <div class="w-24 h-24 rounded-[32px] bg-slate-50 dark:bg-slate-800 flex items-center justify-center mb-8 shadow-sm">
            <x-lucide-frown class="w-12 h-12 text-slate-300" />
        </div>
        <h3 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tight">Data Belum Tersedia</h3>
        <p class="mt-2 text-slate-500 max-w-sm font-medium">Laporan IKM untuk Triwulan {{ $triwulan }} {{ $tahun }} belum diunggah atau belum mendapatkan persetujuan.</p>
        
        <div class="mt-10 flex flex-col sm:flex-row items-center gap-4">
            <a href="{{ route('ikm.upload') }}" wire:navigate class="px-8 py-4 rounded-2xl bg-primary-600 text-white font-black hover:bg-primary-700 transition-all shadow-xl shadow-primary-500/30 flex items-center gap-3">
                <x-lucide-upload-cloud class="w-5 h-5" />
                UNGGAH DATA IKM
            </a>
            <a href="{{ route('ikm.list') }}" wire:navigate class="px-8 py-4 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-black hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                LIHAT RIWAYAT
            </a>
        </div>
    </div>
    @endif
</div>

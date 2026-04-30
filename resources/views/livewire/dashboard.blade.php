<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Dashboard IKM</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Ikhtisar Indeks Kepuasan Masyarakat Kota.</p>
        </div>

        <div class="flex items-center gap-3">
            <select wire:model.live="triwulan" class="rounded-xl border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-sm font-medium focus:ring-primary-500 focus:border-primary-500 transition-all">
                <option value="1">Triwulan 1</option>
                <option value="2">Triwulan 2</option>
                <option value="3">Triwulan 3</option>
                <option value="4">Triwulan 4</option>
            </select>
            <select wire:model.live="tahun" class="rounded-xl border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-sm font-medium focus:ring-primary-500 focus:border-primary-500 transition-all">
                @foreach($tahunOptions as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Rerata Kota</span>
            </div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ number_format($rataRata ?? 0, 2) }}</h3>
                <span class="text-sm font-medium text-slate-400">/ 100</span>
            </div>
        </div>

        <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sangat Baik (A)</span>
            </div>
            <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $jumlahA }}</h3>
            <p class="mt-1 text-sm text-slate-500">Unit Layanan</p>
        </div>

        <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 rounded-xl bg-sky-50 dark:bg-sky-900/20 text-sky-600 dark:text-sky-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.757c1.27 0 2.539.6 3.01 1.691a3.001 3.001 0 11-5.101 3.102l-1.666-3.793V10z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14v1a3 3 0 003 3h10a3 3 0 003-3v-1M4 14V9a4 4 0 014-4h8a4 4 0 014 4v5M4 14h16"></path></svg>
                </div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Baik (B)</span>
            </div>
            <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $jumlahB }}</h3>
            <p class="mt-1 text-sm text-slate-500">Unit Layanan</p>
        </div>

        <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm transition-all hover:shadow-md">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Perlu Evaluasi</span>
            </div>
            <h3 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $dibawahB }}</h3>
            <p class="mt-1 text-sm text-slate-500">Unit Layanan (C/D)</p>
        </div>
    </div>

    <!-- Ranking Section -->
    @if($records->isNotEmpty())
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top 5 -->
        <div class="p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Top 5 Unit Layanan</h3>
                <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold uppercase tracking-wider">Tertinggi</span>
            </div>
            <div class="space-y-4">
                @foreach($top5 as $index => $record)
                <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 hover:bg-emerald-50/50 transition-colors group">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-bold text-sm">
                            {{ $index + 1 }}
                        </span>
                        <div>
                            <p class="font-bold text-slate-900 dark:text-white group-hover:text-emerald-700 transition-colors">{{ $record->nama_opd }}</p>
                            <p class="text-xs text-slate-500 uppercase tracking-tighter">{{ $record->label_kategori }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-lg font-black text-slate-900 dark:text-white">{{ number_format($record->nilai_ikm, 2) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Bottom 5 -->
        <div class="p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">5 Terendah</h3>
                <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-600 text-xs font-bold uppercase tracking-wider">Perlu Atensi</span>
            </div>
            <div class="space-y-4">
                @foreach($bottom5 as $index => $record)
                <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 hover:bg-rose-50/50 transition-colors group">
                    <div class="flex items-center gap-4">
                        <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 font-bold text-sm">
                            {{ count($records) - $index }}
                        </span>
                        <div>
                            <p class="font-bold text-slate-900 dark:text-white group-hover:text-rose-700 transition-colors">{{ $record->nama_opd }}</p>
                            <p class="text-xs text-slate-500 uppercase tracking-tighter">{{ $record->label_kategori }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-lg font-black text-slate-900 dark:text-white">{{ number_format($record->nilai_ikm, 2) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="p-20 rounded-3xl bg-white dark:bg-slate-900 border border-dashed border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-center">
        <div class="w-24 h-24 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center mb-6">
            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Tidak Ada Data</h3>
        <p class="mt-2 text-slate-500 max-w-sm">Data IKM untuk Triwulan {{ $triwulan }} Tahun {{ $tahun }} belum tersedia atau belum difinalisasi.</p>
        <a href="{{ route('ikm.upload') }}" wire:navigate class="mt-6 px-6 py-3 rounded-2xl bg-primary-600 text-white font-bold hover:bg-primary-700 transition-all shadow-lg shadow-primary-500/20">
            Upload Data Sekarang
        </a>
    </div>
    @endif
</div>

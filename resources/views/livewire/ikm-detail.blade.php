<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
        <div class="flex items-start gap-6">
            <div class="p-4 rounded-3xl bg-primary-600 text-white shadow-xl shadow-primary-500/20">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <nav class="flex text-xs font-bold uppercase tracking-widest text-slate-400 mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1">
                        <li class="inline-flex items-center">
                            <a href="{{ route('ikm.list') }}" wire:navigate class="hover:text-primary-600 transition-colors">BATCH IKM</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                                <span class="ml-1">DETAIL</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white">{{ $batch->nama }}</h1>
                <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-slate-500">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                        Triwulan {{ $batch->triwulan }} {{ $batch->tahun }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        {{ $batch->uploadedBy->name ?? 'System' }}
                    </span>
                    <span class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-[10px] font-black uppercase tracking-tighter">{{ $batch->status }}</span>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            @if($canRecalculate)
            <button wire:click="recalculate" class="px-4 py-2.5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-50 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Kalkulasi Ulang
            </button>
            @endif

            @if($canExport)
            <button wire:click="exportExcel" class="px-6 py-2.5 rounded-2xl bg-emerald-600 text-white font-bold text-sm hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-500/20 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel
            </button>
            @endif
        </div>
    </div>

    <!-- Stats Table Card -->
    <div class="p-8 rounded-[40px] bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">Rincian Per Unit Layanan</h2>
            <div class="w-64">
                <input type="text" wire:model.live="search" placeholder="Cari OPD..." class="w-full rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-4 py-2 text-sm">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 dark:border-slate-800">
                        <th class="px-4 py-4 text-left">No</th>
                        <th class="px-4 py-4 text-left">Nama OPD</th>
                        <th class="px-4 py-4 text-center">U1-U9 (NRR)</th>
                        <th class="px-4 py-4 text-center cursor-pointer hover:text-primary-600 transition-colors" wire:click="toggleSort">
                            <div class="flex items-center justify-center gap-1">
                                Nilai IKM
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDir === 'desc' ? 'M19 9l-7 7-7-7' : 'M5 15l7-7 7 7' }}"></path></svg>
                            </div>
                        </th>
                        <th class="px-4 py-4 text-center">Kategori</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    @foreach($records as $index => $record)
                    <tr class="group hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-4 py-6 text-slate-400 font-bold">
                            {{ $records->firstItem() + $index }}
                        </td>
                        <td class="px-4 py-6">
                            <span class="font-bold text-slate-900 dark:text-white group-hover:text-primary-600 transition-colors">{{ $record->nama_opd }}</span>
                        </td>
                        <td class="px-4 py-6">
                            <div class="flex justify-center gap-1 overflow-x-auto max-w-[200px] mx-auto scrollbar-hide">
                                @for($i=1; $i<=9; $i++)
                                    @php $val = $record->{"nrr_u$i"}; @endphp
                                    <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-lg text-[9px] font-black {{ $val >= 3.25 ? 'bg-emerald-50 text-emerald-600' : ($val >= 2.5 ? 'bg-sky-50 text-sky-600' : 'bg-rose-50 text-rose-600') }}" title="U{{ $i }}: {{ $val }}">
                                        {{ number_format($val, 1) }}
                                    </span>
                                @endfor
                            </div>
                        </td>
                        <td class="px-4 py-6 text-center">
                            <span class="text-lg font-black text-slate-900 dark:text-white">{{ number_format($record->nilai_ikm, 2) }}</span>
                            <div class="text-[10px] font-bold text-slate-400">NRR: {{ number_format($record->nrr_tertimbang, 3) }}</div>
                        </td>
                        <td class="px-4 py-6 text-center">
                            @php
                                $catColor = match($record->kategori) {
                                    'A' => 'bg-emerald-100 text-emerald-700',
                                    'B' => 'bg-sky-100 text-sky-700',
                                    'C' => 'bg-amber-100 text-amber-700',
                                    'D' => 'bg-rose-100 text-rose-700',
                                    default => 'bg-slate-100 text-slate-600'
                                };
                            @endphp
                            <div class="inline-flex flex-col items-center">
                                <span class="px-3 py-1 rounded-full text-xs font-black {{ $catColor }} mb-1">{{ $record->kategori }}</span>
                                <span class="text-[9px] font-bold uppercase text-slate-400 whitespace-nowrap">{{ $record->label_kategori }}</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($records->hasPages())
        <div class="mt-8">
            {{ $records->links() }}
        </div>
        @endif
    </div>

    <!-- Demography Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="p-8 rounded-[40px] bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm">
            <h3 class="text-sm font-black uppercase tracking-widest text-slate-400 mb-6 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Responden ({{ $totalSampel }})
            </h3>
            <div class="space-y-4">
                @foreach($demJK as $label => $val)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-bold text-slate-700 dark:text-slate-300">{{ $label }}</span>
                        <span class="font-black text-slate-900 dark:text-white">{{ $val }}</span>
                    </div>
                    <div class="h-2 w-full bg-slate-50 dark:bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-primary-500 rounded-full transition-all duration-1000" style="width: {{ $totalSampel > 0 ? ($val / $totalSampel * 100) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="p-8 rounded-[40px] bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm">
            <h3 class="text-sm font-black uppercase tracking-widest text-slate-400 mb-6 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                Pendidikan
            </h3>
            <div class="space-y-3 h-[200px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-800">
                @foreach($demPendidikan as $label => $val)
                <div class="flex items-center justify-between group">
                    <span class="text-xs font-bold text-slate-500 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">{{ $label }}</span>
                    <span class="px-2 py-0.5 rounded-lg bg-slate-50 dark:bg-slate-800 text-xs font-black text-slate-900 dark:text-white">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="p-8 rounded-[40px] bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm">
            <h3 class="text-sm font-black uppercase tracking-widest text-slate-400 mb-6 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Pekerjaan
            </h3>
            <div class="space-y-3 h-[200px] overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-slate-800">
                @foreach($demPekerjaan as $label => $val)
                <div class="flex items-center justify-between group">
                    <span class="text-xs font-bold text-slate-500 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">{{ $label }}</span>
                    <span class="px-2 py-0.5 rounded-lg bg-slate-50 dark:bg-slate-800 text-xs font-black text-slate-900 dark:text-white">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

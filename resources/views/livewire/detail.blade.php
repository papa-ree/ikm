<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
        <div class="flex items-start gap-5">
            <div class="p-4 bg-linear-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg shrink-0">
                <x-lucide-file-bar-chart-2 class="w-8 h-8 text-white" />
            </div>
            <div>
                {{-- Breadcrumb --}}
                <x-core::breadcrumb
                    :items="[['label' => __('Riwayat Batch'), 'route' => 'ikm.list']]"
                    :active="__('Detail Analisis')"
                />
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white md:text-3xl mt-1">{{ $batch->nama }}</h1>
                <div class="mt-3 flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                        <x-lucide-calendar-range class="w-4 h-4" />
                        <span class="text-xs font-medium">TW{{ $batch->triwulan }} {{ $batch->tahun }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                        <x-lucide-user-check class="w-4 h-4" />
                        <span class="text-xs font-medium">{{ $batch->uploadedBy->name ?? 'System' }}</span>
                    </div>
                    @php
                        $statusStyle = match($batch->status) {
                            'draft'    => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                            'diproses' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300',
                            'selesai'  => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300',
                            'gagal'    => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                            default    => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusStyle }}">
                        {{ ucfirst($batch->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 shrink-0">
            @if($canRecalculate)
            <button wire:click="recalculate"
                class="group px-5 py-2.5 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-semibold text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-all shadow-sm flex items-center gap-2">
                <x-lucide-refresh-ccw class="w-4 h-4 group-hover:rotate-180 transition-transform duration-700" />
                Kalkulasi Ulang
            </button>
            @endif

            @if($canExport)
            <button wire:click="exportExcel"
                class="group px-6 py-2.5 rounded-lg bg-linear-to-r from-emerald-500 to-emerald-600 text-white font-semibold text-sm hover:from-emerald-600 hover:to-emerald-700 transition-all shadow-md hover:shadow-lg flex items-center gap-2">
                <x-lucide-download class="w-4 h-4 group-hover:translate-y-0.5 transition-transform" />
                Export Excel
            </button>
            @endif
        </div>
    </div>

    <!-- Stats Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        {{-- Card Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between px-6 py-5 border-b border-gray-100 dark:border-gray-700 gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-linear-to-br from-indigo-500 to-purple-600 rounded-xl shadow-md">
                    <x-lucide-table-2 class="w-5 h-5 text-white" />
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Rincian Per Unit Layanan</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Data IKM per OPD</p>
                </div>
            </div>
            {{-- Search --}}
            <div class="relative group w-full md:w-72">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <x-lucide-search class="w-4 h-4" />
                </div>
                <input type="text" wire:model.live="search"
                    placeholder="Cari Unit Layanan..."
                    class="w-full rounded-lg border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 pl-10 pr-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="text-[10px] text-gray-400 uppercase font-semibold tracking-[0.2em] bg-gray-50/50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-4 text-left">No</th>
                        <th class="px-6 py-4 text-left">Unit Pelaksana (OPD)</th>
                        <th class="px-6 py-4 text-center">Skor Unsur (NRR)</th>
                        <th class="px-6 py-4 text-center cursor-pointer hover:text-indigo-600 transition-colors" wire:click="toggleSort">
                            <div class="flex items-center justify-center gap-1.5">
                                Nilai IKM
                                <x-lucide-arrow-up-down class="w-3.5 h-3.5" />
                            </div>
                        </th>
                        <th class="px-6 py-4 text-center">Mutu Layanan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($records as $index => $record)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors group">
                        <td class="px-6 py-4 text-gray-400 font-medium">
                            {{ str_pad($records->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $record->nama_opd }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-1">
                                @for($i=1; $i<=9; $i++)
                                    @php $val = $record->{"nrr_u$i"}; @endphp
                                    <div class="flex flex-col items-center">
                                        <span class="text-[8px] font-semibold text-gray-400 mb-1">U{{ $i }}</span>
                                        <span class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg text-[10px] font-semibold border
                                            {{ $val >= 3.25 ? 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800' : ($val >= 2.5 ? 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800' : 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800') }}"
                                            title="Unsur {{ $i }}: {{ $val }}">
                                            {{ number_format($val, 1) }}
                                        </span>
                                    </div>
                                @endfor
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($record->nilai_ikm, 2) }}</span>
                                <span class="text-[9px] font-medium text-gray-400 uppercase mt-0.5">WT: {{ number_format($record->nrr_tertimbang, 3) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $catColor = match($record->kategori) {
                                    'A' => 'bg-linear-to-br from-emerald-500 to-emerald-600 shadow-emerald-500/20',
                                    'B' => 'bg-linear-to-br from-blue-500 to-blue-600 shadow-blue-500/20',
                                    'C' => 'bg-linear-to-br from-amber-500 to-amber-600 shadow-amber-500/20',
                                    'D' => 'bg-linear-to-br from-red-500 to-rose-600 shadow-rose-500/20',
                                    default => 'bg-linear-to-br from-gray-400 to-gray-500 shadow-gray-400/20',
                                };
                            @endphp
                            <div class="inline-flex flex-col items-center">
                                <span class="w-10 h-10 flex items-center justify-center rounded-xl text-base font-bold shadow-lg {{ $catColor }} text-white mb-1">{{ $record->kategori }}</span>
                                <span class="text-[9px] font-medium uppercase text-gray-500 dark:text-gray-400 tracking-wide whitespace-nowrap">{{ $record->label_kategori }}</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($records->hasPages())
        <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
            {{ $records->links() }}
        </div>
        @endif
    </div>

    <!-- Demography & Insights -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="200">
        {{-- Gender Chart --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2.5 bg-linear-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-md">
                    <x-lucide-users class="w-5 h-5 text-white" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Responden</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Total: {{ number_format($totalSampel) }}</p>
                </div>
            </div>
            <div class="space-y-4">
                @foreach($demJK as $label => $val)
                <div>
                    <div class="flex justify-between text-xs font-medium mb-1.5">
                        <span class="text-gray-600 dark:text-gray-400">{{ $label }}</span>
                        <span class="text-gray-900 dark:text-white font-semibold">{{ $val }}</span>
                    </div>
                    <div class="h-2.5 w-full bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                        @php $percent = $totalSampel > 0 ? ($val / $totalSampel * 100) : 0; @endphp
                        <div class="h-full bg-linear-to-r from-indigo-500 to-purple-600 rounded-full transition-all duration-1000 ease-out" style="width: {{ $percent }}%"></div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1 text-right">{{ number_format($percent, 1) }}%</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Education --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2.5 bg-linear-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-md">
                    <x-lucide-graduation-cap class="w-5 h-5 text-white" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pendidikan</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Pendidikan terakhir</p>
                </div>
            </div>
            <div class="space-y-2 max-h-[280px] overflow-y-auto pr-1 scrollbar-thin scrollbar-thumb-gray-400 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-200 dark:scrollbar-track-gray-800 scrollbar-thumb-rounded-full">
                @foreach($demPendidikan as $label => $val)
                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ $label }}</span>
                    <span class="px-2.5 py-1 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 text-xs font-semibold text-emerald-600 dark:text-emerald-400">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Job --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2.5 bg-linear-to-br from-amber-500 to-amber-600 rounded-xl shadow-md">
                    <x-lucide-briefcase class="w-5 h-5 text-white" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pekerjaan</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Profil pekerjaan</p>
                </div>
            </div>
            <div class="space-y-2 max-h-[280px] overflow-y-auto pr-1 scrollbar-thin scrollbar-thumb-gray-400 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-200 dark:scrollbar-track-gray-800 scrollbar-thumb-rounded-full">
                @foreach($demPekerjaan as $label => $val)
                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ $label }}</span>
                    <span class="px-2.5 py-1 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 text-xs font-semibold text-amber-600 dark:text-amber-400">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

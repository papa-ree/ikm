<div wire:init="$refresh">
    {{-- Toolbar: Search & Filters --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 mb-4 px-1">
        {{-- Search --}}
        <div class="relative flex-1 w-full">
            <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                <x-lucide-search class="w-4 h-4 text-gray-400" />
            </div>
            <input
                wire:model.live.debounce.400ms="search"
                type="text"
                placeholder="Cari nama batch atau berkas..."
                class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 pl-9 pr-4 py-2.5 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-400 dark:focus:border-red-500 transition"
            />
        </div>

        {{-- Filter Triwulan --}}
        <select
            wire:model.live="filterTriwulan"
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-red-500/30 transition"
        >
            <option value="">Semua TW</option>
            <option value="1">TW 1</option>
            <option value="2">TW 2</option>
            <option value="3">TW 3</option>
            <option value="4">TW 4</option>
        </select>

        {{-- Filter Tahun --}}
        <select
            wire:model.live="filterTahun"
            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-red-500/30 transition"
        >
            <option value="">Semua Tahun</option>
            @foreach($tahunOptions as $year)
                <option value="{{ $year }}">{{ $year }}</option>
            @endforeach
        </select>
    </div>

    {{-- Table Card --}}
    <div class="overflow-hidden rounded-2xl border border-red-100 dark:border-red-900/40 bg-white dark:bg-gray-800 shadow-sm">

        {{-- Empty State --}}
        @if($batches->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center px-6">
                <div class="w-16 h-16 rounded-2xl bg-red-50 dark:bg-red-900/20 flex items-center justify-center mb-4">
                    <x-lucide-trash-2 class="w-8 h-8 text-red-300 dark:text-red-600" />
                </div>
                <p class="font-semibold text-gray-700 dark:text-gray-300">Trash Kosong</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Tidak ada batch IKM yang dihapus.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-red-100 dark:border-red-900/40 bg-red-50/60 dark:bg-red-900/10">
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-red-500 dark:text-red-400 uppercase tracking-widest">
                            Nama Batch & Berkas
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-red-500 dark:text-red-400 uppercase tracking-widest">
                            Periode
                        </th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold text-red-500 dark:text-red-400 uppercase tracking-widest">
                            Unit
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-red-500 dark:text-red-400 uppercase tracking-widest">
                            Dihapus Oleh / Pada
                        </th>
                        <th class="px-6 py-3.5 text-right text-xs font-bold text-red-500 dark:text-red-400 uppercase tracking-widest">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @foreach($batches as $batch)
                        <tr
                            wire:key="trash-row-{{ $batch->id }}"
                            class="group hover:bg-red-50/40 dark:hover:bg-red-900/10 transition-colors"
                        >
                            {{-- Nama & File --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="p-2.5 rounded-xl bg-red-100 dark:bg-red-900/30 text-red-400 dark:text-red-500 shrink-0">
                                        <x-lucide-file-x class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-700 dark:text-gray-300 line-through decoration-red-300">
                                            {{ $batch->nama }}
                                        </p>
                                        <p class="text-[10px] font-medium text-gray-400 mt-0.5 truncate max-w-[200px]">
                                            {{ $batch->nama_file }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- Periode --}}
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-gray-600 dark:text-gray-400">TW{{ $batch->triwulan }}</span>
                                    <span class="text-[10px] font-medium text-gray-400">{{ $batch->tahun }}</span>
                                </div>
                            </td>

                            {{-- Jumlah OPD --}}
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-bold text-xs border border-gray-200 dark:border-gray-600">
                                    {{ $batch->jumlah_opd }}
                                </span>
                            </td>

                            {{-- Dihapus oleh / pada --}}
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded-full bg-red-200 dark:bg-red-800 flex items-center justify-center text-[9px] font-bold text-red-700 dark:text-red-300 shrink-0">
                                            {{ substr($batch->uploadedBy?->name ?? 'S', 0, 1) }}
                                        </div>
                                        <span class="text-[10px] font-medium text-gray-500 dark:text-gray-400">
                                            {{ $batch->uploadedBy?->name ?? 'System' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[10px] text-red-500 dark:text-red-400 font-medium">
                                        <x-lucide-clock class="w-3 h-3 shrink-0" />
                                        {{ $batch->deleted_at?->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    {{-- Restore --}}
                                    @can(\Bale\Ikm\IkmPermissions::DELETE_IKM)
                                        <button
                                            wire:click="restoreItem('{{ $batch->id }}')"
                                            wire:confirm="Pulihkan batch '{{ addslashes($batch->nama) }}'? Batch dan seluruh record-nya akan dikembalikan."
                                            wire:loading.attr="disabled"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-900/30 hover:bg-emerald-200 dark:hover:bg-emerald-900/50 border border-emerald-200 dark:border-emerald-800 transition-all disabled:opacity-50"
                                            title="Pulihkan batch"
                                        >
                                            <x-lucide-rotate-ccw class="w-3.5 h-3.5" />
                                            Pulihkan
                                        </button>

                                        {{-- Force Delete --}}
                                        <button
                                            wire:click="forceDeleteItem('{{ $batch->id }}')"
                                            wire:confirm="HAPUS PERMANEN batch '{{ addslashes($batch->nama) }}'? Seluruh record IKM di dalamnya akan ikut terhapus dan TIDAK DAPAT dikembalikan."
                                            wire:loading.attr="disabled"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 border border-red-200 dark:border-red-800 transition-all disabled:opacity-50"
                                            title="Hapus permanen"
                                        >
                                            <x-lucide-trash class="w-3.5 h-3.5" />
                                            Hapus Permanen
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            @if($batches->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700/50">
                    {{ $batches->links() }}
                </div>
            @endif
        @endif

        {{-- Loading Overlay --}}
        <div wire:loading.delay class="relative">
            <div class="absolute inset-0 bg-white/50 dark:bg-gray-900/50 rounded-b-2xl flex items-center justify-center py-4">
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <svg class="animate-spin w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    Memuat...
                </div>
            </div>
        </div>
    </div>

    {{-- Summary --}}
    @if($batches->total() > 0)
        <p class="mt-3 text-xs text-gray-400 dark:text-gray-500 text-right px-1">
            Menampilkan {{ $batches->firstItem() }}–{{ $batches->lastItem() }} dari {{ $batches->total() }} batch di trash
        </p>
    @endif
</div>

@php
    $badge = $batch->status_badge;
    $statusStyle = match($badge['color']) {
        'gray'    => 'bg-gray-100/80 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
        'amber'   => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300',
        'blue'    => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
        'emerald' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300',
        'red'     => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
        default   => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
    };
@endphp

<div class="relative overflow-hidden rounded-2xl shadow-xl text-white"
     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);">

    {{-- Decorative blobs --}}
    <div class="absolute top-0 right-0 w-72 h-72 bg-white/10 rounded-full -mr-36 -mt-36 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-52 h-52 bg-white/10 rounded-full -ml-28 -mb-28 pointer-events-none"></div>

    <div class="relative z-10 p-6 md:p-8">
        {{-- Breadcrumb --}}
        <div class="mb-4">
            <x-core::breadcrumb
                :items="[['label' => __('Riwayat Batch'), 'route' => 'ikm.list']]"
                :active="__('Detail Analisis')"
                class="text-white/70 [&_a]:text-white/70 [&_a:hover]:text-white"
            />
        </div>

        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
            {{-- Left: Batch Info --}}
            <div class="flex items-start gap-4 min-w-0">
                <div class="shrink-0 p-3.5 bg-white/15 backdrop-blur-sm rounded-xl ring-1 ring-white/20">
                    <x-lucide-file-bar-chart-2 class="w-7 h-7 text-white" />
                </div>
                <div class="min-w-0">
                    <h1 class="text-xl font-bold text-white md:text-2xl lg:text-3xl leading-tight truncate">
                        {{ $batch->nama }}
                    </h1>

                    {{-- Meta chips --}}
                    <div class="mt-3 flex flex-wrap items-center gap-2">
                        {{-- Period --}}
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/15 backdrop-blur-sm ring-1 ring-white/20 text-xs font-semibold">
                            <x-lucide-calendar-range class="w-3.5 h-3.5" />
                            TW{{ $batch->triwulan }} / {{ $batch->tahun }}
                        </span>

                        {{-- Unit count --}}
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/15 backdrop-blur-sm ring-1 ring-white/20 text-xs font-semibold">
                            <x-lucide-building-2 class="w-3.5 h-3.5" />
                            {{ $batch->jumlah_opd }} Unit Layanan
                        </span>

                        {{-- Status --}}
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/15 backdrop-blur-sm ring-1 ring-white/20 text-xs font-bold uppercase tracking-wide">
                            {{ $badge['label'] }}
                        </span>

                        {{-- Uploader --}}
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/15 backdrop-blur-sm ring-1 ring-white/20 text-xs font-medium">
                            <x-lucide-user-check class="w-3.5 h-3.5" />
                            {{ $batch->uploadedBy->name ?? 'System' }}
                        </span>
                    </div>

                    {{-- Approved info --}}
                    @if($batch->approved_at)
                    <div class="mt-2.5 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-500/20 ring-1 ring-emerald-400/40">
                        <x-lucide-check-circle-2 class="w-4 h-4 text-emerald-300 shrink-0" />
                        <span class="text-xs font-semibold text-emerald-100">
                            Disetujui {{ $batch->approved_at->format('d/m/Y H:i') }}
                            oleh {{ $batch->approvedBy->name ?? 'System' }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Right: Actions --}}
            <div class="flex flex-wrap items-center gap-2 shrink-0 lg:pt-1">
                @if($canRecalculate)
                <button wire:click="recalculate"
                    wire:loading.attr="disabled"
                    class="group inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 backdrop-blur-sm ring-1 ring-white/20 text-sm font-semibold text-white transition-all shadow-sm disabled:opacity-60">
                    <x-lucide-refresh-ccw class="w-4 h-4 group-hover:rotate-180 transition-transform duration-700" wire:loading.class="animate-spin" wire:target="recalculate" />
                    Kalkulasi Ulang
                </button>
                @endif

                @if($canExport)
                <button wire:click="exportExcel"
                    wire:loading.attr="disabled"
                    class="group inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-purple-700 font-semibold text-sm hover:bg-white/90 transition-all shadow-md hover:shadow-lg disabled:opacity-60">
                    <x-lucide-download class="w-4 h-4 group-hover:translate-y-0.5 transition-transform" wire:loading.class="animate-bounce" wire:target="exportExcel" />
                    Export CSV
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

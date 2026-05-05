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
                        <x-lucide-archive class="w-8 h-8 text-white" />
                    </div>
                    <h1 class="text-3xl font-bold text-white md:text-4xl">Riwayat Batch IKM</h1>
                </div>
                <p class="max-w-2xl text-white/90 text-lg">Kelola berkas impor dan status verifikasi data IKM.</p>
            </div>
            <div class="shrink-0">
                <a href="{{ route('ikm.upload') }}" wire:navigate
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-white text-purple-600 font-semibold hover:bg-white/90 transition-all shadow-md hover:shadow-lg">
                    <x-lucide-plus-circle class="w-5 h-5" />
                    Import Batch Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-md flex flex-col md:flex-row gap-4 items-center" data-aos="fade-up" data-aos-delay="100">
        <div class="flex-1 w-full relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                <x-lucide-search class="w-4 h-4" />
            </div>
            <input type="text" wire:model.live="search"
                placeholder="Cari nama batch atau file..."
                class="w-full rounded-lg border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 pl-10 pr-4 py-2.5 text-sm font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
        </div>
        
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <select wire:model.live="filterTriwulan" class="flex-1 md:flex-none rounded-lg border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-4 py-2.5 text-sm font-medium focus:ring-indigo-500 transition-all cursor-pointer">
                <option value="">Semua TW</option>
                <option value="1">TW 1</option>
                <option value="2">TW 2</option>
                <option value="3">TW 3</option>
                <option value="4">TW 4</option>
            </select>
            
            <select wire:model.live="filterTahun" class="flex-1 md:flex-none rounded-lg border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-4 py-2.5 text-sm font-medium focus:ring-indigo-500 transition-all cursor-pointer">
                <option value="">Semua Tahun</option>
                @foreach($tahunOptions as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
            
            <select wire:model.live="filterStatus" class="flex-1 md:flex-none rounded-lg border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-4 py-2.5 text-sm font-medium focus:ring-indigo-500 transition-all cursor-pointer">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="diproses">Diproses</option>
                <option value="selesai">Selesai</option>
                <option value="gagal">Gagal</option>
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="150">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-[10px] text-gray-400 uppercase font-semibold tracking-[0.2em] bg-gray-50/50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-4">Nama Batch & Berkas</th>
                        <th class="px-6 py-4">Periode</th>
                        <th class="px-6 py-4 text-center">Unit</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Oleh</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($batches as $batch)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-400 group-hover:bg-linear-to-br group-hover:from-indigo-500 group-hover:to-purple-600 group-hover:text-white transition-all">
                                    <x-lucide-file-spreadsheet class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $batch->nama }}</p>
                                    <p class="text-[10px] font-medium text-gray-400 mt-0.5 truncate max-w-[200px]">{{ $batch->nama_file }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-semibold text-gray-700 dark:text-gray-300">TW{{ $batch->triwulan }}</span>
                                <span class="text-[10px] font-medium text-gray-400">{{ $batch->tahun }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-bold text-xs border border-indigo-200 dark:border-indigo-800">
                                {{ $batch->jumlah_opd }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusStyle = match($batch->status) {
                                    'draft'    => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                                    'diproses' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300 animate-pulse',
                                    'selesai'  => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300',
                                    'gagal'    => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                                    default    => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[10px] font-semibold {{ $statusStyle }}">
                                {{ ucfirst($batch->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-linear-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-[10px] font-bold text-white shadow-sm">
                                    {{ substr($batch->uploadedBy->name ?? 'S', 0, 1) }}
                                </div>
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $batch->uploadedBy->name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('ikm.detail', $batch) }}" wire:navigate
                                    class="p-2 rounded-lg bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-500 hover:text-indigo-600 hover:border-indigo-500 transition-all shadow-sm"
                                    title="Lihat Detail">
                                    <x-lucide-eye class="w-4 h-4" />
                                </a>
                                
                                @if($canApprove && $batch->status === 'selesai' && !$batch->approved_at)
                                <button wire:click="approveBatch('{{ $batch->id }}')"
                                    wire:confirm="Setujui dan publikasikan batch ini?"
                                    class="p-2 rounded-lg bg-linear-to-br from-emerald-500 to-emerald-600 text-white hover:from-emerald-600 hover:to-emerald-700 transition-all shadow-md"
                                    title="Setujui">
                                    <x-lucide-check-check class="w-4 h-4" />
                                </button>
                                @endif

                                @if($canDelete)
                                <button wire:click="deleteBatch('{{ $batch->id }}')"
                                    wire:confirm="Hapus batch ini selamanya? Seluruh data record di dalamnya akan hilang."
                                    class="p-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-500 hover:bg-red-500 hover:text-white transition-all border border-red-200 dark:border-red-900/40"
                                    title="Hapus">
                                    <x-lucide-trash-2 class="w-4 h-4" />
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="grid items-center justify-center place-items-center gap-y-4">
                                <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                    <x-lucide-inbox class="w-8 h-8 text-gray-400" />
                                </div>
                                <div class="text-center">
                                    <p class="text-xl font-bold text-gray-600 dark:text-white">Belum Ada Riwayat</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Silakan lakukan impor data IKM pertama Anda.</p>
                                </div>
                                <a href="{{ route('ikm.upload') }}" wire:navigate
                                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-linear-to-r from-purple-600 to-purple-700 text-white font-semibold text-sm hover:from-purple-700 hover:to-purple-800 transition-all shadow-md">
                                    <x-lucide-upload-cloud class="w-4 h-4" />
                                    Import Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($batches->hasPages())
        <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
            {{ $batches->links() }}
        </div>
        @endif
    </div>
</div>

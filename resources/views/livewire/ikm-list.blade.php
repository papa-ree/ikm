<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Riwayat Batch IKM</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Kelola file import dan status persetujuan IKM.</p>
        </div>

        <a href="{{ route('ikm.upload') }}" wire:navigate class="px-6 py-3 rounded-2xl bg-primary-600 text-white font-bold hover:bg-primary-700 transition-all shadow-lg shadow-primary-500/20 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Import Baru
        </a>
    </div>

    <!-- Filters -->
    <div class="p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm flex flex-wrap gap-4 items-center">
        <div class="flex-1 min-w-[200px]">
            <input type="text" wire:model.live="search" placeholder="Cari nama batch..." class="w-full rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-4 py-2 text-sm focus:ring-primary-500 focus:border-primary-500 transition-all">
        </div>
        <select wire:model.live="filterTriwulan" class="rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-sm focus:ring-primary-500 transition-all">
            <option value="">Semua Triwulan</option>
            <option value="1">Triwulan 1</option>
            <option value="2">Triwulan 2</option>
            <option value="3">Triwulan 3</option>
            <option value="4">Triwulan 4</option>
        </select>
        <select wire:model.live="filterTahun" class="rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-sm focus:ring-primary-500 transition-all">
            <option value="">Semua Tahun</option>
            @foreach($tahunOptions as $year)
                <option value="{{ $year }}">{{ $year }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterStatus" class="rounded-xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-sm focus:ring-primary-500 transition-all">
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="diproses">Diproses</option>
            <option value="selesai">Selesai</option>
            <option value="gagal">Gagal</option>
        </select>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-slate-400 uppercase font-black tracking-widest bg-slate-50/50 dark:bg-slate-800/50">
                    <tr>
                        <th class="px-6 py-4">Nama Batch</th>
                        <th class="px-6 py-4">Periode</th>
                        <th class="px-6 py-4">Unit Layanan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Diupload Oleh</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    @forelse($batches as $batch)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900 dark:text-white">{{ $batch->nama }}</p>
                            <p class="text-xs text-slate-500 truncate max-w-[150px]">{{ $batch->nama_file }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-medium">TW{{ $batch->triwulan }} {{ $batch->tahun }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-black text-primary-600 dark:text-primary-400">{{ $batch->jumlah_opd }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $badgeColor = match($batch->status) {
                                    'draft' => 'bg-slate-100 text-slate-600',
                                    'diproses' => 'bg-amber-100 text-amber-600',
                                    'selesai' => 'bg-emerald-100 text-emerald-600',
                                    'gagal' => 'bg-rose-100 text-rose-600',
                                    default => 'bg-slate-100 text-slate-600'
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter {{ $badgeColor }}">
                                {{ $batch->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500">
                            {{ $batch->uploadedBy->name ?? 'System' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('ikm.detail', $batch) }}" wire:navigate class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-primary-600 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                
                                @if($canApprove && $batch->status === 'selesai' && !$batch->approved_at)
                                <button wire:click="approveBatch('{{ $batch->id }}')" wire:confirm="Setujui batch ini?" class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 hover:bg-emerald-100 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </button>
                                @endif

                                @if($canDelete)
                                <button wire:click="deleteBatch('{{ $batch->id }}')" wire:confirm="Hapus batch ini? Data record didalamnya juga akan terhapus." class="p-2 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 hover:bg-rose-100 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center text-slate-500">
                            Tidak ada batch ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($batches->hasPages())
        <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/30 border-t border-slate-100 dark:border-slate-800">
            {{ $batches->links() }}
        </div>
        @endif
    </div>
</div>

<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Pengaturan Kategori IKM</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Konfigurasi rentang nilai dan label kategori IKM.</p>
        </div>
        
        <button wire:click="resetToDefault" wire:confirm="Kembalikan semua pengaturan ke standar PermenPAN-RB 14/2017?" class="text-sm font-bold text-primary-600 hover:text-primary-700 flex items-center gap-2 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            Reset ke Default
        </button>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($kodeList as $kode)
            <div class="p-6 rounded-[32px] bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <span class="w-10 h-10 flex items-center justify-center rounded-2xl bg-slate-50 dark:bg-slate-800 text-lg font-black text-slate-900 dark:text-white border border-slate-100 dark:border-slate-700 shadow-sm">
                        {{ $kode }}
                    </span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">KATEGORI</span>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Label Tampilan</label>
                    <input type="text" wire:model="kategori.{{ $kode }}.label" class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-4 py-2.5 text-sm font-bold focus:ring-primary-500 transition-all">
                    @error("kategori.{$kode}.label") <span class="text-[10px] text-rose-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Nilai Min</label>
                        <input type="number" step="0.01" wire:model="kategori.{{ $kode }}.min" class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-4 py-2 text-sm font-black focus:ring-primary-500 transition-all">
                        @error("kategori.{$kode}.min") <span class="text-[10px] text-rose-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Nilai Max</label>
                        <input type="number" step="0.01" wire:model="kategori.{{ $kode }}.max" class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-4 py-2 text-sm font-black focus:ring-primary-500 transition-all">
                        @error("kategori.{$kode}.max") <span class="text-[10px] text-rose-500 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex items-center justify-end">
            <button type="submit" wire:loading.attr="disabled" class="px-10 py-4 rounded-2xl bg-primary-600 text-white font-black hover:bg-primary-700 transition-all shadow-xl shadow-primary-500/30 flex items-center gap-3">
                <svg wire:loading class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>SIMPAN PENGATURAN</span>
            </button>
        </div>
    </form>
</div>

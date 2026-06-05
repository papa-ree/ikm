<div class="max-w-3xl mx-auto space-y-6"
    x-data="{
        triwulan: $wire.entangle('triwulan'),
        submitting: false,
        async submit() {
            if (this.submitting) return;
            this.submitting = true;
            try {
                await $wire.save();
            } finally {
                this.submitting = false;
            }
        }
    }">

    {{-- Hero Header --}}
    <div class="relative overflow-hidden p-8 text-white rounded-2xl shadow-xl"
         style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>
        <div class="relative z-10 text-center">
            <div class="inline-flex p-4 bg-white/20 backdrop-blur-md rounded-2xl mb-4">
                <x-lucide-upload-cloud class="w-10 h-10 text-white" />
            </div>
            <h1 class="text-3xl font-bold text-white md:text-4xl">Import Data IKM</h1>
            <p class="mt-2 text-white/90 max-w-lg mx-auto text-lg">
                Unggah berkas Excel hasil pengolahan kuesioner IKM sesuai standar PermenPAN-RB 14/2017.
            </p>
        </div>
    </div>

    {{-- Info Banner --}}
    <div class="p-5 bg-linear-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border border-amber-200 dark:border-amber-800 rounded-2xl"
         data-aos="fade-up" data-aos-delay="100">
        <div class="flex items-start gap-4">
            <div class="p-2.5 bg-amber-600 rounded-xl shadow-lg shrink-0">
                <x-lucide-info class="w-5 h-5 text-white" />
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-1">Panduan Unggah Berkas</h3>
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    Pastikan format file sesuai template IKM standar PermenPAN-RB 14/2017.
                    Hanya file <strong>.xlsx</strong> atau <strong>.xls</strong> yang diterima (Maks. 5MB).
                </p>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden relative"
         data-aos="fade-up" data-aos-delay="150">

        {{-- Decorative glow --}}
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>

        {{-- Card Header --}}
        <div class="px-8 py-5 border-b border-gray-100 dark:border-gray-700
                    bg-linear-to-r from-indigo-50/50 to-purple-50/50 dark:from-indigo-900/10 dark:to-purple-900/10">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Detail Batch Import</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Isi informasi batch dan unggah file Excel IKM.</p>
        </div>

        <div class="p-8 relative z-10 space-y-6">

            {{-- Nama Batch --}}
            <div>
                <label for="nama" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                    Label Identitas Batch
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300 group-focus-within:text-indigo-500 transition-colors z-10">
                        <x-lucide-tag class="w-4 h-4" />
                    </div>
                    <x-core::input
                        id="nama"
                        wire:model="nama"
                        type="text"
                        placeholder="Contoh: Laporan IKM Triwulan 1 Tahun 2025"
                        class="pl-11"
                    />
                </div>
                @error('nama')
                    <p class="text-xs font-medium text-red-500 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Triwulan — Alpine-only, sync ke Livewire via x-model --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        Periode Triwulan
                    </label>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach([1, 2, 3, 4] as $tw)
                        <button
                            type="button"
                            @click="triwulan = {{ $tw }}"
                            :class="triwulan === {{ $tw }}
                                ? 'bg-linear-to-r from-indigo-600 to-purple-600 text-white border-transparent shadow-md shadow-purple-500/20'
                                : 'bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-600'"
                            class="py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 border">
                            TW{{ $tw }}
                        </button>
                        @endforeach
                    </div>
                    {{-- Badge konfirmasi pilihan aktif --}}
                    <p class="mt-2 text-[11px] text-gray-400 dark:text-gray-500">
                        Dipilih: <span class="font-semibold text-indigo-600 dark:text-indigo-400" x-text="'Triwulan ' + triwulan"></span>
                    </p>
                    @error('triwulan')
                        <p class="text-xs font-medium text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tahun --}}
                <div>
                    <label for="tahun" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                        Tahun Pelaporan
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-300 group-focus-within:text-indigo-500 transition-colors z-10">
                            <x-lucide-calendar class="w-4 h-4" />
                        </div>
                        <x-core::input
                            id="tahun"
                            wire:model="tahun"
                            type="number"
                            min="2020"
                            max="2099"
                            class="pl-11"
                        />
                    </div>
                    @error('tahun')
                        <p class="text-xs font-medium text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- File Upload Zone --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                    Berkas Dokumentasi (Excel)
                </label>
                <x-core::upload-zone
                    wire:model.live="file"
                    accept=".xlsx,.xls,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"
                    :maxSize="5120"
                    :label="__('Tarik & Lepas File ke Sini')"
                    :hint="__('Hanya file .xlsx atau .xls (Maks. 5MB)')"
                    class="w-full"
                />
                @error('file')
                    <p class="text-xs font-medium text-red-500 mt-2 text-center">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Card Footer / Actions --}}
        <div class="px-8 py-5 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700
                    flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex flex-wrap items-center justify-center gap-3 shrink-0">
                <x-core::button 
                    link 
                    href="{{ route('ikm.list') }}" 
                    variant="secondary" 
                    label="Kembali"
                    class="px-5"
                >
                    <x-slot name="icon">
                        <x-lucide-arrow-left class="w-4 h-4" />
                    </x-slot>
                </x-core::button>

                <x-core::button 
                    variant="success" 
                    label="Unduh Template Excel" 
                    wire:click="downloadTemplate"
                    wire:loading.attr="disabled"
                    wire:target="downloadTemplate"  
                >
                    <x-slot name="icon">
                        <div wire:loading wire:target="downloadTemplate" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                        <x-lucide-check-circle wire:loading.remove wire:target="downloadTemplate" class="w-4 h-4" />
                    </x-slot>
                </x-core::button>
            </div>
            <x-core::button
                type="button"
                x-on:click="submit()"
                x-bind:disabled="submitting"
                label=""
                class="w-full sm:w-auto px-10"
            >
                <x-slot name="icon">
                    <svg x-show="submitting"
                         class="w-4 h-4 animate-spin text-white/70"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <x-lucide-database-backup x-show="!submitting" class="w-4 h-4" />
                    <span x-text="submitting ? 'Memproses...' : 'Mulai Import Data'"></span>
                </x-slot>
            </x-core::button>
        </div>
    </div>
</div>

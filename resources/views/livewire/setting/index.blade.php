<div class="max-w-5xl mx-auto space-y-6">
    {{-- Hero Header --}}
    <div class="relative overflow-hidden p-8 text-white rounded-2xl shadow-xl"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-3 bg-white/20 backdrop-blur-md rounded-xl">
                        <x-lucide-settings-2 class="w-8 h-8 text-white" />
                    </div>
                    <h1 class="text-3xl font-bold text-white md:text-4xl">Pengaturan IKM</h1>
                </div>
                <p class="max-w-2xl text-white/90 text-lg">Konfigurasi ambang batas nilai dan label klasifikasi IKM
                    sesuai standar PermenPAN-RB 14/2017.</p>
            </div>
            <div class="shrink-0">
                <button wire:click="resetToDefault"
                    wire:confirm="Kembalikan semua pengaturan ke standar PermenPAN-RB 14/2017? Data yang sudah tersimpan akan diubah."
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-white/20 backdrop-blur-md border border-white/30 text-white font-semibold text-sm hover:bg-white/30 transition-all">
                    <x-lucide-refresh-cw class="w-4 h-4" />
                    Reset ke Default
                </button>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form wire:submit="save" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" data-aos="fade-up" data-aos-delay="100">
            @foreach($kodeList as $kode)
                <div
                    class="group p-6 transition-all duration-300 bg-white border border-gray-100 shadow-md dark:bg-gray-800 rounded-2xl hover:shadow-xl dark:border-gray-700 hover:-translate-y-1">
                    {{-- Category Header --}}
                    <div class="flex items-center gap-4 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                        <span
                            class="w-12 h-12 flex items-center justify-center rounded-xl bg-linear-to-br from-indigo-500 to-purple-600 text-white text-xl font-bold shadow-lg">
                            {{ $kode }}
                        </span>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-[0.25em] text-gray-400">Klasifikasi</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white">Kategori {{ $kode }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        {{-- Label Input --}}
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">
                                Label Kualitas Pelayanan
                            </label>
                            <x-core::input type="text" wire:model="kategori.{{ $kode }}.label"
                                placeholder="Contoh: Sangat Baik" />
                            @error("kategori.{$kode}.label")
                                <p class="text-xs font-medium text-red-500 mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Values --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Nilai
                                    Minimum</label>
                                <x-core::input type="number" step="0.01" wire:model="kategori.{{ $kode }}.min"
                                    placeholder="0.00" />
                                @error("kategori.{$kode}.min")
                                    <p class="text-xs font-medium text-red-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Nilai
                                    Maksimum</label>
                                <x-core::input type="number" step="0.01" wire:model="kategori.{{ $kode }}.max"
                                    placeholder="100.00" />
                                @error("kategori.{$kode}.max")
                                    <p class="text-xs font-medium text-red-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Submit Action --}}
        <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-col items-center gap-4"
            data-aos="fade-up" data-aos-delay="200">
            <x-core::button 
                type="submit" 
                wire:loading.attr="disabled"
                label="Simpan Perubahan"
                class="px-12"
            >
                <x-slot name="icon">
                    <div wire:loading class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                    <x-lucide-save wire:loading.remove class="w-4 h-4" />
                </x-slot>
            </x-core::button>
            {{-- <p class="text-xs font-medium text-gray-400">Terakhir diperbarui: {{ now()->format('d M Y, H:i') }}</p>
            --}}
        </div>
    </form>

    {{-- Template Upload Section --}}
    @role('root')
    <livewire:ikm.setting.template-upload />
    @endrole
</div>
<div class="p-6 bg-white border border-gray-100 shadow-md dark:bg-gray-800 rounded-2xl dark:border-gray-700 mt-6" data-aos="fade-up" data-aos-delay="300">
    <div class="flex items-center gap-4 mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
        <div class="p-3 bg-linear-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg">
            <x-lucide-file-spreadsheet class="w-6 h-6 text-white" />
        </div>
        <div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Master Template IKM</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">Unggah berkas Excel standar yang akan digunakan sebagai referensi import.</p>
        </div>
    </div>

    <form wire:submit="saveTemplate" class="space-y-4">
        <x-core::upload-zone
            wire:model="template_file"
            accept=".xlsx,.xls"
            :maxSize="5120"
            :label="__('Unggah Template Baru')"
            :hint="__('Hanya file .xlsx atau .xls (Maks. 5MB)')"
            class="w-full"
        />
        
        @error('template_file')
            <p class="text-xs font-medium text-red-500 mt-2">{{ $message }}</p>
        @enderror

        <div class="flex justify-end">
            <x-core::button 
                variant="success" 
                label="Simpan Template" 
                wire:loading.attr="disabled"
                wire:target="saveTemplate"
            >
                <x-slot name="icon">
                    <div wire:loading wire:target="saveTemplate" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                    <x-lucide-check-circle wire:loading.remove wire:target="saveTemplate" class="w-4 h-4" />
                </x-slot>
            </x-core::button>
        </div>
    </form>
</div>

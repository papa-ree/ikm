<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Import Data IKM</h1>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Silakan unggah file Excel IKM sesuai format PermenPAN-RB 14/2017.</p>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="p-8 rounded-3xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-sm space-y-6">
            <!-- Nama Batch -->
            <div>
                <label for="nama" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Batch / Label</label>
                <input type="text" id="nama" wire:model="nama" placeholder="Contoh: IKM TW1 2025" class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-primary-500 focus:border-primary-500 transition-all">
                @error('nama') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Triwulan -->
                <div>
                    <label for="triwulan" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Triwulan</label>
                    <select id="triwulan" wire:model="triwulan" class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-primary-500 focus:border-primary-500 transition-all">
                        <option value="1">Triwulan 1</option>
                        <option value="2">Triwulan 2</option>
                        <option value="3">Triwulan 3</option>
                        <option value="4">Triwulan 4</option>
                    </select>
                    @error('triwulan') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Tahun -->
                <div>
                    <label for="tahun" class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tahun</label>
                    <input type="number" id="tahun" wire:model="tahun" class="w-full rounded-2xl border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-4 py-3 text-sm focus:ring-primary-500 focus:border-primary-500 transition-all">
                    @error('tahun') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- File Upload -->
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">File Excel</label>
                <div 
                    x-data="{ isDragging: false }"
                    @dragover.prevent="isDragging = true"
                    @dragleave.prevent="isDragging = false"
                    @drop.prevent="isDragging = false"
                    class="relative border-2 border-dashed rounded-3xl p-10 flex flex-col items-center justify-center transition-all cursor-pointer"
                    :class="isDragging ? 'border-primary-500 bg-primary-50/10' : 'border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-950/50'"
                >
                    <input type="file" wire:model="file" class="absolute inset-0 opacity-0 cursor-pointer" id="file_upload">
                    
                    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 shadow-sm mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    </div>
                    
                    @if($file)
                        <div class="text-center">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $file->getClientOriginalName() }}</p>
                            <p class="text-xs text-slate-500 mt-1">{{ number_format($file->getSize() / 1024, 2) }} KB</p>
                        </div>
                    @else
                        <div class="text-center">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">Klik untuk upload atau drag & drop</p>
                            <p class="text-xs text-slate-500 mt-1">Hanya file .xlsx atau .xls (Max. 5MB)</p>
                        </div>
                    @endif

                    <!-- Upload Progress -->
                    <div x-show="isUploading" class="w-full mt-6">
                        <div class="h-1 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-primary-600 transition-all duration-300" :style="'width: ' + progress + '%'"></div>
                        </div>
                    </div>
                </div>
                @error('file') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('ikm.list') }}" wire:navigate class="px-6 py-3 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                Batal
            </a>
            <button 
                type="submit" 
                wire:loading.attr="disabled"
                class="px-8 py-3 rounded-2xl bg-primary-600 text-white font-bold hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-lg shadow-primary-500/20 flex items-center gap-2"
            >
                <svg wire:loading class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Proses Import</span>
            </button>
        </div>
    </form>
</div>

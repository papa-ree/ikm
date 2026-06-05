<tr wire:key="batch-row-{{ $record->id }}" class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors group">
    <td class="px-6 py-4">
        <div class="flex items-center gap-3">
            <div class="p-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-400 group-hover:bg-linear-to-br group-hover:from-indigo-500 group-hover:to-purple-600 group-hover:text-white transition-all">
                <x-lucide-file-spreadsheet class="w-5 h-5" />
            </div>
            <div>
                <p class="font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $record->nama }}</p>
                <p class="text-[10px] font-medium text-gray-400 mt-0.5 truncate max-w-[200px]">{{ $record->nama_file }}</p>
            </div>
        </div>
    </td>
    <td class="px-6 py-4">
        <div class="flex flex-col">
            <span class="font-semibold text-gray-700 dark:text-gray-300">TW{{ $record->triwulan }}</span>
            <span class="text-[10px] font-medium text-gray-400">{{ $record->tahun }}</span>
        </div>
    </td>
    <td class="px-6 py-4 text-center">
        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-bold text-xs border border-indigo-200 dark:border-indigo-800">
            {{ $record->jumlah_opd }}
        </span>
    </td>
    <td class="px-6 py-4">
        @php
            $badge = $record->status_badge;
            $statusStyle = match($badge['color']) {
                'gray'    => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                'amber'   => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300 animate-pulse',
                'blue'    => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
                'emerald' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300',
                'red'     => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                default   => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
            };
        @endphp
        <div class="flex flex-col gap-1">
            <span class="w-fit px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusStyle }}">
                {{ $badge['label'] }}
            </span>
            @if($record->approved_at)
                <span class="text-[9px] text-emerald-600 dark:text-emerald-400 font-medium flex items-center gap-1">
                    <x-lucide-check-circle-2 class="w-3 h-3" />
                    {{ $record->approved_at->format('d/m/Y H:i') }}
                </span>
            @endif
        </div>
    </td>
    <td class="px-6 py-4">
        <div class="space-y-2">
            <div class="flex items-center gap-2" title="Diunggah oleh">
                <div class="w-6 h-6 rounded-full bg-linear-to-br from-gray-400 to-gray-500 flex items-center justify-center text-[9px] font-bold text-white shadow-sm">
                    {{ substr($record->uploadedBy->name ?? 'S', 0, 1) }}
                </div>
                <span class="text-[10px] font-medium text-gray-500 dark:text-gray-400">Up: {{ $record->uploadedBy->name ?? 'System' }}</span>
            </div>
            @if($record->approvedBy)
            <div class="flex items-center gap-2" title="Disetujui oleh">
                <div class="w-6 h-6 rounded-full bg-linear-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-[9px] font-bold text-white shadow-sm">
                    {{ substr($record->approvedBy->name ?? 'A', 0, 1) }}
                </div>
                <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400">App: {{ $record->approvedBy->name }}</span>
            </div>
            @endif
        </div>
    </td>
    <td class="px-6 py-4 text-right">
        <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
            {{-- Approve Action --}}
            @if(Auth::user()?->can(\Bale\Ikm\IkmPermissions::APPROVE_IKM) && $record->status === 'selesai' && !$record->approved_at)
                <button wire:click="$parent.approveBatch('{{ $record->id }}')"
                    wire:confirm="Setujui dan publikasikan batch ini?"
                    class="p-2 text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 dark:text-emerald-400 dark:hover:text-emerald-300 dark:hover:bg-emerald-900/20 rounded transition-all"
                    title="Setujui Batch">
                    <x-lucide-check-check class="w-4 h-4" />
                </button>
            @endif

            {{-- Standard Actions --}}
            @canany([\Bale\Ikm\IkmPermissions::DELETE_IKM])
                <livewire:core.shared-components.item-actions
                    :editUrl="route('ikm.detail', $record)"
                    :deleteId="$record->id"
                    :navigate="true"
                    confirmMessage="Hapus batch ini selamanya? Seluruh data record di dalamnya akan hilang."
                    wire:key="batch-actions-{{ $record->id }}" />
            @endcanany
        </div>
    </td>
</tr>

<tr wire:key="record-row-{{ $record->id }}" class="hover:bg-gray-50/70 dark:hover:bg-gray-800/50 transition-colors group">
    {{-- # --}}
    <td class="px-4 py-4 text-xs font-semibold text-gray-400 tabular-nums w-px">
        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
    </td>

    {{-- Unit Layanan --}}
    <td class="px-4 py-4">
        <span class="font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors text-sm leading-snug">
            {{ $record->nama_opd }}
        </span>
    </td>

    {{-- NRR Badges (hidden on smaller screens) --}}
    <td class="px-4 py-4 hidden xl:table-cell">
        <div class="flex items-end justify-center gap-0.5">
            @for($i = 1; $i <= 9; $i++)
                @php $val = $record->{"nrr_u$i"}; @endphp
                <div class="flex flex-col items-center">
                    <span class="text-[7px] font-bold text-gray-400 mb-1">U{{ $i }}</span>
                    <span class="w-7 h-7 flex items-center justify-center rounded-md text-[9px] font-bold border
                        {{ $val >= 3.25
                            ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800'
                            : ($val >= 2.5
                                ? 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800'
                                : 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800') }}"
                        title="Unsur {{ $i }}: {{ $val }}">
                        {{ number_format($val, 1) }}
                    </span>
                </div>
            @endfor
        </div>
    </td>

    {{-- Nilai IKM --}}
    <td class="px-4 py-4 text-center">
        <div class="inline-flex flex-col items-center">
            <span class="text-xl font-extrabold text-gray-900 dark:text-white tabular-nums">
                {{ number_format($record->nilai_ikm, 2) }}
            </span>
            <span class="text-[9px] font-medium text-gray-400 uppercase tracking-wide mt-0.5">
                WT {{ number_format($record->nrr_tertimbang, 3) }}
            </span>
        </div>
    </td>

    {{-- Mutu Layanan --}}
    <td class="px-4 py-4 text-center">
        @php
            $catColor = match($record->kategori) {
                'A' => 'from-emerald-500 to-emerald-600 shadow-emerald-500/25',
                'B' => 'from-blue-500 to-blue-600 shadow-blue-500/25',
                'C' => 'from-amber-500 to-amber-600 shadow-amber-500/25',
                'D' => 'from-red-500 to-rose-600 shadow-rose-500/25',
                default => 'from-gray-400 to-gray-500 shadow-gray-400/20',
            };
        @endphp
        <div class="inline-flex flex-col items-center gap-1">
            <span class="w-9 h-9 flex items-center justify-center rounded-xl text-sm font-bold text-white shadow-md bg-linear-to-br {{ $catColor }}">
                {{ $record->kategori }}
            </span>
            <span class="text-[9px] font-semibold uppercase text-gray-500 dark:text-gray-400 tracking-wider whitespace-nowrap">
                {{ $record->label_kategori }}
            </span>
        </div>
    </td>
</tr>

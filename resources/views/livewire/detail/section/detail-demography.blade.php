<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- Gender --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/60">
        <div class="flex items-center gap-3 mb-5">
            <div class="p-2.5 bg-linear-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-md shrink-0">
                <x-lucide-users class="w-5 h-5 text-white" />
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Jenis Kelamin</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Total: {{ number_format($totalSampel) }} responden</p>
            </div>
        </div>
        <div class="space-y-4">
            @foreach($demJK as $label => $val)
            <div>
                <div class="flex justify-between text-xs font-semibold mb-1.5">
                    <span class="text-gray-600 dark:text-gray-400">{{ $label }}</span>
                    <span class="text-gray-900 dark:text-white">{{ number_format($val) }}</span>
                </div>
                <div class="h-2 w-full bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                    @php $pct = $totalSampel > 0 ? ($val / $totalSampel * 100) : 0; @endphp
                    <div class="h-full bg-linear-to-r from-indigo-500 to-purple-600 rounded-full" style="width: {{ $pct }}%"></div>
                </div>
                <p class="text-[10px] text-gray-400 mt-1 text-right">{{ number_format($pct, 1) }}%</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Education --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/60">
        <div class="flex items-center gap-3 mb-5">
            <div class="p-2.5 bg-linear-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-md shrink-0">
                <x-lucide-graduation-cap class="w-5 h-5 text-white" />
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Pendidikan</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Pendidikan terakhir responden</p>
            </div>
        </div>
        <div class="space-y-2">
            @foreach($demPendidikan as $label => $val)
            <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <span class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ $label }}</span>
                <span class="px-2.5 py-1 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                    {{ number_format($val) }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Job --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/60">
        <div class="flex items-center gap-3 mb-5">
            <div class="p-2.5 bg-linear-to-br from-amber-500 to-amber-600 rounded-xl shadow-md shrink-0">
                <x-lucide-briefcase class="w-5 h-5 text-white" />
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Pekerjaan</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Profil pekerjaan responden</p>
            </div>
        </div>
        <div class="space-y-2">
            @foreach($demPekerjaan as $label => $val)
            <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <span class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ $label }}</span>
                <span class="px-2.5 py-1 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 text-xs font-bold text-amber-600 dark:text-amber-400">
                    {{ number_format($val) }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

</div>

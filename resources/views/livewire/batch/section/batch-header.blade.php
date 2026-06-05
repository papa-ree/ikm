<x-core::page-header gradient title="Riwayat Batch IKM" subtitle="Kelola berkas impor dan status verifikasi data IKM.">
    <x-slot name="action">
        @can('create', \Bale\Ikm\Models\IkmBatch::class)
            <x-core::button link href="{{ route('ikm.upload') }}" label="Import Batch Baru">
                <x-slot name="icon">
                    <x-lucide-plus-circle class="w-5 h-5" />
                </x-slot>
            </x-core::button>
        @endcan
    </x-slot>
</x-core::page-header>

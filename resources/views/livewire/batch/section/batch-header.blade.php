<x-core::page-header gradient title="Riwayat Batch IKM" subtitle="Kelola berkas impor dan status verifikasi data IKM.">
    <x-slot name="action">
        {{-- @can(\Bale\Ikm\IkmPermissions::DELETE_IKM)
        <x-core::button link href="{{ route('ikm.trash') }}" label="Trash" variant="danger">
            <x-slot name="icon">
                <x-lucide-trash-2 class="w-4 h-4" />
            </x-slot>
        </x-core::button>
        @endcan --}}
        @can('create', \Bale\Ikm\Models\IkmBatch::class)
            <x-core::button link href="{{ route('ikm.upload') }}" label="Import Batch Baru">
                <x-slot name="icon">
                    <x-lucide-plus-circle class="w-5 h-5" />
                </x-slot>
            </x-core::button>
        @endcan
    </x-slot>
</x-core::page-header>
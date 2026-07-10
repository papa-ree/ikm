<x-core::page-header gradient title="Trash — Batch IKM"
    subtitle="Batch yang dihapus akan tersimpan di sini. Hapus permanen untuk membersihkan data secara total.">
    <x-slot name="action">
        @can(\Bale\Ikm\IkmPermissions::DELETE_IKM)
            <x-core::button wire:click="emptyTrash"
                wire:confirm="Kosongkan seluruh trash? Semua batch dan record-nya akan dihapus PERMANEN dan tidak dapat dikembalikan."
                label="Kosongkan Trash" variant="danger">
                <x-slot name="icon">
                    <x-lucide-trash-2 class="w-4 h-4" />
                </x-slot>
            </x-core::button>
        @endcan
        {{-- <x-core::button link href="{{ route('ikm.list') }}" label="Kembali ke Daftar" variant="secondary">
            <x-slot name="icon">
                <x-lucide-arrow-left class="w-5 h-5" />
            </x-slot>
        </x-core::button> --}}
    </x-slot>
</x-core::page-header>
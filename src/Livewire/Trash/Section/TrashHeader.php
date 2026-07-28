<?php

namespace Bale\Ikm\Livewire\Trash\Section;

use Bale\Cms\Services\TenantConnectionService;
use Bale\Ikm\IkmPermissions;
use Bale\Ikm\Models\IkmBatch;
use Livewire\Component;

class TrashHeader extends Component
{
    public function emptyTrash(): void
    {
        TenantConnectionService::ensureActive();
        $this->authorize(IkmPermissions::DELETE_IKM);

        IkmBatch::onlyTrashed()
            ->get()
            ->each(fn ($batch) => $batch->forceDelete());

        $this->dispatch('toast', message: 'Trash berhasil dikosongkan.', type: 'success');
        $this->dispatch('trash-emptied');
    }

    public function render()
    {
        return view('ikm::livewire.trash.section.trash-header');
    }
}

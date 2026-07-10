<?php

namespace Bale\Ikm\Livewire\Batch\Section;

use Bale\Ikm\IkmPermissions;
use Bale\Ikm\Models\IkmBatch;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BatchTable extends Component
{
    public function deleteItem(string $id): void
    {
        TenantConnectionService::ensureActive();
        $batch = IkmBatch::findOrFail($id);
        $this->authorize('delete', $batch);

        $batch->getConnection()->transaction(function () use ($batch) {
            $batch->delete();
        });

        $this->dispatch('toast', message: 'Batch berhasil dihapus.', type: 'success');
    }

    public function approveBatch(string $id): void
    {
        TenantConnectionService::ensureActive();
        $batch = IkmBatch::findOrFail($id);
        $this->authorize('approve', $batch);

        $batch->update([
            'status'      => 'selesai',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        $this->dispatch('toast', message: 'Batch berhasil disetujui.', type: 'success');
        $this->dispatch('refresh');
    }

    public function render()
    {
        $tahunOptions = range(2020, (int) now()->format('Y'));
        return view('ikm::livewire.batch.section.batch-table', compact('tahunOptions'));
    }
}

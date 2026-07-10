<?php

namespace Bale\Ikm\Livewire\Trash\Section;

use Bale\Ikm\IkmPermissions;
use Bale\Ikm\Models\IkmBatch;
use Bale\Cms\Services\TenantConnectionService;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class TrashTable extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterTriwulan = '';
    public string $filterTahun = '';

    protected $queryString = [
        'search'         => ['except' => ''],
        'filterTriwulan' => ['except' => '', 'as' => 'tw'],
        'filterTahun'    => ['except' => '', 'as' => 'tahun'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterTriwulan(): void
    {
        $this->resetPage();
    }

    public function updatingFilterTahun(): void
    {
        $this->resetPage();
    }

    /**
     * Kembalikan (restore) batch yang sudah dihapus.
     */
    public function restoreItem(string $id): void
    {
        TenantConnectionService::ensureActive();

        $batch = IkmBatch::onlyTrashed()->findOrFail($id);
        $this->authorize('delete', $batch);

        // Restore batch & records terkait
        $batch->getConnection()->transaction(function () use ($batch) {
            $batch->restore();
            $batch->records()->onlyTrashed()->restore();
        });

        $this->dispatch('toast', message: 'Batch berhasil dipulihkan.', type: 'success');
    }

    /**
     * Hapus batch secara permanen beserta seluruh records-nya.
     */
    public function forceDeleteItem(string $id): void
    {
        TenantConnectionService::ensureActive();

        $batch = IkmBatch::onlyTrashed()->findOrFail($id);
        $this->authorize('delete', $batch);

        // forceDelete akan memicu event `forceDeleting` di IkmBatch::booted()
        // yang akan menghapus records secara permanen juga.
        $batch->getConnection()->transaction(function () use ($batch) {
            $batch->forceDelete();
        });

        $this->dispatch('toast', message: 'Batch berhasil dihapus permanen.', type: 'success');
    }

    /**
     * Dipanggil oleh TrashHeader setelah mengosongkan trash.
     */
    #[On('trash-emptied')]
    public function refreshAfterEmpty(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        TenantConnectionService::ensureActive();

        $tahunOptions = range(2020, (int) now()->format('Y'));

        $batches = IkmBatch::onlyTrashed()
            ->with(['uploadedBy:id,name'])
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_file', 'like', '%' . $this->search . '%');
            }))
            ->when($this->filterTriwulan, fn($q) => $q->where('triwulan', $this->filterTriwulan))
            ->when($this->filterTahun, fn($q) => $q->where('tahun', $this->filterTahun))
            ->orderBy('deleted_at', 'desc')
            ->paginate(20);

        return view('ikm::livewire.trash.section.trash-table', compact('batches', 'tahunOptions'));
    }
}

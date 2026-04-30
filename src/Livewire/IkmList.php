<?php

namespace Bale\Ikm\Livewire;

use Bale\Ikm\IkmPermissions;
use Bale\Ikm\Models\IkmBatch;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('ikm::layouts.app')]
#[Title('Daftar Batch IKM')]
class IkmList extends Component
{
    use WithPagination;

    public string $search          = '';
    public ?int   $filterTahun     = null;
    public ?int   $filterTriwulan  = null;
    public ?string $filterStatus   = null;
    public int    $perPage;

    public function mount(): void
    {
        $this->perPage = config('ikm.per_page', 20);
    }

    // Reset pagination saat filter berubah
    public function updatedSearch(): void       { $this->resetPage(); }
    public function updatedFilterTahun(): void  { $this->resetPage(); }
    public function updatedFilterTriwulan(): void { $this->resetPage(); }
    public function updatedFilterStatus(): void { $this->resetPage(); }

    public function deleteBatch(string $id): void
    {
        $batch = IkmBatch::findOrFail($id);
        $this->authorize('delete', $batch);
        $batch->delete();
        session()->flash('success', 'Batch berhasil dihapus.');
    }

    public function approveBatch(string $id): void
    {
        $batch = IkmBatch::findOrFail($id);
        $this->authorize('approve', $batch);

        $batch->update([
            'status'      => 'selesai',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        session()->flash('success', 'Batch berhasil disetujui.');
    }

    public function render()
    {
        $batches = IkmBatch::query()
            ->with(['uploadedBy:id,name', 'approvedBy:id,name'])
            ->when($this->search, fn ($q) =>
                $q->where('nama', 'like', "%{$this->search}%")
            )
            ->when($this->filterTahun,    fn ($q) => $q->where('tahun',    $this->filterTahun))
            ->when($this->filterTriwulan, fn ($q) => $q->where('triwulan', $this->filterTriwulan))
            ->when($this->filterStatus,   fn ($q) => $q->where('status',   $this->filterStatus))
            ->latest()
            ->paginate($this->perPage);

        $tahunOptions = range(2020, (int) now()->format('Y'));

        $canDelete  = Auth::user()?->can(IkmPermissions::DELETE_IKM);
        $canApprove = Auth::user()?->can(IkmPermissions::APPROVE_IKM);

        return view('ikm::livewire.ikm-list', compact(
            'batches', 'tahunOptions', 'canDelete', 'canApprove',
        ));
    }
}

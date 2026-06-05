<?php

namespace Bale\Ikm\Livewire\Detail\Section;

use Bale\Ikm\Models\IkmBatch;
use Bale\Ikm\IkmPermissions;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Bale\Ikm\Services\IkmCalculatorService;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Bale\Ikm\Models\IkmRecord;

class DetailHeader extends Component
{
    public IkmBatch $batch;
    public bool $canExport      = false;
    public bool $canRecalculate = false;

    public function mount(IkmBatch $batch, bool $canExport, bool $canRecalculate): void
    {
        $this->batch          = $batch;
        $this->canExport      = $canExport;
        $this->canRecalculate = $canRecalculate;
    }

    public function recalculate(IkmCalculatorService $calculator): void
    {
        TenantConnectionService::ensureActive();
        $this->authorize('approve', $this->batch);
        $calculator->recalculateBatch($this->batch);
        $this->dispatch('toast', message: 'Kalkulasi ulang berhasil.', type: 'success');
    }

    public function exportExcel(): StreamedResponse
    {
        TenantConnectionService::ensureActive();
        $this->authorize('export', $this->batch);

        $records  = IkmRecord::query()
            ->where('ikm_batch_id', $this->batch->id)
            ->orderBy('nama_opd')
            ->get();

        $filename = "IKM_{$this->batch->triwulan}_{$this->batch->tahun}.csv";

        return response()->streamDownload(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'Nama OPD', 'U1', 'U2', 'U3', 'U4', 'U5', 'U6', 'U7', 'U8', 'U9', 'NRR Tertimbang', 'Nilai IKM', 'Kategori', 'Label Kategori', 'Populasi', 'Sampel']);
            foreach ($records as $i => $rec) {
                fputcsv($handle, [$i + 1, $rec->nama_opd, $rec->nrr_u1, $rec->nrr_u2, $rec->nrr_u3, $rec->nrr_u4, $rec->nrr_u5, $rec->nrr_u6, $rec->nrr_u7, $rec->nrr_u8, $rec->nrr_u9, $rec->nrr_tertimbang, $rec->nilai_ikm, $rec->kategori, $rec->label_kategori, $rec->populasi, $rec->sampel]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function render()
    {
        return view('ikm::livewire.detail.section.detail-header');
    }
}

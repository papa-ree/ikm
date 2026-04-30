<?php

namespace Bale\Ikm\Livewire;

use Bale\Ikm\IkmPermissions;
use Bale\Ikm\Models\IkmBatch;
use Bale\Ikm\Models\IkmRecord;
use Bale\Ikm\Services\IkmCalculatorService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('ikm::layouts.app')]
#[Title('Detail Batch IKM')]
class IkmDetail extends Component
{
    use WithPagination;

    public IkmBatch $batch;

    #[Url]
    public string $search    = '';

    #[Url]
    public string $sortDir   = 'desc';

    public function mount(IkmBatch $batch): void
    {
        $this->authorize('view', $batch);
        $this->batch = $batch;
    }

    public function toggleSort(): void
    {
        $this->sortDir = $this->sortDir === 'desc' ? 'asc' : 'desc';
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function recalculate(IkmCalculatorService $calculator): void
    {
        $this->authorize('approve', $this->batch);
        $calculator->recalculateBatch($this->batch);
        session()->flash('success', 'Kalkulasi ulang berhasil.');
    }

    public function exportExcel(): StreamedResponse
    {
        $this->authorize('export', $this->batch);

        $records = IkmRecord::query()
            ->where('ikm_batch_id', $this->batch->id)
            ->orderBy('nama_opd')
            ->get();

        $filename = "IKM_{$this->batch->triwulan}_{$this->batch->tahun}.csv";

        return response()->streamDownload(function () use ($records) {
            $handle = fopen('php://output', 'w');

            // Header CSV
            fputcsv($handle, [
                'No','Nama OPD','U1','U2','U3','U4','U5','U6','U7','U8','U9',
                'NRR Tertimbang','Nilai IKM','Kategori','Label Kategori',
                'Populasi','Sampel',
            ]);

            foreach ($records as $i => $rec) {
                fputcsv($handle, [
                    $i + 1,
                    $rec->nama_opd,
                    $rec->nrr_u1, $rec->nrr_u2, $rec->nrr_u3,
                    $rec->nrr_u4, $rec->nrr_u5, $rec->nrr_u6,
                    $rec->nrr_u7, $rec->nrr_u8, $rec->nrr_u9,
                    $rec->nrr_tertimbang,
                    $rec->nilai_ikm,
                    $rec->kategori,
                    $rec->label_kategori,
                    $rec->populasi,
                    $rec->sampel,
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function render()
    {
        $records = IkmRecord::query()
            ->where('ikm_batch_id', $this->batch->id)
            ->when($this->search, fn ($q) =>
                $q->where('nama_opd', 'like', "%{$this->search}%")
            )
            ->orderBy('nilai_ikm', $this->sortDir)
            ->paginate(20);

        // ── Ringkasan Demografi ──────────────────────
        $allRecords = IkmRecord::where('ikm_batch_id', $this->batch->id)->get();

        $totalSampel = $allRecords->sum('sampel');

        $demJK = [
            'Laki-laki' => $allRecords->sum('dem_laki'),
            'Perempuan' => $allRecords->sum('dem_perempuan'),
        ];
        $demPendidikan = [
            'SD'      => $allRecords->sum('dem_sd'),
            'SLTP'    => $allRecords->sum('dem_sltp'),
            'SLTA'    => $allRecords->sum('dem_slta'),
            'Diploma' => $allRecords->sum('dem_diploma'),
            'S1'      => $allRecords->sum('dem_s1'),
            'S2'      => $allRecords->sum('dem_s2'),
            'S3'      => $allRecords->sum('dem_s3'),
        ];
        $demPekerjaan = [
            'PNS/ASN'    => $allRecords->sum('dem_pns'),
            'TNI/Polri'  => $allRecords->sum('dem_tni_polri'),
            'Swasta'     => $allRecords->sum('dem_swasta'),
            'Wiraswasta' => $allRecords->sum('dem_wiraswasta'),
            'Pelajar'    => $allRecords->sum('dem_pelajar'),
            'Petani'     => $allRecords->sum('dem_petani'),
            'Lainnya'    => $allRecords->sum('dem_lainnya'),
        ];

        $canExport      = Auth::user()?->can(IkmPermissions::EXPORT_IKM);
        $canRecalculate = Auth::user()?->can(IkmPermissions::APPROVE_IKM);

        return view('ikm::livewire.ikm-detail', compact(
            'records',
            'totalSampel', 'demJK', 'demPendidikan', 'demPekerjaan',
            'canExport', 'canRecalculate',
        ));
    }
}

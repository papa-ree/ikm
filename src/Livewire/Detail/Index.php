<?php

namespace Bale\Ikm\Livewire\Detail;

use Bale\Ikm\IkmPermissions;
use Bale\Ikm\Models\IkmBatch;
use Bale\Ikm\Models\IkmRecord;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\{Layout, Title};
use Livewire\Component;

#[Layout('cms::layouts.app')]
#[Title('Detail Batch IKM')]
class Index extends Component
{
    public IkmBatch $batch;

    public function mount(string $id): void
    {
        TenantConnectionService::ensureActive();
        $this->batch = IkmBatch::findOrFail($id);
        $this->authorize('view', $this->batch);
    }

    public function render()
    {
        TenantConnectionService::ensureActive();

        $allRecords = IkmRecord::where('ikm_batch_id', $this->batch->id)->get();
        $totalSampel = $allRecords->sum('sampel');

        $demJK = [
            'Laki-laki' => $allRecords->sum('dem_laki'),
            'Perempuan'  => $allRecords->sum('dem_perempuan'),
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

        return view('ikm::livewire.detail.index', compact(
            'totalSampel',
            'demJK',
            'demPendidikan',
            'demPekerjaan',
            'canExport',
            'canRecalculate',
        ));
    }
}

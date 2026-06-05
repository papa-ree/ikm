<div class="space-y-6">
    <livewire:ikm.detail.section.detail-header :batch="$batch" :canExport="$canExport" :canRecalculate="$canRecalculate" />
    <livewire:ikm.detail.section.detail-table :batchId="$batch->id" />
    <livewire:ikm.detail.section.detail-demography
        :demJK="$demJK"
        :demPendidikan="$demPendidikan"
        :demPekerjaan="$demPekerjaan"
        :totalSampel="$totalSampel"
    />
</div>

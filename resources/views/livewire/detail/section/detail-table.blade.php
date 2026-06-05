<div>
    <livewire:core-shared-components::data-table
        model="Bale\Ikm\Models\IkmRecord"
        rowView="ikm::livewire.detail.section.record-row"
        connectionResolver="Bale\Cms\Services\TenantConnectionService::resolveForQuery"
        :columns="[
            [
                'key'      => 'no',
                'label'    => __('#'),
                'sortable' => false,
                'class'    => 'w-px',
            ],
            [
                'key'      => 'nama_opd',
                'label'    => __('Unit Pelaksana (OPD)'),
                'sortable' => true,
            ],
            [
                'key'      => 'nrr',
                'label'    => __('Skor Unsur (NRR)'),
                'sortable' => false,
                'class'    => 'text-center hidden xl:table-cell',
            ],
            [
                'key'      => 'nilai_ikm',
                'label'    => __('Nilai IKM'),
                'sortable' => true,
                'class'    => 'text-center',
            ],
            [
                'key'      => 'kategori',
                'label'    => __('Mutu Layanan'),
                'sortable' => true,
                'class'    => 'text-center',
            ],
        ]"
        :constraints="['ikm_batch_id' => $batchId]"
        :searchable="['nama_opd']"
        sortField="nilai_ikm"
        sortDirection="desc"
        :perPage="20"
    />
</div>

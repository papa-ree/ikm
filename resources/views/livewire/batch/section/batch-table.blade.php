<div>
    <livewire:core-shared-components::data-table
        model="Bale\Ikm\Models\IkmBatch"
        rowView="ikm::livewire.batch.section.batch-row"
        connectionResolver="Bale\Cms\Services\TenantConnectionService::resolveForQuery"
        :columns="[
            [
                'key'      => 'nama',
                'label'    => __('Nama Batch & Berkas'),
                'sortable' => true,
            ],
            [
                'key'      => 'triwulan',
                'label'    => __('Periode'),
                'sortable' => true,
            ],
            [
                'key'      => 'jumlah_opd',
                'label'    => __('Unit'),
                'sortable' => true,
                'class'    => 'text-center',
            ],
            [
                'key'      => 'status',
                'label'    => __('Status'),
                'sortable' => true,
            ],
            [
                'key'      => 'uploaded_by',
                'label'    => __('Oleh'),
                'sortable' => false,
            ],
            [
                'key'      => 'actions',
                'label'    => '',
                'sortable' => false,
                'class'    => 'text-right',
            ],
        ]"
        :with="['uploadedBy:id,name', 'approvedBy:id,name']"
        :searchable="['nama', 'nama_file']"
        sortField="created_at"
        sortDirection="desc"
        :perPage="20"
    >
        <x-slot name="filters">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('Triwulan') }}</label>
                <select wire:model.live="activeFilters.triwulan" class="w-full rounded-lg border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-3 py-2 text-sm font-medium focus:ring-indigo-500">
                    <option value="">Semua TW</option>
                    <option value="1">TW 1</option>
                    <option value="2">TW 2</option>
                    <option value="3">TW 3</option>
                    <option value="4">TW 4</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('Tahun') }}</label>
                <select wire:model.live="activeFilters.tahun" class="w-full rounded-lg border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-3 py-2 text-sm font-medium focus:ring-indigo-500">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunOptions as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">{{ __('Status') }}</label>
                <select wire:model.live="activeFilters.status" class="w-full rounded-lg border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 px-3 py-2 text-sm font-medium focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="diproses">Diproses</option>
                    <option value="selesai">Selesai</option>
                    <option value="gagal">Gagal</option>
                </select>
            </div>
        </x-slot>
    </livewire:core-shared-components::data-table>
</div>

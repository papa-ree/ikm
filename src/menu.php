<?php

return [
    [
        'id' => 'ikm-overview',
        'group' => 'ikm',
        'label' => 'Overview',
        'url' => 'ikm/overview',
        'icon' => 'bar-chart-3',
        'permission' => 'ikm.view',
        'table'      => 'ikm_batches',
    ],
    [
        'id' => 'ikm-list',
        'group' => 'ikm',
        'label' => 'Riwayat Batch',
        'url' => 'ikm/batches',
        'icon' => 'list-ordered',
        'permission' => 'ikm.view',
        'table'      => 'ikm_batches',
    ],
    [
        'id' => 'ikm-upload',
        'group' => 'ikm',
        'label' => 'Import Data',
        'url' => 'ikm/upload',
        'icon' => 'upload-cloud',
        'permission' => 'ikm.upload',
        'table'      => 'ikm_batches',
    ],
    [
        'id' => 'ikm-settings',
        'group' => 'ikm',
        'label' => 'Pengaturan',
        'url' => 'ikm/settings',
        'icon' => 'settings-2',
        'permission' => 'ikm.settings',
        'table'      => 'ikm_settings',
    ],
];

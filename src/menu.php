<?php

use Bale\Ikm\IkmPermissions;

return [
    [
        'id' => 'ikm-overview',
        'group' => 'ikm',
        'label' => 'Overview',
        'url' => 'ikm/overview',
        'icon' => 'bar-chart-3',
        'permission' => IkmPermissions::VIEW_IKM,
        'table' => 'ikm_batches',
    ],
    [
        'id' => 'ikm-list',
        'group' => 'ikm',
        'label' => 'Riwayat Batch',
        'url' => 'ikm/batches',
        'icon' => 'list-ordered',
        'permission' => IkmPermissions::VIEW_IKM,
        'table' => 'ikm_batches',
    ],
    [
        'id' => 'ikm-upload',
        'group' => 'ikm',
        'label' => 'Import Data',
        'url' => 'ikm/upload',
        'icon' => 'upload-cloud',
        'permission' => IkmPermissions::UPLOAD_IKM,
        'table' => 'ikm_batches',
    ],
    [
        'id' => 'ikm-settings',
        'group' => 'ikm',
        'label' => 'Pengaturan',
        'url' => 'ikm/settings',
        'icon' => 'settings-2',
        'permission' => IkmPermissions::MANAGE_SETTINGS,
        'table' => 'ikm_settings',
    ],
    // trash
    [
        'id' => 'ikm-trash',
        'group' => 'ikm',
        'label' => 'Trash',
        'url' => 'ikm/batches-trash',
        'icon' => 'trash-2',
        'permission' => IkmPermissions::DELETE_IKM,
        'table' => 'ikm_batches',
    ],
];

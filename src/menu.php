<?php

return [
    [
        'id'         => 'ikm-overview',
        'group'      => 'ikm',
        'label'      => 'Overview',
        'url'        => 'ikm',
        'icon'       => 'bar-chart-3',
        'permission' => \Bale\Ikm\IkmPermissions::VIEW_IKM,
    ],
    [
        'id'         => 'ikm-list',
        'group'      => 'ikm',
        'label'      => 'Riwayat Batch',
        'url'        => 'ikm/batches',
        'icon'       => 'list-ordered',
        'permission' => \Bale\Ikm\IkmPermissions::VIEW_IKM,
    ],
    [
        'id'         => 'ikm-upload',
        'group'      => 'ikm',
        'label'      => 'Import Data',
        'url'        => 'ikm/upload',
        'icon'       => 'upload-cloud',
        'permission' => \Bale\Ikm\IkmPermissions::UPLOAD_IKM,
    ],
    [
        'id'         => 'ikm-settings',
        'group'      => 'ikm',
        'label'      => 'Pengaturan',
        'url'        => 'ikm/settings',
        'icon'       => 'settings-2',
        'permission' => \Bale\Ikm\IkmPermissions::MANAGE_SETTINGS,
    ],
];

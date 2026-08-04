<?php

use Bale\Ikm\IkmPermissions;

/**
 * Menu definisi untuk package bale/ikm (Tenant/CMS Layout).
 *
 * Grup 'ikm' berisi fitur Indeks Kepuasan Masyarakat.
 * Menggunakan type 'tenant' agar muncul di CMS sidebar via MenuRegistry.
 */
return [
    'type' => 'tenant',

    'groups' => [
        [
            'key' => 'ikm',
            'label' => 'IKM',
            'icon' => 'bar-chart-3',
            'items' => [
                [
                    'label' => 'Overview',
                    'url' => 'ikm/overview',
                    'icon' => 'bar-chart-3',
                    'permission' => IkmPermissions::VIEW_IKM,
                    'table' => 'ikm_batches',
                ],
                [
                    'label' => 'Riwayat Batch',
                    'url' => 'ikm/batches',
                    'icon' => 'list-ordered',
                    'permission' => IkmPermissions::VIEW_IKM,
                    'table' => 'ikm_batches',
                ],
                [
                    'label' => 'Import Data',
                    'url' => 'ikm/upload',
                    'icon' => 'upload-cloud',
                    'permission' => IkmPermissions::UPLOAD_IKM,
                    'table' => 'ikm_batches',
                ],
                [
                    'label' => 'Pengaturan',
                    'url' => 'ikm/settings',
                    'icon' => 'settings-2',
                    'permission' => IkmPermissions::MANAGE_SETTINGS,
                    'table' => 'ikm_settings',
                ],
                [
                    'label' => 'Trash',
                    'url' => 'ikm/batches-trash',
                    'icon' => 'trash-2',
                    'permission' => IkmPermissions::DELETE_IKM,
                    'table' => 'ikm_batches',
                ],
            ],
        ],
    ],
];

<?php

namespace Bale\Ikm;

/**
 * Daftar semua permission yang digunakan oleh package bale/ikm.
 *
 * Pembagian peran:
 *   - Admin Pusat : semua permission (ALL)
 *   - Admin OPD   : VIEW_IKM + UPLOAD_IKM + EXPORT_IKM
 */
class IkmPermissions
{
    /** Melihat data IKM (daftar batch & record) */
    const VIEW_IKM = 'ikm.view';

    /** Upload / import file Excel IKM */
    const UPLOAD_IKM = 'ikm.upload';

    /** Menghapus batch atau record IKM */
    const DELETE_IKM = 'ikm.delete';

    /** Menyetujui / finalisasi batch IKM */
    const APPROVE_IKM = 'ikm.approve';

    /** Mengelola konfigurasi kategori IKM (ikm_settings) */
    const MANAGE_SETTINGS = 'ikm.settings';

    /** Export data IKM ke Excel / PDF */
    const EXPORT_IKM = 'ikm.export';

    /**
     * Melihat data IKM seluruh OPD (admin pusat).
     * Admin OPD hanya dapat melihat data OPD-nya sendiri.
     */
    const VIEW_ALL_OPD = 'ikm.view_all_opd';

    /**
     * Daftar lengkap semua permission package ini.
     * Digunakan saat seeding dan assignment ke role admin pusat.
     */
    const ALL = [
        self::VIEW_IKM,
        self::UPLOAD_IKM,
        self::DELETE_IKM,
        self::APPROVE_IKM,
        self::MANAGE_SETTINGS,
        self::EXPORT_IKM,
        self::VIEW_ALL_OPD,
    ];

    /**
     * Permission untuk role admin OPD (terbatas).
     */
    const OPD = [
        self::VIEW_IKM,
        self::UPLOAD_IKM,
        self::EXPORT_IKM,
    ];
}

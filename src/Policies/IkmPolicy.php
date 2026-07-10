<?php

namespace Bale\Ikm\Policies;

use Bale\Ikm\IkmPermissions;
use Bale\Ikm\Models\IkmBatch;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Policy untuk model IkmBatch.
 * Menggunakan permission string dari IkmPermissions.
 */
class IkmPolicy
{
    /**
     * Bypass semua policy untuk user dengan role 'root' (super admin).
     * Return null = lanjut ke method policy berikutnya.
     */
    public function before(Authenticatable $user): ?bool
    {
        if (method_exists($user, 'hasRole') && $user->hasRole('root')) {
            return true;
        }

        return null;
    }

    /**
     * Boleh melihat daftar batch IKM.
     */
    public function viewAny(Authenticatable $user): bool
    {
        return $this->hasPermission($user, IkmPermissions::VIEW_IKM);
    }

    /**
     * Boleh melihat detail satu batch IKM.
     */
    public function view(Authenticatable $user, IkmBatch $batch): bool
    {
        return $this->hasPermission($user, IkmPermissions::VIEW_IKM);
    }

    /**
     * Boleh membuat / mengupload batch IKM baru.
     */
    public function create(Authenticatable $user): bool
    {
        return $this->hasPermission($user, IkmPermissions::UPLOAD_IKM);
    }

    /**
     * Boleh menghapus batch IKM.
     */
    public function delete(Authenticatable $user, IkmBatch $batch): bool
    {
        return $this->hasPermission($user, IkmPermissions::DELETE_IKM);
    }

    /**
     * Boleh memulihkan (restore) batch dari trash.
     */
    public function restore(Authenticatable $user, IkmBatch $batch): bool
    {
        return $this->hasPermission($user, IkmPermissions::DELETE_IKM);
    }

    /**
     * Boleh menghapus batch secara permanen (force delete).
     */
    public function forceDelete(Authenticatable $user, IkmBatch $batch): bool
    {
        return $this->hasPermission($user, IkmPermissions::DELETE_IKM);
    }

    /**
     * Boleh menyetujui / memfinalisasi batch IKM.
     * Hanya berlaku untuk batch yang masih berstatus 'selesai' (belum approved).
     */
    public function approve(Authenticatable $user, IkmBatch $batch): bool
    {
        return $this->hasPermission($user, IkmPermissions::APPROVE_IKM)
            && $batch->status === 'selesai'
            && $batch->approved_at === null;
    }

    // ─────────────────────────────────────────────
    // Helper
    // ─────────────────────────────────────────────

    /**
     * Cek permission via Spatie atau fallback ke can() bawaan Laravel.
     */
    protected function hasPermission(Authenticatable $user, string $permission): bool
    {
        // Spatie Permission
        if (method_exists($user, 'hasPermissionTo')) {
            return $user->hasPermissionTo($permission);
        }

        // Fallback: Laravel default Gate
        return $user->can($permission);
    }
}

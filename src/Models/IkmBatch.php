<?php

namespace Bale\Ikm\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Bale\Cms\Traits\UsesTenantConnection;

class IkmBatch extends Model
{
    use UsesTenantConnection;
    use HasUuids;
    use SoftDeletes;

    protected $table = 'ikm_batches';

    protected $fillable = [
        'nama',
        'triwulan',
        'tahun',
        'nama_file',
        'path_file',
        'jumlah_opd',
        'status',
        'catatan',
        'uploaded_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'triwulan' => 'integer',
        'tahun' => 'integer',
        'jumlah_opd' => 'integer',
        'approved_at' => 'datetime',
    ];

    protected static function booted()
    {
        // Soft delete: ikut soft-delete semua records
        static::deleting(function (IkmBatch $batch) {
            if (!$batch->isForceDeleting()) {
                $batch->records()->delete();
            }
        });

        // Force delete (hapus permanen): hapus semua records secara permanen
        static::forceDeleting(function (IkmBatch $batch) {
            $batch->records()->withTrashed()->forceDelete();
        });
    }

    // ─────────────────────────────────────────────
    // Accessors
    // ─────────────────────────────────────────────

    /**
     * Label periode: "Triwulan 1 2025"
     */
    protected function labelTriwulan(): Attribute
    {
        return Attribute::make(
            get: fn() => "Triwulan {$this->triwulan} {$this->tahun}",
        );
    }

    /**
     * Label badge berdasarkan status.
     *
     * @return array{label: string, color: string}
     */
    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->status === 'selesai' && $this->approved_at) {
                    return ['label' => 'Disetujui', 'color' => 'emerald'];
                }

                return match ($this->status) {
                    'draft' => ['label' => 'Draft', 'color' => 'gray'],
                    'diproses' => ['label' => 'Diproses', 'color' => 'amber'],
                    'selesai' => ['label' => 'Selesai', 'color' => 'blue'],
                    'gagal' => ['label' => 'Gagal', 'color' => 'red'],
                    default => ['label' => ucfirst($this->status), 'color' => 'gray'],
                };
            }
        );
    }

    // ─────────────────────────────────────────────
    // Relations
    // ─────────────────────────────────────────────

    /**
     * Semua record IKM yang tergabung dalam batch ini.
     */
    public function records()
    {
        return $this->hasMany(IkmRecord::class, 'ikm_batch_id');
    }

    /**
     * User yang mengupload batch ini.
     */
    public function uploadedBy()
    {
        return $this->belongsTo(
            config('auth.providers.users.model', \App\Models\User::class),
            'uploaded_by'
        );
    }

    /**
     * User yang menyetujui batch ini.
     */
    public function approvedBy()
    {
        return $this->belongsTo(
            config('auth.providers.users.model', \App\Models\User::class),
            'approved_by'
        );
    }

    // ─────────────────────────────────────────────
    // Scopes
    // ─────────────────────────────────────────────

    /**
     * Filter berdasarkan periode (triwulan & tahun).
     */
    public function scopeByPeriode($query, int $triwulan, int $tahun)
    {
        return $query->where('triwulan', $triwulan)->where('tahun', $tahun);
    }

    /**
     * Filter hanya batch dengan status 'selesai'.
     */
    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }
}

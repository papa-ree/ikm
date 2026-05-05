<?php

namespace Bale\Ikm\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Bale\Cms\Traits\UsesTenantConnection;

class IkmRecord extends Model
{
    use UsesTenantConnection;
    use HasUuids;
    use SoftDeletes;

    protected $table = 'ikm_records';

    protected $fillable = [
        'ikm_batch_id',
        'nama_opd',
        'triwulan',
        'tahun',
        'populasi',
        'sampel',
        // NRR per unsur
        'nrr_u1',
        'nrr_u2',
        'nrr_u3',
        'nrr_u4',
        'nrr_u5',
        'nrr_u6',
        'nrr_u7',
        'nrr_u8',
        'nrr_u9',
        // Demografi jenis kelamin
        'dem_laki',
        'dem_perempuan',
        // Demografi pendidikan
        'dem_sd',
        'dem_sltp',
        'dem_slta',
        'dem_diploma',
        'dem_s1',
        'dem_s2',
        'dem_s3',
        // Demografi pekerjaan
        'dem_pns',
        'dem_tni_polri',
        'dem_swasta',
        'dem_wiraswasta',
        'dem_pelajar',
        'dem_petani',
        'dem_lainnya',
        // Hasil kalkulasi
        'nrr_tertimbang',
        'nilai_ikm',
        'kategori',
        'label_kategori',
    ];

    protected $casts = [
        'triwulan' => 'integer',
        'tahun' => 'integer',
        'populasi' => 'integer',
        'sampel' => 'integer',
        // NRR per unsur → float
        'nrr_u1' => 'float',
        'nrr_u2' => 'float',
        'nrr_u3' => 'float',
        'nrr_u4' => 'float',
        'nrr_u5' => 'float',
        'nrr_u6' => 'float',
        'nrr_u7' => 'float',
        'nrr_u8' => 'float',
        'nrr_u9' => 'float',
        // Hasil kalkulasi → float
        'nrr_tertimbang' => 'float',
        'nilai_ikm' => 'float',
    ];

    // ─────────────────────────────────────────────
    // Accessors
    // ─────────────────────────────────────────────

    /**
     * Detail per unsur dalam format asosiatif [label => nilai].
     *
     * Contoh output:
     * [
     *   'Persyaratan' => 3.25,
     *   'Sistem, Mekanisme, dan Prosedur' => 3.10,
     *   ...
     * ]
     */
    protected function unsurDetail(): Attribute
    {
        return Attribute::make(
            get: function () {
                $unsurConfig = config('ikm.unsur', []);
                $result = [];

                foreach ($unsurConfig as $key => $label) {
                    $column = 'nrr_' . $key; // nrr_u1, nrr_u2, ...
                    $result[$label] = $this->$column;
                }

                return $result;
            }
        );
    }

    /**
     * Ringkasan data demografi dalam struktur terstruktur.
     *
     * @return array{jenis_kelamin: array, pendidikan: array, pekerjaan: array}
     */
    protected function demografiRingkas(): Attribute
    {
        return Attribute::make(
            get: fn() => [
                'jenis_kelamin' => [
                    'Laki-laki' => $this->dem_laki,
                    'Perempuan' => $this->dem_perempuan,
                ],
                'pendidikan' => [
                    'SD' => $this->dem_sd,
                    'SLTP' => $this->dem_sltp,
                    'SLTA' => $this->dem_slta,
                    'Diploma' => $this->dem_diploma,
                    'S1' => $this->dem_s1,
                    'S2' => $this->dem_s2,
                    'S3' => $this->dem_s3,
                ],
                'pekerjaan' => [
                    'PNS/ASN' => $this->dem_pns,
                    'TNI/Polri' => $this->dem_tni_polri,
                    'Swasta' => $this->dem_swasta,
                    'Wiraswasta' => $this->dem_wiraswasta,
                    'Pelajar' => $this->dem_pelajar,
                    'Petani' => $this->dem_petani,
                    'Lainnya' => $this->dem_lainnya,
                ],
            ]
        );
    }

    // ─────────────────────────────────────────────
    // Relations
    // ─────────────────────────────────────────────

    /**
     * Batch tempat record ini berasal.
     */
    public function batch()
    {
        return $this->belongsTo(IkmBatch::class, 'ikm_batch_id');
    }

    // ─────────────────────────────────────────────
    // Scopes
    // ─────────────────────────────────────────────

    /**
     * Filter berdasarkan batch ID.
     */
    public function scopeByBatch($query, string $batchId)
    {
        return $query->where('ikm_batch_id', $batchId);
    }

    /**
     * Filter berdasarkan nama OPD (case-insensitive).
     */
    public function scopeByOPD($query, string $namaOpd)
    {
        return $query->where('nama_opd', 'like', '%' . $namaOpd . '%');
    }
}

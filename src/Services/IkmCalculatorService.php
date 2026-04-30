<?php

namespace Bale\Ikm\Services;

use Bale\Ikm\Models\IkmBatch;
use Bale\Ikm\Models\IkmRecord;
use Bale\Ikm\Models\IkmSetting;

/**
 * Service kalkulasi IKM sesuai PermenPAN-RB No. 14 Tahun 2017.
 *
 * Rumus:
 *   NRR Tertimbang = Σ(NRR_u1..u9) × bobot
 *   Nilai IKM      = NRR Tertimbang × 25
 *   Kategori       = ditentukan dari tabel batas kategori
 */
class IkmCalculatorService
{
    /**
     * Bobot tertimbang per unsur (9 unsur × 0.111 ≈ 1.000).
     * Dibaca dari config, bisa dioverride.
     */
    protected float $bobot;

    public function __construct()
    {
        $this->bobot = (float) config('ikm.bobot', 0.111);
    }

    // ─────────────────────────────────────────────
    // Public API
    // ─────────────────────────────────────────────

    /**
     * Hitung NRR tertimbang, nilai IKM, dan kategori dari satu baris data OPD.
     *
     * @param  array  $data  Raw data dari satu baris Excel (key → value)
     * @return array         Data input + hasil kalkulasi
     */
    public function calculate(array $data): array
    {
        // 1. Kumpulkan nilai NRR per unsur (skip null)
        $nrrValues = [];
        for ($i = 1; $i <= 9; $i++) {
            $key = "nrr_u{$i}";
            $val = isset($data[$key]) && $data[$key] !== '' && $data[$key] !== null
                ? (float) $data[$key]
                : null;
            $nrrValues[$key] = $val;
        }

        // 2. Hitung NRR Tertimbang: Σ(nrr_u1..u9) × bobot
        //    Unsur yang null tidak ikut dijumlahkan (dianggap tidak diisi)
        $nrrTertimbang = null;
        $validValues = array_filter($nrrValues, fn($v) => $v !== null);

        if (count($validValues) > 0) {
            $nrrTertimbang = round(array_sum($validValues) * $this->bobot, 4);
        }

        // 3. Hitung Nilai IKM: NRR Tertimbang × 25
        $nilaiIkm = $nrrTertimbang !== null
            ? round($nrrTertimbang * 25, 2)
            : null;

        // 4. Tentukan kategori
        $kategoriResult = $nilaiIkm !== null
            ? $this->determineKategori($nilaiIkm)
            : ['kode' => null, 'label' => null];

        return array_merge($data, $nrrValues, [
            'nrr_tertimbang' => $nrrTertimbang,
            'nilai_ikm'      => $nilaiIkm,
            'kategori'       => $kategoriResult['kode'],
            'label_kategori' => $kategoriResult['label'],
        ]);
    }

    /**
     * Hitung ulang semua records dalam satu batch dan update ke DB.
     *
     * @param  IkmBatch  $batch
     */
    public function recalculateBatch(IkmBatch $batch): void
    {
        $batch->records()->each(function (IkmRecord $record) {
            $data   = $record->toArray();
            $result = $this->calculate($data);

            $record->update([
                'nrr_tertimbang' => $result['nrr_tertimbang'],
                'nilai_ikm'      => $result['nilai_ikm'],
                'kategori'       => $result['kategori'],
                'label_kategori' => $result['label_kategori'],
            ]);
        });
    }

    /**
     * Tentukan kategori berdasarkan nilai IKM.
     *
     * Prioritas sumber batas:
     *   1. IkmSetting (DB) — key: kategori_a_min, kategori_a_max, kategori_a_label, dsb.
     *   2. config('ikm.kategori') — fallback statis
     *
     * @param  float  $nilaiIkm  Nilai IKM (0–100)
     * @return array{kode: string|null, label: string|null}
     */
    public function determineKategori(float $nilaiIkm): array
    {
        $table = $this->buildKategoriTable();

        foreach ($table as $kode => $batas) {
            if ($nilaiIkm >= $batas['min'] && $nilaiIkm <= $batas['max']) {
                return [
                    'kode'  => $kode,
                    'label' => $batas['label'],
                ];
            }
        }

        // Nilai di luar rentang yang diketahui
        return ['kode' => null, 'label' => null];
    }

    // ─────────────────────────────────────────────
    // Internal Helpers
    // ─────────────────────────────────────────────

    /**
     * Bangun tabel kategori dengan mengutamakan nilai dari IkmSetting (DB),
     * fallback ke config('ikm.kategori').
     *
     * @return array<string, array{label: string, min: float, max: float}>
     */
    protected function buildKategoriTable(): array
    {
        $configTable = config('ikm.kategori', []);
        $result      = [];

        foreach ($configTable as $kode => $defaults) {
            $kodeLC = strtolower($kode); // 'A' → 'a'

            $min   = (float) IkmSetting::get("kategori_{$kodeLC}_min",   $defaults['min']);
            $max   = (float) IkmSetting::get("kategori_{$kodeLC}_max",   $defaults['max']);
            $label =         IkmSetting::get("kategori_{$kodeLC}_label", $defaults['label']);

            $result[$kode] = compact('min', 'max', 'label');
        }

        return $result;
    }
}

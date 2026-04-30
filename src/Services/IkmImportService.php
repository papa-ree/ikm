<?php

namespace Bale\Ikm\Services;

use Bale\Ikm\Models\IkmBatch;
use Bale\Ikm\Models\IkmRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Throwable;

/**
 * Service import data IKM dari file Excel (format PermenPAN-RB 14/2017).
 *
 * Struktur file Excel yang diharapkan:
 *   Row 1 : Header grup  (NO, OPP, NILAI RATA-RATA NRR PER UNSUR, DEMOGRAFI, ...)
 *   Row 2 : Sub-header   (U1..U9, L, P, SD, SLTP, SLTA, DIPLOMA, S1, S2, S3,
 *                         PNS, TNI/Polri, Swasta, Wiraswasta, Pelajar, Petani,
 *                         Lainnya, POPULASI, SAMPEL, NRR TERTIMBANG, NILAI IKM, KATEGORI)
 *   Row 3+: Data OPD
 */
class IkmImportService
{
    /**
     * Pemetaan index kolom (0-based) → key array data.
     * Sesuai urutan sub-header PermenPAN-RB.
     *
     *  0  = NO         (dipakai untuk skip/stop, tidak diimport)
     *  1  = OPP/Nama OPD
     *  2  = U1 … 10 = U9
     *  11 = L (Laki)   12 = P (Perempuan)
     *  13..19 = SD, SLTP, SLTA, DIPLOMA, S1, S2, S3
     *  20..26 = PNS, TNI/POLRI, SWASTA, WIRASWASTA, PELAJAR, PETANI, LAINNYA
     *  27 = POPULASI   28 = SAMPEL
     *  29 = NRR TERTIMBANG (skip — kita hitung ulang)
     *  30 = NILAI IKM  (skip)
     *  31 = KATEGORI   (skip)
     */
    protected const COLUMN_MAP = [
        1  => 'nama_opd',
        2  => 'nrr_u1',
        3  => 'nrr_u2',
        4  => 'nrr_u3',
        5  => 'nrr_u4',
        6  => 'nrr_u5',
        7  => 'nrr_u6',
        8  => 'nrr_u7',
        9  => 'nrr_u8',
        10 => 'nrr_u9',
        11 => 'dem_laki',
        12 => 'dem_perempuan',
        13 => 'dem_sd',
        14 => 'dem_sltp',
        15 => 'dem_slta',
        16 => 'dem_diploma',
        17 => 'dem_s1',
        18 => 'dem_s2',
        19 => 'dem_s3',
        20 => 'dem_pns',
        21 => 'dem_tni_polri',
        22 => 'dem_swasta',
        23 => 'dem_wiraswasta',
        24 => 'dem_pelajar',
        25 => 'dem_petani',
        26 => 'dem_lainnya',
        27 => 'populasi',
        28 => 'sampel',
    ];

    /** Kolom NRR (nilai float, nullable) */
    protected const NRR_COLUMNS = [
        'nrr_u1','nrr_u2','nrr_u3','nrr_u4','nrr_u5',
        'nrr_u6','nrr_u7','nrr_u8','nrr_u9',
    ];

    /** Kolom demografi (nilai integer, default 0) */
    protected const DEM_COLUMNS = [
        'dem_laki','dem_perempuan',
        'dem_sd','dem_sltp','dem_slta','dem_diploma','dem_s1','dem_s2','dem_s3',
        'dem_pns','dem_tni_polri','dem_swasta','dem_wiraswasta',
        'dem_pelajar','dem_petani','dem_lainnya',
    ];

    public function __construct(
        protected IkmCalculatorService $calculator,
    ) {}

    // ─────────────────────────────────────────────
    // Public API
    // ─────────────────────────────────────────────

    /**
     * Proses import file Excel ke dalam database.
     *
     * @param  IkmBatch  $batch  Batch yang sudah tersimpan dengan path_file terisi
     * @return ImportResult
     */
    public function import(IkmBatch $batch): ImportResult
    {
        // Tandai batch sedang diproses
        $batch->update(['status' => 'diproses']);

        $success = 0;
        $failed  = 0;
        $errors  = [];
        $rows    = [];

        try {
            $filePath = storage_path($batch->path_file);

            if (! file_exists($filePath)) {
                throw new \RuntimeException("File tidak ditemukan: {$filePath}");
            }

            $spreadsheet = IOFactory::load($filePath);
            $sheet       = $spreadsheet->getActiveSheet();

            $dataRows = $this->extractDataRows($sheet);

            foreach ($dataRows as $rowIndex => $rawRow) {
                $rowNumber = $rowIndex + 3; // row 1 & 2 adalah header

                try {
                    $rowData = $this->mapRowToData($rawRow);
                    $result  = $this->calculator->calculate($rowData);

                    $rows[] = $this->prepareRecord($result, $batch);
                    $success++;
                } catch (Throwable $e) {
                    $failed++;
                    $opdName = $rawRow[1] ?? '-';
                    $errors[] = [
                        'row'     => $rowNumber,
                        'opd'     => is_string($opdName) ? $opdName : (string) $opdName,
                        'message' => $e->getMessage(),
                    ];

                    Log::warning("[IKM Import] Baris {$rowNumber} gagal: " . $e->getMessage(), [
                        'batch_id' => $batch->id,
                        'raw_row'  => $rawRow,
                    ]);
                }
            }

            // Bulk insert semua record yang berhasil
            if (! empty($rows)) {
                IkmRecord::insert($rows);
            }

            $importResult = new ImportResult($success, $failed, $errors);

            // Update status batch
            $batch->update([
                'jumlah_opd' => $success,
                'status'     => $failed === 0 ? 'selesai' : 'selesai',
                'catatan'    => $importResult->summary(),
            ]);

        } catch (Throwable $e) {
            $importResult = new ImportResult(0, 1, [[
                'row'     => 0,
                'opd'     => '-',
                'message' => 'Fatal: ' . $e->getMessage(),
            ]]);

            $batch->update([
                'status'  => 'gagal',
                'catatan' => $importResult->summary(),
            ]);

            Log::error('[IKM Import] Fatal error: ' . $e->getMessage(), [
                'batch_id' => $batch->id,
            ]);
        }

        return $importResult;
    }

    // ─────────────────────────────────────────────
    // Internal — Parsing
    // ─────────────────────────────────────────────

    /**
     * Ekstrak baris data dari worksheet (mulai row 3, skip header 1 & 2).
     * Stop jika kolom NO kosong atau baris rekapitulasi ("Jumlah").
     *
     * @return array[]  Array of raw rows (indexed by column index 0-based)
     */
    protected function extractDataRows(Worksheet $sheet): array
    {
        $data       = [];
        $highestRow = $sheet->getHighestDataRow();

        for ($rowNum = 3; $rowNum <= $highestRow; $rowNum++) {
            $no  = $sheet->getCellByColumnAndRow(1, $rowNum)->getValue(); // kolom A (1-based)
            $opd = $sheet->getCellByColumnAndRow(2, $rowNum)->getValue(); // kolom B (1-based)

            // Stop: baris rekapitulasi (NO = null/kosong dan OPD mengandung "Jumlah")
            if (empty($no) && is_string($opd) && Str::contains(strtolower($opd), 'jumlah')) {
                break;
            }

            // Skip: kolom NO kosong atau bukan angka
            if (empty($no) || ! is_numeric($no)) {
                continue;
            }

            // Ambil semua kolom yang diperlukan (0-based untuk COLUMN_MAP)
            $row = [];
            $maxCol = max(array_keys(self::COLUMN_MAP));

            for ($col = 0; $col <= $maxCol; $col++) {
                // PhpSpreadsheet column index adalah 1-based
                $row[$col] = $sheet->getCellByColumnAndRow($col + 1, $rowNum)->getValue();
            }

            $data[] = $row;
        }

        return $data;
    }

    /**
     * Petakan raw row (indexed by kolom) ke array key-value sesuai model.
     *
     * @param  array  $rawRow  Raw row dari spreadsheet (0-based index)
     * @return array           Data siap dikirim ke calculator
     */
    protected function mapRowToData(array $rawRow): array
    {
        $data = [];

        foreach (self::COLUMN_MAP as $colIndex => $fieldKey) {
            $rawValue = $rawRow[$colIndex] ?? null;

            if (in_array($fieldKey, self::NRR_COLUMNS, true)) {
                // NRR: float atau null jika kosong
                $data[$fieldKey] = ($rawValue !== null && $rawValue !== '')
                    ? (float) $rawValue
                    : null;

            } elseif (in_array($fieldKey, self::DEM_COLUMNS, true)) {
                // Demografi: integer, default 0
                $data[$fieldKey] = ($rawValue !== null && $rawValue !== '')
                    ? (int) $rawValue
                    : 0;

            } elseif (in_array($fieldKey, ['populasi', 'sampel'], true)) {
                // Populasi & sampel: integer atau null
                $data[$fieldKey] = ($rawValue !== null && $rawValue !== '')
                    ? (int) $rawValue
                    : null;

            } else {
                // String (nama_opd, dsb.)
                $data[$fieldKey] = $rawValue !== null ? trim((string) $rawValue) : null;
            }
        }

        return $data;
    }

    /**
     * Siapkan array record untuk bulk insert ke tabel ikm_records.
     *
     * @param  array     $result  Hasil calculate()
     * @param  IkmBatch  $batch
     * @return array
     */
    protected function prepareRecord(array $result, IkmBatch $batch): array
    {
        $now = now();

        return [
            'id'             => (string) Str::uuid(),
            'ikm_batch_id'   => $batch->id,
            'nama_opd'       => $result['nama_opd'] ?? null,
            'triwulan'       => $batch->triwulan,
            'tahun'          => $batch->tahun,
            'populasi'       => $result['populasi'] ?? null,
            'sampel'         => $result['sampel'] ?? null,
            // NRR per unsur
            'nrr_u1'         => $result['nrr_u1'] ?? null,
            'nrr_u2'         => $result['nrr_u2'] ?? null,
            'nrr_u3'         => $result['nrr_u3'] ?? null,
            'nrr_u4'         => $result['nrr_u4'] ?? null,
            'nrr_u5'         => $result['nrr_u5'] ?? null,
            'nrr_u6'         => $result['nrr_u6'] ?? null,
            'nrr_u7'         => $result['nrr_u7'] ?? null,
            'nrr_u8'         => $result['nrr_u8'] ?? null,
            'nrr_u9'         => $result['nrr_u9'] ?? null,
            // Demografi
            'dem_laki'       => $result['dem_laki'] ?? 0,
            'dem_perempuan'  => $result['dem_perempuan'] ?? 0,
            'dem_sd'         => $result['dem_sd'] ?? 0,
            'dem_sltp'       => $result['dem_sltp'] ?? 0,
            'dem_slta'       => $result['dem_slta'] ?? 0,
            'dem_diploma'    => $result['dem_diploma'] ?? 0,
            'dem_s1'         => $result['dem_s1'] ?? 0,
            'dem_s2'         => $result['dem_s2'] ?? 0,
            'dem_s3'         => $result['dem_s3'] ?? 0,
            'dem_pns'        => $result['dem_pns'] ?? 0,
            'dem_tni_polri'  => $result['dem_tni_polri'] ?? 0,
            'dem_swasta'     => $result['dem_swasta'] ?? 0,
            'dem_wiraswasta' => $result['dem_wiraswasta'] ?? 0,
            'dem_pelajar'    => $result['dem_pelajar'] ?? 0,
            'dem_petani'     => $result['dem_petani'] ?? 0,
            'dem_lainnya'    => $result['dem_lainnya'] ?? 0,
            // Hasil kalkulasi
            'nrr_tertimbang' => $result['nrr_tertimbang'] ?? null,
            'nilai_ikm'      => $result['nilai_ikm'] ?? null,
            'kategori'       => $result['kategori'] ?? null,
            'label_kategori' => $result['label_kategori'] ?? null,
            // Timestamps
            'created_at'     => $now,
            'updated_at'     => $now,
        ];
    }
}

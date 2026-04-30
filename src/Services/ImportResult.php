<?php

namespace Bale\Ikm\Services;

/**
 * Value object hasil proses import IKM dari file Excel.
 * Immutable — dibuat sekali di akhir proses import.
 */
readonly class ImportResult
{
    /**
     * @param  int    $success  Jumlah baris OPD yang berhasil diimport
     * @param  int    $failed   Jumlah baris OPD yang gagal diimport
     * @param  array  $errors   Detail error per baris: ['row' => int, 'opd' => string, 'message' => string]
     */
    public function __construct(
        public int $success,
        public int $failed,
        public array $errors = [],
    ) {}

    /**
     * Apakah ada baris yang gagal diimport?
     */
    public function hasErrors(): bool
    {
        return $this->failed > 0;
    }

    /**
     * Total baris yang diproses (berhasil + gagal).
     */
    public function total(): int
    {
        return $this->success + $this->failed;
    }

    /**
     * Ringkasan teks untuk dicatat di batch->catatan.
     */
    public function summary(): string
    {
        $lines = ["Import selesai: {$this->success} berhasil, {$this->failed} gagal."];

        foreach ($this->errors as $err) {
            $baris = $err['row'] ?? '?';
            $opd   = $err['opd'] ?? '-';
            $msg   = $err['message'] ?? 'Unknown error';
            $lines[] = "  [Baris {$baris}] {$opd}: {$msg}";
        }

        return implode("\n", $lines);
    }
}

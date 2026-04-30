<?php

namespace Bale\Ikm\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class IkmSetting extends Model
{
    use HasUuids;

    protected $table = 'ikm_settings';

    protected $fillable = [
        'key',
        'value',
        'label',
        'group',
    ];

    // ─────────────────────────────────────────────
    // Static Helper Methods
    // ─────────────────────────────────────────────

    /**
     * Ambil nilai setting berdasarkan key.
     * Fallback ke config('ikm') jika tidak ditemukan di DB.
     *
     * Contoh: IkmSetting::get('kategori_a_min', 88.31)
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        if ($setting) {
            return $setting->value;
        }

        // Fallback ke config Laravel jika ada
        return config('ikm.' . $key, $default);
    }

    /**
     * Simpan atau update nilai setting.
     * Update jika key sudah ada, buat baru jika belum.
     *
     * Contoh: IkmSetting::set('kategori_a_min', '88.31')
     */
    public static function set(string $key, mixed $value): static
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value],
        );
    }

    /**
     * Ambil semua setting dalam satu group, di-key-kan per key.
     *
     * Contoh: IkmSetting::allByGroup('kategori')
     *
     * @return \Illuminate\Support\Collection<string, static>
     */
    public static function allByGroup(string $group): Collection
    {
        return static::where('group', $group)
            ->get()
            ->keyBy('key');
    }
}

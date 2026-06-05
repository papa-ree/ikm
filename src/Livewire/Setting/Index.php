<?php

namespace Bale\Ikm\Livewire\Setting;

use Bale\Ikm\IkmPermissions;
use Bale\Ikm\Models\IkmSetting;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Bale\Cms\Services\TenantConnectionService;

#[Layout('cms::layouts.app')]
#[Title('Pengaturan Kategori IKM')]
class Index extends Component
{
    /** @var array<string, array{label: string, min: float|string, max: float|string}> */
    public array $kategori = [];

    public function mount(): void
    {
        TenantConnectionService::ensureActive();
        abort_unless(Auth::user()?->can(IkmPermissions::MANAGE_SETTINGS), 403);
        $this->loadSettings();
    }

    public function save(): void
    {
        TenantConnectionService::ensureActive();
        abort_unless(Auth::user()?->can(IkmPermissions::MANAGE_SETTINGS), 403);

        $this->validate($this->rules());
        $this->validateNoOverlap();

        foreach ($this->kategori as $kode => $values) {
            $kodeLC = strtolower($kode);
            IkmSetting::set("kategori_{$kodeLC}_min", $values['min']);
            IkmSetting::set("kategori_{$kodeLC}_max", $values['max']);
            IkmSetting::set("kategori_{$kodeLC}_label", $values['label']);
        }

        session()->flash('success', 'Pengaturan kategori IKM berhasil disimpan.');
    }

    public function resetToDefault(): void
    {
        TenantConnectionService::ensureActive();
        abort_unless(Auth::user()?->can(IkmPermissions::MANAGE_SETTINGS), 403);

        $defaults = config('ikm.kategori', []);

        foreach ($defaults as $kode => $values) {
            $this->kategori[$kode] = [
                'label' => $values['label'],
                'min' => $values['min'],
                'max' => $values['max'],
            ];
        }

        $this->save();
        session()->flash('success', 'Konfigurasi direset ke default PermenPAN-RB 14/2017.');
    }

    public function render()
    {
        return view('ikm::livewire.setting.index', [
            'kodeList' => array_keys($this->kategori),
        ]);
    }

    // ─────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────

    protected function loadSettings(): void
    {
        $defaults = config('ikm.kategori', []);

        foreach ($defaults as $kode => $defaults) {
            $kodeLC = strtolower($kode);
            $this->kategori[$kode] = [
                'label' => IkmSetting::get("kategori_{$kodeLC}_label", $defaults['label']),
                'min' => IkmSetting::get("kategori_{$kodeLC}_min", $defaults['min']),
                'max' => IkmSetting::get("kategori_{$kodeLC}_max", $defaults['max']),
            ];
        }
    }

    protected function rules(): array
    {
        $rules = [];
        foreach (array_keys($this->kategori) as $kode) {
            $rules["kategori.{$kode}.label"] = 'required|string|max:50';
            $rules["kategori.{$kode}.min"] = 'required|numeric|min:0|max:100';
            $rules["kategori.{$kode}.max"] = 'required|numeric|min:0|max:100';
        }
        return $rules;
    }

    /**
     * Pastikan tidak ada overlap antar rentang kategori.
     * Contoh: max A harus >= min A, tidak overlap dengan B, dst.
     */
    protected function validateNoOverlap(): void
    {
        foreach ($this->kategori as $kode => $values) {
            if ((float) $values['min'] > (float) $values['max']) {
                $this->addError(
                    "kategori.{$kode}.min",
                    "Nilai minimum kategori {$kode} tidak boleh lebih besar dari maksimum."
                );
            }
        }

        if ($this->getErrorBag()->isNotEmpty()) {
            $this->dispatch('validation-failed');
            return;
        }
    }
}

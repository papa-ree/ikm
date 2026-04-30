<?php

namespace Bale\Ikm\Livewire;

use Bale\Ikm\IkmPermissions;
use Bale\Ikm\Models\IkmRecord;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('ikm::layouts.app')]
#[Title('Dashboard IKM')]
class Dashboard extends Component
{
    public int $tahun;
    public int $triwulan;

    public function mount(): void
    {
        $this->tahun     = (int) now()->format('Y');
        $this->triwulan  = $this->currentTriwulan();
    }

    public function render()
    {
        $baseQuery = IkmRecord::query()
            ->with('batch')
            ->whereHas('batch', fn ($q) => $q->where('status', 'selesai'))
            ->where('triwulan', $this->triwulan)
            ->where('tahun',    $this->tahun);

        // Jika user tidak punya VIEW_ALL_OPD, filter hanya OPD milik user
        if (! Auth::user()?->can(IkmPermissions::VIEW_ALL_OPD)) {
            $opdUser = config('ikm.opd_user_field')
                ? Auth::user()->{config('ikm.opd_user_field')}
                : null;

            if ($opdUser) {
                $baseQuery->where('nama_opd', $opdUser);
            }
        }

        $records = $baseQuery->get();

        // ── Metrik ──────────────────────────────────
        $rataRata  = $records->avg('nilai_ikm');
        $jumlahA   = $records->where('kategori', 'A')->count();
        $jumlahB   = $records->where('kategori', 'B')->count();
        $dibawahB  = $records->whereIn('kategori', ['C', 'D'])->count();

        // ── Top & Bottom 5 ──────────────────────────
        $sorted  = $records->sortByDesc('nilai_ikm')->values();
        $top5    = $sorted->take(5);
        $bottom5 = $sorted->reverse()->take(5)->values();

        // ── Opsi Filter ─────────────────────────────
        $tahunOptions = range(2020, (int) now()->format('Y'));

        return view('ikm::livewire.dashboard', compact(
            'records', 'rataRata', 'jumlahA', 'jumlahB', 'dibawahB',
            'top5', 'bottom5', 'tahunOptions',
        ));
    }

    // ─────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────

    protected function currentTriwulan(): int
    {
        $month = (int) now()->format('n');
        return (int) ceil($month / 3);
    }
}

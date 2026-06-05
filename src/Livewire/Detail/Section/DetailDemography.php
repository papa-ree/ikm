<?php

namespace Bale\Ikm\Livewire\Detail\Section;

use Livewire\Component;

class DetailDemography extends Component
{
    public array $demJK         = [];
    public array $demPendidikan = [];
    public array $demPekerjaan  = [];
    public int   $totalSampel   = 0;

    public function mount(array $demJK, array $demPendidikan, array $demPekerjaan, int $totalSampel): void
    {
        $this->demJK         = $demJK;
        $this->demPendidikan = $demPendidikan;
        $this->demPekerjaan  = $demPekerjaan;
        $this->totalSampel   = $totalSampel;
    }

    public function render()
    {
        return view('ikm::livewire.detail.section.detail-demography');
    }
}

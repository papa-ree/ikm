<?php

namespace Bale\Ikm\Livewire\Detail\Section;

use Livewire\Component;

class DetailTable extends Component
{
    public string $batchId;

    public function mount(string $batchId): void
    {
        $this->batchId = $batchId;
    }

    public function render()
    {
        return view('ikm::livewire.detail.section.detail-table');
    }
}

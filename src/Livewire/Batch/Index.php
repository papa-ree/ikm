<?php

namespace Bale\Ikm\Livewire\Batch;

use Livewire\Attributes\{Layout, Title};
use Livewire\Component;

#[Layout('cms::layouts.app')]
#[Title('Bale | IKM Batches')]
class Index extends Component
{
    public function render()
    {
        return view('ikm::livewire.batch.index');
    }
}

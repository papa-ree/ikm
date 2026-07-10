<?php

namespace Bale\Ikm\Livewire\Trash;

use Livewire\Attributes\{Layout, Title};
use Livewire\Component;

#[Layout('cms::layouts.app')]
#[Title('Bale | IKM Trash')]
class Index extends Component
{
    public function render()
    {
        return view('ikm::livewire.trash.index');
    }
}

<?php

declare(strict_types=1);

namespace Domains\Test\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('test::layouts.domain')]
#[Title('Test')]
class Home extends Component
{
    public function render()
    {
        return view('test::livewire.home');
    }
}

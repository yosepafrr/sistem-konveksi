<?php

namespace App\Livewire\Components;

use Livewire\Component;

class SideLink extends Component
{
    public $href;
    public $icon;
    public $label;
    public $active = false;

    public function mount($href = '#', $icon = '', $label = '', $active = false)
    {
        $this->href = $href;
        $this->icon = $icon;
        $this->label = $label;
        $this->active = $active;
    }

    public function render()
    {
        return view('livewire.components.side-link');
    }
}

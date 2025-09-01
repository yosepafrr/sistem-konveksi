<?php

namespace App\Livewire;

use App\Models\Store;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app', ['title' => 'Store List'])]


class StoreList extends Component
{
    public function render()
    {
        return view('livewire.store-list', [
            'stores' => Store::latest()->get(),
        ]);
    }
}

<?php

namespace App\Livewire;

use App\Models\Item;
use Livewire\Component;
use Livewire\Attributes\Layout;


#[Layout('layouts.app', ['title' => 'Store List'])]
class ProductList extends Component
{
    public function render()
    {
        return view('livewire.product-list', [
            'products' => Item::latest()->get(),
        ]);
    }
}

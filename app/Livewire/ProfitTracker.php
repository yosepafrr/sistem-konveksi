<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app', ['title' => 'Profit Tracker'])]

class ProfitTracker extends Component
{
    public $orders = [];
    public $stores = [];

    public function mount()
    {
        $this->stores = Auth::user()->stores;
    }
    public function render()
    {
        return view('livewire.profit-tracker');
    }
}

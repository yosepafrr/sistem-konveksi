<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;
use App\Models\Store;

#[Layout('layouts.app', ['title' => 'Profit Tracker'])]

class ProfitTracker extends Component
{
    public $orders;
    public $stores;

    public $totalOrderSellingPrice;

    public $totalEscrowAmount;

    public function mount()
    {
        $this->stores = Auth::user()->stores;
        $this->orders = $this->stores->first()?->orders ?? collect();
        $this->totalOrderSellingPrice = $this->orders->sum('order_selling_price');
        $this->totalEscrowAmount = $this->orders->sum('escrow_amount');
    }
    public function render()
    {
        return view('livewire.profit-tracker');
    }
}

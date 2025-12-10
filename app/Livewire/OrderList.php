<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app', ['title' => 'Store List'])]
class OrderList extends Component
{
    public $stores;
    public $selectedStatuses = [];
    public $selectedStores = [];

    public function mount()
    {
        $this->stores = Auth::user()->stores()->get();
        $this->selectedStatuses = session('selectedStatuses', []);
        $this->selectedStores = session('selectedStores', []);
    }
    
    public function updatedSelectedStatuses()
    {
        session(['selectedStatuses' => $this->selectedStatuses]);
    }

    public function updatedSelectedStores()
    {
        session(['selectedStores' => $this->selectedStores]);
    }

    public function toggleStatusFilter($status)
    {
        if (in_array($status, $this->selectedStatuses)) {
            $this->selectedStatuses = array_diff($this->selectedStatuses, [$status]);
        } else {
            $this->selectedStatuses[] = $status;
        }
        // Update session setiap kali berubah
        session(['selectedStatuses' => $this->selectedStatuses]);
    }
    public function toggleStoreFilter($storeId)
    {
        if (in_array($storeId, $this->selectedStores)) {
            $this->selectedStores = array_diff($this->selectedStores, [$storeId]);
        } else {
            $this->selectedStores[] = $storeId;
        }
        // Update session setiap kali berubah
        session(['selectedStores' => $this->selectedStores]);
    }

    protected $listeners = ['echo:orders,OrderCreated' => '$refresh'];


    public function render()
    {
        $this->stores = Auth::user()->stores()->get();

        $storeIds = $this->stores->pluck('id');

        $this->selectedStatuses = session('selectedStatuses', []);
        $this->selectedStores = session('selectedStores', []);

        // Load ulang orders setiap render (dipanggil juga saat poll)
        $query = Order::whereIn('store_id', $storeIds)
            ->with('orderItems.item')
            ->latest();

        if (!empty($this->selectedStatuses)) {
            $query->whereIn('order_status', $this->selectedStatuses);
        }
        if (!empty($this->selectedStores)) {
            $query->whereIn('store_id', $this->selectedStores);
        }

        $orders = $query->get()->sortByDesc('order_time');

        return view('livewire.order-list', [
            'stores' => $this->stores,
            'orders' => $orders,
        ]);
    }
}

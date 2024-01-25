<?php

namespace App\Livewire;

use App\Models\Kota;
use Livewire\Component;

class PriceAlindo extends Component
{
    public $perPage = 10;
    public $search = '';
    public function render()
    {
        $kotas = Kota::search($this->search)->orderBy('kota', 'desc')->paginate($this->perPage);
        return view('livewire.price-alindo', compact('kotas'));
    }
}

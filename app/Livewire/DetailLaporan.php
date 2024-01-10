<?php

namespace App\Livewire;

use Livewire\Component;

class DetailLaporan extends Component
{
    public $dataInvoices;

    public function mount($invoices){
        $this->dataInvoices = $invoices;
    }

    public function render()
    {
        return view('livewire.detail-laporan', ['invoices' => $this->dataInvoices]);
    }
}

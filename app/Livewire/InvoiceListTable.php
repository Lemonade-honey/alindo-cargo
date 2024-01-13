<?php

namespace App\Livewire;

use Livewire\Component;

class InvoiceListTable extends Component
{
    use \Livewire\WithPagination;

    protected $queryString = ['search' => ['except' => '']];
    
    public string $search = '';

    public $filter = '';

    public $perPage = 10;

    public function placeholder(){
        return view('livewire.skeleton.loading');
    }

    public function render()
    {
        $invoices = \App\Models\invoice\Invoice::with("invoicePerson", "invoiceData", "invoiceCost")
        ->search($this->search)
        ->when($this->filter != '', function($query){
            $query->where("status", $this->filter)->orWhereHas("InvoiceCost", function($query){
                $query->where('status', $this->filter);
            });
        })
        ->orderBy("id", "desc")
        ->paginate($this->perPage);
        
        // balum bisa implementasi service expired pada livewire

        return view('livewire.invoice-list-table', compact("invoices"));
    }
}

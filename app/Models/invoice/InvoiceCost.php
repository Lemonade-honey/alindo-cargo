<?php

namespace App\Models\invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceCost extends Model
{
    use HasFactory;

    protected $guarded = [
        "id"
    ];

    /**
     * Relation table
     */
    public function oneInvoice(){
        return $this->belongsTo(Invoice::class, "id", "id_invoice");
    }
}

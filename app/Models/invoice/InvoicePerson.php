<?php

namespace App\Models\invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePerson extends Model
{
    use HasFactory;

    protected $table = "invoice_peoples";

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

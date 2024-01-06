<?php

namespace App\Models\invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceData extends Model
{
    use HasFactory;

    protected $table = "invoice_datas";
    
    protected $guarded = [
        "id"
    ];

    /**
     * Cast attribut data
     */
    protected $casts = [
        "form_setting" => "array"
    ];

    /**
     * Relation Table Invoice
     */
    public function oneInvoice(){
        return $this->belongsTo(Invoice::class, "id", "id_invoice");
    }
}

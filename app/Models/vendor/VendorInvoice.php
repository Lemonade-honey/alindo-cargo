<?php

namespace App\Models\vendor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorInvoice extends Model
{
    use HasFactory;

    protected $guarded = [
        "id"
    ];

    /**
     * Relation table
     */
    public function hasVendor(){
        return $this->belongsToMany(VendorDetail::class, "id_vendor_details", "id");
    }

    public function invoice(){
        return $this->belongsTo(\App\Models\invoice\Invoice::class, "id_invoice", "id");
    }
}

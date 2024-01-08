<?php

namespace App\Models\vendor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorDetail extends Model
{
    use HasFactory;

    protected $guarded = [
        "id"
    ];

    /**
     * Relation table
     */
    public function vendor(){
        return $this->hasOne(Vendor::class, "id", "id_vendor");
    }

    public function kota(){
        return $this->hasOne(\App\Models\Kota::class, "id", "id_kota");
    }
}

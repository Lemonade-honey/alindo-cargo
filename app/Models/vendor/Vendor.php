<?php

namespace App\Models\vendor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $guarded = [
        "id"
    ];

    // Start Point
    protected static function boot()
    {
        parent::boot();
        
        // hapus data dengan semua relasinya
        static::deleting(function($model){
            $model->hasVendorDetail()->delete();
        });
    }

    /**
     * Relation table
     */
    public function hasVendorDetail(){
        return $this->hasMany(VendorDetail::class, "id_vendor");
    }

    // list wilyah-wilayah vendor
    public function wilayah(){
        return $this->belongsToMany(\App\Models\Kota::class, "vendor_details", "id_vendor", "id_kota")->withPivot("id", "harga");
    }
}

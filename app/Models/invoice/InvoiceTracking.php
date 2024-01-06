<?php

namespace App\Models\invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceTracking extends Model
{
    use HasFactory;

    protected $guarded = [
        "id",
        "tracking_guid"
    ];

    // Start Point
    protected static function boot()
    {
        // mengambil dari class Model
        parent::boot();

        // membuat uuid otomatis pada attribut tracking_guid
        static::creating(function ($model) {
            $model->tracking_guid = \Ramsey\Uuid\Uuid::uuid4()->toString();
        });
    }

    /**
     * Casts attribut data 
     */
    protected $casts = [
        "tracking" => "array"
    ];

    /**
     * Relation table
     */
    public function oneInvoice(){
        return $this->belongsTo(Invoice::class, "id", "id_invoice");
    }
}

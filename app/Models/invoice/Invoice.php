<?php

namespace App\Models\invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded = [
        "id",
        "invoice"
    ];

    // Start Point
    protected static function boot()
    {
        parent::boot();

        // saat create data
        static::creating(function ($model) {
            // Mendapatkan tanggal saat ini dengan format dd-mm-yy
            $currentDate = now()->format('dmy');

            // Mendapatkan increment invoice pada hari ini
            $lastInvoice = self::whereDate('created_at', today())->orderBy('id', 'desc')->first();

            // Jika sudah ada invoice pada hari ini, increment
            $increment = $lastInvoice ? (int) substr($lastInvoice->invoice, -4) + 1 : 0;

            // Menghasilkan invoice baru
            $model->invoice = $currentDate . sprintf('%04d', $increment);
        });

        // hapus data dengan semua relasinya
        static::deleting(function($model){
            $model->invoiceData->delete();
            $model->invoicePerson->delete();
            $model->invoiceCost->delete();
            $model->invoiceTracking->delete();
        });
    }

    /**
     * Casts attribut data 
     */
    protected $casts = [
        "history" => "array"
    ];

    /**
     * Get and Set Attribut data
     */
    public function getCreatedAtAttribute($value){
        return \Carbon\Carbon::parse($value)->format('H:i, d M Y');
    }

    /**
     * Relation this table
     */
    public function invoiceData(){
        return $this->hasOne(InvoiceData::class, "id_invoice", "id");
    }

    public function invoiceCost(){
        return $this->hasOne(InvoiceCost::class, "id_invoice", "id");
    }

    public function invoicePerson(){
        return $this->hasOne(InvoicePerson::class, "id_invoice", "id");
    }

    public function invoiceTracking(){
        return $this->hasOne(InvoiceTracking::class, "id_invoice", "id");
    }

    public function invoiceVendors(){
        return $this->belongsToMany(\App\Models\vendor\VendorDetail::class, "vendor_invoices", "id_invoice", "id_vendor_details");
    }
}

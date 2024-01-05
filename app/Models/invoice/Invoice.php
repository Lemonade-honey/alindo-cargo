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
    }

    /**
     * Casts attribut data 
     */
    protected $casts = [
        "history" => "array"
    ];

    /**
     * Relation this table
     */
    public function oneInvoiceData(){
        return $this->hasOne(InvoiceData::class, "id_invoice", "id");
    }
}

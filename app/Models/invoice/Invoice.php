<?php

namespace App\Models\invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded = [
        "id",
        "invoice",
        "resi"
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
            $lastResi = self::whereDate('created_at', today())->orderBy('id', 'desc')->first();

            // Jika sudah ada invoice pada hari ini, increment
            $incrementResi = $lastResi ? (int) substr($lastResi->invoice, -4) + 1 : 0;

            $model->invoice = "INV/" .date('y') . "/" . Invoice::angkaKeRomawi(date('m')) . "-" . Invoice::angkaKeRomawi(date('d')) . "/" . sprintf('%04d', $incrementResi);
            $model->resi = $currentDate . sprintf('%04d', $incrementResi);
        });

        // hapus data dengan semua relasinya
        static::deleting(function($model){
            $model->invoiceData->delete();
            $model->invoicePerson->delete();
            $model->invoiceCost->delete();
            $model->invoiceTracking->delete();
        });
    }

    // Fungsi untuk mengonversi angka menjadi angka romawi
    private static function angkaKeRomawi($angka) {
        $romawi = '';
        $angkaRomawi = array(
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        );

        
        foreach ($angkaRomawi as $simbol => $nilai) {
            while ($angka >= $nilai) {
                $romawi .= $simbol;
                $angka -= $nilai;
            }
        }
        
        return $romawi;
    }

    /**
     * Casts attribut data 
     */
    protected $casts = [
        "history" => "array"
    ];

    /**
     * Scope Function
     */
    public function scopeSearch($query, $value){
        $query->where('invoice', 'like', "%{$value}%")
        ->orWhere('tujuan', 'like', "%{$value}%")
        ->orWhereHas('InvoicePerson', function($query) use ($value){
            $query->where('pengirim', 'like', "%{$value}%");
        });
    }

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
        return $this->belongsTo(InvoiceData::class, "id", "id_invoice");
    }

    public function invoiceCost(){
        return $this->belongsTo(InvoiceCost::class, "id", "id_invoice");
    }

    public function invoicePerson(){
        return $this->belongsTo(InvoicePerson::class, "id", "id_invoice");
    }

    public function invoiceTracking(){
        return $this->belongsTo(InvoiceTracking::class, "id", "id_invoice");
    }

    public function invoiceVendors(){
        return $this->belongsToMany(\App\Models\vendor\VendorDetail::class, "vendor_invoices", "id_invoice", "id_vendor_details");
    }
}

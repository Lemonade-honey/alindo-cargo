<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    use HasFactory;

    protected $guarded = [
        "id"
    ];

    /**
     * Scope Function
     */
    public function scopeSearch($query, $value){
        $query->where('kota', 'like', "%{$value}%")
        ->orWhere('harga', 'like', "%{$value}%");
    }

    /**
     * Ubah attribut kota menjadi UPPERCASE sebelum disimpan
     */
    public function setKotaAttribute($value){
        $this->attributes['kota'] = strtoupper($value);
    }
}

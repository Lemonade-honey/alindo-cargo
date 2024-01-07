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
     * Ubah attribut kota menjadi UPPERCASE sebelum disimpan
     */
    public function setKotaTujuanAttribute($value){
        $this->attributes['kota'] = strtoupper($value);
    }
}

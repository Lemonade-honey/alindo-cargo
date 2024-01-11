<?php

namespace App\Models\costumer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostumerTetap extends Model
{
    use HasFactory;

    protected $guarded = [
        "id"
    ];

    public function costumer1(){
        return $this->belongsTo(Costumer::class, "costumer_1");
    }

    public function costumer2(){
        return $this->belongsTo(Costumer::class, "costumer_2");
    }
}

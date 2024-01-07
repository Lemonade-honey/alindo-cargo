<?php

namespace App\Models\member;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelasiMember extends Model
{
    use HasFactory;

    protected $guarded = [
        "id"
    ];

    /**
     * Relation this table
     */
    public function member1(){
        return $this->belongsTo(Member::class, "id_member1", "id");
    }

    public function member2(){
        return $this->belongsTo(Member::class, "id_member2", "id");
    }
}

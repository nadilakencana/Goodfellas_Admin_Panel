<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $table = 'discount';

    protected $guarded = [];

    public $timestamps = true;

    public function Discount_detail(){
        return $this->hasMany(Discount_detail_order::class,'id_discount','id');
    }

    public function Discount_retur(){
        return $this->hasMany(DiscountMenuRefund::class,'id_discount','id');
    }
}

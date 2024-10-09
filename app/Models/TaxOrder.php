<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxOrder extends Model
{
    use HasFactory;
    protected $table = 'tax_order';

    protected $guarded = [];

    public $timestamps = true;

    public function tax(){
        return $this->belongsTo(Taxes::class, 'id_tax','id');
    }

    public function order(){
        return $this->belongsTo(Orders::class,'id_order','id');
    }
}

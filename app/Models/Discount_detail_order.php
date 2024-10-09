<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount_detail_order extends Model
{
    use HasFactory;
    protected $table = 'discount_detail_order';
    protected $fillable = [
        'id_detail_order',
        'id_discount',
        'total_discount',

    ];

    public $timestamps = true;

    public function Detail_order(){
        return $this->belongsTo(DetailOrder::class, 'id_detail_order', 'id');
    }
    public function id_Detail(){
        return $this->belongsTo(Additional_menu_detail::class, 'id_detail_order', 'id_detail_order');
    }

    public function discount(){
        return $this->belongsTo(Discount::class, 'id_discount', 'id');
    }
}

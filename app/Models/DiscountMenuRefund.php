<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountMenuRefund extends Model
{
    use HasFactory;
    protected $table = 'discount_refund';
    protected $fillable = [
        'id_refund_menu',
        'id_menu',
        'id_discount',
        'nominal_dis',
        'id_admin'

    ];

     public $timestamps = true;

     public function Refund(){
        return $this->belongsTo(RefundOrderMenu::class, 'id_refund_menu', 'id');
     }
     public function menu(){
        return $this->belongsTo(Menu::class, 'id_menu', 'id');
     }
     public function Discount(){
        return $this->belongsTo(Discount::class, 'id_discount', 'id');
     }
     public function admin(){
        return $this->belongsTo(Admin::class, 'id_admin', 'id');
     }

     public function refundDis(){
        return $this->belongsTo(AdditionalRefund::class, 'id_refund_menu', 'id_refund_menu');
    }

}

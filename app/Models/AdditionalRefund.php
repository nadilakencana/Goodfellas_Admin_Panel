<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalRefund extends Model
{
    use HasFactory;
    protected $table = 'additional_refund';
    protected $fillable = [
        'id_refund_menu',
        'id_option_additional',
        'harga',
        'tanggal',
        'id_admin',
        'id_menu',
        'qty',
        'total_'

    ];

     public $timestamps = true;

     public function Refund(){
        return $this->belongsTo(RefundOrderMenu::class, 'id_refund_menu', 'id');
     }
     public function additionOps(){
        return $this->belongsTo(OptionModifier::class, 'id_option_additional', 'id');
     }
     public function admin(){
        return $this->belongsTo(Admin::class, 'id_admin', 'id');
     }
     public function menu(){
        return $this->belongsTo(Menu::class, 'id_menu', 'id');
     }

}

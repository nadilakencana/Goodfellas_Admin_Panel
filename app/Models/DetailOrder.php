<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model
{
    use HasFactory;
    protected $table = 'detail_order';

    protected $fillable = [
        'id_order',
        'harga',
        'total',
        'id_menu',
        'qty',
        'id_varian',
        'catatan',
        'id_sales_type'

    ];

    public $timestamp = false;

    public function order(){
        return $this->belongsTo(Orders::class, 'id_order', 'id');
    }

    public function menu(){
        return $this->belongsTo(Menu::class, 'id_menu', 'id');
    }

    public function vocher(){
        return $this->belongsTo(Vocher::class, 'id_vocher','id');
    }

    public function varian(){
        return $this->belongsTo(VarianMenu::class,'id_varian','id');
    }

    public function optionModif(){
        return $this->belongsTo(OptionModifier::class,'id_option_modifier');
    }

    public function AddOptional_order(){
        return $this->hasMany(Additional_menu_detail::class, 'id_detail_order', 'id');
    }

    public function Discount_menu_order(){
        return $this->hasMany(Discount_detail_order::class, 'id_detail_order','id');
    }

    public function salesType(){
        return $this->belongsTo(SalesType::class, 'id_sales_type', 'id');
    }
}

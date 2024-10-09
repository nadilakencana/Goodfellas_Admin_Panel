<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundOrderMenu extends Model
{
    
    use HasFactory;
    protected $table = 'refund_menu_order';
    protected $fillable = [
        'id_order',
        'refund_nominal',
        'id_admin',
        'id_menu',
        'alasan_refund',
        'tanggal',


    ];

    public $timestamps = true;

    public function order(){
        return $this->belongsTo(Orders::class, 'id_order', 'id');

    }
    public function admin(){
            return $this->belongsTo(Admin::class, 'id_admin', 'id');

    }
    public function menu(){
        return$this->belongsTo(Menu::class, 'id_menu', 'id');
    }
    public function detail_order(){
        return$this->belongsTo(DetailOrder::class, 'id_order', 'id_order');
    }
    public function varian(){
        return$this->belongsTo(VarianMenu::class, 'id_varian', 'id');
    }

    public function RefundAdds(){
        return$this->hasMany(AdditionalRefund::class, 'id_refund_menu', 'id');
    }


}

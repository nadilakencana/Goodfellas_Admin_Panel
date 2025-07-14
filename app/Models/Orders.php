<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $table = 'orders';

    protected $guarded = [];

    public $timestamps = true;



    public function details(){
        return $this->hasMany(DetailOrder::class, 'id_order' ,'id');
    }
    
    public function user(){
        return $this->belongsTo(User::class, 'id_user','id');
    }
    public function status(){
        return $this->belongsTo(StatusOrder::class, 'id_status', 'id');
    }

    public function booking(){
        return $this->belongsTo(BookingTempat::class, 'id_booking', 'id');
    }

    public function admin(){
        return $this->belongsTo(Admin::class,'id_admin', 'id');
    }

    public function sales_type(){
        return $this->belongsTo(SalesType::class,'id_sales_type','id');
    }

    public function payment(){
        return $this->belongsTo(TypePayment::class, 'id_type_payment');
    }

    public function refund(){
        return $this->hasMany(RefundOrderMenu::class, 'id_order', 'id');

    }

    public function DiscountRefund(){
        return $this->hasMany(DiscountMenuRefund::class, 'id_order', 'id');

    }

    public function addisRefund(){
        return $this->hasMany(AdditionalRefund::class, 'id_order', 'id');
    }
}

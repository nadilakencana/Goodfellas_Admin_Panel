<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundOrder extends Model
{
    use HasFactory;
    protected $table = 'refund_order';
    protected $fillable = [
        'id_order',
        'name_bill',
        'kode_refund',
        'subtotal',
        'total_refund',
        'id_admin',
        'tanggal',
        'deleted',
        'id_admin_deleted',
        'alasan_delete',
        'deleted_at',

    ];

    public $timestamps = true;

    public function order(){
         return $this->belongsTo(Orders::class, 'id_order', 'id');
    }

    public function admin(){
         return $this->belongsTo(Admin::class, 'id_admin', 'id');
    }
    
   public function refundItem()
    {
        return $this->hasMany(RefundOrderMenu::class, 'id_refund_order', 'id');
    }
}

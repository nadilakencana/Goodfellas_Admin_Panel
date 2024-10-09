<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusOrder extends Model
{
    use HasFactory;
    protected $table = 'status_order';

    protected $fillable = ['satatus_order'];

    public $timestamp = false;


    public function detail(){
        return $this->hasMany(DetailOrder::class, 'id_status', 'id');
    }
     public function booking(){
        return $this->hasMany(BookingTempat::class, 'id_status', 'id');
    }
}

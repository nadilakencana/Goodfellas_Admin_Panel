<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point_User extends Model
{
    use HasFactory;
    protected $table = 'point_user';
    protected $fillable = [

        'id_user',
        'id_order',
        'tanggal',
        'point_in',
        'keterangan'
    ];

    public $timestamps = true;

    public function user(){
        return $this->belongsTo(user::class, 'id_user' , 'id');
    }

    public function detail_order(){
        return $this->belongsTo(Orders::class, 'id_order','id');
    }
}

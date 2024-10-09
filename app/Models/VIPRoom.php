<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class VIPRoom extends Model

{

    use HasFactory;



    protected $table = 'room_vip';



    protected $fillable = [



        'type_room',
        'slug_room',

        'deskripsi',

        'image',

        'min_dp',

    ];



    public $timestamps = false;



    public function booking(){

        return $this->hasMany(BookingTempat::class, 'id_room', 'id');

    }
    
    public function typeHarga(){
        return $this->hasMany(TypeHarga::class, 'id_room', 'id');
    }

}


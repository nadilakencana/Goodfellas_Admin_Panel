<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class BookingTempat extends Model

{

    use HasFactory;

    protected $table = 'booking_tempat';



    protected $fillable = [
        'id_user',
        'nomer_hp',
        'kode_boking',
        'id_room',
        'type_time',
        'tanggal_booking',
        'jam_booking',
        'bukti_pembayaran',
        'id_status',
        'nominal_dp'

    ];



    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function room(){
        return $this->belongsTo(VIPRoom::class, 'id_room', 'id');
    }
     public function status(){
        return $this->belongsTo(StatusOrder::class,'id_status','id');
    }

}


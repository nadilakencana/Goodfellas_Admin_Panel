<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VocherClaimUser extends Model
{
    use HasFactory;
    protected $table = 'vocher_claim_user';
    protected $fillable  = ['id_user','id_vocher','tanggal_claim', 'flag', 'kode_qr','id_admin','tanggal_tukar'];
    public $timestamps = true;

    public function admin(){
        return $this->belongsTo(Admin::class, 'id_admin','id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
    public function vocher(){
        return $this->belongsTo(VocherGift::class, 'id_vocher', 'id');
    }
}

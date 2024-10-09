<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notify_user extends Model
{
    use HasFactory;
    protected $table = 'notify_user';
    protected $fillable = [

        'id_user',
        'message',
        'tanggal',
        'status'
    ];

    public $timestamps = true;

    public function user(){
        return $this->belongsTo(User::class, 'id_user' , 'id');
    }
}

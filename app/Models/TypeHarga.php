<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeHarga extends Model
{
    use HasFactory;

    protected $table = 'type_harga_room';

    protected $fillable = [
        'durasi', 'harga', 'id_room'
    ];

    public $timestamps = false;


    public function room(){
        return $this->belongsTo(VIPRoom::class, 'id_room','id');

    }
}

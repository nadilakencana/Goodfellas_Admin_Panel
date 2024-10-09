<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sift extends Model
{
    use HasFactory;
    protected $table = 'sift';
    protected $guarded = [];


    public function admin(){
        return $this->belongsTo(Admin::class, 'id_admin', 'id');
    }
}

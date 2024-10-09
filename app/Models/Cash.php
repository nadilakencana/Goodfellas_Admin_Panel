<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    use HasFactory;
    protected $table = 'cash';
    protected $guarded = [];


    public function admin(){
        return $this->belongsTo(Admin::class, 'id_admin', 'id');
    }

}

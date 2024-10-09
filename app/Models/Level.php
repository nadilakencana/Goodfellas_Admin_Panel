<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;
    protected $table = 'level';
    protected $guarded = [];

    public function admin(){
        return $this->hasMany(Admin::class, 'id_level',' id');
    }
}

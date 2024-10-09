<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VarianMenu extends Model
{
    use HasFactory;
    protected $table = 'varian_menu';

    protected $guarded = [];

    public $timestamps = true;

    public function Menu(){
        return $this->belongsTo(Menu::class,'id_menu','id');
    }
    public function Detail_menu(){
        return $this->hasMany(DetailOrder::class,'id_varian','id');
    }
}

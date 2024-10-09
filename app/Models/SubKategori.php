<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKategori extends Model
{
    use HasFactory;
    protected $table = 'sub_kategori_menu';
    protected $fillable = [
        'id_kategori',
        'sub_kategori',

    ];

    public $timestamps = false;

    public function kategori(){ //fungsi yang berelasi dengan model Kategori
        return $this->belongsTo(Kategori::class, 'id_kategori','id');
    }
    public function menu(){ // fungsi yang berelasi dengan model Menu
        return $this->hasMany(Menu::class, 'id_sub_kategori','id');
    }
}

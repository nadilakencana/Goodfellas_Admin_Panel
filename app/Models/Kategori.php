<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    protected $table = 'kategori';

    protected $fillable = [
        'kategori_nama',
    ];

    public $timestamps = false;

    public function menu(){ //fungsi yang ber relasi dengan model Menu
        return $this->hasMany(Menu::class, 'id_kategori', 'id');
    }

    public function subkategori(){ // fungsi yang berelasi dengan model SubKategori
        return $this->hasMany(SubKategori::class, 'id_kategori','id');
    }

    public function banner(){// funsi yang berelasi dengan model Banner
        return $this->hasMany(Banner::class ,'id_kategori','id');
    }
}

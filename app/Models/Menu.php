<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'menu'; //mendeklarisasi table

    protected $fillable = [

        'nama_menu',
        'slug',
        'deskripsi',
        'harga',
        'id_kategori',
        'id_sub_kategori',
        'promo',
        'image',
        'id_group_modifier',
        'custom'

    ]; //mendeklarisasi atribut table menu yang harus di isi

    public $timestamps = false;


 public function scopeFilter($query, array $filters){

    $query->when($filters['search'] ?? false, function($query, $search){

        return $query->where('nama_menu', 'LIKE','%'. $search .'%');
    });
    $query->when($filters['filter'] ?? false, function($query, $filter){
        return $query->where('id_sub_kategori', $filter);
    });

 }



    public function kategori(){ //fungsi yang ber relasi dengan model Kategori
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id');
    }
    public function subKategori(){ //fungsi yang ber relasi dengan model SubKategori
        return $this->belongsTo(SubKategori::class, 'id_sub_kategori', 'id');
    }

    public function varian(){
        return $this->hasMany(VarianMenu::class, 'id_menu', 'id');
    }

    public function additional(){
        return $this->belongsTo(GroupModifier::class, 'id_group_modifier','id');
    }
}

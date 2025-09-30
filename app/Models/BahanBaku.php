<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;
    
    protected $table = 'bahan_baku';
    
    protected $fillable = [
        'nama_bahan',
        'stok_porsi',
        'stok_minimum',
        
    ];

    

    public function menuResep()
    {
        return $this->hasMany(MenuResep::class, 'id_bahan_baku', 'id');
    }

    public function menus()
    {
        return $this->hasManyThrough(Menu::class, MenuResep::class, 'id_bahan_baku', 'id', 'id', 'id_menu');
    }


    // Cek apakah stok kritis
    public function isStokKritis()
    {
        return $this->stok_porsi <= $this->stok_minimum;
    }

    // Update stok dengan logging
    public function updateStok($jumlah)
    {
        $stokSebelum = $this->stok_porsi;
        $this->stok_porsi += $jumlah;
        $this->save();

        return $this;
    }
}
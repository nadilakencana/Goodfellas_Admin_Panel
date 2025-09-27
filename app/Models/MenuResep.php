<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuResep extends Model
{
    use HasFactory;
    
    protected $table = 'menu_resep';
    
    protected $fillable = [
        'id_menu',
        'id_bahan_baku',
        'porsi_diperlukan'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id');
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan_baku', 'id');
    }

    // Cek apakah bahan baku cukup untuk jumlah porsi tertentu
    public function cekStokCukup($jumlahPorsi = 1)
    {
        $dibutuhkan = $this->porsi_diperlukan * $jumlahPorsi;
        return $this->bahanBaku->stok_porsi >= $dibutuhkan;
    }

    // Hitung berapa porsi maksimal yang bisa dibuat dari bahan baku ini
    public function hitungMaksimalPorsi()
    {
        if ($this->porsi_diperlukan <= 0) {
            return 0;
        }
        
        return floor($this->bahanBaku->stok_porsi / $this->porsi_diperlukan);
    }
}
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
        'harga_per_porsi'
    ];

    protected $casts = [
        'harga_per_porsi' => 'decimal:2'
    ];

    public function menuResep()
    {
        return $this->hasMany(MenuResep::class, 'id_bahan_baku', 'id');
    }

    public function stokLog()
    {
        return $this->hasMany(StokLog::class, 'id_item', 'id')
                    ->where('tipe', 'bahan_baku');
    }

    // Cek apakah stok kritis
    public function isStokKritis()
    {
        return $this->stok_porsi <= $this->stok_minimum;
    }

    // Update stok dengan logging
    public function updateStok($jumlah, $tipeTransaksi, $keterangan = null, $orderId = null, $userId = null)
    {
        $stokSebelum = $this->stok_porsi;
        $this->stok_porsi += $jumlah;
        $this->save();

        // Log perubahan
        StokLog::create([
            'tipe' => 'bahan_baku',
            'id_item' => $this->id,
            'tipe_transaksi' => $tipeTransaksi,
            'jumlah_sebelum' => $stokSebelum,
            'jumlah_perubahan' => $jumlah,
            'jumlah_sesudah' => $this->stok_porsi,
            'keterangan' => $keterangan,
            'id_order' => $orderId,
            'created_by' => $userId
        ]);

        return $this;
    }
}
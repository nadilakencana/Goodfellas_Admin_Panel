<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokLog extends Model
{
    use HasFactory;
    
    protected $table = 'stok_log';
    
    protected $fillable = [
        'tipe',
        'id_item',
        'tipe_transaksi',
        'jumlah_sebelum',
        'jumlah_perubahan',
        'jumlah_sesudah',
        'keterangan',
        'id_order',
        'created_by'
    ];

    protected $casts = [
        'jumlah_sebelum' => 'decimal:2',
        'jumlah_perubahan' => 'decimal:2',
        'jumlah_sesudah' => 'decimal:2'
    ];

    // Relasi polymorphic untuk item (menu atau bahan baku)
    public function item()
    {
        if ($this->tipe === 'menu') {
            return $this->belongsTo(Menu::class, 'id_item', 'id');
        } else {
            return $this->belongsTo(BahanBaku::class, 'id_item', 'id');
        }
    }

    public function order()
    {
        return $this->belongsTo(Orders::class, 'id_order', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id');
    }

    // Scope untuk filter berdasarkan tipe
    public function scopeMenu($query)
    {
        return $query->where('tipe', 'menu');
    }

    public function scopeBahanBaku($query)
    {
        return $query->where('tipe', 'bahan_baku');
    }

    public function scopeTransaksiMasuk($query)
    {
        return $query->where('tipe_transaksi', 'masuk');
    }

    public function scopeTransaksiKeluar($query)
    {
        return $query->where('tipe_transaksi', 'keluar');
    }
}
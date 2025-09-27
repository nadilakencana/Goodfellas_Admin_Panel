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
        'custom',
        'stok_tersedia',
        'stok_minimum',
        'tipe_stok'
    ]; //mendeklarisasi atribut table menu yang harus di isi

    public $timestamps = false;
    protected $appends = ['encrypted_id'];

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

    public function resep(){
        return $this->hasMany(MenuResep::class, 'id_menu', 'id');
    }

    public function stokLog(){
        return $this->hasMany(StokLog::class, 'id_item', 'id')
                    ->where('tipe', 'menu');
    }

    // Method untuk cek stok berdasarkan bahan baku
    public function hitungStokDariBahanBaku()
    {
        if ($this->tipe_stok !== 'bahan_baku') {
            return $this->stok_tersedia;
        }

        if ($this->resep->isEmpty()) {
            return 0;
        }

        $stokMinimum = PHP_INT_MAX;
        
        foreach ($this->resep as $resep) {
            $bahanBaku = $resep->bahanBaku;
            if ($resep->porsi_diperlukan > 0) {
                $stokTersedia = floor($bahanBaku->stok_porsi / $resep->porsi_diperlukan);
                $stokMinimum = min($stokMinimum, $stokTersedia);
            } else {
                $stokMinimum = 0;
                break;
            }
        }
        
        return $stokMinimum === PHP_INT_MAX ? 0 : $stokMinimum;
    }

    // Cek apakah menu tersedia (stok cukup)
    public function isStokTersedia($jumlah = 1)
    {
        if ($this->tipe_stok === 'bahan_baku') {
            return $this->hitungStokDariBahanBaku() >= $jumlah;
        } else {
            return $this->stok_tersedia >= $jumlah;
        }
    }

    // Cek apakah stok kritis
    public function isStokKritis()
    {
        $stokAktual = $this->tipe_stok === 'bahan_baku' 
            ? $this->hitungStokDariBahanBaku() 
            : $this->stok_tersedia;
            
        return $stokAktual <= $this->stok_minimum;
    }

    // Update stok dengan logging (untuk tipe manual)
    public function updateStok($jumlah, $tipeTransaksi, $keterangan = null, $orderId = null, $userId = null)
    {
        if ($this->tipe_stok !== 'manual') {
            throw new \Exception('Stok menu ini dikelola berdasarkan bahan baku');
        }

        $stokSebelum = $this->stok_tersedia;
        $this->stok_tersedia += $jumlah;
        $this->save();

        // Log perubahan
        StokLog::create([
            'tipe' => 'menu',
            'id_item' => $this->id,
            'tipe_transaksi' => $tipeTransaksi,
            'jumlah_sebelum' => $stokSebelum,
            'jumlah_perubahan' => $jumlah,
            'jumlah_sesudah' => $this->stok_tersedia,
            'keterangan' => $keterangan,
            'id_order' => $orderId,
            'created_by' => $userId
        ]);

        return $this;
    }


    /**
     * Membuat atribut virtual 'encrypted_id'.
     *
     * @return string
     */
    public function getEncryptedIdAttribute()
    {
        // Fungsi encrypt() global milik Laravel
        return encrypt($this->id);
    }
}

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
        'stok',
        'stok_minimum',
        'active',
        'tipe_stok',
        'id_bahan_baku'
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

    public function bahanBaku(){
        return $this->hasOneThrough(BahanBaku::class, MenuResep::class, 'id_menu', 'id', 'id', 'id_bahan_baku');
    }

    public function stokLog(){
        return $this->hasMany(StokLog::class, 'id_item', 'id')
                    ->where('tipe', 'menu');
    }

    // Method untuk cek stok berdasarkan bahan baku
    public function hitungStokDariBahanBaku()
    {
        if ($this->tipe_stok !== 'Stok Bahan Baku') {
            return $this->stok;
        }

        $bahanBaku = $this->bahanBaku;
        return $bahanBaku ? $bahanBaku->stok_porsi : 0;
    }

    // Cek apakah menu tersedia (stok cukup)
    public function isStokTersedia($jumlah = 1)
    {
        if ($this->tipe_stok === 'Stok Bahan Baku') {
            return $this->hitungStokDariBahanBaku() >= $jumlah;
        } else {
            return $this->stok >= $jumlah;
        }
    }

    // Cek apakah stok kritis
    public function isStokKritis()
    {
        $stokAktual = $this->tipe_stok === 'Stok Bahan Baku' 
            ? $this->hitungStokDariBahanBaku() 
            : $this->stok;
            
        return $stokAktual <= $this->stok_minimum;
    }

    // Update stok dengan logging (untuk tipe manual)
    public function updateStok($jumlah)
    {
        if ($this->tipe_stok !== 'Stok Manual') {
            throw new \Exception('Stok menu ini dikelola berdasarkan bahan baku');
        }

        $stokSebelum = $this->stok;
        $this->stok += $jumlah;
        $this->save();

    
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

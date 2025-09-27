# Database Design - Sistem Stok Management

## Tabel yang Perlu Ditambahkan

### 1. Tabel `bahan_baku`
```sql
CREATE TABLE bahan_baku (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_bahan VARCHAR(255) NOT NULL,
    satuan VARCHAR(50) NOT NULL, -- kg, gram, liter, ml, pcs
    stok_tersedia DECIMAL(10,2) NOT NULL DEFAULT 0,
    stok_minimum DECIMAL(10,2) NOT NULL DEFAULT 0,
    harga_per_satuan DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 2. Tabel `menu_resep` (Junction Table)
```sql
CREATE TABLE menu_resep (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_menu INT NOT NULL,
    id_bahan_baku INT NOT NULL,
    jumlah_diperlukan DECIMAL(10,2) NOT NULL, -- jumlah bahan baku yang diperlukan per porsi
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_menu) REFERENCES menu(id) ON DELETE CASCADE,
    FOREIGN KEY (id_bahan_baku) REFERENCES bahan_baku(id) ON DELETE CASCADE
);
```

### 3. Update Tabel `menu` (Tambah kolom stok)
```sql
ALTER TABLE menu ADD COLUMN stok_tersedia INT DEFAULT 0;
ALTER TABLE menu ADD COLUMN stok_minimum INT DEFAULT 0;
ALTER TABLE menu ADD COLUMN tipe_stok ENUM('bahan_baku', 'manual') DEFAULT 'manual';
-- tipe_stok: 'bahan_baku' = stok dihitung dari bahan baku, 'manual' = stok diatur manual
```

### 4. Tabel `stok_log` (History perubahan stok)
```sql
CREATE TABLE stok_log (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipe ENUM('menu', 'bahan_baku') NOT NULL,
    id_item INT NOT NULL, -- id menu atau id bahan_baku
    tipe_transaksi ENUM('masuk', 'keluar', 'adjustment') NOT NULL,
    jumlah_sebelum DECIMAL(10,2) NOT NULL,
    jumlah_perubahan DECIMAL(10,2) NOT NULL,
    jumlah_sesudah DECIMAL(10,2) NOT NULL,
    keterangan TEXT,
    id_order INT NULL, -- jika perubahan karena order
    created_by INT NULL, -- id admin yang melakukan perubahan
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Model Laravel yang Perlu Dibuat

### 1. Model BahanBaku
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    protected $table = 'bahan_baku';
    
    protected $fillable = [
        'nama_bahan',
        'satuan', 
        'stok_tersedia',
        'stok_minimum',
        'harga_per_satuan'
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
}
```

### 2. Model MenuResep
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuResep extends Model
{
    protected $table = 'menu_resep';
    
    protected $fillable = [
        'id_menu',
        'id_bahan_baku',
        'jumlah_diperlukan'
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id');
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan_baku', 'id');
    }
}
```

### 3. Update Model Menu
```php
// Tambahkan ke Model Menu yang sudah ada

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
    'stok_tersedia',    // TAMBAH
    'stok_minimum',     // TAMBAH
    'tipe_stok'         // TAMBAH
];

// Tambahkan relasi
public function resep()
{
    return $this->hasMany(MenuResep::class, 'id_menu', 'id');
}

public function stokLog()
{
    return $this->hasMany(StokLog::class, 'id_item', 'id')
                ->where('tipe', 'menu');
}

// Method untuk cek stok berdasarkan bahan baku
public function hitungStokDariBahanBaku()
{
    if ($this->tipe_stok !== 'bahan_baku') {
        return $this->stok_tersedia;
    }

    $stokMinimum = PHP_INT_MAX;
    
    foreach ($this->resep as $resep) {
        $bahanBaku = $resep->bahanBaku;
        $stokTersedia = floor($bahanBaku->stok_tersedia / $resep->jumlah_diperlukan);
        $stokMinimum = min($stokMinimum, $stokTersedia);
    }
    
    return $stokMinimum === PHP_INT_MAX ? 0 : $stokMinimum;
}
```

## Alur Kerja Sistem

### 1. Ketika Order Masuk
```php
// Service class untuk handle order
class StokService 
{
    public function prosesOrder($menuId, $quantity)
    {
        $menu = Menu::find($menuId);
        
        if ($menu->tipe_stok === 'bahan_baku') {
            return $this->prosesOrderDenganBahanBaku($menu, $quantity);
        } else {
            return $this->prosesOrderManual($menu, $quantity);
        }
    }
    
    private function prosesOrderDenganBahanBaku($menu, $quantity)
    {
        // Cek stok bahan baku
        foreach ($menu->resep as $resep) {
            $dibutuhkan = $resep->jumlah_diperlukan * $quantity;
            if ($resep->bahanBaku->stok_tersedia < $dibutuhkan) {
                return ['success' => false, 'message' => 'Stok bahan baku tidak cukup'];
            }
        }
        
        // Kurangi stok bahan baku
        foreach ($menu->resep as $resep) {
            $dibutuhkan = $resep->jumlah_diperlukan * $quantity;
            $resep->bahanBaku->decrement('stok_tersedia', $dibutuhkan);
            
            // Log perubahan stok
            StokLog::create([
                'tipe' => 'bahan_baku',
                'id_item' => $resep->bahanBaku->id,
                'tipe_transaksi' => 'keluar',
                'jumlah_sebelum' => $resep->bahanBaku->stok_tersedia + $dibutuhkan,
                'jumlah_perubahan' => -$dibutuhkan,
                'jumlah_sesudah' => $resep->bahanBaku->stok_tersedia,
                'keterangan' => "Order menu: {$menu->nama_menu}"
            ]);
        }
        
        return ['success' => true];
    }
    
    private function prosesOrderManual($menu, $quantity)
    {
        if ($menu->stok_tersedia < $quantity) {
            return ['success' => false, 'message' => 'Stok menu tidak cukup'];
        }
        
        $menu->decrement('stok_tersedia', $quantity);
        
        // Log perubahan stok
        StokLog::create([
            'tipe' => 'menu',
            'id_item' => $menu->id,
            'tipe_transaksi' => 'keluar',
            'jumlah_sebelum' => $menu->stok_tersedia + $quantity,
            'jumlah_perubahan' => -$quantity,
            'jumlah_sesudah' => $menu->stok_tersedia,
            'keterangan' => "Order menu manual"
        ]);
        
        return ['success' => true];
    }
}
```

### 2. Dashboard Monitoring Stok
```php
// Controller untuk dashboard stok
class StokController extends Controller
{
    public function dashboard()
    {
        $bahanBakuKritis = BahanBaku::whereRaw('stok_tersedia <= stok_minimum')->get();
        $menuKritis = Menu::where('tipe_stok', 'manual')
                         ->whereRaw('stok_tersedia <= stok_minimum')
                         ->get();
        
        $menuBahanBakuKritis = Menu::where('tipe_stok', 'bahan_baku')
                                  ->get()
                                  ->filter(function($menu) {
                                      return $menu->hitungStokDariBahanBaku() <= $menu->stok_minimum;
                                  });
        
        return view('stok.dashboard', compact('bahanBakuKritis', 'menuKritis', 'menuBahanBakuKritis'));
    }
}
```

## Keuntungan Sistem Ini

1. **Fleksibilitas**: Menu bisa menggunakan sistem stok bahan baku atau manual
2. **Akurasi**: Stok menu otomatis terhitung dari bahan baku
3. **Monitoring**: Dashboard untuk pantau stok kritis
4. **History**: Log semua perubahan stok
5. **Scalable**: Mudah ditambah fitur seperti auto-reorder, forecasting, dll

## Implementasi Bertahap

1. **Phase 1**: Buat tabel dan model baru
2. **Phase 2**: Implementasi logic stok untuk bahan baku
3. **Phase 3**: Update UI untuk management stok
4. **Phase 4**: Dashboard monitoring dan reporting
5. **Phase 5**: Fitur advanced (auto-reorder, alerts, dll)
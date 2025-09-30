<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\BahanBaku;
use Illuminate\Support\Facades\DB;

class StokService
{
    /**
     * Proses order dan kurangi stok
     */
    public function prosesOrder($menuId, $quantity, $orderId = null)
    {
        $menu = Menu::with('resep.bahanBaku')->find($menuId);
        
        if (!$menu) {
            return ['success' => false, 'message' => 'Menu tidak ditemukan'];
        }

        if ($menu->tipe_stok === 'Stok Bahan Baku') {
            return $this->prosesOrderDenganBahanBaku($menu, $quantity, $orderId);
        } else {
            return $this->prosesOrderManual($menu, $quantity, $orderId);
        }
    }

    /**
     * Proses order untuk menu dengan stok bahan baku
     */
    private function prosesOrderDenganBahanBaku($menu, $quantity, $orderId = null)
    {
        $bahanBaku = $menu->bahanBaku;
        
        if (!$bahanBaku) {
            return ['success' => false, 'message' => 'Bahan baku tidak ditemukan untuk menu ini'];
        }
        
        if ($bahanBaku->stok_porsi < $quantity) {
            return [
                'success' => false, 
                'message' => "Stok bahan baku '{$bahanBaku->nama_bahan}' tidak cukup. Dibutuhkan: {$quantity} porsi, Tersedia: {$bahanBaku->stok_porsi} porsi"
            ];
        }
        
        try {
            $bahanBaku->updateStok(-$quantity);
            return ['success' => true, 'message' => 'Stok berhasil dikurangi'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
        }
    }

    /**
     * Proses order untuk menu dengan stok manual
     */
    private function prosesOrderManual($menu, $quantity, $orderId = null)
    {
        if ($menu->stok < $quantity) {
            return [
                'success' => false, 
                'message' => "Stok menu '{$menu->nama_menu}' tidak cukup. Dibutuhkan: {$quantity}, Tersedia: {$menu->stok}"
            ];
        }
        
        try {
            $menu->updateStok(-$quantity);
            return ['success' => true, 'message' => 'Stok berhasil dikurangi'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
        }
    }

    /**
     * Restock bahan baku
     */
    public function restockBahanBaku($bahanBakuId, $jumlah, $keterangan = null, $userId = null)
    {
        $bahanBaku = BahanBaku::find($bahanBakuId);
        
        if (!$bahanBaku) {
            return ['success' => false, 'message' => 'Bahan baku tidak ditemukan'];
        }

        try {
            $bahanBaku->updateStok($jumlah, 'masuk', $keterangan, null, $userId);
            return ['success' => true, 'message' => 'Restock berhasil'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
        }
    }

    /**
     * Restock menu manual
     */
    public function restockMenu($menuId, $jumlah, $keterangan = null, $userId = null)
    {
        $menu = Menu::find($menuId);
        
        if (!$menu) {
            return ['success' => false, 'message' => 'Menu tidak ditemukan'];
        }

        if ($menu->tipe_stok !== 'manual') {
            return ['success' => false, 'message' => 'Menu ini menggunakan stok bahan baku, tidak bisa restock manual'];
        }

        try {
            $menu->updateStok($jumlah, 'masuk', $keterangan, null, $userId);
            return ['success' => true, 'message' => 'Restock berhasil'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
        }
    }

    /**
     * Get dashboard data stok kritis
     */
    public function getDashboardData()
    {
        // Bahan baku kritis
        $bahanBakuKritis = BahanBaku::whereRaw('stok_porsi <= stok_minimum')->get();
        
        // Menu manual kritis
        $menuManualKritis = Menu::where('tipe_stok', 'Stok Manual')
                                ->whereRaw('stok <= stok_minimum')
                                ->get();
        
        // Menu bahan baku kritis
        $menuBahanBaku = Menu::where('tipe_stok', 'Stok Bahan Baku')
                            ->with('resep.bahanBaku')
                            ->get();
        
        $menuBahanBakuKritis = $menuBahanBaku->filter(function($menu) {
            return $menu->hitungStokDariBahanBaku() <= $menu->stok_minimum;
        });

        return [
            'bahan_baku_kritis' => $bahanBakuKritis,
            'menu_manual_kritis' => $menuManualKritis,
            'menu_bahan_baku_kritis' => $menuBahanBakuKritis,
            'total_kritis' => $bahanBakuKritis->count() + $menuManualKritis->count() + $menuBahanBakuKritis->count()
        ];
    }

    /**
     * Cek ketersediaan menu untuk order
     */
    public function cekKetersediaanMenu($items)
    {
        $hasil = [];
        $semuaTersedia = true;

        foreach ($items as $item) {
            $menu = Menu::with('resep.bahanBaku')->find($item['menu_id']);
            $quantity = $item['quantity'];
            
            if (!$menu) {
                $hasil[] = [
                    'menu_id' => $item['menu_id'],
                    'tersedia' => false,
                    'message' => 'Menu tidak ditemukan'
                ];
                $semuaTersedia = false;
                continue;
            }

            $tersedia = $menu->isStokTersedia($quantity);
            
            $hasil[] = [
                'menu_id' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'tersedia' => $tersedia,
                'stok_tersedia' => $menu->tipe_stok === 'Stok Bahan Baku' 
                    ? $menu->hitungStokDariBahanBaku() 
                    : $menu->stok,
                'quantity_diminta' => $quantity,
                'message' => $tersedia ? 'Tersedia' : 'Stok tidak cukup'
            ];

            if (!$tersedia) {
                $semuaTersedia = false;
            }
        }

        return [
            'semua_tersedia' => $semuaTersedia,
            'detail' => $hasil
        ];
    }


    public function adjustMenuStock($menuId, $oldQty, $newQty, $orderId, $userId, $keterangan)
    {
        $difference = $newQty - $oldQty;
        
        if ($difference > 0) {
            // Tambah qty, kurangi stok
            return $this->reduceMenuStock($menuId, $difference, $orderId, $userId, $keterangan);
        } elseif ($difference < 0) {
            // Kurangi qty, tambah stok kembali
            return $this->restoreMenuStock($menuId, abs($difference), $orderId, $userId, $keterangan);
        }
        
        return ['success' => true, 'message' => 'Tidak ada perubahan quantity'];
    }

    /**
     * Kurangi stok menu
     */
    public function reduceMenuStock($menuId, $quantity, $orderId = null, $userId = null, $keterangan = null)
    {
        $menu = Menu::with('resep.bahanBaku')->find($menuId);
        
        if (!$menu) {
            return ['success' => false, 'message' => 'Menu tidak ditemukan'];
        }

        if ($menu->tipe_stok === 'Stok Bahan Baku') {
            return $this->prosesOrderDenganBahanBaku($menu, $quantity, $orderId);
        } else {
            return $this->prosesOrderManual($menu, $quantity, $orderId);
        }
    }

    /**
     * Kembalikan stok menu
     */
    public function restoreMenuStock($menuId, $quantity, $orderId = null, $userId = null, $keterangan = null)
    {
        $menu = Menu::with('resep.bahanBaku')->find($menuId);
        
        if (!$menu) {
            return ['success' => false, 'message' => 'Menu tidak ditemukan'];
        }

        DB::beginTransaction();
        
        try {
            if ($menu->tipe_stok === 'Stok Bahan Baku') {
                // Kembalikan stok bahan baku
                $bahanBaku = $menu->bahanBaku;
                if ($bahanBaku) {
                    $bahanBaku->updateStok($quantity);
                }
            } else {
                // Kembalikan stok manual
                $menu->updateStok($quantity);
            }
            
            DB::commit();
            return ['success' => true, 'message' => 'Stok berhasil dikembalikan'];
            
        } catch (\Exception $e) {
            DB::rollback();
            return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
        }
    }
}
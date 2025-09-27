# Flowchart Sistem Stok Management

## 1. Alur Order Processing

```
┌─────────────────┐
│ Customer Order  │
└─────────┬───────┘
          │
          ▼
┌─────────────────┐
│ Ambil Data Menu │
└─────────┬───────┘
          │
          ▼
     ┌─────────┐
     │ Cek     │
     │ Tipe    │ ──Manual──► ┌─────────────────────┐
     │ Stok    │             │ Cek Stok Menu       │
     │ Menu?   │             │ Langsung            │
     └─────────┘             └──────────┬──────────┘
          │                             │
          │                             ▼
     Bahan Baku                   ┌─────────┐
          │                       │ Stok    │
          ▼                       │ Menu    │ ──Ya──► ┌─────────────────┐
┌─────────────────┐               │ Cukup?  │         │ Kurangi Stok    │
│ Ambil Resep     │               └─────────┘         │ Menu            │
│ Menu            │                    │              └─────────┬───────┘
└─────────┬───────┘                    │                        │
          │                         Tidak                       ▼
          ▼                            │              ┌─────────────────┐
┌─────────────────┐                    │              │ Log Perubahan   │
│ Loop Setiap     │                    │              │ Stok Menu       │
│ Bahan Baku      │                    │              └────────┬───── ──┘
└─────────┬───────┘                    │                       │
          │                            │                       │
          ▼                            ▼                       │
     ┌─────────┐                  ┌─────────────────┐          │
     │ Stok    │                  │ Tolak Order     │          │
     │ Bahan   │ ──Tidak────────► │                 │          │
     │ Baku    │                  └─────────┬───────┘          │
     │ Cukup?  │                            │                  │
     └─────────┘                            ▼                  │
          │                       ┌─────────────────┐          │
          │Ya                     │ Notifikasi      │          │
          ▼                       │ Stok Tidak      │          │
┌─────────────────┐               │ Cukup           │          │
│ Kurangi Stok    │               └─────────────────┘          │
│ Bahan Baku      │                                            │
└─────────┬───────┘                                            │
          │                                                    │
          ▼                                                    │
┌─────────────────┐                                            │
│ Log Perubahan   │                                            │
│ Stok            │                                            │
└─────────┬───────┘                                            │
          │                                                    │
          ▼                                                    │
     ┌─────────┐                                               │
     │ Masih   │                                               │
     │ Ada     │ ──Ya──┐                                       │
     │ Bahan   │       │                                       │
     │ Lain?   │       │                                       │
     └─────────┘       │                                       │
          │            │                                       │
       Tidak           │                                       │
          │            │                                       │
          ▼            │                                       │
┌─────────────────┐    │                                       │
│ Order Berhasil  │◄───┘                                       │
└─────────┬───────┘◄───────────────────────────────────────────┘
          │
          ▼
┌─────────────────┐
│ Lanjut Proses   │
│ Order           │
└─────────────────┘
```

## 2. Alur Restock Management

```
┌─────────────────┐
│ Admin Login     │
└─────────┬───────┘
          │
          ▼
┌─────────────────┐
│ Pilih Jenis     │
│ Restock        │
└─────────┬───────┘
          │
    ┌─────┼─────┐
    │     │     │
Bahan Baku  │  Menu Manual
    │     │     │
    ▼     │     ▼
┌─────────────────┐ │ ┌─────────────────┐
│ Input Data      │ │ │ Input Data      │
│ Bahan Baku      │ │ │ Menu            │
└─────────┬───────┘ │ └─────────┬───────┘
          │         │           │
          ▼         │           ▼
┌─────────────────┐ │ ┌─────────────────┐
│ Input Jumlah    │ │ │ Input Jumlah    │
│ Masuk           │ │ │ Masuk           │
└─────────┬───────┘ │ └─────────┬───────┘
          │         │           │
          ▼         │           ▼
┌─────────────────┐ │ ┌─────────────────┐
│ Update Stok     │ │ │ Update Stok     │
│ Bahan Baku      │ │ │ Menu            │
└─────────┬───────┘ │ └─────────┬───────┘
          │         │           │
          ▼         │           ▼
┌─────────────────┐ │ ┌─────────────────┐
│ Log Transaksi   │ │ │ Log Transaksi   │
│ Masuk           │ │ │ Masuk           │
└─────────┬───────┘ │ └─────────┬───────┘
          │         │           │
          ▼         │           │
┌─────────────────┐ │           │
│ Recalculate     │ │           │
│ Menu Terkait    │ │           │
└─────────┬───────┘ │           │
          │         │           │
          └─────────┼───────────┘
                    │
                    ▼
          ┌─────────────────┐
          │ Notifikasi      │
          │ Berhasil        │
          └─────────────────┘
```

## 3. Alur Monitoring Dashboard

```
┌─────────────────┐
│ Dashboard Load  │
└─────────┬───────┘
          │
          ▼
┌─────────────────┐
│ Query Bahan     │
│ Baku Kritis     │
└─────────┬───────┘
          │
          ▼
┌─────────────────┐
│ Query Menu      │
│ Manual Kritis   │
└─────────┬───────┘
          │
          ▼
┌─────────────────┐
│ Query Menu      │
│ Bahan Baku      │
└─────────┬───────┘
          │
          ▼
┌─────────────────┐
│ Hitung Stok     │
│ Menu dari       │
│ Bahan Baku      │
└─────────┬───────┘
          │
          ▼
┌─────────────────┐
│ Filter Menu     │
│ Kritis          │
└─────────┬───────┘
          │
          ▼
┌─────────────────┐
│ Gabungkan       │
│ Semua Data      │
│ Kritis          │
└─────────┬───────┘
          │
          ▼
┌─────────────────┐
│ Tampilkan       │
│ Alert           │
└─────────┬───────┘
          │
          ▼
┌─────────────────┐
│ Tampilkan       │
│ Grafik Stok     │
└─────────┬───────┘
          │
          ▼
┌─────────────────┐
│ Auto Refresh    │
│ setiap 5 menit  │
└─────────────────┘
```

## 4. Alur Perhitungan Stok Menu dari Bahan Baku

```
┌─────────────────────────┐
│ Menu dengan Tipe        │
│ 'bahan_baku'            │
└───────────┬─────────────┘
            │
            ▼
┌─────────────────────────┐
│ Ambil Semua Resep       │
└───────────┬─────────────┘
            │
            ▼
┌─────────────────────────┐ ◄──┐
│ Loop Setiap Bahan Baku  │    │
│ dalam Resep             │    │
└───────────┬─────────────┘    │
            │                  │
            ▼                  │
┌─────────────────────────┐    │
│ Hitung: Stok Bahan Baku │    │
│ ÷ Porsi Diperlukan      │    │
└───────────┬─────────────┘    │
            │                  │
            ▼                  │
┌─────────────────────────┐    │
│ Simpan Hasil            │    │
│ Perhitungan             │    │
└───────────┬─────────────┘    │
            │                  │
            ▼                  │
       ┌─────────┐             │
       │ Masih   │             │
       │ Ada     │ ──Ya────────┘
       │ Bahan   │
       │ Lain?   │
       └─────────┘
            │
         Tidak
            │
            ▼
┌─────────────────────────┐
│ Ambil Nilai Minimum     │
│ dari Semua Perhitungan  │
└───────────┬─────────────┘
            │
            ▼
┌─────────────────────────┐
│ Return Stok Menu        │
│ yang Tersedia           │
└─────────────────────────┘
```

## 5. Database Relationship Diagram

```
┌─────────────────────┐       ┌─────────────────────┐       ┌─────────────────────┐
│      KATEGORI       │       │        MENU         │       │     MENU_RESEP      │
├─────────────────────┤   1:N ├─────────────────────┤   1:N ├─────────────────────┤
│ • id (PK)           │◄──────┤ • id (PK)           │◄──────┤ • id (PK)           │
│ • kategori_nama     │       │ • nama_menu         │       │ • id_menu (FK)      │
└─────────────────────┘       │ • id_kategori (FK)  │       │ • id_bahan_baku(FK) │
                              │ • stok_tersedia     │       │ • porsi_diperlukan  │
                              │ • tipe_stok         │       └─────────────────────┘
                              |                     │
                              └─────────────────────┘                   │ N:1
                                       │                                │
                                       │ 1:N                            ▼
                                       ▼                    ┌─────────────────────┐
                              ┌─────────────────────┐       │     BAHAN_BAKU      │
                              │      STOK_LOG       │       ├─────────────────────┤
                              ├─────────────────────┤   N:1 │ • id (PK)           │
                              │ • id (PK)           │◄──────┤ • nama_bahan        │
                              │ • tipe              │       │ • stok_porsi        │
                              │ • id_item           │       |                     |
                              │ • jumlah_sebelum    │       └─────────────────────┘
                              │ • jumlah_perubahan  │                    │
                              │ • jumlah_sesudah    │                    │ 1:N
                              │ • keterangan        │                    │
                              └─────────────────────┘                    │
                                                                         ▼
                                                            ┌─────────────────────┐
                                                            │      STOK_LOG       │
                                                            │   (Bahan Baku)      │
                                                            └─────────────────────┘

Relationships:
• KATEGORI (1) ──── (N) MENU
• MENU (1) ──── (N) MENU_RESEP
• BAHAN_BAKU (1) ──── (N) MENU_RESEP  
• MENU (1) ──── (N) STOK_LOG
• BAHAN_BAKU (1) ──── (N) STOK_LOG

Table Details:

┌─────────────────────────────────────────────────────────────────────────────────┐
│                                   MENU                                         │
├─────────────────────────────────────────────────────────────────────────────────┤
│ • id (PK) - Primary Key                                                         │
│ • nama_menu - Nama menu makanan                                                 │
│ • id_kategori (FK) - Foreign Key ke tabel kategori                             │
│ • stok_tersedia - Jumlah stok menu yang tersedia                               │
│ • stok_minimum - Batas minimum stok untuk alert                                │
│ • tipe_stok - ENUM('manual', 'bahan_baku')                                     │
└─────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────┐
│                                BAHAN_BAKU                                      │
├─────────────────────────────────────────────────────────────────────────────────┤
│ • id (PK) - Primary Key                                                         │
│ • nama_bahan - Nama bahan baku                                                  │
│ • stok_porsi - Jumlah stok dalam porsi                                         │
│ • stok_minimum - Batas minimum stok untuk alert                                │
│ • harga_per_porsi - Harga per porsi bahan baku                                 │
└─────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────┐
│                               MENU_RESEP                                       │
├─────────────────────────────────────────────────────────────────────────────────┤
│ • id (PK) - Primary Key                                                         │
│ • id_menu (FK) - Foreign Key ke tabel menu                                     │
│ • id_bahan_baku (FK) - Foreign Key ke tabel bahan_baku                         │
│ • porsi_diperlukan - Jumlah porsi bahan baku untuk 1 porsi menu                │
└─────────────────────────────────────────────────────────────────────────────────┘
```

## 6. Contoh Skenario Penggunaan

### Skenario 1: Menu dengan Bahan Baku
- **Menu**: Nasi Goreng
- **Bahan Baku**: 
  - Dada Ayam: 1 porsi per menu (stok: 10 porsi)
  - Nasi: 1 porsi per menu (stok: 25 porsi)
  - Telur: 1 porsi per menu (stok: 20 porsi)

**Perhitungan Stok Menu**:
- Dari Dada Ayam: 10 ÷ 1 = 10 porsi
- Dari Nasi: 25 ÷ 1 = 25 porsi  
- Dari Telur: 20 ÷ 1 = 20 porsi
- **Stok Menu Tersedia**: min(10, 25, 20) = **10 porsi**

### Skenario 2: Order 5 Porsi Nasi Goreng
**Pengurangan Stok**:
- Dada Ayam: 10 - (5 × 1) = 5 porsi
- Nasi: 25 - (5 × 1) = 20 porsi
- Telur: 20 - (5 × 1) = 15 porsi

**Stok Menu Setelah Order**:
- Dari Dada Ayam: 5 ÷ 1 = 5 porsi
- Dari Nasi: 20 ÷ 1 = 20 porsi
- Dari Telur: 15 ÷ 1 = 15 porsi
- **Stok Menu Tersedia**: min(5, 20, 15) = **5 porsi**

## 7. Keuntungan Sistem

1. **Real-time Stock Tracking**: Stok selalu update otomatis
2. **Multi-level Inventory**: Bisa kelola stok bahan baku dan menu
3. **Flexible Management**: Menu bisa pakai stok manual atau bahan baku
4. **Complete Audit Trail**: Semua perubahan stok tercatat
5. **Proactive Alerts**: Notifikasi stok kritis
6. **Accurate Costing**: Bisa hitung cost per menu dari bahan baku
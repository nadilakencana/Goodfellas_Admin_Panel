# Dokumentasi Hubungan Antar File - Sistem POS Goodfellas

## 1. Dashboard POS (Point of Sales)

### File Utama:
- **View**: `resources/views/POS/dashboard_POS.blade.php`
- **Controller**: `app/Http/Controllers/POSController.php`
- **JavaScript**: `public/asset/assets/js/function_POS.js`
- **Route**: `routes/web.php` (route: `/POS-Dashboard-Goofellas`)

### Hubungan File:
```
dashboard_POS.blade.php
├── Menggunakan layout: layout/master.blade.php
├── Include partial views:
│   ├── POS/part_lain/popUp_Additional.blade.php
│   ├── POS/part_lain/Daftar-Bill.blade.php (dinamis)
│   └── POS/part_lain/PopUpDiscount.blade.php (dinamis)
├── JavaScript dependencies:
│   ├── function_POS.js (fungsi utama POS)
│   ├── idle timer check.js (timer idle)
│   └── pusher.min.js (real-time notifications)
└── Controller methods yang dipanggil:
    ├── POSdashboard() - tampilan utama
    ├── addOrder() - tambah item ke cart
    ├── postOrderPOS() - proses order
    ├── paymentProses() - proses pembayaran
    └── modifyBill() - edit order yang sudah ada
```

## 2. Menu Management

### File Utama:
- **View**: `resources/views/Menu/index.blade.php`
- **Controller**: `app/Http/Controllers/MenuController.php`
- **Model**: `app/Models/Menu.php`

### Hubungan File:
```
Menu/index.blade.php
├── Controller: MenuController.php
│   ├── indexMenu() - tampil daftar menu
│   ├── createMenu() - form tambah menu
│   ├── PushCreate() - simpan menu baru
│   ├── editMenu() - form edit menu
│   ├── UpdateMenu() - update menu
│   └── deleteMenu() - hapus menu
├── Related Views:
│   ├── Menu/create.blade.php
│   ├── Menu/edit.blade.php
│   └── Menu/bahanBaku.blade.php
└── Models yang terkait:
    ├── Menu.php
    ├── Kategori.php
    ├── SubKategori.php
    ├── VarianMenu.php
    └── BahanBaku.php
```

## 3. Order Management

### File Utama:
- **View**: `resources/views/Orders/index.blade.php`
- **Controller**: `app/Http/Controllers/OrderController.php`
- **Model**: `app/Models/Orders.php`

### Hubungan File:
```
Orders/index.blade.php
├── Controller: OrderController.php
│   ├── indexOrder() - daftar order
│   ├── detailOrder() - detail order
│   ├── updateOrderStatus() - update status
│   ├── refundMenuOrder() - refund menu
│   └── DeleteOrder() - hapus order
├── Related Views:
│   ├── Orders/detail.blade.php
│   ├── Orders/edit.blade.php
│   └── Orders/filter_data.blade.php
└── Models yang terkait:
    ├── Orders.php
    ├── DetailOrder.php
    ├── StatusOrder.php
    ├── RefundOrder.php
    └── TaxOrder.php
```

## 4. Report System

### File Utama:
- **View**: `resources/views/Report/ReportSales.blade.php`
- **Controller**: `app/Http/Controllers/ReportSalesController.php`
- **Export Controller**: `app/Http/Controllers/ExportLaporanController.php`

### Hubungan File:
```
Report/ReportSales.blade.php
├── Controller: ReportSalesController.php
│   ├── Report() - laporan utama
│   ├── pymentMethod() - laporan metode pembayaran
│   ├── ItemSales() - laporan penjualan item
│   ├── GrossProfit() - laporan gross profit
│   └── Category() - laporan kategori
├── Export Controller: ExportLaporanController.php
│   ├── ExportSalesSummary() - export ringkasan
│   ├── ExportPaymentMethode() - export metode bayar
│   ├── ExportGrossProfit() - export gross profit
│   └── ExportItemSales() - export item sales
├── Related Views:
│   ├── Report/Sales.blade.php
│   ├── Report/ItemSales.blade.php
│   ├── Report/grossProfit.blade.php
│   └── Report/export_report/ (folder export views)
└── Export Classes:
    ├── app/Exports/ReportSalesExport.php
    ├── app/Exports/ItemSalesExport.php
    └── app/Exports/GrossProfitExport.php
```

## 5. Authentication System

### File Utama:
- **View**: `resources/views/Auth/login.blade.php`
- **Controller**: `app/Http/Controllers/AuthController.php`
- **Model**: `app/Models/Admin.php`

### Hubungan File:
```
Auth/login.blade.php
├── Controller: AuthController.php
│   ├── login() - tampil form login
│   ├── pushlogin() - proses login
│   ├── logOut() - logout
│   ├── DataUser() - data user
│   └── DataAdmin() - data admin
├── Related Views:
│   ├── Auth/registrasi.blade.php
│   ├── Auth/profile.blade.php
│   └── Auth/forget_password.blade.php
└── Models yang terkait:
    ├── Admin.php
    ├── User.php
    └── Level.php
```

## 6. Category Management

### File Utama:
- **View**: `resources/views/Kategori/index.blade.php`
- **Controller**: `app/Http/Controllers/KategoriController.php`
- **Model**: `app/Models/Kategori.php`
- **JavaScript**: `resources/js/kategori_check_fix.js`

### Hubungan File:
```
Kategori/index.blade.php
├── Controller: KategoriController.php
│   ├── indexKat() - daftar kategori
│   ├── createKat() - form tambah kategori
│   ├── pushKat() - simpan kategori
│   ├── editKat() - form edit kategori
│   ├── UpadateKategori() - update kategori
│   └── deleteKat() - hapus kategori
├── Related Views:
│   ├── Kategori/create.blade.php
│   └── Kategori/edit.blade.php
├── JavaScript:
│   └── kategori_check_fix.js
└── Models yang terkait:
    ├── Kategori.php
    └── SubKategori.php
```

## 7. Payment & Discount System

### File Utama:
- **Controller**: `app/Http/Controllers/PaymentController.php`
- **Views**: `resources/views/TypePayment/` & `resources/views/Discount/`
- **Models**: `app/Models/TypePayment.php` & `app/Models/Discount.php`

### Hubungan File:
```
Payment & Discount System
├── PaymentController.php
│   ├── TypePayment() - daftar tipe pembayaran
│   ├── CreateDataPaymentType() - form tambah tipe
│   ├── Discount() - daftar discount
│   └── CreateDataDis() - form tambah discount
├── Views:
│   ├── TypePayment/data.blade.php
│   ├── TypePayment/CreateData.blade.php
│   ├── Discount/data.blade.php
│   └── Discount/CreateData.blade.php
└── Models:
    ├── TypePayment.php
    ├── Discount.php
    └── Discount_detail_order.php
```

## 8. Print System

### File Utama:
- **Controller**: `app/Http/Controllers/PrintController.php`
- **Views**: `resources/views/POS/part_lain/print_*.blade.php`

### Hubungan File:
```
Print System
├── PrintController.php
│   ├── printBill() - print bill thermal
│   ├── printTicket() - print tiket thermal
│   ├── printKitchen() - print kitchen thermal
│   └── printShiftThermal() - print shift thermal
├── POSController.php (print methods):
│   ├── PrintBill() - print bill view
│   ├── printTiket() - print tiket view
│   ├── printKitchen() - print kitchen view
│   └── printData() - generate print file
├── Print Views:
│   ├── POS/part_lain/print_bill.blade.php
│   ├── POS/part_lain/print_tiket.blade.php
│   ├── POS/part_lain/print_kitchen.blade.php
│   └── cash/print_sift.blade.php
└── Print Templates:
    ├── public/asset/assets/file_print/test-template.docx
    ├── public/asset/assets/file_print/template-tiket.docx
    └── public/asset/assets/file_print/template-kitchen.docx
```

## 9. Customer Order System

### File Utama:
- **Controller**: `app/Http/Controllers/OrderCustomerController.php`
- **Views**: `resources/views/CustomerOrder/`
- **JavaScript**: `public/asset/assets/js/OrderCustomer.js`

### Hubungan File:
```
Customer Order System
├── OrderCustomerController.php
│   ├── index() - halaman utama customer
│   ├── category() - kategori menu
│   ├── AddTocart() - tambah ke cart
│   ├── PostOrderCustomer() - submit order
│   └── searchMenu() - cari menu
├── Views:
│   ├── CustomerOrder/index.blade.php
│   ├── CustomerOrder/categoryMenu.blade.php
│   ├── CustomerOrder/CartCustomer.blade.php
│   └── CustomerOrder/main_content.blade.php
├── JavaScript:
│   └── OrderCustomer.js
└── Related Models:
    ├── Orders.php
    ├── DetailOrder.php
    └── QRTable.php
```

## 10. Cash Management

### File Utama:
- **Controller**: `app/Http/Controllers/CashController.php`
- **Views**: `resources/views/cash/`
- **Model**: `app/Models/Cash.php`

### Hubungan File:
```
Cash Management
├── CashController.php
│   ├── DataShift() - data shift kasir
│   ├── startSift() - mulai shift
│   ├── EndSift() - akhiri shift
│   ├── detailSift() - detail shift
│   └── print_sift() - print laporan shift
├── Views:
│   ├── cash/dataSiftCash.blade.php
│   ├── cash/detailSift.blade.php
│   └── cash/print_sift.blade.php
└── Models:
    ├── Cash.php
    ├── Sift.php
    └── Orders.php (untuk kalkulasi)
```

## 11. Layout & Master Template

### File Utama:
- **Master Layout**: `resources/views/layout/master.blade.php`
- **Navbar**: `resources/views/layout/navbar.blade.php`
- **Sidebar**: `resources/views/layout/sidebar.blade.php`

### Hubungan File:
```
Layout System
├── layout/master.blade.php (template utama)
│   ├── Include: layout/navbar.blade.php
│   ├── Include: layout/sidebar.blade.php
│   ├── CSS: public/asset/tamplate/css/
│   └── JS: public/asset/tamplate/js/
├── Digunakan oleh semua views:
│   ├── @extends('layout.master')
│   └── @section('content')
└── Assets:
    ├── public/asset/tamplate/ (AdminLTE template)
    ├── public/asset/assets/css/ (custom CSS)
    └── public/asset/assets/js/ (custom JS)
```

## 12. API & Real-time Features

### File Utama:
- **API Controller**: `app/Http/Controllers/ApiGetDataServerController.php`
- **Events**: `app/Events/MessageCreated.php`
- **Pusher Config**: `config/broadcasting.php`

### Hubungan File:
```
Real-time System
├── Events:
│   ├── MessageCreated.php - event order baru
│   └── OrderCustomerCreate.php - event customer order
├── Pusher Integration:
│   ├── pusher.min.js (client-side)
│   ├── config/broadcasting.php (config)
│   └── Dashboard POS (listener)
├── API Endpoints:
│   └── ApiGetDataServerController.php
└── Notifications:
    ├── audio_notif.wav
    ├── audio_order.mp3
    └── notif-frame.blade.php
```

## 13. Database Models & Relationships

### Model Relationships:
```
Database Models
├── Orders.php
│   ├── hasMany: DetailOrder
│   ├── belongsTo: Admin, User, StatusOrder, TypePayment
│   └── hasOne: BookingTempat
├── DetailOrder.php
│   ├── belongsTo: Orders, Menu, VarianMenu, SalesType
│   ├── hasMany: Additional_menu_detail, Discount_detail_order
│   └── hasOne: RefundOrderMenu
├── Menu.php
│   ├── belongsTo: Kategori, SubKategori, GroupModifier
│   ├── hasMany: VarianMenu, DetailOrder
│   └── hasOne: BahanBaku, MenuResep
├── Admin.php
│   ├── hasMany: Orders, Aktivity
│   └── belongsTo: Level
└── User.php
    ├── hasMany: Orders, Point_User, Notify_user
    └── hasMany: VocherClaimUser
```

## 14. Services & Business Logic

### Service Classes:
```
Services
├── app/Services/KodePesananService.php
│   └── kodePesanan() - generate kode order
├── app/Services/StokService.php
│   ├── cekKetersediaanMenu() - cek stok menu
│   ├── prosesOrder() - proses stok order
│   ├── adjustMenuStock() - adjust stok
│   └── restoreMenuStock() - restore stok
└── Digunakan oleh:
    ├── POSController.php
    ├── OrderController.php
    └── MenuController.php
```

## 15. Routes & URL Structure

### Route Groups:
```
routes/web.php
├── Authentication Routes:
│   ├── GET / → AuthController@login
│   ├── POST /login → AuthController@pushlogin
│   └── GET /logout → AuthController@logOut
├── POS Routes:
│   ├── GET /POS-Dashboard-Goofellas → POSController@POSdashboard
│   ├── POST /data-session → POSController@addOrder
│   ├── POST /post-order-pos → POSController@postOrderPOS
│   └── POST /update-payment-order → POSController@paymentProses
├── Menu Routes:
│   ├── GET /menu → MenuController@indexMenu
│   ├── GET /create/menu → MenuController@createMenu
│   └── POST /create/push/menu → MenuController@PushCreate
├── Order Routes:
│   ├── GET /orders → OrderController@indexOrder
│   ├── GET /detail-order/{kode} → OrderController@detailOrder
│   └── POST /detail-order/update/status/{kode} → OrderController@updateOrderStatus
└── Report Routes:
    ├── GET /report → ReportSalesController@Report
    ├── POST /export-sales-summery → ExportLaporanController@ExportSalesSummary
    └── GET /gross-profit → ReportSalesController@GrossProfit
```

## Kesimpulan

Sistem POS Goodfellas menggunakan arsitektur MVC Laravel dengan struktur yang terorganisir:

1. **Controllers** menangani logika bisnis dan komunikasi dengan database
2. **Views** menggunakan Blade templating dengan layout master yang konsisten
3. **Models** mendefinisikan relasi database dan business logic
4. **JavaScript** menangani interaksi real-time dan AJAX calls
5. **Services** memisahkan business logic kompleks dari controllers
6. **Events & Broadcasting** untuk fitur real-time menggunakan Pusher

Setiap modul memiliki struktur yang konsisten dengan pemisahan concern yang jelas, memudahkan maintenance dan pengembangan lebih lanjut.
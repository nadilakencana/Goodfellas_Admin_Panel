<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\SubKategoriController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VipRoomController;
use App\Http\Controllers\VocherGiftController;
use App\Http\Controllers\ModifierController;
use App\Http\Controllers\TaxesController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportSalesController;
use App\Http\Controllers\ExportLaporanController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\CashController;
use App\Models\VocherGift;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Stmt\Return_;
use App\Http\Controllers\OrderCustomerController;
use App\Http\Controllers\QrCodeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', function () {
//     return view('dashboard');
// });
// Route::get('/regist', function () {
//     return view('Auth.registrasi');
// });

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'pushlogin'])->name('login.push');
Route::get('/registrasi', [RegisterController::class,'regis'])->name('regist');
Route::post('/push/regis',[RegisterController::class, 'pushRegist'])->name('push.regist');
Route::get('/logout', [AuthController::class, 'logOut'])->name('logout');
// Route::middleware(['auth:admin'])->group(function () {
Route::get('/test-print',[PrintController::class, 'testPrintKitchen'])->name('test.print');
Route::get('/test-print-cancel',[PrintController::class, 'testPembatalanKitchen'])->name('test.print.cancel');
Route::get('/test-print-bill',[PrintController::class, 'testPrintBill'])->name('test.print.bill');
Route::get('/test-print-page',[PrintController::class, 'testPageWidht'])->name('test.print.page');
Route::get('/test-print-logo',[PrintController::class, 'testPrintLogo'])->name('test.print.logo');
Route::get('/Dashboard', [DashboardController::class, 'index'])->name('Dashboard');
Route::get('/notif-frame', [DashboardController::class, 'notifFrame'])->name('notif');

//Level Dan User/admin
Route::controller(AuthController::class)->group(function () {
    Route::get('/data-user', 'DataUser')->name('dataUser');
    Route::get('/data-Admin', 'DataAdmin')->name('dataAdmin');
    Route::get('/edit-Admin/{id}', 'editDataAdmin')->name('editDataAdmin');
    Route::post('/update-Admin/{id}', 'udpdateDataAdmin')->name('udpdateDataAdmin');
    Route::delete('/delete-data-admin/{id}', 'deleteDataAdmin')->name('deleteDataAdmin');
    Route::post('/update-password-admin', 'ResetPassword')->name('ResetPassword');
    Route::get('data-level', 'levelLog')->name('LevelLog');
    Route::post('/create-Level', 'createLevel')->name('createLevel');
    Route::post('/update-level/{id}',  'UpdateLevel')->name('UpdateLevel');
    Route::delete('delet-data/{id}', 'DeteletLevel')->name('DeteletLevel');
});

//Menu
Route::get('/menu', [MenuController::class, 'indexMenu'])->name('menu');
Route::get('/create/menu',[MenuController::class, 'createMenu'])->name('create.menu');
Route::post('create/push/menu', [MenuController::class, 'PushCreate'])->name('push.menu');
Route::get('/edit/menu/{id}', [MenuController::class,'editMenu'])->name('edit.menu');
Route::put('/update/push/menu/{id}',[MenuController::class,'UpdateMenu'])->name('update.menu');
Route::delete('/delete/menu/{id}',[MenuController::class,'deleteMenu'])->name('delete');

// contactUs
Route::get('data-Contact-Us', [ContactUsController::class, 'dataContact'])->name('contactUs');

//Kategori
Route::get('/kategori',[KategoriController::class, 'indexKat'])->name('kategori');
Route::get('/create/kategori', [KategoriController::class,'createKat'])->name('create.kat');
Route::post('/push/kategori',[KategoriController::class,'pushKat'])->name('kat.push');
Route::get('/edit/kategori/{id}',[KategoriController::class,'editKat'])->name('edit.kat');
Route::put('/push/edit/kategori/{id}', [KategoriController::class, 'UpadateKategori'])->name('update.kat');
Route::delete('/delete/kategori/{id}', [KategoriController::class, 'deleteKat'])->name('delete.kat');

//Sub Kategori
Route::get('/sub-kategori',[SubKategoriController::class, 'indexSubKat'])->name('subkategori');
Route::get('/create/sub-kategori', [SubKategoriController::class,'createSubKat'])->name('create.subKat');
Route::post('/push/sub-kategori',[SubKategoriController::class,'pushSubKat'])->name('subKat.push');
Route::get('/edit/sub-kategori/{id}',[SubKategoriController::class,'editSubKat'])->name('edit.subKat');
Route::put('/push/edit/sub-kategori/{id}', [SubKategoriController::class, 'UpdateSubKat'])->name('update.subKat');
Route::get('/delete/sub-kategori/{id}', [SubKategoriController::class, 'deleteSubKat'])->name('delete.subKat');

//Order

Route::get('/orders',[OrderController::class,'indexOrder'])->name('order');
Route::get('/detail-order/{kode}', [OrderController::class,'detailOrder'])->name('detail.order');
Route::post('/detail-order/update/status/{kode}', [OrderController::class,'updateOrderStatus'])->name('status.update');
Route::post('/get-data', [OrderController::class, 'laporan'])->name('laporan');
Route::post('/Refund-menu', [OrderController::class,'refundMenuOrder'])->name('refund');
Route::get('/filter-data-order', [OrderController::class, 'filterPeriode'])->name('filter');
Route::get('/delete-order/{id}', [OrderController::class, 'DeleteOrder'])->name('delete_order');
Route::post('/delete-data', [OrderController::class, 'DeleteDataOrder'])->name('delete-order');

//report

Route::controller(ReportSalesController::class)->group(function () {
    Route::get('/report', 'Report')->name('report');
    Route::get('/payment', 'pymentMethod')->name('payment');
    Route::get('/sales-type', 'SelesType')->name('SelesType');
    Route::get('/item-sales', 'ItemSales')->name('Item-Sales');
    Route::get('/modifier', 'Modifier')->name('Modifier');
    Route::get('/discount', 'Discount')->name('Discount');
    Route::get('/taxes', 'Taxes')->name('Taxes');
    Route::get('/filterSummary', 'fileterSalesSummary')->name('fileterSalesSummary');
    Route::get('/gross-profit', 'GrossProfit')->name('GrossProfit');
    Route::get('/categori', 'Category')->name('Category');
    Route::get('/data-penjualan','viewReport')->name('penjualan-data');
});

Route::controller(ExportLaporanController::class)->group(function(){
    Route::post('/export-sales-summery', 'ExportSalesSummary')->name('salesSummary');
    Route::post('/export-payment-method', 'ExportPaymentMethode')->name('PaymentMethod');
    Route::post('/export-gross-profit', 'ExportGrossProfit')->name('grossprofit');
    Route::post('/export-sales-type', 'ExportSelesType')->name('SalesType');
    Route::post('/export-Item-sales', 'ExportItemSales')->name('ItemSales');
    Route::post('/export-modifier', 'ExportModifier')->name('modifier');
    Route::post('/export-discount', 'ExportDiscount')->name('discount');
    Route::post('/export-taxes', 'ExportTaxes')->name('taxes');
    Route::post('/export-category', 'ExportCategory')->name('category');
    Route::post('/getDetail-transection', 'transactionDetail')->name('detail-transaction');
});

//Vip Room

Route::get('/vip-room', [VipRoomController::class, 'indexRoom'])->name('Vip-Room');
Route::get('/vip-room/create',[VipRoomController::class, 'createRoom'])->name('create.room');
Route::post('/vip-room/push/create',[VipRoomController::class, 'pushCreate'])->name('create-push.room');
Route::get('/vip-room/edit/{id}',[VipRoomController::class, 'editRoom'])->name('edit.room');
Route::put('/vip-room/edit/push/{id}', [VipRoomController::class,'updateRoom'])->name('update.room');
Route::delete('/vip-room/delete/{id}', [VipRoomController::class, 'DeleteRoom'])->name('delete.room');

// booking
Route::get('/room-booking', [VipRoomController::class, 'DataBooking'])->name('data.booking');
Route::get('/detail/booking/{kode}',[VipRoomController::class,'detailBooking'])->name('detail.booking');
Route::post('/update-status/{kode}', [VipRoomController::class,'updateBookingStatus'])->name('update-status-booking');
Route::delete('/delete-data-booking/{id}', [VipRoomController::class,'DeletedataBook'])->name('delete-data-booking');

//POS Dashboard
Route::get('/POS-Dashboard-Goofellas', [POSController::class,'POSdashboard'])->name('pos');
Route::get('/get-menu-discount',[POSController::class,'PartMenuDiscount'])->name('getMenuDiscount');
Route::get('/get-all-menu',[POSController::class, 'PartAllMenu'])->name('allmenu');
Route::get('/get-SubMenu/{id}',[POSController::class,'PartSubMenu'])->name('subMenu');
Route::get('/variasi-menu', [POSController::class,'getVariasi'])->name('variasi-menu');
Route::get('/option-additional', [POSController::class,'getOptionAdditional'])->name('option-add');
Route::post('delete/item',[POSController::class,'hapus'])->name('item.delete');
Route::post('post-order-pos', [POSController::class, 'postOrderPOS'])->name('POS-Order');
Route::get('data-detail-order-ref',[POSController::class,'getDataBillDetail'])->name('ref-detail-bil');
Route::post('modify-bill', [POSController::class, 'modifyBill'])->name('billModify');
Route::post('Order-Update', [POSController::class, 'updateOrder'])->name('updateorder');
Route::post('/update-item-order', [POSController::class, 'editOrder'])->name('edit-item');
Route::post('delete-modify', [POSController::class, 'deletemodify'])->name('Delete-item');

// log activity
Route::post('activity-log', [POSController::class, 'Action_log'])->name('action-log');
Route::post('update-sales-type', [POSController::class, 'updateSalesTypeOnDetailOrder'])->name('update_sales_type');
// ssetion clear
Route::get('/clear-session', [POSController::class, 'clearSession'])->name('sessionClear');
// payment
Route::post('/update-payment-order', [POSController::class, 'paymentProses'])->name('pyment-order');
// print bill
Route::get('bill-order/{id}', [POSController::class, 'PrintBill'])->name('print-bill');
Route::get('bill-tiket/{id}', [POSController::class, 'printTiket'])->name('print-tiket');
Route::get('bill-kitchen/{id}', [POSController::class, 'printKitchen'])->name('print-kitchen');
Route::post('print/{id}', [POSController::class, 'printData'])->name('print');
Route::post('update-last-print/{id}', [POSController::class, 'updateLastPrint'])->name('update_last_print');
Route::post('print-bill-thermal/{id}', [PrintController::class, 'printBill'])->name('print-bill-thermal');
//Route::get('print-bill-thermal/{id}', [PrintController::class, 'printBill'])->name('print-bill-thermal');
Route::post('print-ticket-thermal/{id}', [PrintController::class, 'printTicket'])->name('print-ticket-thermal');
Route::post('print-kitchen-thermal/{id}', [PrintController::class, 'printKitchen'])->name('print-kitchen-thermal');
Route::get('reprint-kitchen-thermal/{id}', [PrintController::class, 'printUlangKitchen'])->name('reprint-kitchen-thermal');

// print item delete
Route::post('print-item-delete', [POSController::class, 'printDataItemDelete'])->name('print_item_delete');
Route::post('print-item-delete-thermal', [PrintController::class, 'printDataItemDeleteThermal'])->name('print_item_delete_thermal');
Route::post('item-delete', [POSController::class, 'afterPrintDelete'])->name('item_delete');

Route::post('/split-bill', [POSController::class,'splitBill'])->name('split-bill');
Route::get('/detail-itms-split', [POSController::class,'itemSplitBill'])->name('bill-split');
Route::get('/detail-itms-split-server/{id}', [POSController::class,'ItemSplitTodataServer'])->name('bill-split-server');
Route::get('/data-print-server/{id}', [POSController::class, 'printTodataServer'])->name('data-print-server');
Route::get('/data-menu-kategori/{id}', [POSController::class, 'partMenuKat'])->name('menu-kategori-pos');
Route::get('/data-session-order', [POSController::class, 'dataDetailOrder'])->name('view_detail_session');
Route::get('/data-bill', [POSController::class, 'DataBill'])->name('dataBill');
Route::get('pos/data-discount', [POSController::class, 'getDiscount'])->name('dataDiscount');

// });
// data Vocher
Route::get('/data-vocher', [VocherGiftController::class,'data'])->name('vocher-gif');
Route::get('/form-create-vocher-gift', [VocherGiftController::class,'create_data'])->name('form-create-vocher');
Route::post('/create-data-gift-Vocher',[VocherGiftController::class, 'post_create_data'])->name('create-vocher');
Route::get('/form-edit-data/{id}', [VocherGiftController::class,'Edit_data'])->name('edit-data-vocher');
Route::put('/post-data-edit/{id}', [VocherGiftController::class,'post_Edit_data'])->name('post-edit-vocher');
Route::delete('/hapus-data-vocher/{id}', [VocherGiftController::class,'deleteData'])->name('hapus-vocher');
Route::get('/claim-vocher', [VocherGiftController::class, 'claimVocherUser'])->name('claimVocher');
Route::get('/vocher', [VocherGiftController::class, 'detailclaim'])->name('vocher');
Route::post('/vocher-claim', [VocherGiftController::class, 'claimUserVocher'])->name('vocher-claim');

// modifier
Route::get('/data-Modifier', [ModifierController::class,'dataModif'])->name('dataModifier');
Route::get('/create-data-Modifier',[ModifierController::class, 'CreateGroup'])->name('create-data');
Route::get('/edit-data-Modifier/{id}',[ModifierController::class, 'editDataGroup'])->name('edit-data');
Route::post('/Post-data-modifier', [ModifierController::class,'postCreateGroupModif'])->name('post-data-create');
Route::post('/update-data-modifier/{id}', [ModifierController::class, 'postEditGroup'])->name('update-data-modifier');
Route::get('/detail-data/{id}', [ModifierController::class, 'detailData'])->name('detail-modifier');
Route::delete('/delete-data-modifier/{id}',[ModifierController::class, 'hapusData'])->name('hapus-data-modifier');

// type Sales
Route::get('/Type-Sales', [OrderController::class,'salestype'])->name('data-SalesType');
Route::get('/Create-Type-Sales',[OrderController::class, 'createSalesType'])->name('create-type');
Route::post('/Post-data-type-sales', [OrderController::class, 'postTypeSales'])->name('post-data-type');
Route::get('/Edit-data-sales-type/{id}',[OrderController::class, 'EditSalesType'])->name('Edit-data-type');
Route::post('/Update-data-type-sales/{id}', [OrderController::class, 'UpdateSalesType'])->name('update-type-sales');
Route::delete('/delete-data-type-sales/{id}',[OrderController::class, 'DeleteTypeSales'])->name('delete-data-type');

// Taxes
Route::get('/Data-Tax',[TaxesController::class,'dataTax'])->name('data-tax');
Route::get('/Create-Data-Taxes', [TaxesController::class, 'createTax'])->name('Create-data-tax');
Route::post('/post-data-tax',[TaxesController::class,'postTax'])->name('post-data-tax');
Route::get('/Edit-data-tax/{id}',[TaxesController::class,'EditDataTax'])->name('edit-data-tax');
Route::post('/Update-data-tax/{id}',[TaxesController::class,'UpdateDataTax'])->name('update-data-tax');
Route::delete('/Delete-data-tax/{id}',[TaxesController::class,'deleteTax'])->name('delete-data-tax');

// type Payment
Route::get('/data-type-payment', [PaymentController::class, 'TypePayment'])->name('data-Payment');
Route::get('/create-data-type-payment',[PaymentController::class, 'CreateDataPaymentType'])->name('Create-data-payment');
Route::post('/post-data-payment', [PaymentController::class, 'postDataPaymentType'])->name('post-data-payment');
Route::get('/Edit-data-type-payment/{id}',[PaymentController::class, 'editDataTypePayment'])->name('edit-type-payment');
Route::post('/Update-data-type-payment/{id}',[PaymentController::class, 'updateDataTypePayment'])->name('update-data-type-payment');
Route::delete('/hapus-data-type-payment/{id}',[PaymentController::class, 'deleteTypePayment'])->name('delete-data-payment');

// discount
Route::get('/data-discount', [PaymentController::class, 'Discount'])->name('data-discount');
Route::get('/create-data-discount',[PaymentController::class, 'CreateDataDis'])->name('Create-data-discount');
Route::post('/post-data-discount', [PaymentController::class, 'PostDataDiscount'])->name('post-data-discount');
Route::get('/Edit-data-discount/{id}',[PaymentController::class, 'EditDataDis'])->name('edit-type-discount');
Route::post('/Update-data-discount/{id}',[PaymentController::class, 'UpdateDataDiscount'])->name('update-data-discount');
Route::delete('/hapus-data-discount/{id}',[PaymentController::class, 'deleteDiscount'])->name('delete-data-discount');

Route::post('data-session',[POSController::class,'addOrder'])->name('addOrder');
Route::controller(CashController::class)->group(function(){
    Route::get('data-sift', 'DataShift')->name('cash_sift');
    Route::post('start-sift', 'startSift')->name('start_sift');
    Route::post('end-sift/{id}', 'EndSift')->name('end_sift');
    Route::get('detail-sift/{id}', 'detailSift')->name('detail_sift');
    Route::post('kas', 'kas')->name('kas');
    Route::get('print-sift/{id}', 'print_sift')->name('print_sift');
    Route::get('print-report-sift/{id}', 'Print_report')->name('print_report');
});

Route::get('print-sift-thermal/{id}', [PrintController::class, 'printShiftThermal'])->name('print.shift.thermal');

Route::controller(OrderCustomerController::class)->group(function(){
    Route::get('Order/Customer', 'index')->name('Orders.customer');
    Route::get('category/{slug}', 'category')->name('OrderCustomer.category');
    Route::get('Sub-category/{slug}', 'Subcat')->name('OrderCustomer.Subcategory');
    Route::get('additional-pop', 'additional')->name('popAdditional');
    Route::post('add-to-cart', 'AddTocart')->name('Order.customer.add_cart');
    Route::get('cart', 'cartSession')->name('Order.customer.cart');
    Route::get('clear-session', 'clearSession')->name('Order.customer.clearSession');
    Route::post('delete-item-cart', 'hapus')->name('Order.customer.itemDelete');
    Route::post('edit-item-cart', 'editOrder')->name('Order.customer.itemEdit');
});

Route::controller(QrCodeController::class)->group(function(){
    Route::get('data-Qr', 'index')->name('Qr-table');
    Route::get('create-Qr', 'CraateQRTable')->name('Qr-table.form');
    Route::post('post-Qr', 'PostQRTable')->name('Qr-table.post');
    Route::get('QR-download', 'QRDetail')->name('Qr-table.download');

});
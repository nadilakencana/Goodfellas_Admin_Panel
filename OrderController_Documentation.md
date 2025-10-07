# OrderController Documentation

## Overview
The `OrderController` is a comprehensive order management system that handles order processing, refunds, sales types, reporting, and complex business logic for the Goodfellas POS system. It manages the complete order lifecycle from creation to completion, including advanced features like refund processing and Excel reporting.

## Dependencies
- `Illuminate\Http\Request`
- `App\Models\DetailOrder`
- `App\Models\Orders`
- `App\Models\Point_User`
- `App\Models\StatusOrder`
- `App\Models\Notify_user`
- `App\Models\Additional_menu_detail`
- `App\Models\AdditionalRefund`
- `App\Models\Discount_detail_order`
- `App\Models\SalesType`
- `App\Models\Taxes`
- `App\Models\TaxOrder`
- `App\Models\Menu`
- `App\Models\RefundOrderMenu`
- `App\Models\DiscountMenuRefund`
- `App\Models\OptionModifier`
- `App\Models\Aktivity`
- `App\Models\RefundOrder`
- `App\Exports\LaporanPenjualanExport`
- `Maatwebsite\Excel\Facades\Excel`
- `Illuminate\Support\Facades\DB`
- `Illuminate\Support\Facades\Log`
- `Illuminate\Support\Facades\Crypt`
- `Sentinel`
- `Carbon\Carbon`
- `App\Events\MessageCreated`

## Class Structure
```php
class OrderController extends Controller
```

## Utility Methods

### 1. kodePesanan($length = 5)
**Purpose**: Generate unique order codes for refunds.

**Parameters**:
- `$length` (integer, default: 5) - Length of random string

**Algorithm**:
```php
$str = 'RF'; // Refund prefix
$charecters = array_merge(range('A', 'Z'), range('a', 'z'));
$max = count($charecters) - 1;
for ($i = 0; $i < $length; $i++) {
    $rand = mt_rand(0, $max);
    $str .= $charecters[$rand];
}
return $str;
```

**Returns**: String (e.g., "RFaBcDe")

## Order Management Methods

### 2. indexOrder(Request $request)
**Purpose**: Display comprehensive order dashboard with analytics and filtering.

**Parameters**:
- `$request->startDate` (string, optional) - Start date filter
- `$request->endDate` (string, optional) - End date filter

**Default Date Range**: Current day if no dates provided

**Process Flow**:

#### Order Statistics Calculation
```php
$order_new = Orders::where('id_status', 1)
    ->whereBetween('tanggal', [$startDate, $endDate])
    ->where('deleted', 0)
    ->whereNotNull('id_user')
    ->get();

$order_new_nonUser = Orders::where('id_status', 1)
    ->whereBetween('tanggal', [$startDate, $endDate])
    ->where('id_user', null)
    ->where('deleted', 0)
    ->get();
```

#### Financial Analytics
**Complex Financial Calculations**:
1. **Item Sales**: Sum of all detail order totals
2. **Refund Analysis**: Total refunded amounts and quantities
3. **Discount Impact**: Total discounts minus refund discounts
4. **Net Calculations**: Gross sales minus discounts and refunds
5. **Tax Integration**: PB1 and Service Charge calculations

**Grand Total Formula**:
```php
$allGrandSales = $items + $hargaRefund;
$allGrandDis = $totalDiscount - $refundDisCountSum;
$allGrandRefund = $hargaRefund;
$allGrandNet = $allGrandSales - $allGrandDis - $allGrandRefund;

$PB1 = $taxpb1->tax_rate / 100;
$Service = $service->tax_rate / 100;
$nominalPb1 = $allGrandNet * $PB1;
$nominalService = $allGrandNet * $Service;
$totalTax = $nominalPb1 + $nominalService;
$TotalGrand = $allGrandNet + $totalTax;
```

**Returns**: Order index view with comprehensive analytics

**Data Passed to View**:
- `order_new`, `order_selesai`, `order_batal`, `order_new_nonUser` - Order collections
- `orderCount`, `orderSumTotal` - Order statistics
- `TotalGrand`, `allGrandNet` - Financial totals
- `startDate`, `endDate` - Date range parameters

---

### 3. detailOrder($kode)
**Purpose**: Display detailed order information with refund data.

**Parameters**:
- `$kode` (string) - Order code (kode_pemesanan)

**Process**:
1. Find order by order code
2. Load all related data (taxes, refunds, status options)
3. Calculate refund totals and discounts
4. Encrypt refund IDs for security

**Security Feature**:
```php
$refundOrder = RefundOrder::where('id_order', $detail->id)->get()->map(function ($refund) {
    $refund->encrypted_id = Crypt::encryptString($refund->id);
    return $refund;
});
```

**Returns**: Order detail view with comprehensive order information

---

### 4. updateOrderStatus(Request $request, $kode)
**Purpose**: Update order status and handle point system integration.

**Parameters**:
- `$request->data['id_status']` (integer) - New status ID
- `$kode` (string) - Order code

**Point System Integration**:
When order status changes to completed (status 2):
```php
if($request->data['id_status'] == '2' ){
    $total_order = intval($detail->subtotal);
    $point = ($total_order * 1) / 1000; // 1 point per 1000 currency units
    
    if($detail->id_user){
        $point_user = new Point_User();
        $point_user->id_user = $detail->id_user;
        $point_user->id_order = $detail->id;
        $point_user->tanggal = Carbon::now()->toDateTimeString();
        $point_user->point_in = $point;
        $point_user->keterangan = 'Points have entered '.$point.' point of the order code '.$detail->kode_pemesanan;
        $point_user->save();
        
        // Create notification
        $notify = new Notify_user();
        $notify->id_user = $detail->id_user;
        $notify->message = $point_user->keterangan;
        $notify->tanggal = Carbon::now()->toDateTimeString();
        $notify->status = 'unread';
        $notify->save();
        
        // Trigger real-time event
        event(new MessageCreated(['message' => $point_user->keterangan]));
    }
}
```

**Returns**: JSON response with success status and updated order data

---

## Refund Management System

### 5. refundMenuOrder(Request $request)
**Purpose**: Process complex menu refunds with stock restoration and financial recalculation.

**Parameters**:
- `$request->order_id` (integer) - Target order ID
- `$request->menu` (array) - Items to refund with details
- `$request->detail_menu` (array) - Original order details to modify
- `$request->subTotalrefund` (numeric) - Refund subtotal
- `$request->TotalRefund` (numeric) - Total refund amount
- `$request->tx_pb1` (numeric) - Updated PB1 tax
- `$request->tx_service` (numeric) - Updated service tax

**Complex Process Flow**:

#### 1. Create Refund Order
```php
$refundOrder = new RefundOrder();
$refundOrder->id_order = $orders->id;
$refundOrder->name_bill = 'Refund-'.$orders->kode_pemesanan;
$refundOrder->kode_refund = $this->kodePesanan();
$refundOrder->subtotal = $request->subTotalrefund;
$refundOrder->total_retur = $request->TotalRefund;
$refundOrder->id_admin = $admin;
$refundOrder->tanggal = $date;
$refundOrder->save();
```

#### 2. Process Each Refunded Item
```php
foreach ($menuRefund as $refund) {
    $menu = Menu::where('id', $refund['id_menu'])->first();
    
    // Stock restoration using StokService
    if($menu->kategori->kategori_nama === 'Foods'){
        $result = $stokService->restoreMenuStock(
            $menu->id,
            $refund['qty'],
            $refund['id_order'],
            Sentinel::getUser()->id,
            "Refund menu: {$menu->nama_menu}"
        );
        
        if (!$result['success']) {
            throw new \Exception($result['message']);
        }
    }
    
    // Create refund menu record
    $itmRefund = new RefundOrderMenu();
    $itmRefund->id_order = $refund['id_order'];
    $itmRefund->id_refund_order = $refundOrder->id;
    $itmRefund->id_menu = $refund['id_menu'];
    $itmRefund->refund_nominal = ($refund['harga_menu'] + $refund['adds']) * $refund['qty'];
    $itmRefund->harga = $refund['harga_menu'];
    $itmRefund->qty = $refund['qty'];
    $itmRefund->catatan = $refund['catatan'];
    $itmRefund->id_varian = $refund['varian'];
    $itmRefund->id_admin = $admin;
    $itmRefund->alasan_refund = $refund['alasan'];
    $itmRefund->tanggal = Carbon::now()->toDateTimeString();
    $itmRefund->save();
}
```

#### 3. Handle Refund Discounts and Additionals
```php
// Process discount refunds
if (isset($refund['discount'])) {
    foreach ($refund['discount'] as $dis) {
        $discount = new DiscountMenuRefund();
        $discount->id_refund_menu = $itmRefund->id;
        $discount->id_menu = $dis['id_menu'];
        $discount->id_discount = $dis['idDiscount'];
        $discount->nominal_dis = $dis['nominalDis'];
        $discount->id_admin = $admin;
        $discount->save();
    }
}

// Process additional refunds
if (isset($refund['additional'])) {
    foreach ($refund['additional'] as $add) {
        $additional = new AdditionalRefund();
        $additional->id_refund_menu = $itmRefund->id;
        $additional->id_menu = $add['id_menu'];
        $additional->id_option_additional = $add['id_add'];
        $additional->harga = $add['nominal'];
        $additional->qty = $add['qty'];
        $additional->total_ = $add['Total_adds'];
        $additional->tanggal = Carbon::now()->toDateTimeString();
        $additional->id_admin = $admin;
        $additional->save();
    }
}
```

#### 4. Update Original Order Details
```php
foreach($menuDetail as $item){
    $detail_menu = DetailOrder::where('id', $item["id_detail"])->first();
    
    if ($detail_menu) {
        if ($detail_menu->id_order == $item['id_order'] && $detail_menu->id_menu == $item['id_menu']) {
            $detail_menu->qty -= $item['qty'];
            
            // Delete if quantity becomes zero or negative
            if ($detail_menu->qty <= 0) {
                $idDetailOrder = $detail_menu->id;
                $detail_menu->delete();
                
                // Clean up related records
                Discount_detail_order::where('id_detail_order', $idDetailOrder)->delete();
                Additional_menu_detail::where('id_detail_order', $idDetailOrder)->delete();
            } else {
                // Recalculate totals
                $detail_menu->total = ($item['harga_menu'] + $item['adds']) * $detail_menu->qty;
                $detail_menu->save();
            }
        }
    }
}
```

#### 5. Recalculate Order Totals
```php
$sumDetailOrder = DetailOrder::where('id_order', $orders->id)->sum('total');
$sumDiscount = Discount_detail_order::whereHas('Detail_order', function ($query) use ($orders) {
    $query->where('id_order', $orders->id);
})->sum('total_discount');

// Update tax records
$tax_order_pb1 = TaxOrder::where('id_order', $orders->id)->where('id_tax', 1)->first();
$tax_order_pb1->total_tax = $request->tx_pb1;
$tax_order_pb1->save();

$tax_order_service = TaxOrder::where('id_order', $orders->id)->where('id_tax', 2)->first();
$tax_order_service->total_tax = $request->tx_service;
$tax_order_service->save();

// Update order totals
$subtotalNew = $sumDetailOrder - $sumDiscount;
$totalNew = $subtotalNew + $tax_order_pb1->total_tax + $tax_order_service->total_tax;
$orders->subtotal = $subtotalNew;
$orders->total_order = $totalNew;
$orders->cash = $totalNew;
$orders->save();
```

**Transaction Safety**: Entire process wrapped in database transaction with rollback on failure.

**Returns**: JSON response with success/failure status

---

## Order Deletion Methods

### 6. DeleteDataOrder(Request $request)
**Purpose**: Soft delete order with stock restoration and activity logging.

**Parameters**:
- `$request->id_order` (integer) - Order ID to delete
- `$request->alasan_delete` (string) - Deletion reason

**Process Flow**:

#### 1. Soft Delete Order
```php
$order = Orders::where('id', $request->id_order)->first();
$order->deleted = 1;
$order->deleted_at = Carbon::now()->toDateTimeString();
$order->id_admin_deleted = $admin;
$order->alasan_delete = $request->alasan_delete;
$order->save();
```

#### 2. Handle Related Refund Orders
```php
$refundOrder = RefundOrder::where('id_order', $order->id)->first();
if($refundOrder){
    $refundOrder->deleted = 1;
    $refundOrder->id_admin_delete = $order->id_admin_deleted;
    $refundOrder->alasan_delete = $order->alasan_delete;
    $refundOrder->deleted_at = $date;
    $refundOrder->save();
}
```

#### 3. Stock Restoration
```php
$detail_item = DetailOrder::where('id_order', $order->id)->get();

foreach($detail_item as $detail){
    $menu = Menu::where('id', $detail->id_menu)->first();
    
    if($detail->menu->id_kategori == 2){ // Foods category
        if ($menu->tipe_stok === 'Stok Bahan Baku'){
            $bahanBaku = $menu->bahanBaku()->first();
            if ($bahanBaku) {
                $bahanBaku->stok_porsi = $bahanBaku->stok_porsi + $detail->qty;
                $bahanBaku->save();
            }
        } else {
            $menu->stok = $menu->stok + $detail->qty;
            $menu->save();
        }
    }
}
```

#### 4. Activity Logging
```php
$activity = new Aktivity();
$activity->id_admin = $admin;
$activity->keterangan = "Menghapus Data Order";
$activity->detail = json_encode([
    'id' => $order->id,
    'name' => $order->name,
    'status' => $order->status
]);
$activity->save();
```

**Returns**: Redirect with success/error message

---

### 7. DeleteOrder($id)
**Purpose**: Hard delete order with complete cleanup of all related records.

**Parameters**:
- `$id` (encrypted string) - Encrypted order ID

**Process**: Complete cascade deletion of:
- Order details and their additionals/discounts
- Tax orders
- Refund menus and their additionals/discounts
- Main order record

**Transaction Safety**: Uses database transactions with comprehensive error handling.

---

## Sales Type Management

### 8. salestype()
**Purpose**: Display all sales types.

**Returns**: Sales type list view (`SalesType.dataType`)

---

### 9. createSalesType()
**Purpose**: Display sales type creation form.

**Returns**: Creation form view (`SalesType.CreateTypeSales`)

---

### 10. postTypeSales(Request $request)
**Purpose**: Create new sales type.

**Parameters**:
- `$request->name` (string, required) - Sales type name

**Validation**:
```php
$request->validate(['name'=> 'required']);
```

---

### 11. EditSalesType($id), UpdateSalesType(Request $request, $id), DeleteTypeSales($id)
**Purpose**: Standard CRUD operations for sales types with encrypted ID handling.

## Reporting and Analytics

### 12. laporan(Request $request)
**Purpose**: Generate comprehensive Excel sales report.

**Parameters**:
- `$request->start_date` (date) - Report start date
- `$request->end_date` (date) - Report end date

**Process**:
1. Calculate sales metrics for each menu item
2. Calculate additional item sales
3. Include refund analysis
4. Generate Excel file using `LaporanPenjualanExport`

**Complex Calculations**:
```php
foreach ($menu as $itm) {
    // Sales calculations
    $items = DetailOrder::where('created_at', '>=', $tanggal_mulai)
        ->where('created_at', '<', $tanggal_akhir)
        ->where('id_menu', $itm->id)
        ->whereHas('orders', function($query) {
            $query->where('delete', 0)->whereNull('deleted_at');
        })
        ->value('harga');
    
    // Refund calculations
    $SumRefund = RefundOrderMenu::where('id_menu', $itm->id)
        ->where('created_at', '>=', $tanggal_mulai)
        ->where('created_at', '<', $tanggal_akhir)
        ->sum('qty');
    
    // Net sales calculation
    $netSales = $harga - $disTotal - $totalRefund;
}
```

**Returns**: Excel file download

---

### 13. filterPeriode(Request $request)
**Purpose**: Filter orders by date range for reporting.

**Parameters**:
- `$request->start_date` (date) - Filter start date
- `$request->end_date` (date) - Filter end date

**Returns**: Filtered order view (`Orders.filter_data`)

## Advanced Features

### 1. Point System Integration
- Automatic point calculation on order completion
- Real-time notifications to users
- Point history tracking
- Event-driven architecture for notifications

### 2. Stock Management Integration
- Automatic stock deduction on orders
- Stock restoration on refunds and deletions
- Support for ingredient-based stock calculations
- Integration with `StokService` for complex operations

### 3. Financial Calculations
- Multi-level discount handling
- Tax integration (PB1, Service Charge)
- Refund impact on financial metrics
- Complex net sales calculations

### 4. Real-time Features
- WebSocket events for notifications
- Live order status updates
- Real-time point notifications
- Activity logging for audit trails

### 5. Reporting Capabilities
- Excel export functionality
- Comprehensive sales analytics
- Refund analysis reports
- Date range filtering
- Multi-dimensional data analysis

## Security Features

### 1. Authentication & Authorization
- Sentinel authentication on all methods
- Admin-level access requirements
- Activity logging for audit trails

### 2. Data Protection
- Encrypted IDs in URLs
- Input validation and sanitization
- SQL injection prevention through ORM
- Transaction safety with rollbacks

### 3. Business Logic Security
- Stock validation to prevent negative inventory
- Order status validation
- Refund authorization checks
- Financial calculation integrity

## Error Handling

### 1. Database Transactions
```php
DB::beginTransaction();
try {
    // Complex operations
    DB::commit();
    return response()->json(['success' => 1]);
} catch (\Exception $e) {
    DB::rollBack();
    return response()->json(['success' => 0, 'error' => $e->getMessage()], 500);
}
```

### 2. Stock Validation
- Prevents overselling through stock checks
- Handles ingredient-based stock calculations
- Provides meaningful error messages

### 3. Comprehensive Logging
- Database operation logging
- Error logging with context
- Activity tracking for business operations

## Performance Considerations

### 1. Query Optimization
- Efficient joins and relationships
- Proper indexing on foreign keys
- Eager loading for related models
- Aggregation at database level

### 2. Transaction Management
- Minimal transaction scope
- Proper rollback handling
- Batch operations where possible

### 3. Memory Management
- Efficient collection handling
- Streaming for large datasets
- Proper resource cleanup

This comprehensive documentation covers all aspects of the OrderController, from basic CRUD operations to complex business logic involving refunds, stock management, and financial calculations.
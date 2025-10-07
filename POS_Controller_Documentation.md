# POS Controller Documentation

## Overview
The `POSController` is the main controller for handling Point of Sale (POS) operations in the Goodfellas Admin Panel. It manages order processing, menu handling, payment processing, and various POS-related functionalities.

## Table of Contents
- [Dependencies](#dependencies)
- [Constructor](#constructor)
- [Main Methods](#main-methods)
- [Order Management](#order-management)
- [Session Management](#session-management)
- [Payment Processing](#payment-processing)
- [Printing Functions](#printing-functions)
- [Utility Methods](#utility-methods)

## Dependencies

### Models Used
- `Orders` - Order management
- `Menu` - Menu items
- `Kategori` - Categories
- `StatusOrder` - Order status
- `SubKategori` - Sub-categories
- `DetailOrder` - Order details
- `OptionModifier` - Menu modifiers
- `VarianMenu` - Menu variants
- `Discount` - Discount management
- `Taxes` - Tax calculations
- `TypePayment` - Payment types
- `SalesType` - Sales types
- `Additional_menu_detail` - Additional menu items
- `TaxOrder` - Order taxes
- `Point_User` - User points
- `Aktivity` - Activity logging

### Services Used
- `KodePesananService` - Order code generation
- `StokService` - Stock management

## Constructor

```php
public function __construct(KodePesananService $kode_pesanan)
```

Initializes the controller with the order code generation service.

## Main Methods

### 1. POSdashboard()
**Purpose**: Displays the main POS dashboard with all necessary data.

**Returns**: View with menu items, categories, discounts, payments, taxes, and cart data.

**Key Features**:
- Loads all active menu items
- Retrieves categories and subcategories
- Gets discount and payment options
- Calculates cart subtotal from session

### 2. MenuCheckCategory(Request $request)
**Purpose**: Retrieves menu details with category and stock information.

**Parameters**:
- `id` - Menu ID

**Returns**: JSON response with menu data including category and stock info.

## Order Management

### 3. addOrder(Request $request)
**Purpose**: Adds items to the cart session.

**Parameters**:
- `id` - Menu ID
- `qty` - Quantity
- `harga` - Price
- `variasi` - Variant ID
- `additional` - Additional items
- `discount` - Applied discounts
- `catatan` - Notes

**Stock Validation**:
- Validates stock availability for Foods category
- Checks both regular stock and ingredient-based stock
- Returns error if insufficient stock

### 4. editOrder(Request $request)
**Purpose**: Modifies existing cart items.

**Parameters**: Same as addOrder plus:
- `key` - Cart item key for identification

### 5. modifyBill(Request $request)
**Purpose**: Modifies existing orders that haven't been paid.

**Key Features**:
- Stock adjustment handling
- Order detail updates
- Additional items management
- Discount management
- Order total recalculation

**Private Helper Methods**:
- `getOrCreateOrderDetail()` - Gets existing or creates new order detail
- `handleStockAdjustment()` - Manages stock changes
- `updateOrderDetail()` - Updates order detail information
- `handleAdditionals()` - Manages additional items
- `handleDiscounts()` - Manages discount applications
- `updateOrderTotals()` - Recalculates order totals

### 6. postOrderPOS(Request $request)
**Purpose**: Processes and saves the complete order.

**Process Flow**:
1. Validates stock availability
2. Creates order record
3. Processes each cart item
4. Handles stock deduction for Foods
5. Creates order details
6. Processes additionals and discounts
7. Handles tax calculations
8. Clears cart session

**Private Helper Methods**:
- `createOrder()` - Creates main order record
- `createOrderDetail()` - Creates order detail records
- `handleOrderExtras()` - Processes additionals and discounts
- `handleOrderTaxes()` - Processes tax calculations

## Session Management

### 7. dataDetailOrder()
**Purpose**: Retrieves current cart session data.

**Returns**: JSON with cart items, subtotal, and rendered view.

### 8. hapus(Request $request)
**Purpose**: Removes items from cart session.

**Parameters**:
- `id` - Cart item key

### 9. clearSession()
**Purpose**: Clears the entire cart session.

## Payment Processing

### 10. paymentProses(Request $request)
**Purpose**: Processes payment for an order.

**Features**:
- Updates order with payment information
- Changes order status to paid
- Calculates and awards user points
- Sends notifications to users
- Triggers real-time events

### 11. updateOrder(Request $request)
**Purpose**: Updates order information (name, table number, totals).

## Bill Management

### 12. splitBill(Request $request)
**Purpose**: Splits an existing bill into multiple orders.

**Process**:
1. Validates original order status
2. Creates new split order
3. Moves or duplicates order items
4. Handles additionals and discounts
5. Recalculates totals for both orders

**Helper Method**:
- `recalculateAndUpdateOrderTotals()` - Recalculates order totals after split

### 13. DataBill()
**Purpose**: Retrieves list of open bills.

### 14. getDataBillDetail(Request $request)
**Purpose**: Gets detailed information for a specific bill.

## Item Management

### 15. deletemodify(Request $request)
**Purpose**: Validates if an order item can be deleted.

### 16. afterPrintDelete(Request $request)
**Purpose**: Deletes order items after printing and restores stock.

**Features**:
- Stock restoration for Foods category
- Discount cleanup
- Order total recalculation
- Transaction safety with rollback

## Menu Data Retrieval

### 17. getVariasi(Request $request)
**Purpose**: Gets menu variants for a specific menu item.

### 18. getOptionAdditional(Request $request)
**Purpose**: Gets additional options for a menu item.

### 19. Menu Category Methods
- `partMenuKat($id)` - Gets menus by category
- `PartAllMenu()` - Gets all menus
- `PartSubMenu($id)` - Gets menus by subcategory
- `PartMenuDiscount()` - Gets menu discount view

## Printing Functions

### 20. PrintBill($id)
**Purpose**: Displays bill for printing.

### 21. printTiket($id)
**Purpose**: Displays ticket for printing (new items only).

### 22. printKitchen($id)
**Purpose**: Displays kitchen order (Foods category only).

### 23. updateLastPrint(Request $request, $id)
**Purpose**: Updates last print timestamp for order items.

### 24. printData(Request $request, $id)
**Purpose**: Generates Word document for printing.

**Document Types**:
- Bill - Complete order bill
- Tiket - Customer ticket
- Kitchen - Kitchen order

### 25. printDataItemDelete(Request $request)
**Purpose**: Generates deletion receipt for removed items.

## Utility Methods

### 26. itemSplitBill(Request $request)
**Purpose**: Displays split bill interface.

### 27. Action_log(Request $request)
**Purpose**: Logs user activities for audit trail.

### 28. getDiscount()
**Purpose**: Displays discount popup interface.

### 29. updateSalesTypeOnDetailOrder()
**Purpose**: Updates null sales types to default value.

## Error Handling

All methods implement comprehensive error handling:
- Try-catch blocks for exception handling
- Database transaction rollbacks on failures
- Detailed error messages in JSON responses
- Proper HTTP status codes

## Security Features

- Sentinel authentication checks on all methods
- Input validation and sanitization
- Database transaction safety
- Stock validation to prevent overselling

## Stock Management Integration

The controller integrates with `StokService` for:
- Stock availability checking
- Stock deduction on orders
- Stock restoration on deletions
- Stock adjustment on modifications

## Response Format

Most methods return standardized JSON responses:
```json
{
    "success": 1|0,
    "message": "Description",
    "data": {...},
    "error": "Error message (if any)"
}
```

## Usage Examples

### Adding Item to Cart
```javascript
$.ajax({
    url: '/pos/add-order',
    method: 'POST',
    data: {
        id: menuId,
        qty: quantity,
        harga: price,
        variasi: variantId,
        additional: additionalItems,
        discount: discounts,
        catatan: notes
    }
});
```

### Processing Payment
```javascript
$.ajax({
    url: '/pos/payment-process',
    method: 'POST',
    data: {
        id: orderId,
        Idpayment: paymentTypeId,
        cash: cashAmount,
        change_: changeAmount,
        total: totalAmount
    }
});
```

### Splitting Bill
```javascript
$.ajax({
    url: '/pos/split-bill',
    method: 'POST',
    data: {
        target_order: originalOrderId,
        itms: [
            {id_item: itemId, qty: splitQuantity}
        ],
        type_pyment: paymentTypeId,
        cash: cashAmount,
        change: changeAmount
    }
});
```

## Notes

- All monetary values should be handled as integers (in smallest currency unit)
- Stock management is only applied to Foods category items
- Session-based cart management for temporary storage
- Real-time notifications for user point updates
- Comprehensive audit logging for all actions
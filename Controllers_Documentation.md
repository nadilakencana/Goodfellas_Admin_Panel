# Controllers Documentation - Goodfellas Admin Panel

## Overview
This document provides comprehensive documentation for all controllers in the Goodfellas Admin Panel POS system. Each controller handles specific functionality within the application.

## Table of Contents
1. [AuthController](#authcontroller)
2. [DashboardController](#dashboardcontroller)
3. [MenuController](#menucontroller)
4. [OrderController](#ordercontroller)
5. [KategoriController](#kategoricontroller)
6. [SubKategoriController](#subkategoricontroller)
7. [PaymentController](#paymentcontroller)
8. [TaxesController](#taxescontroller)
9. [ModifierController](#modifiercontroller)
10. [ReportSalesController](#reportsalescontroller)
11. [CashController](#cashcontroller)
12. [RegisterController](#registercontroller)
13. [POSController](#poscontroller)

---

## AuthController

### Purpose
Handles authentication, user management, admin management, and access level control.

### Key Methods

#### Authentication
- `login()` - Display login form
- `pushlogin(Request $request)` - Process login authentication
- `logOut()` - Handle user logout and session cleanup

#### User Management
- `DataUser()` - Display all users
- `DataAdmin()` - Display all admin users
- `editDataAdmin($id)` - Edit admin user form
- `udpdateDataAdmin(Request $request, $id)` - Update admin user data
- `deleteDataAdmin($id)` - Delete admin user
- `ResetPassword(Request $request)` - Reset user password

#### Level Management
- `levelLog()` - Display user levels
- `createLevel(Request $request)` - Create new user level
- `UpdateLevel(Request $request, $id)` - Update user level
- `DeteletLevel($id)` - Delete user level

### Security Features
- Sentinel authentication integration
- Password encryption with bcrypt
- Session management
- Role-based access control

---

## DashboardController

### Purpose
Provides comprehensive dashboard analytics and reporting with various chart visualizations.

### Key Methods

#### Main Dashboard
- `Index(Request $request)` - Main dashboard with analytics
- `notifFrame()` - Notification frame display

### Analytics Features
- **Sales Analytics**: Gross sales, net sales, refunds, discounts
- **Order Statistics**: New orders, completed orders, cancelled orders
- **Top Selling Items**: Most popular menu items with filtering
- **Chart Visualizations**:
  - Daily gross sales trends
  - Day-of-week performance
  - Hourly sales patterns
  - Category performance by volume and sales
  - Top items by category

### Data Processing
- Date range filtering (default: current month)
- Complex SQL queries with joins and aggregations
- Real-time calculations for taxes and totals
- Dynamic chart data generation

---

## MenuController

### Purpose
Manages menu items, categories, variants, and ingredient management.

### Key Methods

#### Menu Management
- `indexMenu()` - Display all menu items
- `createMenu()` - Create new menu form
- `PushCreate(Request $request)` - Save new menu
- `editMenu($id)` - Edit menu form
- `updateMenu(Request $request, $id)` - Update menu data
- `deleteMenu(Request $request, $id)` - Soft delete menu

#### Ingredient Management
- `bahanBaku()` - Display ingredients
- `createBahanBaku()` - Create ingredient form
- `pushCreateBahanBaku(Request $request)` - Save ingredient
- `editBahanBaku($id)` - Edit ingredient form
- `updateBahanBaku(Request $request, $id)` - Update ingredient
- `deleteBahanBaku(Request $request, $id)` - Delete ingredient

### Features
- Image upload handling
- Menu variants management
- Stock management (regular stock vs ingredient-based)
- Category and subcategory associations
- Group modifier assignments
- Recipe management for ingredient-based items

---

## OrderController

### Purpose
Comprehensive order management including processing, refunds, sales types, and reporting.

### Key Methods

#### Order Management
- `indexOrder(Request $request)` - Display orders with filtering
- `detailOrder($kode)` - Show order details
- `updateOrderStatus(Request $request, $kode)` - Update order status
- `DeleteDataOrder(Request $request)` - Soft delete order
- `DeleteOrder($id)` - Hard delete order with cleanup

#### Refund Management
- `refundMenuOrder(Request $request)` - Process menu refunds
- Stock restoration on refunds
- Discount and additional item handling
- Order total recalculation

#### Sales Type Management
- `salestype()` - Display sales types
- `createSalesType()` - Create sales type form
- `postTypeSales(Request $request)` - Save sales type
- `EditSalesType($id)` - Edit sales type
- `UpdateSalesType(Request $request, $id)` - Update sales type
- `DeleteTypeSales($id)` - Delete sales type

#### Reporting
- `laporan(Request $request)` - Generate Excel reports
- `filterPeriode(Request $request)` - Filter orders by date range

### Advanced Features
- Point system integration
- Real-time notifications
- Complex refund processing with stock management
- Excel export functionality
- Comprehensive order analytics

---

## KategoriController

### Purpose
Simple CRUD operations for menu categories.

### Key Methods
- `indexKat()` - Display categories
- `createKat()` - Create category form
- `pushKat(Request $request)` - Save category
- `editKat($id)` - Edit category form
- `UpadateKategori(Request $request, $id)` - Update category
- `deleteKat($id)` - Delete category

### Features
- Basic category management
- Encrypted ID handling
- Form validation

---

## SubKategoriController

### Purpose
Manages subcategories with parent category relationships.

### Key Methods
- `indexSubKat()` - Display subcategories (non-deleted)
- `createSubKat()` - Create subcategory form
- `pushSubKat(Request $request)` - Save subcategory
- `editSubKat($id)` - Edit subcategory form
- `UpdateSubKat(Request $request, $id)` - Update subcategory
- `deleteSubKat($id)` - Soft delete subcategory

### Features
- Parent category association
- Slug generation
- Soft delete functionality

---

## PaymentController

### Purpose
Manages payment types and discount systems.

### Key Methods

#### Payment Types
- `TypePayment()` - Display payment types
- `CreateDataPaymentType()` - Create payment type form
- `postDataPaymentType(Request $request)` - Save payment type
- `editDataTypePayment($id)` - Edit payment type
- `updateDataTypePayment(Request $request, $id)` - Update payment type
- `deleteTypePayment($id)` - Delete payment type

#### Discount Management
- `Discount()` - Display discounts
- `CreateDataDis()` - Create discount form
- `PostDataDiscount(Request $request)` - Save discount
- `EditDataDis($id)` - Edit discount form
- `UpdateDataDiscount(Request $request, $id)` - Update discount
- `deleteDiscount($id)` - Delete discount

### Features
- Image upload for payment types
- Percentage-based discount system
- URL generation for payment type images

---

## TaxesController

### Purpose
Simple tax management system.

### Key Methods
- `dataTax()` - Display taxes
- `createTax()` - Create tax form
- `postTax(Request $request)` - Save tax
- `EditDataTax($id)` - Edit tax form
- `UpdateDataTax(Request $request, $id)` - Update tax
- `deleteTax($id)` - Delete tax

### Features
- Tax rate management
- Percentage-based calculations
- Basic CRUD operations

---

## ModifierController

### Purpose
Manages menu modifiers and additional options.

### Key Methods
- `dataModif()` - Display modifier groups
- `CreateGroup()` - Create modifier group form
- `postCreateGroupModif(Request $request)` - Save modifier group
- `editDataGroup($id)` - Edit modifier group
- `postEditGroup(Request $request, $id)` - Update modifier group
- `detailData(Request $request)` - Get modifier details via AJAX
- `hapusData(Request $request, $id)` - Delete modifier group

### Features
- Group-based modifier system
- Multiple options per group
- Active/inactive status management
- External API synchronization
- Dynamic option management

---

## ReportSalesController

### Purpose
Comprehensive sales reporting and analytics system.

### Key Methods

#### Main Reports
- `Report(Request $request)` - Main sales report
- `fileterSalesSummary(Request $request)` - Filtered sales summary
- `GrossProfit(Request $request)` - Gross profit analysis
- `pymentMethod(Request $request)` - Payment method analysis
- `SelesType(Request $request)` - Sales type analysis
- `ItemSales(Request $request)` - Detailed item sales report
- `Modifier(Request $request)` - Modifier sales analysis
- `Discount(Request $request)` - Discount usage report
- `Taxes(Request $request)` - Tax calculation report
- `Category(Request $request)` - Category performance report

### Advanced Analytics
- **Complex Calculations**: Gross sales, net sales, refunds, discounts
- **Variant Analysis**: Menu items with and without variants
- **Time-based Filtering**: Flexible date range selection
- **Multi-dimensional Analysis**: By category, payment method, sales type
- **Tax Integration**: Automatic tax calculations (PB1, Service Charge)

### Data Processing Features
- Sophisticated SQL queries with multiple joins
- Aggregation functions for totals and averages
- Refund impact calculations
- Discount effectiveness analysis

---

## CashController

### Purpose
Cash management and shift reporting system.

### Key Methods

#### Shift Management
- `DataShift()` - Display all shifts
- `startSift(Request $request)` - Start new shift
- `detailSift($id)` - Detailed shift information
- `EndSift(Request $request, $id)` - End shift with calculations
- `deleteShift(Request $request)` - Delete shift
- `print_sift($id)` - Print shift report
- `Print_report(Request $request, $id)` - Generate Word document report

#### Cash Operations
- `kas(Request $request)` - Record cash in/out transactions

### Advanced Features
- **Comprehensive Calculations**: Expected vs actual cash
- **Payment Method Breakdown**: Cash, EDC, online payments
- **Refund Tracking**: By payment method
- **Tax Calculations**: Integrated with refund processing
- **Document Generation**: Word template processing
- **Multi-payment Analysis**: Detailed breakdown by payment type

---

## RegisterController

### Purpose
Simple user registration functionality.

### Key Methods
- `regis()` - Display registration form
- `pushRegist(Request $request)` - Process registration

### Features
- Basic user registration
- Email validation
- Password encryption
- Default level assignment

---

## POSController

### Purpose
Main Point of Sale operations (covered in separate detailed documentation).

### Key Features
- Order processing
- Cart management
- Payment processing
- Bill splitting
- Printing functionality
- Stock management integration

---

## Common Patterns Across Controllers

### Authentication
All controllers use Sentinel authentication:
```php
if(Sentinel::check()){
    // Controller logic
}else{
    return redirect()->route('login');
}
```

### Error Handling
Consistent error handling with try-catch blocks and JSON responses:
```php
try {
    // Operation logic
    return response()->json(['success' => 1, 'message' => 'Success']);
} catch (\Exception $e) {
    return response()->json(['success' => 0, 'error' => $e->getMessage()], 500);
}
```

### ID Encryption
Most controllers use Laravel's encrypt/decrypt for ID security:
```php
$dec = decrypt($id);
$model = Model::findOrFail($dec);
```

### Validation
Standard Laravel validation patterns:
```php
$request->validate([
    'field' => 'required|rule',
    'email' => 'required|email:dns|unique:table'
]);
```

### Response Patterns
Consistent redirect patterns with flash messages:
```php
return redirect()->route('route')->with('Success', 'Message');
return redirect()->back()->with('error', 'Error Message');
```

## Database Integration

### Models Used
- Orders, DetailOrder, RefundOrder, RefundOrderMenu
- Menu, Kategori, SubKategori, VarianMenu
- Admin, User, Level
- Taxes, TaxOrder, Discount, Discount_detail_order
- TypePayment, SalesType
- Cash, Sift, Point_User
- Additional_menu_detail, OptionModifier, GroupModifier

### Complex Relationships
- Orders have many DetailOrder
- DetailOrder belongs to Menu, has many Additional_menu_detail
- Menu belongs to Kategori, SubKategori, has many VarianMenu
- Comprehensive refund system with related models

## Security Features

### Authentication & Authorization
- Sentinel-based authentication
- Role-based access control
- Session management
- Password encryption

### Data Protection
- ID encryption for URLs
- Input validation and sanitization
- SQL injection prevention through Eloquent ORM
- CSRF protection (Laravel default)

### Business Logic Security
- Stock validation to prevent overselling
- Order status validation
- Payment verification
- Refund authorization

## Performance Considerations

### Database Optimization
- Efficient queries with proper joins
- Eager loading for relationships
- Indexed foreign keys
- Pagination where appropriate

### Caching Strategy
- Session-based cart storage
- Calculated totals caching
- Report data optimization

### File Management
- Organized file uploads
- Document generation optimization
- Image handling for menu items and payment types

## Integration Points

### External Services
- Real-time notifications (WebSocket events)
- Excel export functionality
- Word document generation
- HTTP API calls for synchronization

### Internal Services
- StokService for inventory management
- KodePesananService for order code generation
- Point system integration
- Activity logging system

This documentation provides a comprehensive overview of all controllers in the Goodfellas Admin Panel, their purposes, key methods, and integration patterns.
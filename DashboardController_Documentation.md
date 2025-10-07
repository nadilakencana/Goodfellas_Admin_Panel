# DashboardController Documentation

## Overview
The `DashboardController` serves as the central analytics hub for the Goodfellas Admin Panel, providing comprehensive business intelligence, sales analytics, and performance metrics through interactive dashboards and visualizations.

## Dependencies
- `Illuminate\Http\Request`
- `App\Models\DetailOrder`
- `App\Models\BookingTempat`
- `App\Models\Kategori`
- `App\Models\Orders`
- `App\Models\StatusOrder`
- `App\Models\RefundOrderMenu`
- `App\Models\DiscountMenuRefund`
- `App\Models\Discount_detail_order`
- `App\Models\Taxes`
- `Illuminate\Support\Facades\DB`
- `Sentinel`

## Class Structure
```php
class DashboardController extends Controller
```

## Methods Documentation

### 1. Index(Request $request)
**Purpose**: Main dashboard method that provides comprehensive business analytics and visualizations.

**Parameters**:
- `$request->startDate` (string, optional) - Start date for analytics (default: start of current month)
- `$request->endDate` (string, optional) - End date for analytics (default: end of current month)

**Authentication**: Requires Sentinel authentication

**Returns**:
- Authenticated: Dashboard view with comprehensive analytics data
- Unauthenticated: Redirect to login

#### Data Processing Flow

##### 1. Date Range Handling
```php
if($request->has('startDate')){
    $startDate = $request->startDate;
    $endDate = $request->endDate;
} else {
    $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
    $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
}
```

##### 2. Order Statistics Calculation
- **New Orders**: `Orders::where('id_status', 1)->where('deleted', 0)->count()`
- **Completed Orders**: `Orders::where('id_status', 2)->where('deleted', 0)->count()`
- **Cancelled Orders**: `Orders::where('deleted', 1)->count()`
- **Average Order Value**: `Orders::where('id_status', 2)->avg('total_order')`

##### 3. Top Selling Items Analysis
**Algorithm**:
1. Query items with minimum 20 quantity sold
2. If no items meet criteria, reduce to 10 minimum
3. If still no items, include all items with minimum 1 quantity
4. Group by menu ID and calculate totals

**Query Structure**:
```php
$topSellingItems = DetailOrder::select('id_menu', DB::raw('SUM(qty) as total_qty'), DB::raw('AVG(harga) as avg_price'))
    ->whereHas('order', function ($query) use ($startDate, $endDate) {
        $query->where('id_status', 2)
            ->where('deleted', 0)
            ->whereBetween('tanggal', [$startDate, $endDate]);
    })
    ->groupBy('id_menu')
    ->orderByDesc('total_qty')
    ->with('menu')
    ->get();
```

##### 4. Financial Calculations
For each top-selling item:
- **Gross Sales**: `(total_qty + refund_qty) * avg_price`
- **Total Refunds**: `refund_price * refund_qty`
- **Discount Total**: `total_discount - refund_discount`
- **Net Sales**: `gross_sales - discount_total - total_refunds`

##### 5. Chart Data Generation

###### Top Products Chart
```php
$chartData = [
    'labels' => $topSellingItems->pluck('menu.nama_menu'),
    'datasets' => [[
        'label' => 'Total Qty Sold',
        'data' => $topSellingItems->pluck('total_qty'),
        'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
        'borderColor' => 'rgba(75, 192, 192, 1)',
        'borderWidth' => 1,
    ]]
];
```

###### Daily Gross Sales Chart
- Groups orders by date
- Calculates daily gross sales including refunds
- Generates time-series data for trend analysis

###### Day of Week Performance
- Analyzes sales patterns by day of week
- Uses `DAYNAME()` SQL function
- Aggregates sales for each day across the date range

###### Hourly Sales Pattern
- Analyzes sales by hour of day using `HOUR(created_at)`
- Creates 24-hour performance array
- Identifies peak business hours

###### Category Analysis
- **By Volume**: Total quantity sold per subcategory
- **By Sales**: Total revenue per subcategory
- Uses joins between `detail_order`, `menu`, and `sub_kategori_menu`

##### 6. Grand Total Calculations
**Overall Financial Metrics**:
- **All Grand Sales**: Total item sales + refund amounts
- **All Grand Discounts**: Total discounts - refund discounts
- **All Grand Refunds**: Total refund amounts
- **All Grand Net**: Grand sales - discounts - refunds

**Tax Calculations**:
```php
$PB1 = $taxpb1->tax_rate / 100;
$Service = $service->tax_rate / 100;
$nominalPb1 = $allGrandNet * $PB1;
$nominalService = $allGrandNet * $Service;
$totalTax = $nominalPb1 + $nominalService;
$TotalGrand = $allGrandNet + $totalTax;
```

#### Data Passed to View
- `endDate`, `startDate` - Date range parameters
- `pesanan_batal`, `pesanan_selesai`, `pesanan_baru` - Order statistics
- `itemSalesMenu` - Top selling items with financial metrics
- `chartData` - Top products chart data
- `allGrandSales`, `allGrandDis`, `allGrandRefund`, `allGrandNet` - Financial totals
- `totalTax`, `TotalGrand` - Tax calculations and final totals
- `avrg_order_bill` - Average order value
- `chartDailyGrossSales` - Daily sales trend data
- `chartDayOfWeekGrossSales` - Weekly pattern data
- `chartHourlyGrossSales` - Hourly pattern data
- `chartCategoryByVolume` - Category volume analysis
- `chartCategoryBySales` - Category sales analysis
- `chartData_items_cat` - Items by category breakdown

---

### 2. notifFrame()
**Purpose**: Display notification frame for real-time notifications.

**Parameters**: None

**Returns**: View (`notif-frame`)

**Usage**: Typically loaded in iframe or AJAX for real-time notification display.

## Advanced Analytics Features

### 1. Multi-dimensional Analysis
The dashboard provides analysis across multiple dimensions:
- **Time-based**: Daily, weekly, hourly patterns
- **Product-based**: Individual items, categories, subcategories
- **Financial**: Gross sales, net sales, refunds, discounts
- **Operational**: Order counts, average values, completion rates

### 2. Dynamic Filtering System
**Adaptive Thresholds**: The top-selling items algorithm adapts based on data availability:
```php
// Primary filter: >= 20 items sold
$topSellingItems = $topSellingItems->filter(function ($item) {
    return $item->total_qty >= 20;
});

// Fallback filter: >= 10 items sold
if ($topSellingItems->isEmpty()) {
    // Reduce threshold to 10
}

// Final fallback: >= 1 item sold
if ($topSellingItems->isEmpty()) {
    // Include all items with any sales
}
```

### 3. Complex Financial Calculations
**Refund Impact Analysis**:
- Tracks refunded quantities and amounts
- Calculates net impact on sales figures
- Includes refund discounts in calculations

**Discount Effectiveness**:
- Measures total discount amounts
- Tracks discount usage patterns
- Calculates net discount impact after refunds

### 4. Chart.js Integration
**Supported Chart Types**:
- Bar charts for product performance
- Line charts for time-series data
- Pie charts for category breakdowns
- Multi-dataset charts for comparative analysis

**Color Schemes**:
```php
'backgroundColor' => [
    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
    '#FF9F40', '#FFCD', '#C9CBCF', '#FF6384', '#36A2EB',
    // ... additional colors for variety
]
```

## Performance Optimization

### 1. Query Optimization
**Efficient Joins**: Uses proper joins and eager loading:
```php
->whereHas('order', function ($query) use ($startDate, $endDate) {
    $query->whereBetween('tanggal', [$startDate, $endDate])
        ->where('id_status', 2)
        ->where('deleted', 0);
})
```

**Aggregation Functions**: Uses database-level aggregations:
```php
DB::raw('SUM(qty) as total_qty')
DB::raw('AVG(harga) as avg_price')
```

### 2. Data Processing Efficiency
**Grouped Processing**: Processes data in logical groups to minimize database queries.

**Calculated Fields**: Pre-calculates complex metrics to avoid repeated calculations in views.

### 3. Memory Management
**Collection Filtering**: Uses Laravel collections for efficient data filtering and transformation.

**Lazy Loading**: Implements eager loading where appropriate to prevent N+1 query problems.

## Business Intelligence Features

### 1. Sales Trend Analysis
- **Daily Trends**: Identifies daily sales patterns
- **Weekly Patterns**: Shows day-of-week performance
- **Hourly Distribution**: Reveals peak business hours

### 2. Product Performance Metrics
- **Top Performers**: Identifies best-selling items
- **Category Analysis**: Compares category performance
- **Revenue Contribution**: Shows revenue impact by product

### 3. Financial Health Indicators
- **Gross vs Net Sales**: Shows impact of discounts and refunds
- **Average Order Value**: Tracks customer spending patterns
- **Tax Calculations**: Provides accurate tax reporting

### 4. Operational Metrics
- **Order Completion Rates**: Tracks operational efficiency
- **Cancellation Analysis**: Identifies potential issues
- **Processing Statistics**: Shows order flow metrics

## Error Handling

### 1. Data Validation
```php
if(Sentinel::check()){
    // Process dashboard data
}else{
    return redirect()->route('login');
}
```

### 2. Date Range Validation
- Provides sensible defaults (current month)
- Handles invalid date formats gracefully
- Ensures end date is after start date

### 3. Division by Zero Protection
- Checks for zero values before division operations
- Provides default values for empty datasets
- Handles edge cases in calculations

## Usage Examples

### 1. Default Dashboard Load
```php
GET /dashboard
// Returns current month analytics
```

### 2. Custom Date Range
```php
GET /dashboard?startDate=2024-01-01&endDate=2024-01-31
// Returns January 2024 analytics
```

### 3. AJAX Notification Frame
```php
GET /notif-frame
// Returns notification frame for embedding
```

## Integration Points

### 1. Real-time Updates
- Notification system integration
- Live data refresh capabilities
- WebSocket support for real-time metrics

### 2. Export Capabilities
- Chart data available for export
- Financial reports generation
- PDF/Excel export integration points

### 3. Mobile Responsiveness
- Chart.js responsive configuration
- Mobile-optimized layouts
- Touch-friendly interactions

## Configuration Requirements

### 1. Database Indexes
Recommended indexes for optimal performance:
```sql
CREATE INDEX idx_orders_status_date ON orders(id_status, tanggal, deleted);
CREATE INDEX idx_detail_order_menu_order ON detail_order(id_menu, id_order);
CREATE INDEX idx_orders_created_at ON orders(created_at);
```

### 2. Chart.js Configuration
```javascript
// Responsive chart configuration
options: {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: {
            beginAtZero: true
        }
    }
}
```

## Troubleshooting

### Common Issues
1. **Slow Loading**: Check database indexes and query optimization
2. **Chart Rendering**: Verify Chart.js library loading and data format
3. **Date Range Issues**: Validate date format and range logic
4. **Memory Issues**: Monitor collection sizes and implement pagination if needed

### Debug Steps
1. Check database query performance
2. Verify data integrity and relationships
3. Monitor memory usage during processing
4. Validate chart data structure
5. Test with different date ranges and data volumes
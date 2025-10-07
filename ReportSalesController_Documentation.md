# ReportSalesController Documentation

## Overview
The `ReportSalesController` is a comprehensive business intelligence and analytics engine for the Goodfellas POS system. It provides detailed sales reporting, financial analysis, and performance metrics across multiple dimensions including time periods, products, categories, payment methods, and sales types.

## Dependencies
- `Illuminate\Http\Request`
- `App\Models\Orders`
- `App\Models\Point_User`
- `App\Models\StatusOrder`
- `App\Models\Notify_user`
- `App\Models\Additional_menu_detail`
- `App\Models\AdditionalRefund`
- `App\Models\DetailOrder`
- `App\Models\Discount`
- `App\Models\Discount_detail_order`
- `App\Models\SalesType`
- `App\Models\Taxes`
- `App\Models\TaxOrder`
- `App\Models\Menu`
- `App\Models\RefundOrderMenu`
- `App\Models\DiscountMenuRefund`
- `App\Models\GroupModifier`
- `App\Models\OptionModifier`
- `App\Models\SubKategori`
- `App\Models\TypePayment`
- `App\Models\VarianMenu`
- `App\Models\RefundOrder`
- `Illuminate\Support\Facades\DB`
- `Illuminate\Support\Facades\Log`
- `Sentinel`
- `Carbon\Carbon`

## Class Structure
```php
class ReportSalesController extends Controller
```

## Core Financial Calculation Engine

### Base Financial Metrics Calculation
All report methods use a standardized financial calculation engine:

```php
// Core sales data
$items = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate) {
    $query->whereBetween('tanggal', [$startDate, $endDate])
        ->where('id_status', 2)->where('deleted', 0);
})->sum('total');

// Refund data
$hargaRefund = RefundOrderMenu::whereHas('order', function ($query) use ($startDate, $endDate) {
    $query->whereBetween('tanggal', [$startDate, $endDate])
        ->where('id_status', 2)->where('deleted', 0);
})->sum('refund_nominal');

// Discount calculations
$totalDiscount = Discount_detail_order::whereHas('Detail_order', function ($query) use ($startDate, $endDate) {
    $query->whereHas('order', function ($query) use ($startDate, $endDate) {
        $query->whereBetween('tanggal', [$startDate, $endDate])
            ->where('id_status', 2)->where('deleted', 0);
    });
})->sum('total_discount');

// Financial totals
$allGrandSales = $items + $hargaRefund;
$allGrandDis = $totalDiscount - $refundDisCountSum;
$allGrandRefund = $hargaRefund;
$allGrandNet = $allGrandSales - $allGrandDis - $allGrandRefund;
```

## Main Report Methods

### 1. Report(Request $request)
**Purpose**: Generate main sales report with comprehensive financial overview.

**Parameters**:
- `$request->startDate` (string, optional) - Start date (default: start of current month)
- `$request->endDate` (string, optional) - End date (default: end of current month)

**Process Flow**:
1. **Date Range Setup**: Default to current month if no dates provided
2. **Financial Calculations**: Execute core financial metrics calculation
3. **Tax Integration**: Calculate PB1 and Service Charge
4. **Grand Total Calculation**: Final totals including taxes

**Tax Calculation Logic**:
```php
$taxpb1 = Taxes::where('nama', 'PB1')->first();
$service = Taxes::where('nama', 'Service Charge')->first();
$PB1 = $taxpb1->tax_rate / 100;
$Service = $service->tax_rate / 100;
$nominalPb1 = $allGrandNet * $PB1;
$nominalService = $allGrandNet * $Service;
$totalTax = $nominalPb1 + $nominalService;
$TotalGrand = $allGrandNet + $totalTax;
```

**Returns**: Main report view (`Report.ReportSales`) with comprehensive financial data

---

### 2. fileterSalesSummary(Request $request)
**Purpose**: Filtered sales summary with adjusted discount calculations.

**Key Difference from Main Report**:
```php
// Modified discount calculation
$allGrandDis = $totalDiscount; // Direct discount without refund adjustment
```

**Returns**: Sales summary view (`Report.Sales`)

---

### 3. GrossProfit(Request $request)
**Purpose**: Gross profit analysis with detailed margin calculations.

**Calculation Focus**: Emphasizes profit margins and cost analysis

**Returns**: Gross profit view (`Report.grossProfit`)

---

## Payment Method Analysis

### 4. pymentMethod(Request $request)
**Purpose**: Comprehensive analysis of sales performance by payment method.

**Process Flow**:

#### Payment Method Iteration
```php
$paymentMetode = TypePayment::all();
$paymentData = [];

foreach ($paymentMetode as $payment) {
    // Sales by payment method
    $items = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate, $payment) {
        $query->whereBetween('tanggal', [$startDate, $endDate])
            ->where('id_status', 2)
            ->where('deleted', 0)
            ->where('id_type_payment', $payment->id);
    })->sum('total');
    
    // Refunds by payment method
    $hargaRefund = RefundOrderMenu::whereHas('order', function ($query) use ($startDate, $endDate, $payment) {
        $query->whereBetween('tanggal', [$startDate, $endDate])
            ->where('id_status', 2)
            ->where('deleted', 0)
            ->where('id_type_payment', $payment->id);
    })->sum('refund_nominal');
    
    // Transaction count
    $totalTransaksi = Orders::where('id_type_payment', $payment->id)
        ->whereBetween('tanggal', [$startDate, $endDate])
        ->where('id_status', 2)
        ->where('deleted', 0)
        ->count();
}
```

#### Payment Data Structure
```php
$paymentData[] = [
    'paymentMethod' => $payment,
    'totalOrder' => $totalTransaksi,
    'totalPembayaran' => $TotalGrand
];
```

**Returns**: Payment method analysis view (`Report.paymentMethod`)

---

## Sales Type Analysis

### 5. SelesType(Request $request)
**Purpose**: Analyze sales performance by sales type (Dine-in, Takeaway, Delivery, etc.).

**Complex Query Structure**:
```php
foreach ($typeSales as $types) {
    $typesId = $types->id;
    
    // Count items by sales type
    $countItemTypeSales = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate) {
        $query->whereBetween('tanggal', [$startDate, $endDate])
            ->where('id_status', 2)->where('deleted', 0);
    })->where('id_sales_type', $types->id)->count();
    
    // Total revenue by sales type
    $SumTotal = Orders::whereHas('details', function ($query) use ($typesId) {
        $query->where('id_sales_type', $typesId);
    })->whereBetween('tanggal', [$startDate, $endDate])
        ->where('id_status', 2)->where('deleted', 0)
        ->sum('total_order');
    
    // Refund analysis by sales type
    $totalRefund = RefundOrderMenu::whereHas('detail_order', function ($query) use ($typesId, $startDate, $endDate) {
        $query->where('id_sales_type', $typesId)->whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->where('deleted', 0)->where('id_status', 2)
                ->whereBetween('tanggal', [$startDate, $endDate]);
        });
    })->sum('refund_nominal');
}
```

**Returns**: Sales type analysis view (`Report.SalesData`)

---

## Product Performance Analysis

### 6. ItemSales(Request $request)
**Purpose**: Detailed item-level sales analysis with variant support.

**Complex Variant Handling**:

#### Menu Items with Variants
```php
foreach ($menu as $itm) {
    if ($itm->varian->isNotEmpty()) {
        // Process each variant separately
        foreach ($itm->varian as $varian) {
            // Sales by variant
            $itmsum = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0);
            })->where('id_menu', $itm->id)
                ->where('id_varian', $varian->id)
                ->sum('qty');
            
            // Refunds by variant
            $refundSum = RefundOrderMenu::where('id_menu', $itm->id)
                ->where('id_varian', $varian->id)
                ->whereHas('order', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('id_status', 2)->where('deleted', 0);
                })->sum('qty');
            
            // Financial calculations per variant
            $grossSalesVarian = $TotalSum + $TotalSumRfeund;
            $netSalesVarian = $grossSalesVarian - $totalDiscountVarian - $totalRefundVarian;
            
            $variants[] = [
                'variant_name' => $varian->nama,
                'itemSold' => $sumSold,
                'itemrefund' => $refundSum,
                'GrossSalse' => $grossSalesVarian,
                'Discount' => $totalDiscountVarian,
                'Refund' => $totalRefundVarian,
                'NetSales' => $netSalesVarian,
            ];
        }
    }
}
```

#### Menu Items without Variants
```php
else {
    // Direct calculation for non-variant items
    $itmsum = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate) {
        $query->whereBetween('tanggal', [$startDate, $endDate])
            ->where('id_status', 2)->where('deleted', 0);
    })->where('id_menu', $itm->id)->sum('qty');
    
    // Calculate totals without variant complexity
    $netSales = $grossSales - $totalDiscount - $totalRefund;
}
```

#### Special Handling for Non-Variant Orders
```php
// Handle menu items with variants but ordered without variant selection
$detailOrders = DetailOrder::with(['menu', 'order'])
    ->whereHas('menu', function ($query) {
        $query->where('custom', 0)->whereHas('varian');
    })
    ->whereNull('id_varian')
    ->whereHas('order', function ($query) use ($startDate, $endDate) {
        $query->whereBetween('tanggal', [$startDate, $endDate])
            ->where('id_status', 2)
            ->where('deleted', 0);
    })
    ->get()
    ->groupBy('id_menu');
```

**Returns**: Item sales analysis view (`Report.ItemSales`)

---

## Modifier Analysis

### 7. Modifier(Request $request)
**Purpose**: Analyze sales performance of menu modifiers and additional options.

**Complex Modifier Calculations**:
```php
foreach ($additional as $adds) {
    $addsId = $adds->id;
    
    // Modifier sales quantity
    $itmAdsSold = Additional_menu_detail::whereHas('detail_order', function ($query) use ($startDate, $endDate) {
        $query->whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate])
                ->where('id_status', 2)->where('deleted', 0);
        });
    })->where('id_option_additional', $adds->id)->sum('qty');
    
    // Price calculation for modifiers
    $itmAdsSoldHarga = Additional_menu_detail::whereHas('detail_order', function ($query) use ($startDate, $endDate) {
        $query->whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate])
                ->where('id_status', 2)->where('deleted', 0);
        });
    })->where('id_option_additional', $adds->id)->value('total') ?? 0;
    
    // Average price calculation
    $hargaAdds = 0;
    if ($itmAdsSoldHarga !== 0 && $QtyAdsSold !== 0) {
        $hargaAdds = $itmAdsSoldHarga / $QtyAdsSold;
    }
    
    // Discount analysis for modifiers
    $discount = Discount::whereHas('Discount_detail', function ($query) use ($addsId, $startDate, $endDate) {
        $query->whereHas('Detail_order', function ($query) use ($addsId, $startDate, $endDate) {
            $query->whereHas('AddOptional_order', function ($query) use ($addsId) {
                $query->where('id_option_additional', $addsId);
            })->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0);
            });
        });
    })->value('rate_dis') ?? 0;
}
```

**Returns**: Modifier analysis view (`Report.modifier`)

---

## Discount Analysis

### 8. Discount(Request $request)
**Purpose**: Analyze discount usage and effectiveness.

**Discount Metrics**:
```php
foreach ($Discount as $dis) {
    // Discount usage count
    $countDis = Discount_detail_order::whereHas('Detail_order', function ($query) use ($startDate, $endDate) {
        $query->whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate])
                ->where('id_status', 2)->where('deleted', 0);
        });
    })->where('id_discount', $dis->id)->count();
    
    // Total discount amount
    $grossDis = Discount_detail_order::whereHas('Detail_order', function ($query) use ($startDate, $endDate) {
        $query->whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate])
                ->where('id_status', 2)->where('deleted', 0);
        });
    })->where('id_discount', $dis->id)->sum('total_discount');
    
    // Refund discount analysis
    $refundDisCount = DiscountMenuRefund::whereHas('Refund', function ($query) use ($startDate, $endDate) {
        $query->whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate])
                ->where('id_status', 2)->where('deleted', 0);
        });
    })->where('id_discount', $dis->id)->sum('nominal_dis');
    
    // Net discount calculation
    $sumDiscountTotal = $grossDis + $refundDisCount;
    $netDis = $sumDiscountTotal - $refundDisCount;
}
```

**Returns**: Discount analysis view (`Report.discount`)

---

## Tax Analysis

### 9. Taxes(Request $request)
**Purpose**: Analyze tax collection and calculations.

**Tax Calculation Process**:
```php
foreach ($taxes as $tax) {
    // Calculate net sales base for tax
    $allGrandSales = $items + $hargaRefund;
    $allGrandDis = $totalDiscount - $refundDisCountSum;
    $allGrandRefund = $hargaRefund;
    $netGross = $allGrandSales - $allGrandDis - $allGrandRefund;
    
    // Apply tax rate
    $taxs = $tax->tax_rate / 100;
    $nominal = $netGross * $taxs;
    
    $dataTax[] = [
        'Taxs' => $tax,
        'Net' => $netGross,
        'taxTotal' => $nominal
    ];
}
```

**Returns**: Tax analysis view (`Report.Taxes`)

---

## Category Performance Analysis

### 10. Category(Request $request)
**Purpose**: Analyze sales performance by product categories.

**Category Analysis Process**:
```php
foreach ($subcategory as $cat) {
    $idCat = $cat->id;
    
    // Sales by category
    $totalItem = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate) {
        $query->whereBetween('tanggal', [$startDate, $endDate])
            ->where('id_status', 2)->where('deleted', 0);
    })->whereHas('menu.subKategori', function ($query) use ($idCat) {
        $query->where('id', $idCat);
    })->sum('total');
    
    // Quantity sold by category
    $qtySold = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate) {
        $query->whereBetween('tanggal', [$startDate, $endDate])
            ->where('id_status', 2)->where('deleted', 0);
    })->whereHas('menu.subKategori', function ($query) use ($idCat) {
        $query->where('id', $idCat);
    })->sum('qty');
    
    // Discount analysis by category
    $discount = Discount_detail_order::whereHas('Detail_order', function ($query) use ($idCat, $startDate, $endDate) {
        $query->whereHas('order', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate])
                ->where('id_status', 2)->where('deleted', 0);
        })->whereHas('menu', function ($query) use ($idCat) {
            $query->where('id_sub_kategori', $idCat);
        });
    })->sum('total_discount');
    
    // Refund analysis by category
    $qtyRefund = RefundOrderMenu::whereHas('order', function ($query) use ($startDate, $endDate) {
        $query->whereBetween('tanggal', [$startDate, $endDate])
            ->where('id_status', 2)->where('deleted', 0);
    })->whereHas('menu.subKategori', function ($query) use ($idCat) {
        $query->where('id', $idCat);
    })->sum('qty');
}
```

**Returns**: Category analysis view (`Report.Category`)

---

## Export and Data Visualization

### 11. viewReport(Request $request)
**Purpose**: Generate structured data for export or API consumption.

**Data Structure**:
```php
$penjualan = Orders::with('details')->whereBetween('tanggal', [$startDate, $endDate])->get();

$dataDetail = [];
foreach ($penjualan as $order) {
    foreach ($order->details as $detail) {
        $dataDetail[] = [
            'nama' => $detail->menu->nama_menu ?? null,
            'varian' => $detail->varian->nama ?? null,
            'qty' => $detail->qty,
            'harga' => $detail->harga,
            'total' => $detail->total
        ];
    }
}

return response()->json([
    'Data' => [
        'Penjualan' => $penjualan,
        'detail' => $dataDetail
    ]
], 200);
```

**Returns**: JSON response with structured sales data

## Advanced Analytics Features

### 1. Multi-Dimensional Analysis
The controller provides analysis across multiple business dimensions:
- **Time-based**: Date range filtering with flexible periods
- **Product-based**: Individual items, variants, categories
- **Financial**: Gross sales, net sales, refunds, discounts, taxes
- **Operational**: Payment methods, sales types, modifier usage
- **Customer**: Sales type analysis (dine-in, takeaway, delivery)

### 2. Variant-Aware Calculations
**Complex Variant Logic**:
- Handles menu items with multiple variants
- Separate calculations for each variant
- Special handling for orders without variant selection
- Aggregated reporting across all variants

### 3. Refund Impact Analysis
**Comprehensive Refund Tracking**:
- Refund quantities and amounts by all dimensions
- Refund discount calculations
- Net impact on sales figures
- Refund analysis by payment method and sales type

### 4. Discount Effectiveness Metrics
**Discount Analysis Features**:
- Usage frequency tracking
- Total discount amounts
- Refund discount impact
- Net discount effectiveness
- Category-specific discount analysis

## Performance Optimization Strategies

### 1. Query Optimization
**Efficient Database Operations**:
```php
// Use proper joins and relationships
->whereHas('order', function ($query) use ($startDate, $endDate) {
    $query->whereBetween('tanggal', [$startDate, $endDate])
        ->where('id_status', 2)->where('deleted', 0);
})

// Aggregate at database level
->sum('total')
->count()
->avg('harga')
```

### 2. Memory Management
**Efficient Data Processing**:
- Process data in chunks for large datasets
- Use database aggregation instead of collection operations
- Implement lazy loading for relationships
- Clear variables after processing large datasets

### 3. Caching Strategy
**Report Caching Opportunities**:
- Cache frequently accessed report data
- Implement Redis caching for complex calculations
- Use query result caching for static periods
- Cache chart data for dashboard integration

## Business Intelligence Features

### 1. Financial Health Indicators
- **Gross vs Net Analysis**: Impact of discounts and refunds
- **Margin Analysis**: Profit calculations and cost analysis
- **Tax Compliance**: Accurate tax reporting and calculations
- **Payment Method Performance**: Revenue by payment type

### 2. Operational Metrics
- **Sales Type Performance**: Channel effectiveness analysis
- **Product Performance**: Best and worst performing items
- **Category Analysis**: Category contribution to revenue
- **Modifier Effectiveness**: Additional revenue from modifiers

### 3. Trend Analysis
- **Time-based Trends**: Daily, weekly, monthly patterns
- **Seasonal Analysis**: Performance across different periods
- **Growth Metrics**: Period-over-period comparisons
- **Forecasting Data**: Historical data for predictions

## Error Handling and Validation

### 1. Data Validation
```php
if(Sentinel::check()){
    // Process report data
}else{
    return redirect()->route('login');
}
```

### 2. Date Range Validation
- Sensible defaults (current month)
- Date format validation
- Range validation (end date after start date)
- Maximum range limits for performance

### 3. Division by Zero Protection
```php
$hargaAdds = 0;
if ($itmAdsSoldHarga !== 0 && $QtyAdsSold !== 0) {
    $hargaAdds = $itmAdsSoldHarga / $QtyAdsSold;
}
```

## Integration Points

### 1. Dashboard Integration
- Chart data generation for dashboard widgets
- Real-time metrics for live dashboards
- KPI calculations for executive summaries

### 2. Export Capabilities
- Excel export integration
- PDF report generation
- CSV data export
- API data endpoints

### 3. External System Integration
- ERP system data feeds
- Accounting system integration
- Business intelligence platform connections
- Third-party analytics tools

## Usage Examples

### 1. Monthly Sales Report
```php
GET /report?startDate=2024-01-01&endDate=2024-01-31
```

### 2. Payment Method Analysis
```php
GET /report/payment-method?startDate=2024-01-01&endDate=2024-01-31
```

### 3. Item Performance Report
```php
GET /report/item-sales?startDate=2024-01-01&endDate=2024-01-31
```

### 4. Category Analysis
```php
GET /report/category?startDate=2024-01-01&endDate=2024-01-31
```

## Troubleshooting

### Common Issues
1. **Slow Report Generation**: Optimize queries and add database indexes
2. **Memory Issues**: Implement chunking for large datasets
3. **Incorrect Calculations**: Verify refund and discount logic
4. **Missing Data**: Check order status and deletion flags

### Debug Steps
1. Monitor query performance and execution time
2. Verify data integrity and relationships
3. Check calculation logic with sample data
4. Validate date range and filtering logic
5. Test with different data volumes and scenarios

This comprehensive documentation covers all aspects of the ReportSalesController, providing detailed insights into its sophisticated analytics capabilities and business intelligence features.
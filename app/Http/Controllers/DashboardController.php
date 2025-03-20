<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\DetailOrder;

use App\Models\BookingTempat;

use App\Models\Kategori;
use App\Models\Orders;
use App\Models\StatusOrder;
use Illuminate\Support\Facades\DB;
use App\Models\RefundOrderMenu;
use App\Models\DiscountMenuRefund;
use App\Models\Discount_detail_order;
use App\Models\Taxes;
use Sentinel;

class DashboardController extends Controller

{

    public function Index(Request $request){
        if(Sentinel::check()){

            if($request->has('startDate')){
                $startDate = $request->startDate;
                // $startDate = '2024-05-27';
                $endDate = $request->endDate;
                // $endDate = '2024-06-06';
            }
            else{
                $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
                $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
            }
            
            $itemSalesMenu = [];
            $allGrandSales = 0;
            $allGrandDis = 0;
            $allGrandRefund = 0;
            $allGrandNet = 0;
            $grossSalesDaily = [];

            $pesanan_baru = Orders::where('id_status', 1)->where('tanggal', '>=', $startDate)->where('tanggal', '<=', $endDate)->where('deleted', 0)->count();
            $pesanan_selesai = Orders::where('id_status', 2)->where('tanggal', '>=', $startDate)->where('tanggal', '<=', $endDate)->where('deleted', 0)->count();
            $pesanan_batal = Orders::where('deleted', 1)->where('tanggal', '>=', $startDate)->where('tanggal', '<=', $endDate)->count();
            $avrg_order_bill = Orders::where('id_status', 2)->whereBetween('tanggal', [$startDate, $endDate])->where('deleted', 0)->avg('total_order');

            $topSellingItems = DetailOrder::select('id_menu', DB::raw('SUM(qty) as total_qty'), DB::raw('AVG(harga) as avg_price'))
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->where('id_status', 2)
                    ->where('deleted', 0)
                    ->whereBetween('tanggal', [$startDate, $endDate]);
            })
            ->groupBy('id_menu')
            ->orderByDesc('total_qty')
            ->with('menu') // Assuming you have a product relation to get the product details
            ->get();
        
            $topSellingItems = $topSellingItems->filter(function ($item) {
                return $item->total_qty >= 20;
            });
            
            if ($topSellingItems->isEmpty()) {
                $topSellingItems = DetailOrder::select('id_menu', DB::raw('SUM(qty) as total_qty'), DB::raw('AVG(harga) as avg_price'))
                    ->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->where('id_status', 2)
                            ->where('deleted', 0)
                            ->whereBetween('tanggal', [$startDate, $endDate]);
                    })
                    ->groupBy('id_menu')
                    ->orderByDesc('total_qty')
                    ->with('menu') // Assuming you have a product relation to get the product details
                    ->get()
                    ->filter(function ($item) {
                        return $item->total_qty >= 10;
                    });
            }
            
            if ($topSellingItems->isEmpty()) {
                $topSellingItems = DetailOrder::select('id_menu', DB::raw('SUM(qty) as total_qty'), DB::raw('AVG(harga) as avg_price'))
                    ->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->where('id_status', 2)
                            ->where('deleted', 0)
                            ->whereBetween('tanggal', [$startDate, $endDate]);
                    })
                    ->groupBy('id_menu')
                    ->orderByDesc('total_qty')
                    ->with('menu') // Assuming you have a product relation to get the product details
                    ->get()
                    ->filter(function ($item) {
                        return $item->total_qty >= 1;
                    });
            }
            // return response()->json($topSellingItems);

            foreach($topSellingItems as $itm){
                 $Refund = RefundOrderMenu::whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)->where('deleted', 0);
                    })->where('id_menu', $itm->id)->sum('qty');
                
                $hargaRefund = RefundOrderMenu::where('id_menu', $itm->id)
                    ->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)->where('deleted', 0);
                    })->value('harga');

                $totalDiscount = Discount_detail_order::whereHas('Detail_order', function ($query) use ($itm,$startDate, $endDate) {
                            $query->where('id_menu', $itm->id)
                                ->whereHas('order', function ($query) use ($startDate, $endDate) {
                                    $query->whereBetween('tanggal', [$startDate, $endDate])
                                    ->where('id_status', 2)->where('deleted', 0);
                                });
                        })->sum('total_discount');

                $refundDisCountSum = DiscountMenuRefund::whereHas('Refund', function ($query) use ( $itm,$startDate, $endDate) {
                        $query->where('id_menu', $itm->id)->whereHas('order', function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('tanggal', [$startDate, $endDate])
                                ->where('id_status', 2)->where('deleted', 0);
                        });
                    })->sum('nominal_dis');

                $harga = ($itm->total_qty + $Refund) * $itm->avg_price ;
                $totalRefund = $hargaRefund * $Refund;

                $disTotal = $totalDiscount - $refundDisCountSum;
                $netSales = $harga - $disTotal - $totalRefund;

                $itemSalesMenu[] = [
                    'Name' => $itm->menu->nama_menu,
                    'itemSold' => $itm->total_qty,
                    'GrossSalse' => $harga,
                    'NetSales' => $netSales,
                    
                ];

            } 
            
            // chart top product
            $chartData = [
                'labels' => $topSellingItems->pluck('menu.nama_menu'),
                'datasets' => [
                    [
                        'label' => 'Total Qty Sold',
                        'data' => $topSellingItems->pluck('total_qty'),
                        'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                        'borderColor' => 'rgba(75, 192, 192, 1)',
                        'borderWidth' => 1,
                    ]
                ]
            ];

              // harga menu detail order
            $items = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0);
            })->sum('total');
            // total qty menu detail order
            $itmsum = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0);
            })->sum('qty');
            // sum qty refund
            $SumRefund = RefundOrderMenu::whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0);
            })->sum('qty');
            // harga item refund
            $hargaRefund = RefundOrderMenu::whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0);
            })->sum('refund_nominal');
            // sum total discount
            $totalDiscount = Discount_detail_order::whereHas('Detail_order', function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('id_status', 2)->where('deleted', 0);
                });
            })->sum('total_discount');

            // sum total refund discount
            $refundDisCountSum = DiscountMenuRefund::whereHas('Refund', function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('id_status', 2)->where('deleted', 0);
                });
            })->sum('nominal_dis');

            $taxpb1 = Taxes::where('nama', 'PB1')->first();
            $service = Taxes::where('nama', 'Service Charge')->first();


            $allGrandDis =  $totalDiscount - $refundDisCountSum;
            $allGrandRefund = $hargaRefund;
            $allGrandSales =   $items + $hargaRefund;
            $allGrandNet =  $allGrandSales -  $allGrandDis -  $allGrandRefund;

            $PB1 = $taxpb1->tax_rate / 100;
            $Service = $service->tax_rate / 100;

            $nominalPb1 = $allGrandNet * $PB1;
            $nominalService = $allGrandNet * $Service;

            $totalTax = $nominalPb1 + $nominalService;

            $TotalGrand = $allGrandNet + $totalTax;


            $Order_bill = Orders::select(DB::raw('DATE(tanggal) as date'), 'id')
            ->where('id_status', 2)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('deleted', 0)
            ->groupBy('date', 'id')
            ->orderBy('date')
            ->get();


            foreach($Order_bill as $Order) {
                $items = DetailOrder::where('id_order', $Order->id)
                    ->sum('total');

                $hargaRefund = RefundOrderMenu::where('id_order', $Order->id)
                    ->sum('refund_nominal');

                $GrandSales = $items + $hargaRefund;

                $grossSalesDaily[$Order->date][] = $GrandSales;
            }

            // dd($grossSalesDaily);
            $grossSalesDaily = collect($grossSalesDaily)->map(function($sales, $date) {
                return [
                    'date' => $date,
                    'gross_sales' => array_sum($sales)
                ];
            })->values();
            
            $chartDailyGrossSales = [
                'labels' => $grossSalesDaily->pluck('date'),
                'datasets' => [
                    [
                        'label' => 'Gross Sales',
                        'data' => $grossSalesDaily->pluck('gross_sales'),
                        'backgroundColor' => [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                            '#FF9F40', '#FFCD', '#C9CBCF', '#FF6384', '#36A2EB',
                            '#4A1313', '#D0A024', '#51D024', '#245FD0', '#C493CC',
                            '#F4D3D5', '#F6076C', '#F65D07', '#FAFF1B', '#140A3A',
                        ],
                        'borderColor' => 'rgba(75, 192, 192, 1)',
                        
                        'borderWidth' => 1,
                    ]
                ]
            ];
            
            // week daily gross sales
            $Order_bill_week = Orders::select(DB::raw('DAYNAME(tanggal) as day_of_week'), 'id', 'tanggal')
            ->where('id_status', 2)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('deleted', 0)
            ->orderBy('tanggal')
            ->get();

       
            $grossSalesPerDayOfWeek = [
                'Sunday' => 0,
                'Monday' => 0,
                'Tuesday' => 0,
                'Wednesday' => 0,
                'Thursday' => 0,
                'Friday' => 0,
                'Saturday' => 0,
            ];

            foreach($Order_bill_week as $Order_week) {
            // Calculate the total sales and refunds
            $items_week = DetailOrder::where('id_order', $Order_week->id)->sum('total');
            $hargaRefund_week = RefundOrderMenu::where('id_order', $Order_week->id)->sum('refund_nominal');
            $GrandSales_week = $items_week + $hargaRefund_week;

            // Group sales by day of the week
            if(isset($grossSalesPerDayOfWeek[$Order_week->day_of_week])) {
                $grossSalesPerDayOfWeek[$Order_week->day_of_week] += $GrandSales_week;
            }
            }

            $chartDayOfWeekGrossSales = [
            'labels' => array_keys($grossSalesPerDayOfWeek),
            'datasets' => [
                [
                    'label' => 'Gross Sales Amount',
                    'data' => array_values($grossSalesPerDayOfWeek),
                    'backgroundColor' => [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                            '#FF9F40', '#FFCD', '#C9CBCF', '#FF6384', '#36A2EB',
                            '#4A1313', '#D0A024', '#51D024', '#245FD0', '#C493CC',
                            '#F4D3D5', '#F6076C', '#F65D07', '#FAFF1B', '#140A3A',
                        ],
                   
                    'borderWidth' => 1,
                ]
            ]
            ];

            // hourly 
            $startDateTime = $startDate . ' 00:00:00';
            $endDateTime = $endDate . ' 23:59:59';
            $Order_bill_hourly = Orders::select(DB::raw('HOUR(created_at) as hour'), 'id', 'created_at')
            ->where('id_status', 2)
            ->whereBetween('created_at', [$startDateTime, $endDateTime])
            ->where('deleted', 0)
            ->orderBy('created_at')
            ->get();
                // dd($Order_bill_hourly);
            // Inisialisasi array untuk menyimpan gross sales per jam
            $grossSalesPerHour = array_fill(0, 24, 0);

            foreach($Order_bill_hourly as $Order_hourly) {
                // Menghitung total penjualan dan refund per jam
                $items_hourly = DetailOrder::where('id_order', $Order_hourly->id)->sum('total');
                $hargaRefund_hourly = RefundOrderMenu::where('id_order', $Order_hourly->id)->sum('refund_nominal');
                $GrandSales_hourly = $items_hourly + $hargaRefund_hourly;

                // Mengelompokkan penjualan berdasarkan jam
                $hour = (int) $Order_hourly->hour;
                $grossSalesPerHour[$hour] += $GrandSales_hourly;
            }

            // Menyiapkan data untuk Chart.js
            $chartHourlyGrossSales = [
                'labels' => range(0, 23),
                'datasets' => [
                    [
                        'label' => 'Gross Sales Amount',
                        'data' => array_values($grossSalesPerHour),
                        'backgroundColor' => [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                            '#FF9F40', '#FFCD', '#C9CBCF', '#FF6384', '#36A2EB',
                            '#4A1313', '#D0A024', '#51D024', '#245FD0', '#C493CC',
                            '#F4D3D5', '#F6076C', '#F65D07', '#FAFF1B', '#140A3A',
                        ],
                        'borderColor' => 'rgba(75, 192, 192, 1)',
                        'borderWidth' => 1,
                    ]
                ]
            ];

            // dd($chartHourlyGrossSales);
            // end Hourly

            // category by volum
            $categoryVolumes = DetailOrder::with(['menu'])
            ->select('sub_kategori_menu.sub_kategori', DB::raw('SUM(detail_order.qty) as total_qty'))
            ->join('menu', 'detail_order.id_menu', '=', 'menu.id')
            ->join('sub_kategori_menu', 'menu.id_sub_kategori', '=', 'sub_kategori_menu.id')
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)
                    ->where('deleted', 0);
            })
            ->groupBy('sub_kategori_menu.sub_kategori')
            ->get();

            $categoryLabels = $categoryVolumes->pluck('sub_kategori');
            $categoryData = $categoryVolumes->pluck('total_qty');

            $chartCategoryByVolume = [
                'labels' => $categoryLabels,
                'datasets' => [
                    [
                        'label' => 'Category by Volume',
                        'data' => $categoryData,
                        'backgroundColor' => [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                            '#FF9F40', '#FFCD', '#C9CBCF', '#FF6384', '#36A2EB',
                            '#4A1313', '#D0A024', '#51D024', '#245FD0', '#C493CC',
                            '#F4D3D5', '#F6076C', '#F65D07', '#FAFF1B', '#140A3A',
                        ],
                    ]
                ]
            ];

            // category by sales
            $categorySales = DetailOrder::with(['menu'])
            ->select('sub_kategori_menu.sub_kategori', DB::raw('SUM(detail_order.qty * detail_order.harga) as total_sales'))
            ->join('menu', 'detail_order.id_menu', '=', 'menu.id')
            ->join('sub_kategori_menu', 'menu.id_sub_kategori', '=', 'sub_kategori_menu.id')
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)
                    ->where('deleted', 0);
            })
            ->groupBy('sub_kategori_menu.sub_kategori')
            ->get();

            $categoryLabels = $categorySales->pluck('sub_kategori');
            $categoryData = $categorySales->pluck('total_sales');
            
            $chartCategoryBySales = [
                'labels' => $categoryLabels,
                'datasets' => [
                    [
                        'label' => 'Category by Sales',
                        'data' => $categoryData,
                        'backgroundColor' => [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                            '#FF9F40', '#FFCD56', '#C9CBCF', '#FF6384', '#36A2EB',
                            '#4A1313', '#D0A024', '#51D024', '#245FD0', '#C493CC',
                            '#F4D3D5', '#F6076C', '#F65D07', '#FAFF1B', '#140A3A',
                        ],
                    ]
                ]
            ];

            // top Items by Category
            $categories = DetailOrder::with(['menu'])
                ->select('sub_kategori_menu.sub_kategori', 'menu.nama_menu', DB::raw('SUM(detail_order.qty) as total_qty'))
                ->join('menu', 'detail_order.id_menu', '=', 'menu.id')
                ->join('sub_kategori_menu', 'menu.id_sub_kategori', '=', 'sub_kategori_menu.id')
                ->whereHas('order', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('id_status', 2)
                        ->where('deleted', 0);
                })
                ->groupBy('sub_kategori_menu.sub_kategori', 'menu.nama_menu')
                ->get();

            // Menyusun data untuk chart
            $chartData_items_cat = [];
            foreach ($categories as $category) {
                $sub_kategori = $category->sub_kategori;
                if (!isset($chartData_items_cat[$sub_kategori])) {
                    $chartData_items_cat[$sub_kategori] = [
                        'labels' => [],
                        'data' => [],
                    ];
                }
                $chartData_items_cat[$sub_kategori]['labels'][] = $category->nama_menu;
                $chartData_items_cat[$sub_kategori]['data'][] = $category->total_qty;
            }
            // dd( $chartData_items_cat);

            return view('dashboard', compact(
                'endDate',
                'startDate',
                'pesanan_batal', 
                'pesanan_selesai', 
                'pesanan_baru', 
                'itemSalesMenu', 
                'chartData',
                'allGrandSales',
                'allGrandDis',
                'allGrandRefund',
                'allGrandNet',
                'totalTax',
                'TotalGrand',
                'avrg_order_bill',
                'chartDailyGrossSales',
                'chartDayOfWeekGrossSales',
                'chartHourlyGrossSales',
                'chartCategoryByVolume',
                'chartCategoryBySales',
                'chartData_items_cat'
        ));
        }else{
            return redirect()->route('login');
        }


    }
    
	public function notifFrame(){
		return view('notif-frame');
	}

}


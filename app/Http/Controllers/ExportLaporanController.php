<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Point_User;
use App\Models\StatusOrder;
use App\Models\Notify_user;
use Sentinel;
use Carbon\Carbon;
use App\Events\MessageCreated;
use App\Models\Additional_menu_detail;
use App\Models\AdditionalRefund;
use App\Models\DetailOrder;
use App\Models\Discount;
use App\Models\Discount_detail_order;
use App\Models\SalesType;
use App\Models\Taxes;
use App\Models\TaxOrder;
use App\Models\Menu;
use App\Models\RefundOrderMenu;
use App\Models\DiscountMenuRefund;
use App\Models\GroupModifier;
use App\Models\OptionModifier;
use App\Models\SubKategori;
use App\Models\TypePayment;
use App\Exports\ReportSalesExport;
use App\Exports\GrossProfitExport;
use App\Exports\PaymentMethodeExport;
use App\Exports\CategoryExport;
use App\Exports\DiscountSalesExport;
use App\Exports\ItemSalesExport;
use App\Exports\ModifierSalesExport;
use App\Exports\SalesTypeExport;
use App\Exports\TaxesSalesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Models\VarianMenu;
use App\Exports\DetailTransactionItemsExport;
use DB;

class ExportLaporanController extends Controller
{
    public function ExportSalesSummary(Request $request)
    {
        if (Sentinel::check()) {

            if ($request->has('startDate')) {
                $startDate = $request->startDate;
                $endDate = $request->endDate;
            } else {
                $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
                $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
            }

            $allGrandSales = 0;
            $allGrandDis = 0;
            $allGrandRefund = 0;
            $allGrandNet = 0;


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
                    $query->whereBetween('tanggal', [$startDate, $endDate]);
                });
            })->sum('nominal_dis');
    
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
    
        
    
            $taxpb1 = Taxes::where('nama', 'PB1')->first();
            $service = Taxes::where('nama', 'Service Charge')->first();
            // dd($totalDiscount,$refundDisCountSum);
            $allGrandSales =  $items + $hargaRefund;
    
            $sumDiscountTotal =  $totalDiscount + $refundDisCountSum;
            $allGrandDis =  $sumDiscountTotal - $refundDisCountSum;
            $allGrandRefund = $hargaRefund;
            $allGrandNet =  $allGrandSales -  $allGrandDis -  $allGrandRefund;
    
            $PB1 = $taxpb1->tax_rate / 100;
            $Service = $service->tax_rate / 100;
    
            $nominalPb1 = $allGrandNet * $PB1;
            $nominalService = $allGrandNet * $Service;
    
            $totalTax = $nominalPb1 + $nominalService;
            $TotalGrand = $allGrandNet + $totalTax;

            Log::info("Exporting sales summary", [
                'allGrandSales' => $allGrandSales,
                'allGrandDis' => $allGrandDis,
                'allGrandRefund' => $allGrandRefund,
                'allGrandNet' => $allGrandNet,
                'totalTax' => $totalTax,
                'TotalGrand' => $TotalGrand
            ]);

            return Excel::download(new ReportSalesExport(
                $allGrandSales,
                $allGrandDis,
                $allGrandRefund,
                $allGrandNet,
                $totalTax,
                $TotalGrand,
            ), 'Laporan Sales Summary.xlsx');

            
        } else {
            return redirect()->route('login');
        }
    }

    public function ExportGrossProfit(Request $request)
    {
        if (Sentinel::check()) {
            if ($request->has('startDate')) {

                $startDate = $request->startDate;
                $endDate = $request->endDate;
            } else {
                $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
                $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
            }


            $allGrandSales = 0;
            $allGrandDis = 0;
            $allGrandRefund = 0;
            $allGrandNet = 0;

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



            $taxpb1 = Taxes::where('nama', 'PB1')->first();
            $service = Taxes::where('nama', 'Service Charge')->first();

            $allGrandSales =   $items + $hargaRefund;
            $sumDiscountTotal =  $totalDiscount + $refundDisCountSum;
            $allGrandDis =  $sumDiscountTotal - $refundDisCountSum;
            $allGrandRefund =  $hargaRefund;
            $allGrandNet =  $allGrandSales -  $allGrandDis -  $allGrandRefund;

            $PB1 = $taxpb1->tax_rate / 100;
            $Service = $service->tax_rate / 100;

            $nominalPb1 = $allGrandNet * $PB1;
            $nominalService = $allGrandNet * $Service;

            $totalTax = $nominalPb1 + $nominalService;

            $TotalGrand = $allGrandNet + $totalTax;

            return Excel::download(new GrossProfitExport(
                $allGrandSales,
                $allGrandDis,
                $allGrandRefund,
                $allGrandNet,
                $totalTax,
                $TotalGrand,
            ), 'Laporan Gross Profit.xlsx');
        } else {
            return redirect()->route('login');
        }
    }

    public function ExportPaymentMethode(Request $request)
    {
        if (Sentinel::check()) {
            if ($request->has('startDate')) {

                $startDate = $request->startDate;
                $endDate = $request->endDate;
            } else {
                $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
                $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
            }

            $paymentMetode = TypePayment::all();
            $paymentData = [];
            $totalOrders = 0;
            $totalPembayarans= 0;
            $menu = Menu::all();
            $additional = OptionModifier::all();

            foreach ($paymentMetode as $payment) {

                 // harga menu detail order
                $items = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate, $payment) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('id_status', 2)->where('deleted', 0)->where('id_type_payment', $payment->id);
                })->sum('total');

                // total qty menu detail order
                $itmsum = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate, $payment) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('id_status', 2)->where('deleted', 0)->where('id_type_payment', $payment->id);
                })->sum('qty');

                // sum total discount
                $totalDiscount = Discount_detail_order::whereHas('Detail_order', function ($query) use ($startDate, $endDate, $payment) {
                    $query->whereHas('order', function ($query) use ($startDate, $endDate, $payment) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)->where('deleted', 0)->where('id_type_payment', $payment->id);
                    });
                })->sum('total_discount');

                // sum total refund discount
                $refundDisCountSum = DiscountMenuRefund::whereHas('Refund', function ($query) use ($startDate, $endDate, $payment) {
                    $query->whereHas('order', function ($query) use ($startDate, $endDate, $payment) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])->where('deleted', 0)->where('id_type_payment', $payment->id);
                    });
                })->sum('nominal_dis');

                // sum qty refund
                $SumRefund = RefundOrderMenu::whereHas('order', function ($query) use ($startDate, $endDate, $payment) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('id_status', 2)->where('deleted', 0)->where('id_type_payment', $payment->id);
                })->sum('qty');
                // harga item refund
                $hargaRefund = RefundOrderMenu::whereHas('order', function ($query) use ($startDate, $endDate, $payment) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('id_status', 2)->where('deleted', 0)->where('id_type_payment', $payment->id);
                })->sum('refund_nominal');

                $totalTransaksi = Orders::where('id_type_payment', $payment->id)->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0)->count();


                $taxpb1 = Taxes::where('nama', 'PB1')->first();
                $service = Taxes::where('nama', 'Service Charge')->first();
                // dd($totalDiscount,$refundDisCountSum);
                $allGrandSales =  $items + $hargaRefund;

                $sumDiscountTotal =  $totalDiscount + $refundDisCountSum;
                $allGrandDis =  $sumDiscountTotal - $refundDisCountSum;
                $allGrandRefund = $hargaRefund;
                $allGrandNet =  $allGrandSales -  $allGrandDis -  $allGrandRefund;

                $PB1 = $taxpb1->tax_rate / 100;
                $Service = $service->tax_rate / 100;

                $nominalPb1 = $allGrandNet * $PB1;
                $nominalService = $allGrandNet * $Service;

                $totalTax = $nominalPb1 + $nominalService;
                $GandTotal = $allGrandNet + $totalTax;

                $paymentData[] = [
                    'paymentMethod' => $payment,
                    'totalOrder' =>  $totalTransaksi,
                    'totalPembayaran' => $GandTotal
                ];
            }

            return Excel::download(new PaymentMethodeExport(
                $paymentData,
                $paymentMetode,
                $totalOrders,
                $totalPembayarans,

            ), 'Laporan Payment Methode.xlsx');
        } else {
            return redirect()->route('login');
        }
    }

    public function ExportSelesType(Request $request)
    {
        if (Sentinel::check()) {

            if ($request->has('startDate')) {

                $startDate = $request->startDate;
                $endDate = $request->endDate;
            } else {
                $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
                $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
            }

            $typeSales = SalesType::all();
            $SumTotal = 0;
            $SalesData = [];
            $totalOrders =0;
            $totalPembayarans = 0;



            foreach ($typeSales as $types) {
                //count menu yang sesuai type sales
                $typesId = $types->id;
                $countItemTypeSales = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('id_status', 2)->where('deleted', 0);
                })->where('id_sales_type', $types->id)->count();

                $SumTotal = Orders::whereHas('details', function ($query) use ($typesId) {
                    $query->where('id_sales_type', $typesId);
                })->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0)
                    ->sum('total_order');


                $totalRefund = RefundOrderMenu::whereHas('detail_order', function ($query) use ($typesId, $startDate, $endDate) {
                    $query->where('id_sales_type', $typesId)->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->where('deleted', 0)->where('id_status', 2)
                            ->whereBetween('tanggal', [$startDate, $endDate]);
                    });
                })->sum('refund_nominal');


                $refundAddsSum = AdditionalRefund::whereHas('Refund', function ($query) use ($typesId, $startDate, $endDate) {
                    $query->whereHas('detail_order', function ($query) use ($typesId, $startDate, $endDate) {
                        $query->where('id_sales_type', $typesId)->whereHas('order', function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('tanggal', [$startDate, $endDate])
                                ->where('id_status', 2)->where('deleted', 0);
                        });
                    });
                })->sum('total_');

                $refundDisCountSum = DiscountMenuRefund::whereHas('Refund', function ($query) use ($typesId, $startDate, $endDate) {
                    $query->whereHas('detail_order', function ($query) use ($typesId, $startDate, $endDate) {
                        $query->where('id_sales_type', $typesId)->whereHas('order', function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('tanggal', [$startDate, $endDate])
                                ->where('id_status', 2)->where('deleted', 0);
                        });
                    });
                })->sum('nominal_dis');


                $taxpb1 = Taxes::where('nama', 'PB1')->first();
                $service = Taxes::where('nama', 'Service Charge')->first();

                $PB1 = $taxpb1->tax_rate / 100;
                $Service = $service->tax_rate / 100;

                $grossRef = $totalRefund;
                // dd($grossRef);
                $subref = $grossRef  - $refundDisCountSum;
                //dd($subref);
                $nominalPb1Ref = $subref * $PB1;
                $nominalServiceRef = $subref * $Service;

                $totalTaxRef = $nominalPb1Ref + $nominalServiceRef;
                //dd($totalTaxRef);
                $subTotalRef = $subref + $totalTaxRef;
                //dd($subTotalRef);
                $GandTotal = $SumTotal - $subTotalRef;
                // $GandTotal = $SumTotal;

                $SalesData[] = [
                    'Sales Type' => $types->name,
                    'totalOrder' =>  $countItemTypeSales,
                    'Total' => $GandTotal,

                ];
            }

            return Excel::download(new SalesTypeExport(
                $SalesData,
                $totalOrders,
                $totalPembayarans

            ), 'Laporan Sales.xlsx');
        } else {
            return redirect()->route('login');
        }
    }

    public function ExportItemSales(Request $request)
    {
        if (Sentinel::check()) {

            if ($request->has('startDate')) {

                $startDate = $request->startDate;
                $endDate = $request->endDate;
            } else {
                $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
                $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
            }
            
            $menu = Menu::with('varian')->get();
            $additional = OptionModifier::all();

            $menuCustom = DetailOrder::whereHas('menu', function ($query) {
                $query->where('custom', '1')->with('varian');
            })->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0);
            })->get();

            $itemSalesMenu = [];
            $itemSalesAdss = [];
            
            $TOTAL = [];
            $totalItemSoldMenu = 0;
            $totalItemRefundMenu = 0;
            $totalGrossMenu = 0;
            $totalDiscountMenu = 0;
            $totalRefundMenu = 0;
            $totalNetMenu = 0;
            $totalItemSoldAdds = 0;
            $totalItemRefundAdds = 0;
            $totalGrossAdds = 0;
            $totalDiscountAdds = 0;
            $totalRefundAdds = 0;
            $totalNetAdds = 0;
            $totalItemSoldMenuOld = 0;
            $totalItemRefundMenuOld = 0;
            $totalGrossMenuOld = 0;
            $totalDiscountMenuOld = 0;
            $totalRefundMenuOld = 0; 
            $totalNetMenuOld = 0;

            foreach ($menu as $itm) {
                $totalItemSold = 0;
                $totalRefund = 0;
                $totalGrossSales = 0;
                $totalDiscount = 0;
                $totalNetSales = 0;

                $variants = [];

                // Cek apakah menu memiliki varian
                if ($itm->varian->isNotEmpty()) {
                    // Jika ada varian, hitung data untuk setiap varian
                    foreach ($itm->varian as $varian) {

                        // Total qty yang terjual
                        $itmsum = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('tanggal', [$startDate, $endDate])
                                ->where('id_status', 2)->where('deleted', 0);
                        })->where('id_menu', $itm->id)
                            ->where('id_varian', $varian->id)
                            ->sum('qty');

                        //total sum detail item    
                        $TotalSum = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('tanggal', [$startDate, $endDate])
                                ->where('id_status', 2)->where('deleted', 0);
                        })->where('id_menu', $itm->id)
                            ->where('id_varian', $varian->id)
                            ->sum('total');

                        // Total refund
                        $refundSum = RefundOrderMenu::where('id_menu', $itm->id)
                            ->where('id_varian', $varian->id)
                            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                                $query->whereBetween('tanggal', [$startDate, $endDate])
                                    ->where('id_status', 2)->where('deleted', 0);
                            })->sum('qty');

                        // Total refund
                        $TotalSumRfeund = RefundOrderMenu::where('id_menu', $itm->id)
                            ->where('id_varian', $varian->id)
                            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                                $query->whereBetween('tanggal', [$startDate, $endDate])
                                    ->where('id_status', 2)->where('deleted', 0);
                            })->sum('refund_nominal');

                        // Harga varian
                        $hargaVarian = $varian->harga;

                        // Hitung total refund berdasarkan harga varian
                        $totalRefundVarian = $TotalSumRfeund;

                        // Total qty yang terjual termasuk refund
                        $sumSold = $itmsum + $refundSum;

                        // Total gross sales
                        $grossSalesVarian = $TotalSum + $TotalSumRfeund;

                        // Total discount
                        $totalDiscountVarian = Discount_detail_order::whereHas('Detail_order', function ($query) use ($itm, $varian, $startDate, $endDate) {
                            $query->where('id_menu', $itm->id)
                                ->where('id_varian', $varian->id)
                                ->whereHas('order', function ($query) use ($startDate, $endDate) {
                                    $query->whereBetween('tanggal', [$startDate, $endDate])
                                        ->where('id_status', 2)->where('deleted', 0);
                                });
                        })->sum('total_discount');

                        // Net Sales
                        $netSalesVarian = $grossSalesVarian - $totalDiscountVarian - $totalRefundVarian;

                        // Simpan data per varian
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

                    // Jika menu memiliki varian, gunakan hanya data varian
                    $itemSalesMenu[] = [
                        'Name' => $itm->nama_menu,
                        'Variants' => $variants
                    ];
                } else {
                    // Jika menu tidak memiliki varian, hitung langsung datanya
                    $itmsum = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)->where('deleted', 0);
                    })->where('id_menu', $itm->id)->sum('qty');

                    //Total sum item sales
                    $TotalSumSales = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)->where('deleted', 0);
                    })->where('id_menu', $itm->id)->sum('total');

                    $refundSum = RefundOrderMenu::where('id_menu', $itm->id)
                        ->whereHas('order', function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('tanggal', [$startDate, $endDate])
                                ->where('id_status', 2)->where('deleted', 0);
                        })->sum('qty');

                    //refund total sum item   
                    $TotalrefundSum = RefundOrderMenu::where('id_menu', $itm->id)
                        ->whereHas('order', function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('tanggal', [$startDate, $endDate])
                                ->where('id_status', 2)->where('deleted', 0);
                        })->sum('refund_nominal');

                    $harga = $itm->harga;

                    
                    $totalRefund = $TotalrefundSum;
                    $sumSold = $itmsum + $refundSum;
                    $grossSales = $TotalSumSales + $TotalrefundSum;

                    $totalDiscount = Discount_detail_order::whereHas('Detail_order', function ($query) use ($itm, $startDate, $endDate) {
                        $query->where('id_menu', $itm->id)->whereNull('id_varian')
                            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                                $query->whereBetween('tanggal', [$startDate, $endDate])
                                    ->where('id_status', 2)->where('deleted', 0);
                            });
                    })->sum('total_discount');

                    $netSales = $grossSales - $totalDiscount - $totalRefund;

                    $itemSalesMenu[] = [
                        'Name' => $itm->nama_menu,
                        'itemSold' => $sumSold,
                        'itemrefund' => $refundSum,
                        'GrossSalse' => $grossSales,
                        'Discount' => $totalDiscount,
                        'Refund' => $totalRefund,
                        'NetSales' => $netSales,
                        'Variants' => []
                    ];
                }
            }



            $itemsOrderNonVar = [];

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

           

            foreach ($detailOrders as $id_menu => $details) {
                $menu = $details->first()->menu;

                // Hitung total qty, total sales
                $itmsum = $details->sum('qty');
                $TotalSumSales = $details->sum('total');

                // Hitung refund
                $refundSum = RefundOrderMenu::where('id_menu', $id_menu)
                    ->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)
                            ->where('deleted', 0);
                    })->sum('qty');

                $TotalrefundSum = RefundOrderMenu::where('id_menu', $id_menu)
                    ->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)
                            ->where('deleted', 0);
                    })->sum('refund_nominal');

                $totalRefund = $TotalrefundSum;
                $sumSold = $itmsum + $refundSum;
                $grossSales = $TotalSumSales + $TotalrefundSum;

                // Hitung total discount
                $totalDiscount = Discount_detail_order::whereHas('Detail_order', function ($query) use ($id_menu, $startDate, $endDate) {
                    $query->where('id_menu', $id_menu)->whereNull('id_varian')
                        ->whereHas('order', function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('tanggal', [$startDate, $endDate])
                                    ->where('id_status', 2)
                                    ->where('deleted', 0);
                        });
                })->sum('total_discount');

                $netSales = $grossSales - $totalDiscount - $totalRefund;

                $itemsOrderNonVar[$id_menu] = [
                    'Name' => $menu->nama_menu,
                    'itemSold' => $sumSold,
                    'itemrefund' => $refundSum,
                    'GrossSalse' => $grossSales,
                    'Discount' => $totalDiscount,
                    'Refund' => $totalRefund,
                    'NetSales' => $netSales,
                ];

                
            }

            
           Log::info($itemsOrderNonVar);

            return Excel::download(new ItemSalesExport(
                $itemSalesAdss,
                $itemSalesMenu,
                $totalItemSoldMenu,
                $totalItemRefundMenu,
                $totalGrossMenu,
                $totalDiscountMenu,
                $totalRefundMenu ,
                $totalNetMenu ,
                $totalItemSoldAdds ,
                $totalItemRefundAdds ,
                $totalGrossAdds ,
                $totalDiscountAdds ,
                $totalRefundAdds ,
                $totalNetAdds ,
                $itemsOrderNonVar,
                $totalItemSoldMenuOld,
                $totalItemRefundMenuOld,
                $totalGrossMenuOld,
                $totalDiscountMenuOld,
                $totalRefundMenuOld,
                $totalNetMenuOld

            ), 'Laporan Item Report.xlsx');
        } else {
            return redirect()->route('login');
        }
    }

    public function ExportModifier(Request $request)
    {
        if (Sentinel::check()) {

            if ($request->has('startDate')) {

                $startDate = $request->startDate;
                $endDate = $request->endDate;
            } else {
                $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
                $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
            }

            $additional = OptionModifier::all();
            $itemSalesAdss = [];
            $qty = 0;
            $Gross = 0;
            $Dis = 0;
            $ref = 0;
            $netSels= 0;


            foreach ($additional as $adds) {

                $addsId = $adds->id;

                $itmAdsSold =  Additional_menu_detail::whereHas('detail_order', function ($query) use ($startDate, $endDate) {
                    $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)->where('deleted', 0);
                        });
                })->where('id_option_additional', $adds->id)->sum('qty');

                // harga item adds
                $itmAdsSoldHarga =  Additional_menu_detail::whereHas('detail_order', function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('id_status', 2)->where('deleted', 0);
                });
                })->where('id_option_additional', $adds->id)->value('total') ?? 0;

                //qty itm adds sold
                $QtyAdsSold =  Additional_menu_detail::whereHas('detail_order', function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('id_status', 2)->where('deleted', 0);
                });
                })->where('id_option_additional', $adds->id)->value('qty') ?? 0;

                $hargaAdds = 0;
                if ($itmAdsSoldHarga !== 0 && $QtyAdsSold !== 0) {
                    $hargaAdds = $itmAdsSoldHarga / $QtyAdsSold;
                }

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

                $itemDis = $discount / 100;
                $discountItms = $itmAdsSoldHarga * $itemDis ;

                $refundSum = AdditionalRefund::whereHas('Refund', function ($query) use ($startDate, $endDate) {
                    $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)->where('deleted', 0);
                    });
                })->where('id_option_additional', $adds->id)->sum('qty');

                $Sumdiscount = Discount::whereHas('Discount_detail', function ($query) use ($addsId, $startDate, $endDate) {
                    $query->whereHas('Detail_order', function ($query) use ($addsId, $startDate, $endDate) {
                        $query->whereHas('AddOptional_order', function ($query) use ($addsId) {
                            $query->where('id_option_additional', $addsId);
                        })->whereHas('order', function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('tanggal', [$startDate, $endDate])
                                ->where('id_status', 2)->where('deleted', 0);
                        });
                    });
                })->sum('rate_dis');



                $refund = AdditionalRefund::whereHas('Refund', function ($query) use ($startDate, $endDate) {
                    $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)->where('deleted', 0);
                    });
                })->where('id_option_additional', $adds->id)->value('harga');

                $disRefund = Discount::whereHas('Discount_retur', function ($query) use ($addsId, $startDate, $endDate) {
                    $query->whereHas('Refund', function ($query) use ($addsId, $startDate, $endDate) {
                        $query->whereHas('RefundAdds', function ($query) use ($addsId) {
                            $query->where('id_option_additional', $addsId);
                        })->whereHas('order', function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('tanggal', [$startDate, $endDate])
                                ->where('id_status', 2)->where('deleted', 0);
                        });
                    });
                })->sum('rate_dis');

                $grosSale = $adds->harga * ($itmAdsSold + $refundSum);
                $grosRefund = $refund * $refundSum;
            

                $NetSales = ($grosSale - $grosRefund) - $discountItms ;

                $itemSalesAdss[] = [
                    'Name' => $adds,
                    'item Sold' => $itmAdsSold + $refundSum,
                    'DisNominal' => $discountItms,
                    'item refund' => $refundSum,
                    'Gross Salse' => $grosSale,
                    'Refund' => $grosRefund,
                    'Net Sales' => $NetSales,

                ];
            }



            return Excel::download(new ModifierSalesExport(
                $itemSalesAdss,
                $Dis,
                $qty,
                $Gross ,
                $Dis,
                $ref ,
                $netSels,

            ), 'Laporan Modifier.xlsx');
        } else {
            return redirect()->route('login');
        }
    }

    public function ExportDiscount(Request $request)
    {
        if (Sentinel::check()) {
            if ($request->has('startDate')) {

                $startDate = $request->startDate;
                $endDate = $request->endDate;
            } else {
                $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
                $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
            }
            $Discount = Discount::all();

            $dataDiscount = [];
            $count = 0;
            $Gross = 0;
            $ref = 0;
            $netSels= 0;

            foreach ($Discount as $dis) {

                $countDis = Discount_detail_order::whereHas('Detail_order', function ($query) use ($startDate, $endDate) {
                    $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)->where('deleted', 0);
                    });
                })->where('id_discount', $dis->id)->count();

                $grossDis = Discount_detail_order::whereHas('Detail_order', function ($query) use ($startDate, $endDate) {
                    $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)->where('deleted', 0);
                    });
                })->where('id_discount', $dis->id)->sum('total_discount');

                $refundDisCount = DiscountMenuRefund::whereHas('Refund', function ($query) use ($startDate, $endDate) {
                    $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('id_status', 2)->where('deleted', 0);
                    });
                })->where('id_discount', $dis->id)->sum('nominal_dis');

                $sumDiscountTotal =  $grossDis + $refundDisCount;

                $netDis = $sumDiscountTotal - $refundDisCount;

                $dataDiscount[] = [
                    'nama' => $dis,
                    'count' => $countDis,
                    'Gross' => $sumDiscountTotal,
                    'refund' => $refundDisCount,
                    'Net' => $netDis
                ];
            }



            return Excel::download(new DiscountSalesExport(
                $dataDiscount,
                $count ,
                $Gross ,
                $ref ,
                $netSels,

            ), 'Laporan Discount Sales.xlsx');
        } else {
            return redirect()->route('login');
        }
    }

    public function ExportTaxes(Request $request)
    {
        if (Sentinel::check()) {
            if ($request->has('startDate')) {

                $startDate = $request->startDate;
                $endDate = $request->endDate;
            } else {
                $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
                $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
            }

            $taxes = Taxes::all();
            $dataTax = [];
            $totalTax=0;


            foreach ($taxes as $tax) {
                $items = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('id_status', 2)->where('deleted', 0);
                })->sum('total');

              

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
                        $query->whereBetween('tanggal', [$startDate, $endDate]);
                    });
                })->sum('nominal_dis');

              
                // harga item refund
                $hargaRefund = RefundOrderMenu::whereHas('order', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('id_status', 2)->where('deleted', 0);
                })->sum('refund_nominal');

                $allGrandSales =  $items + $hargaRefund;

                $sumDiscountTotal =  $totalDiscount + $refundDisCountSum;
                $allGrandDis =  $sumDiscountTotal - $refundDisCountSum;
                $allGrandRefund = $hargaRefund;
                $netGross =  $allGrandSales -  $allGrandDis -  $allGrandRefund;


                $taxs = $tax->tax_rate / 100;
                $nominal = $netGross * $taxs;


                $dataTax[] = [
                    'Taxs' => $tax,
                    'Net' => $netGross,
                    'taxTotal' => $nominal
                ];
            }


            return Excel::download(new TaxesSalesExport(
                $dataTax,
                $totalTax,
            ), 'Laporan Taxes Sales.xlsx');
        } else {
            return redirect()->route('login');
        }
    }

    public function ExportCategory(Request $request)
    {
        if (Sentinel::check()) {

            if ($request->has('startDate')) {

                $startDate = $request->startDate;
                $endDate = $request->endDate;
            } else {
                $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
                $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
            }


            $subcategory = SubKategori::all();
            $groupModifier = GroupModifier::all();

            $kategori = [];
            $modifier = [];
            $dataAditional = [];
            $totalNominalKat = 0;
            $totalItemSoldMenu = 0;
            $totalItemRefundMenu = 0;
            $totalGrossMenu = 0;
            $totalDiscountMenu = 0;
            $totalRefundMenu = 0;
            $totalNetMenu = 0;
            $totalItemSoldAdds = 0;
            $totalItemRefundAdds = 0;
            $totalGrossAdds = 0;
            $totalDiscountAdds = 0;
            $totalRefundAdds = 0;
            $totalNetAdds = 0;
            $totalNominalKat = 0;



            foreach ($subcategory as $cat) {
                $idCat = $cat->id;
                $hargaData = [];

                $totalItem = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)->where('deleted', 0);
                    })->whereHas('menu.subKategori', function ($query) use ($idCat){
                        $query->where('id', $idCat);
                })->sum('total');



                $qtySold = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('tanggal', [$startDate, $endDate])
                                ->where('id_status', 2)->where('deleted', 0);
                        })->whereHas('menu.subKategori', function ($query) use ($idCat){
                            $query->where('id', $idCat);
                })->sum('qty');

                // dd($qtySold);
                $discount = Discount_detail_order::whereHas('Detail_order', function ($query) use ($idCat, $startDate, $endDate) {
                            $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                                    $query->whereBetween('tanggal', [$startDate, $endDate])
                                        ->where('id_status', 2)->where('deleted', 0);
                            })->whereHas('menu', function ($query) use ($idCat){
                                $query->where('id_sub_kategori', $idCat);
                            });
                    })->sum('total_discount');

                $qtyRefund = RefundOrderMenu::whereHas('order', function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('tanggal', [$startDate, $endDate])
                                ->where('id_status', 2)->where('deleted', 0);
                        })->whereHas('menu.subKategori', function ($query) use ($idCat){
                            $query->where('id', $idCat);
                })->sum('qty');

                $RefundTotal = RefundOrderMenu::whereHas('order', function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('tanggal', [$startDate, $endDate])
                                ->where('id_status', 2)->where('deleted', 0);
                        })->whereHas('menu.subKategori', function ($query) use ($idCat){
                            $query->where('id', $idCat);
                })->sum('refund_nominal');

                $discountRef = DiscountMenuRefund::whereHas('Refund', function ($query) use ($idCat,$startDate, $endDate) {
                    $query->whereHas('order', function($query) use ($startDate, $endDate){
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)->where('deleted', 0);
                    })->whereHas('menu', function($query) use ($idCat){
                        $query->where('id_sub_kategori', $idCat);
                    });
                })->sum('nominal_dis');


                //menghitung discount sales dengan discount refund
                $discountNominalSum =  $discount + $discountRef;
                //hasil discount total di kurangi dengan discount refund
                $disTotal = $discountNominalSum - $discountRef;
                //total qty yang terjual di hitung dari total sales qty dengan refund qty
                $sumTotalqty = $qtySold + $qtyRefund;

                $gross = $totalItem + $RefundTotal;


                $kategori[] = [
                    'Name' => $cat,
                    'itemSold' => $sumTotalqty,
                    'itemrefund' => $qtyRefund,
                    'GrossSalse' => $gross,
                    'Discount' => $disTotal,
                    'Refund' => $RefundTotal,
                    // 'data_Harga' => $hargaMenu,
                    'NetSales' => 0
                ];
            }


            foreach ($groupModifier as $modCat) {

                $IdModCat = $modCat->id;


                $hargaModCat = Additional_menu_detail::whereHas('optional_Add.groupModif', function ($query) use ($IdModCat, $startDate, $endDate) {
                    $query->where('id', $IdModCat)->whereBetween('created_at', [$startDate, $endDate]);
                })->sum('total');

                $qtySoldModcat = Additional_menu_detail::whereHas('optional_Add.groupModif', function ($query) use ($IdModCat, $startDate, $endDate) {
                    $query->where('id', $IdModCat)->whereBetween('created_at', [$startDate, $endDate]);
                })->sum('qty');

                $qtyRefundMod = AdditionalRefund::whereHas('additionOps.groupModif', function ($query) use ($IdModCat) {
                    $query->where('id', $IdModCat);
                })->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('qty');

                $nominalRefund = AdditionalRefund::whereHas('additionOps.groupModif', function ($query) use ($IdModCat) {
                    $query->where('id', $IdModCat);
                })->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('total_');


                $grosSale = $hargaModCat * $qtySoldModcat;
                $RefgrosSale = $nominalRefund * $qtyRefundMod;
                $NetSales = $hargaModCat  - $nominalRefund;

                $modifier[] = [
                    'Name' => $modCat,
                    'itemSold' => $qtySoldModcat,
                    'itemrefund' => $qtyRefundMod,
                    'Gross Salse' => $hargaModCat,
                    'Discount' => 0,
                    'Refund' => $nominalRefund,
                    'NetSales' => $NetSales
                ];
            }

            return Excel::download(new CategoryExport(
                $kategori,
                $modifier,
                $hargaData,
                $totalNominalKat,
                $totalItemSoldMenu ,
                $totalItemRefundMenu ,
                $totalGrossMenu ,
                $totalDiscountMenu,
                $totalRefundMenu,
                $totalNetMenu ,
                $totalItemSoldAdds ,
                $totalItemRefundAdds ,
                $totalGrossAdds ,
                $totalDiscountAdds,
                $totalRefundAdds ,
                $totalNetAdds ,
                $totalNominalKat ,
            ), 'Laporan Categori Sales.xlsx');
        } else {
            return redirect()->route('login');
        }
    }

    public function transactionDetail(Request $request){
        if(Sentinel::check()){
            if ($request->has('startDate')) {
                $startDate = $request->startDate;
                $endDate = $request->endDate;
            } else {
                $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
                $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
                // $startDate = '2024-08-01';
                // $endDate = '2024-08-31';
            }

            $itemSalesMenu =[];
            
            $detailOrder = DetailOrder::whereHas('order', function($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)
                    ->where('deleted', 0);
            })->get();

            foreach ($detailOrder as $itm) {
                
                $totalDiscount = Discount_detail_order::whereHas('Detail_order', function ($query) use ($itm) {
                    $query->where('id_detail_order', $itm->id);
                })->sum('total_discount');

                $qtyRefund = RefundOrderMenu::where('id_order', $itm->id_order)
                ->where('id_menu', $itm->id_menu)->value('qty');

                $refundDisCountSum = DiscountMenuRefund::whereHas('Refund', function ($query) use ($itm) {
                    $query->where('id_order', $itm->id_order)->where('id_menu', $itm->id_menu);
                })->sum('nominal_dis');

                $hargaRefund = RefundOrderMenu::where('id_order', $itm->id_order)
                ->where('id_menu', $itm->id_menu)->value('harga');

                $nominalRefund = RefundOrderMenu::where('id_order', $itm->id_order)
                ->where('id_menu', $itm->id_menu)->value('refund_nominal');


                $totalRefund = $hargaRefund * $qtyRefund;
                $disTotal = $totalDiscount - $refundDisCountSum;
                $total = $itm->total - $totalDiscount;

                $gross = $itm->total + $nominalRefund;
                $netSales = $gross - $totalDiscount - $nominalRefund;
                $tax = 15/100;
                $nominalTax = $netSales * $tax;
                $totalColect = $netSales + $nominalTax;

                $itemSalesMenu[] = [
                    'Kode_Pesanan' => $itm->order->kode_pemesanan,
                    'create' => $itm->order->created_at,
                    'Tanggal' => $itm->order->updated_at,
                    'Name' => $itm->menu->nama_menu,
                
                    'Varian' => $itm->varian->nama ?? '',
                    'itemSold' => $itm->qty,
                    'itemrefund' => $qtyRefund,
                    'GrossSalse' => $gross,
                    'Discount' => $totalDiscount,
                    // 'Total' => $total,
                    'Refund' => $nominalRefund,
                    'Discount_ref' => $refundDisCountSum,
                    'NetSales' => $netSales,
                    'Tax' => $nominalTax,
                    'totalColect' => $totalColect,
                    'paymentMetode' => $itm->order->payment->nama
                   
                ];
                // dd($itemSalesMenu);
              
  
            }
            // dd($itemSalesMenu);
            if (empty($itemSalesMenu)) {
                return back()->with('error', 'No data available for the selected date range.');
            }else{
                return Excel::download(new DetailTransactionItemsExport(
                    $itemSalesMenu
                ), 'Laporan Detail Transaksi.xlsx');
            }
            
        }else{
            return redirect()->route('login');
        }
    }
}

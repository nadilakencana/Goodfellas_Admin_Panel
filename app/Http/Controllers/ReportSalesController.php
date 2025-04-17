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
use App\Models\VarianMenu;
use App\Models\RefundOrder;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use DB;

class ReportSalesController extends Controller
{
    public function Report(Request $request)
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

            $menu = Menu::all();
            $additional = OptionModifier::all();

            
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

            return view('Report.ReportSales', compact(
                'allGrandSales',
                'allGrandDis',
                'allGrandRefund',
                'allGrandNet',
                'totalTax',
                'TotalGrand',
                'startDate',
                'endDate'
            ));
        } else {
            return redirect()->route('login');
        }
    }

    public function fileterSalesSummary(Request $request)
    {
        
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

        $Menu = [];
        $Adss = [];



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
        // dd($totalDiscount, $refundDisCountSum);

        return view('Report.Sales', compact(
            'allGrandSales',
            'allGrandDis',
            'allGrandRefund',
            'allGrandNet',
            'totalTax',
            'TotalGrand',
            'startDate',
            'endDate'
        ));
    }

    public function GrossProfit(Request $request)
    {

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

        // dd($totalDiscount, $refundDisCountSum);

        return view('Report.grossProfit', compact(
            'allGrandSales',
            'allGrandDis',
            'allGrandRefund',
            'allGrandNet',
            'totalTax',
            'TotalGrand',
            'startDate',
            'endDate'
        ));
    }

    public function pymentMethod(Request $request)
    {

        if ($request->has('startDate')) {

            $startDate = $request->startDate;
            $endDate = $request->endDate;
        } else {
            $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
            $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
        }

        $paymentMetode = TypePayment::all();
        $paymentData = [];
        $menu = Menu::all();
        $additional = OptionModifier::all();

        foreach ($paymentMetode as $payment) {
            // harga menu detail order
            $items = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate,$payment) {
                $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0)->where('id_type_payment', $payment->id);
            })->sum('total');

            // total qty menu detail order
            $itmsum = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate,$payment) {
                $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0)->where('id_type_payment', $payment->id);
            })->sum('qty');

            // sum total discount
            $totalDiscount = Discount_detail_order::whereHas('Detail_order', function ($query) use ($startDate, $endDate,$payment) {
                $query->whereHas('order', function ($query) use ($startDate, $endDate,$payment) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('id_status', 2)->where('deleted', 0)->where('id_type_payment', $payment->id);
                });
            })->sum('total_discount');

            // sum total refund discount
            $refundDisCountSum = DiscountMenuRefund::whereHas('Refund', function ($query) use ($startDate, $endDate,$payment) {
                $query->whereHas('order', function ($query) use ($startDate, $endDate,$payment) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])->where('deleted', 0)->where('id_type_payment', $payment->id);
                });
            })->sum('nominal_dis');

            // sum qty refund
            $SumRefund = RefundOrderMenu::whereHas('order', function ($query) use ($startDate, $endDate,$payment) {
                $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0)->where('id_type_payment', $payment->id);
            })->sum('qty');
            // harga item refund
            $hargaRefund = RefundOrderMenu::whereHas('order', function ($query) use ($startDate, $endDate,$payment) {
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
            $TotalGrand = $allGrandNet + $totalTax;

           

           

            $paymentData[] = [
                'paymentMethod' => $payment,
                'totalOrder' =>  $totalTransaksi,
                'totalPembayaran' => $TotalGrand
            ];
        }


        return view('Report.paymentMethod', compact(
            'paymentMetode',
            'paymentData',
            'startDate',
            'endDate'
        ));
    }

    public function SelesType(Request $request)
    {
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
            $GandTotal = $SumTotal;
            // $GandTotal = $SumTotal;

            $SalesData[] = [
                'Sales Type' => $types->name,
                'totalOrder' =>  $countItemTypeSales,
                'Total' => $GandTotal,

            ];
        }




        return view('Report.SalesData', compact(
            'SalesData',
            'startDate',
            'endDate'
        ));
    }

    public function ItemSales(Request $request)
    {

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
        $itmOrderNonVar = [];
        $itemSalesMenuCustom = [];
        $itemSalesAdss = [];
        $TOTAL = [];
        $totalCustomMakanan = 0;
        $totalCustomMinuman = 0;

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
                    $query->where('id_menu', $itm->id)
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

        // dd($menuOldNonVar);

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
                $query->where('id_menu', $id_menu)
                    ->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                                ->where('id_status', 2)
                                ->where('deleted', 0);
                    });
            })->sum('total_discount');

            $netSales = $grossSales - $totalDiscount - $totalRefund;

            $itmOrderNonVar[] = [
                'Name' => $menu->nama_menu,
                'itemSold' => $sumSold,
                'itemrefund' => $refundSum,
                'GrossSalse' => $grossSales,
                'Discount' => $totalDiscount,
                'Refund' => $totalRefund,
                'NetSales' => $netSales,
            ];
        }

        // dd($itmOrderNonVar);

        


        return view('Report.ItemSales', compact(
            'menu',
            'itemSalesMenu',
            'itemSalesAdss',
            'itmOrderNonVar',
            'startDate',
            'endDate'

        ));
    }

    public function Modifier(Request $request)
    {

        if ($request->has('startDate')) {

            $startDate = $request->startDate;
            $endDate = $request->endDate;
        } else {
            $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
            $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
        }

        $additional = OptionModifier::all();
        $itemSalesAdss = [];



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



        return view('Report.modifier', compact(
            'itemSalesAdss',
            'startDate',
            'endDate'
        ));
    }


    public function Discount(Request $request)
    {
        if ($request->has('startDate')) {

            $startDate = $request->startDate;
            $endDate = $request->endDate;
        } else {
            $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
            $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
        }
        $Discount = Discount::all();

        $dataDiscount = [];

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



        return view('Report.discount', compact(
            'dataDiscount',
            'startDate',
            'endDate'
        ));
    }

    public function Taxes(Request $request)
    {

        if ($request->has('startDate')) {

            $startDate = $request->startDate;
            $endDate = $request->endDate;
        } else {
            $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
            $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
        }

        $taxes = Taxes::all();
        $dataTax = [];



            foreach ($taxes as $tax) {
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


        return view('Report.Taxes', compact(
            'dataTax',
            'startDate',
            'endDate'
        ));
    }

    public function Category(Request $request)
    {

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
        // $hargaData = [];



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

            // if ($hargaMenu == 0) {
            //     $harga = $sumTotalqty * $hargaRefund;
            // } else {
            //     $harga = $sumTotalqty * $hargaMenu;
            // }

            // dd($GrossTotal);


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


        // foreach ($groupModifier as $modCat) {

        //     $IdModCat = $modCat->id;


        //     $hargaModCat = Additional_menu_detail::whereHas('optional_Add.groupModif', function ($query) use ($IdModCat, $startDate, $endDate) {
        //         $query->where('id', $IdModCat)->whereBetween(
        //             DB::raw('DATE(created_at)'),
        //             [$startDate, $endDate]
        //         );
        //     })->sum('total');

        //     $qtySoldModcat = Additional_menu_detail::whereHas('optional_Add.groupModif', function ($query) use ($IdModCat, $startDate, $endDate) {
        //         $query->where('id', $IdModCat)->whereBetween(
        //             DB::raw('DATE(created_at)'),
        //             [$startDate, $endDate]
        //         );
        //     })->sum('qty');

        //     $qtyRefundMod = AdditionalRefund::whereHas('additionOps.groupModif', function ($query) use ($IdModCat) {
        //         $query->where('id', $IdModCat);
        //     })->whereBetween(
        //         DB::raw('DATE(created_at)'),
        //         [$startDate, $endDate]
        //     )
        //         ->sum('qty');

        //     $nominalRefund = AdditionalRefund::whereHas('additionOps.groupModif', function ($query) use ($IdModCat) {
        //         $query->where('id', $IdModCat);
        //     })->whereBetween(
        //         DB::raw('DATE(created_at)'),
        //         [$startDate, $endDate]
        //     )
        //         ->sum('total_');


        //     $grosSale = $hargaModCat * $qtySoldModcat;
        //     $RefgrosSale = $nominalRefund * $qtyRefundMod;
        //     $NetSales = $hargaModCat  - $nominalRefund;

        //     $modifier[] = [
        //         'Name' => $modCat,
        //         'itemSold' => $qtySoldModcat,
        //         'itemrefund' => $qtyRefundMod,
        //         'Gross Salse' => $hargaModCat,
        //         'Discount' => 0,
        //         'Refund' => $nominalRefund,
        //         'NetSales' => $NetSales
        //     ];
        // }


        return view('Report.Category', compact(
            'kategori',
            'modifier',
            'hargaData',
            'startDate',
            'endDate'
        ));
    }


    //export laporan


}

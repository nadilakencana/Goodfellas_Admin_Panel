<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Models\Orders;
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
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use DB;

class ReportSalesController extends Controller
{
    public function Report(Request $request)
    {
        if (Sentinel::check()) {

            if($request->has('startDate')){
                
                $startDate = $request->startDate;
                $endDate = $request->endDate;
               
            }
            else{
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
            $refundDisCountSum = DiscountMenuRefund::whereHas('Refund', function ($query) use($startDate, $endDate){
                $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('id_status', 2)->where('deleted', 0);
                });
            })->sum('nominal_dis');

            $taxpb1 = Taxes::where('nama', 'PB1')->first();
            $service = Taxes::where('nama', 'Service Charge')->first();

            $sumDiscountTotal =  $totalDiscount + $refundDisCountSum;
            $allGrandDis =  $sumDiscountTotal - $refundDisCountSum;
            $allGrandRefund = $hargaRefund - $refundDisCountSum;
            $allGrandSales =   $items + $hargaRefund;
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
        if($request->has('startDate')){
                $startDate = $request->startDate;
                $endDate = $request->endDate;
               
            }
            else{
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

        // $refundAddsSum = AdditionalRefund::whereHas('Refund.order', function ($query) use ($startDate, $endDate) {
        //     $query->whereBetween('tanggal', [$startDate, $endDate])
        //         ->where('id_status', 2)->where('deleted', 0);
        // })->sum('total_');

        $taxpb1 = Taxes::where('nama', 'PB1')->first();
        $service = Taxes::where('nama', 'Service Charge')->first();
        // dd($totalDiscount,$refundDisCountSum);
        $allGrandSales =  $items + $hargaRefund;
        
        $sumDiscountTotal =  $totalDiscount + $refundDisCountSum;
        $allGrandDis =  $sumDiscountTotal - $refundDisCountSum;
        $allGrandRefund = $hargaRefund ;
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
        
        if($request->has('startDate')){
            
            $startDate = $request->startDate;
            $endDate = $request->endDate;
           
        }
        else{
            $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
            $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
        }

       
        $allGrandSales = 0;
        $allGrandDis = 0;
        $allGrandRefund = 0;
        $allGrandNet = 0;

            // harga menu detail order
            $items = DetailOrder::whereHas('order', function ($query) use  ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0);
            })->sum('total');
            // total qty menu detail order
            $itmsum = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0);
            })->sum('qty');

            // sum total discount
            $totalDiscount = Discount_detail_order::whereHas('Detail_order', function ($query) use  ($startDate, $endDate) {
                $query->whereHas('order', function ($query) use  ($startDate, $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('id_status', 2)->where('deleted', 0);
                });
            })->sum('total_discount');

            // sum total refund discount
            $refundDisCountSum = DiscountMenuRefund::whereHas('Refund', function ($query) use  ($startDate, $endDate) {
                $query->whereHas('order', function ($query) use  ($startDate, $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('id_status', 2)->where('deleted', 0);
                });
            })->sum('nominal_dis');

            // sum qty refund
            $SumRefund = RefundOrderMenu::whereHas('order', function ($query) use  ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0);
            })->sum('qty');
            // harga item refund
            $hargaRefund = RefundOrderMenu::whereHas('order', function ($query) use  ($startDate, $endDate) {
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

        if($request->has('startDate')){
            
            $startDate = $request->startDate;
            $endDate = $request->endDate;
        }else{
            $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
            $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
        }

        $paymentMetode = TypePayment::all();
        $paymentData = [];
        $menu = Menu::all();
        $additional = OptionModifier::all();

        foreach ($paymentMetode as $payment) {

                $totalTransaksi = Orders::where('id_type_payment', $payment->id)->whereBetween('tanggal', [$startDate, $endDate])
                ->where('id_status', 2)->where('deleted', 0)->count();

                $totalGrand = Orders::where('id_type_payment', $payment->id)->whereBetween('tanggal', [$startDate, $endDate])
                   ->where('id_status', 2)->where('deleted', 0)
                    ->sum('total_order');

                $refund = RefundOrderMenu::whereHas('order', function ($query) use ($startDate, $endDate, $payment) {
                    $query->where('id_type_payment', $payment->id)
                        ->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('id_status', 2)->where('deleted', 0);
                })->sum('refund_nominal');

                $discountRefund = DiscountMenuRefund::whereHas('Refund', function ($query) use ($startDate, $endDate, $payment) {
                    $query->whereHas('order', function ($query) use ($startDate, $endDate, $payment) {
                        $query->where('id_type_payment', $payment->id)->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)->where('deleted', 0);
                    });
                })->sum('nominal_dis');

                $hargarefundAdds = AdditionalRefund::whereHas('Refund', function ($query) use ($startDate, $endDate, $payment) {
                    $query->whereHas('order', function ($query) use ($startDate, $endDate, $payment) {
                        $query->where('id_type_payment', $payment->id)->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)->where('deleted', 0);
                    });
                })->sum('total_');

                // $totalRef = $refund + $hargarefundAdds;
                $subRef = $refund - $discountRefund;

                $taxpb1 = Taxes::where('nama', 'PB1')->first();
                $service = Taxes::where('nama', 'Service Charge')->first();

                $PB1 = $taxpb1->tax_rate / 100;
                $Service = $service->tax_rate / 100;

                $nominalPb1Ref = $subRef * $PB1;
                $nominalServiceRef = $subRef * $Service;

                $totalTaxRef = $nominalPb1Ref + $nominalServiceRef;

                $subTotalRef = $subRef + $totalTaxRef;

                $GandTotal = $totalGrand - $subTotalRef;

                $paymentData[] = [
                    'paymentMethod' => $payment,
                    'totalOrder' =>  $totalTransaksi,
                    'totalPembayaran' => $GandTotal
                ];
        }
        
    
        return view('Report.paymentMethod', compact('paymentMetode', 'paymentData','startDate',
                'endDate'));
    }

    public function SelesType(Request $request)
    {
        if($request->has('startDate')){
            
            $startDate = $request->startDate;
            $endDate = $request->endDate;
        }else{
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
                    $query->whereHas('detail_order', function ($query) use ($typesId,$startDate, $endDate) {
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
        
      


        return view('Report.SalesData', compact('SalesData','startDate',
                'endDate'));
    }

    public function ItemSales(Request $request)
    {
        
        if($request->has('startDate')){
            
            $startDate = $request->startDate;
            $endDate = $request->endDate;
           
        }
        else{
            $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
            $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
        }
        $menu = Menu::with('varian')->get();
        $additional = OptionModifier::all();

        $itemSalesMenu = [];
        $itemSalesAdss = [];
        $TOTAL = [];

        
           

        foreach ($menu as $itm) {
              
                $items = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0);
                })->where('id_menu', $itm->id)->value('harga');

                $itmsum = DetailOrder::whereHas('order', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0);
                })->where('id_menu', $itm->id)->sum('qty');
               
                $totalDiscount = Discount_detail_order::whereHas('Detail_order', function ($query) use ($itm,$startDate, $endDate) {
                            $query->where('id_menu', $itm->id)
                                ->whereHas('order', function ($query) use ($startDate, $endDate) {
                                    $query->whereBetween('tanggal', [$startDate, $endDate])
                                    ->where('id_status', 2)->where('deleted', 0);
                                });
                        })->sum('total_discount');

                $varian = VarianMenu::whereHas('Detail_menu',function($query)use ($itm,$startDate, $endDate) {
                            $query->where('id_menu', $itm->id)
                                ->whereHas('order', function ($query) use ($startDate, $endDate){
                                    $query->whereBetween('tanggal', [$startDate, $endDate])
                                        ->where('id_status', 2)
                                        ->where('deleted', 0);
                                });
                            })->pluck('nama');
                   
                

                $refundDisCountSum = DiscountMenuRefund::whereHas('Refund', function ($query) use ( $itm,$startDate, $endDate) {
                        $query->where('id_menu', $itm->id)->whereHas('order', function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('tanggal', [$startDate, $endDate])
                                ->where('id_status', 2)->where('deleted', 0);
                        });
                    })->sum('nominal_dis');

                $SumRefund = RefundOrderMenu::whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)->where('deleted', 0);
                    })->where('id_menu', $itm->id)->sum('qty');

                $hargaRefund = RefundOrderMenu::where('id_menu', $itm->id)
                    ->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)->where('deleted', 0);
                    })->value('harga');


                $sumSold = $itmsum + $SumRefund;

                if($items == 0){
                    $harga = $sumSold * $hargaRefund ;
                }else{
                    $harga = $sumSold * $items ;
                }
                

                $totalRefund = $hargaRefund * $SumRefund;

                $discountNominalSum =  $totalDiscount + $refundDisCountSum;
                $disTotal = $discountNominalSum - $refundDisCountSum;

                $netSales = $harga - $disTotal - $totalRefund;

                $itemSalesMenu[] = [
                    'Name' => $itm->nama_menu,
                    'itemSold' => $sumSold,
                    'itemrefund' => $SumRefund,
                    'GrossSalse' => $harga,
                    'Discount' => $disTotal,
                    'Refund' => $totalRefund,
                    'NetSales' => $netSales,
                    'Variants' => $varian
                ];
        }

        foreach ($additional as $adds) {

                $itmAdsSold = Additional_menu_detail::whereHas('detail_order', function ($query) use ($startDate, $endDate){
                    $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0);
                    });
                })->where('id_option_additional', $adds->id)->sum('qty');

               
                $refundSum = AdditionalRefund::whereHas('Refund', function ($query) use ($startDate, $endDate) {
                    $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)->where('deleted', 0);
                    });
                })->where('id_option_additional', $adds->id)->sum('qty');


                $refund = AdditionalRefund::whereHas('Refund', function ($query) use ($startDate, $endDate) {
                    $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)->where('deleted', 0);
                    });
                })->where('id_option_additional', $adds->id)->value('harga');

                $AddsTotalSum = $itmAdsSold + $refundSum;
               
                $grosSale = $AddsTotalSum * $adds->harga;
                

                $grosRefund = $refund * $refundSum;


                $NetSales = $grosSale  - $grosRefund;

                $itemSalesAdss[] = [
                    'Name' => $adds,
                    'item Sold' => $AddsTotalSum,
                    'item refund' => $refundSum,
                    'Gross Salse' => $grosSale,
                    'Refund' => $grosRefund,
                    'Net Sales' => $NetSales
                ];
        }
       


        return view('Report.ItemSales', compact(
            'menu',
            'itemSalesMenu',
            'itemSalesAdss',
            'startDate',
            'endDate'

        ));
    }

    public function Modifier(Request $request)
    {

        if($request->has('startDate')){
            
            $startDate = $request->startDate;
            $endDate = $request->endDate;
           
        }
        else{
            $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
            $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
        }

        $additional = OptionModifier::all();
        $itemSalesAdss = [];

       

            foreach ($additional as $adds) {

                $addsId = $adds->id;

                $itmAdsSold =  Additional_menu_detail::whereHas('detail_order', function ($query) use ($startDate, $endDate){
                    $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0);
                    });
                })->where('id_option_additional', $adds->id)->sum('qty');

                $refundSum = AdditionalRefund::whereHas('Refund', function ($query) use ($startDate, $endDate) {
                    $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                            ->where('id_status', 2)->where('deleted', 0);
                    });
                })->where('id_option_additional', $adds->id)->sum('qty');

                $discount = Discount::whereHas('Discount_detail', function ($query) use ($addsId, $startDate, $endDate){
                        $query->whereHas('Detail_order', function ($query) use ($addsId,$startDate, $endDate) {
                            $query->whereHas('AddOptional_order', function ($query) use ($addsId){
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

                $disRefund =Discount::whereHas('Discount_retur', function ($query) use ($addsId, $startDate, $endDate){
                        $query->whereHas('Refund', function ($query) use ($addsId, $startDate, $endDate) {
                            $query->whereHas('RefundAdds', function ($query) use ($addsId){
                                $query->where('id_option_additional', $addsId);
                            })->whereHas('order', function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('tanggal', [$startDate, $endDate])
                                ->where('id_status', 2)->where('deleted', 0);
                            });
                        });
                           
                        })->sum('rate_dis');

                $grosSale = $adds->harga * $itmAdsSold;
                $grosRefund = $refund * $refundSum;
                $totalDis = $discount - $disRefund;
                $Dis = $totalDis / 100;
                $nominalDis = $grosSale * $Dis;

                $NetSales = $grosSale  - $nominalDis - $grosRefund;

                $itemSalesAdss[] = [
                    'Name' => $adds,
                    'item Sold' => $itmAdsSold,
                    'DisNominal' => $nominalDis,
                    'item refund' => $refundSum,
                    'Gross Salse' => $grosSale,
                    'Refund' => $grosRefund,
                    'Net Sales' => $NetSales,

                ];
            }
       


        return view('Report.modifier', compact('itemSalesAdss','startDate',
                'endDate'));
    }


    public function Discount(Request $request)
    {
        if($request->has('startDate')){
            
            $startDate = $request->startDate;
            $endDate = $request->endDate;
           
        }else{
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
                        $query->whereBetween('tanggal', [$startDate, $endDate]);
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
      


        return view('Report.discount', compact('dataDiscount','startDate',
                'endDate'));
    }

    public function Taxes(Request $request)
    {
        
        if($request->has('startDate')){
            
            $startDate = $request->startDate;
            $endDate = $request->endDate;
           
        }
        else{
            $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
            $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
        }

        $taxes = Taxes::all();
        $dataTax = [];

        

            foreach ($taxes as $tax) {
                $Order = Orders::whereBetween('tanggal', [$startDate, $endDate])
                ->where('id_status', 2)
                ->where('deleted', 0)->sum('subtotal');

                $refund = RefundOrderMenu::whereHas('order', function($query) use ($startDate, $endDate){
                    $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)
                    ->where('deleted', 0);
                })->sum('refund_nominal');

                $discountRefund = DiscountMenuRefund::whereHas('Refund', function ($query) use ($startDate, $endDate) {
                        $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('tanggal', [$startDate, $endDate])
                                ->where('id_status', 2)->where('deleted', 0);
                        });
                    })->sum('nominal_dis');

                $totalDiscount = Discount_detail_order::whereHas('Detail_order', function ($query) use ($startDate, $endDate) {
                            $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                                    $query->whereBetween('tanggal', [$startDate, $endDate])
                                    ->where('id_status', 2)->where('deleted', 0);
                                });
                        })->sum('total_discount');

                $addsRefund = AdditionalRefund::whereHas('Refund', function ($query) use ($startDate, $endDate){
                    $query->whereHas('order', function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('tanggal', [$startDate, $endDate])
                    ->where('id_status', 2)->where('deleted', 0);
                    });
                })->sum('total_');
                // echo($refund);
                // echo($discountRefund);
                
                $netGross = $Order  - ($refund - $discountRefund);

                $taxs = $tax->tax_rate / 100;
                $nominal = $netGross * $taxs;

                $dataTax[] = [
                    'Taxs' => $tax,
                    'Net' => $netGross,
                    'taxTotal' => $nominal
                ];
            }
        

        return view('Report.Taxes', compact('dataTax','startDate',
                'endDate'));
    }

    public function Category(Request $request)
    {

        if($request->has('startDate')){
            
            $startDate = $request->startDate;
            $endDate = $request->endDate;
           
        }
        else{
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

                $hargaMenu = DetailOrder::whereHas('menu.subKategori', function ($query) use ($idCat) {
                    $query->where('id', $idCat);
                })->whereBetween(DB::raw('DATE(created_at)'), 
                    [$startDate, $endDate])
                ->sum('harga');

                

                $qtySold = DetailOrder::whereHas('menu.subKategori', function ($query) use ($idCat) {
                    $query->where('id', $idCat);
                })->whereBetween(DB::raw('DATE(created_at)'), 
                    [$startDate, $endDate])
                    ->sum('qty');
                // dd($qtySold);
                $discount = Discount_detail_order::whereHas('Detail_order.menu.subKategori', function ($query) use ($idCat) {
                    $query->where('id', $idCat);
                })->whereBetween(DB::raw('DATE(created_at)'), 
                    [$startDate, $endDate])
                    ->sum('total_discount');

                $qtyRefund = RefundOrderMenu::whereHas('menu.subKategori', function ($query) use ($idCat) {
                    $query->where('id', $idCat);
                })->whereBetween(DB::raw('DATE(created_at)'), 
                    [$startDate, $endDate])
                    ->sum('qty');

                $hargaRefund = RefundOrderMenu::whereHas('menu.subKategori', function ($query) use ($idCat) {
                    $query->where('id', $idCat);
                })->whereBetween(DB::raw('DATE(created_at)'), 
                    [$startDate, $endDate])
                    ->sum('refund_nominal');

                $discountRef = DiscountMenuRefund::whereHas('menu.subKategori', function ($query) use ($idCat) {
                    $query->where('id', $idCat);
                })->whereBetween(DB::raw('DATE(created_at)'), 
                    [$startDate, $endDate])
                    ->sum('nominal_dis');


                //menghitung discount sales dengan discount refund
                $discountNominalSum =  $discount + $discountRef;
                //hasil discount total di kurangi dengan discount refund
                $disTotal = $discountNominalSum - $discountRef;
                //total qty yang terjual di hitung dari total sales qty dengan refund qty
                $sumTotalqty = $qtySold + $qtyRefund;


                if($hargaMenu == 0){
                    $harga = $sumTotalqty * $hargaRefund ;
                }else{
                    $harga = $sumTotalqty * $hargaMenu ;
                }

                // dd($GrossTotal);
                  
                    
                    $kategori[] = [
                        'Name' => $cat,
                        'itemSold' => $sumTotalqty,
                        'itemrefund' => $qtyRefund,
                        'GrossSalse' => $harga,
                        'Discount' => $disTotal,
                        'Refund' => $hargaRefund,
                        'data_Harga' => $hargaMenu,
                        'NetSales' => 0
                    ];
               
            }


            foreach ($groupModifier as $modCat) {

                $IdModCat = $modCat->id;


                $hargaModCat = Additional_menu_detail::whereHas('optional_Add.groupModif', function ($query) use ($IdModCat,$startDate, $endDate) {
                    $query->where('id', $IdModCat)->whereBetween(DB::raw('DATE(created_at)'), 
                    [$startDate, $endDate]);
                })->sum('total');

                $qtySoldModcat = Additional_menu_detail::whereHas('optional_Add.groupModif', function ($query) use ($IdModCat,$startDate, $endDate) {
                    $query->where('id', $IdModCat)->whereBetween(DB::raw('DATE(created_at)'), 
                    [$startDate, $endDate]);
                })->sum('qty');

                $qtyRefundMod = AdditionalRefund::whereHas('additionOps.groupModif', function ($query) use ($IdModCat) {
                    $query->where('id', $IdModCat);
                })->whereBetween(DB::raw('DATE(created_at)'), 
                    [$startDate, $endDate])
                    ->sum('qty');

                $nominalRefund = AdditionalRefund::whereHas('additionOps.groupModif', function ($query) use ($IdModCat) {
                    $query->where('id', $IdModCat);
                })->whereBetween(DB::raw('DATE(created_at)'), 
                    [$startDate, $endDate])
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
       

        return view('Report.Category', compact('kategori', 'modifier', 'hargaData','startDate',
                'endDate'));
    }



    //export laporan


}

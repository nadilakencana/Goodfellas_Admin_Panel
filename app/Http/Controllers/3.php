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

class ExportLaporanController extends Controller
{
    public function ExportSalesSummary(Request $request){
         if (Sentinel::check()) {
            $gross_Sales = 0;
            $DiscountTotal = 0;
            $totalGrandGrosSalesMenu = 0;
            $totalGrandDisMenu = 0;
            $totalGrandRefudMenu = 0;
            $totalNetMenu = 0;
            $totalGrandGrosSalesAdds = 0;
            $totalGrandDisAdds = 0;
            $totalGrandRefudAdds = 0;
            $totalNetAdds = 0;
            $allGrandSales = 0;
            $allGrandDis = 0;
            $allGrandRefund = 0;
            $allGrandNet = 0;

            $menu = Menu::all();
            $additional = OptionModifier::all();

            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = date('Y-m-d', strtotime($request->input('start_date')));
                $end_date = date('Y-m-d', strtotime($request->input('end_date') . ' +1 day'));
                foreach ($menu as $itm) {
                    // harga menu detail order
                    $items = DetailOrder::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->where('id_menu', $itm->id)->value('harga');
                    // total qty menu detail order
                    $itmsum = DetailOrder::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->where('id_menu', $itm->id)->sum('qty');

                    // sum total discount
                    $totalDiscount = Discount_detail_order::where('discount_detail_order.created_at', '>=', $start_date)
                        ->where('discount_detail_order.created_at', '<', $end_date)
                        ->join('detail_order', 'discount_detail_order.id_detail_order', 'detail_order.id')
                        ->where('detail_order.id_menu', $itm->id)
                        ->sum('discount_detail_order.total_discount');

                    // sum total refund discount
                    $refundDisCountSum = DiscountMenuRefund::where('created_at', '>=', $start_date)
                        ->where('created_at', '<', $end_date)->where('id_menu', $itm->id)->sum('nominal_dis');

                    // sum qty refund
                    $SumRefund = RefundOrderMenu::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->where('id_menu', $itm->id)->sum('qty');
                    // harga item refund
                    $hargaRefund = RefundOrderMenu::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->where('id_menu', $itm->id)->value('harga');

                    // total qty menu detail order
                    $itemsold = $itmsum;
                    //gross sales menu
                    $harga =  $items * $itemsold;
                    //gross refund item
                    $totalRefund = $SumRefund *  $hargaRefund;
                    //gross discount
                    $disTotal = $totalDiscount - $refundDisCountSum;
                    //Net Seles
                    $netSales = $harga - $disTotal - $totalRefund;

                    $totalGrandGrosSalesMenu += $harga;
                    $totalGrandDisMenu += $disTotal;
                    $totalGrandRefudMenu += $totalRefund;
                    $totalNetMenu += $netSales;
                }

                foreach ($additional as $adds) {


                    $itmAdsSold = Additional_menu_detail::where('additional_menu.created_at', '>=', $start_date)->where('additional_menu.created_at', '<', $end_date)
                        ->join('detail_order', 'additional_menu.id_detail_order', '=', 'detail_order.id')
                        ->where('additional_menu.id_option_additional', $adds->id)
                        ->sum('detail_order.qty');

                    $refundSum = AdditionalRefund::where('additional_refund.created_at', '>=', $start_date)->where('additional_refund.created_at', '<', $end_date)
                        ->join('refund_menu_order', 'additional_refund.id_refund_menu', '=', 'refund_menu_order.id')
                        ->where('id_option_additional', $adds->id)->sum('refund_menu_order.qty');


                    $refund = AdditionalRefund::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->where('id_option_additional', $adds->id)
                        ->sum('total_');

                    $grosSale = $adds->harga * $itmAdsSold;
                    $RefgrosSale = $refund * $refundSum;
                    $NetSales = $grosSale  - $RefgrosSale;
                    $totalGrandGrosSalesAdds += $grosSale;
                    $totalGrandRefudAdds += $refund;
                    $totalNetAdds += $NetSales;
                }
            } else {
                $Bulan = Carbon::now()->isoFormat('MM');
                foreach ($menu as $itm) {
                    // harga menu detail order
                    $items = DetailOrder::where('created_at', 'LIKE', '%-' . $Bulan . '-%')->where('id_menu', $itm->id)->value('harga');
                    // total qty menu detail order
                    $itmsum = DetailOrder::where('created_at', 'LIKE', '%-' . $Bulan . '-%')->where('id_menu', $itm->id)->sum('qty');

                    // sum total discount
                    $totalDiscount = Discount_detail_order::join('detail_order', 'discount_detail_order.id_detail_order', '=', 'detail_order.id')
                        ->where('detail_order.id_menu', $itm->id)->where('detail_order.created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->sum('discount_detail_order.total_discount');

                    // sum total refund discount
                    $refundDisCountSum = DiscountMenuRefund::where('discount_refund.created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->where('id_menu', $itm->id)->sum('nominal_dis');

                    // sum qty refund
                    $SumRefund = RefundOrderMenu::where('id_menu', $itm->id)->where('created_at', 'LIKE', '%-' . $Bulan . '-%')->sum('qty');
                    // harga item refund
                    $hargaRefund = RefundOrderMenu::where('id_menu', $itm->id)->where('created_at', 'LIKE', '%-' . $Bulan . '-%')->value('harga');

                    // total qty menu detail order
                    $itemsold = $itmsum;
                    //gross sales menu
                    $harga =  $items * $itemsold;
                    //gross refund item
                    $totalRefund = $SumRefund *  $hargaRefund;
                    //gross discount
                    $disTotal = $totalDiscount - $refundDisCountSum;
                    //Net Seles
                    $netSales = $harga - $disTotal - $totalRefund;

                    $totalGrandGrosSalesMenu += $harga;
                    $totalGrandDisMenu += $disTotal;
                    $totalGrandRefudMenu += $totalRefund;
                    $totalNetMenu += $netSales;
                }

                foreach ($additional as $adds) {


                    $itmAdsSold = Additional_menu_detail::join('detail_order', 'additional_menu.id_detail_order', '=', 'detail_order.id')
                        ->where('additional_menu.created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->where('additional_menu.id_option_additional', $adds->id)
                        ->sum('detail_order.qty');

                    $refundSum = AdditionalRefund::join('refund_menu_order', 'additional_refund.id_refund_menu', '=', 'refund_menu_order.id')
                        ->where('additional_refund.created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->where('id_option_additional', $adds->id)->sum('refund_menu_order.qty');


                    $refund = AdditionalRefund::where('created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->where('id_option_additional', $adds->id)
                        ->sum('total_');

                    $grosSale = $adds->harga * $itmAdsSold;
                    $RefgrosSale = $refund * $refundSum;
                    $NetSales = $grosSale  - $RefgrosSale;

                    $totalGrandGrosSalesAdds += $grosSale;
                    $totalGrandRefudAdds += $refund;
                    $totalNetAdds += $NetSales;
                }
            }


            $taxpb1 = Taxes::where('nama', 'PB1')->first();
            $service = Taxes::where('nama', 'Service Charge')->first();

            $allGrandSales =  $totalGrandGrosSalesMenu + $totalGrandGrosSalesAdds;
            $allGrandDis = $totalGrandDisMenu + $totalGrandDisAdds;
            $allGrandRefund = $totalGrandRefudMenu + $totalGrandRefudAdds;
            $allGrandNet =  $allGrandSales -  $allGrandDis -  $allGrandRefund;

            $PB1 = $taxpb1->tax_rate / 100;
            $Service = $service->tax_rate / 100;

            $nominalPb1 = $allGrandNet * $PB1;
            $nominalService = $allGrandNet * $Service;

            $totalTax = $nominalPb1 + $nominalService;

            $TotalGrand = $allGrandNet + $totalTax;



            return Excel::download(new ReportSalesExport (
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

    public function ExportGrossProfit(Request $request){
         if (Sentinel::check()) {
            $gross_Sales = 0;
            $DiscountTotal = 0;
            $totalGrandGrosSalesMenu = 0;
            $totalGrandDisMenu = 0;
            $totalGrandRefudMenu = 0;
            $totalNetMenu = 0;
            $totalGrandGrosSalesAdds = 0;
            $totalGrandDisAdds = 0;
            $totalGrandRefudAdds = 0;
            $totalNetAdds = 0;
            $allGrandSales = 0;
            $allGrandDis = 0;
            $allGrandRefund = 0;
            $allGrandNet = 0;


            $menu = Menu::all();
            $additional = OptionModifier::all();


            if ($request->has('start_date') && $request->has('end_date')) {

                $start_date = date('Y-m-d', strtotime($request->input('start_date')));
                $end_date = date('Y-m-d', strtotime($request->input('end_date') . ' +1 day'));

                foreach ($menu as $itm) {
                    // harga menu detail order
                    $items = DetailOrder::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->where('id_menu', $itm->id)->value('harga');
                    // total qty menu detail order
                    $itmsum = DetailOrder::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->where('id_menu', $itm->id)->sum('qty');

                    // sum total discount
                    $totalDiscount = Discount_detail_order::where('discount_detail_order.created_at', '>=', $start_date)
                        ->where('discount_detail_order.created_at', '<', $end_date)
                        ->join('detail_order', 'discount_detail_order.id_detail_order', '=', 'detail_order.id')
                        ->where('detail_order.id_menu', $itm->id)
                        ->sum('discount_detail_order.total_discount');

                    // sum total refund discount
                    $refundDisCountSum = DiscountMenuRefund::where('created_at', '>=', $start_date)
                        ->where('created_at', '<', $end_date)->where('id_menu', $itm->id)->sum('nominal_dis');

                    // sum qty refund
                    $SumRefund = RefundOrderMenu::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->where('id_menu', $itm->id)->sum('qty');
                    // harga item refund
                    $hargaRefund = RefundOrderMenu::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->where('id_menu', $itm->id)->value('harga');

                    // total qty menu detail order
                    $itemsold = $itmsum;
                    //gross sales menu
                    $harga =  $items * $itemsold;
                    //gross refund item
                    $totalRefund = $SumRefund *  $hargaRefund;
                    //gross discount
                    $disTotal = $totalDiscount - $refundDisCountSum;
                    //Net Seles
                    $netSales = $harga - $disTotal - $totalRefund;

                    $totalGrandGrosSalesMenu += $harga;
                    $totalGrandDisMenu += $disTotal;
                    $totalGrandRefudMenu += $totalRefund;
                    $totalNetMenu += $netSales;
                }

                foreach ($additional as $adds) {

                    $itmAdsSold = Additional_menu_detail::where('additional_menu.created_at', '>=', $start_date)->where('additional_menu.created_at', '<', $end_date)
                        ->join('detail_order', 'additional_menu.id_detail_order', '=', 'detail_order.id')
                        ->where('additional_menu.id_option_additional', $adds->id)
                        ->sum('detail_order.qty');

                    $refundSum = AdditionalRefund::where('additional_refund.created_at', '>=', $start_date)->where('additional_refund.created_at', '<', $end_date)
                        ->join('refund_menu_order', 'additional_refund.id_refund_menu', '=', 'refund_menu_order.id')
                        ->where('id_option_additional', $adds->id)->sum('refund_menu_order.qty');


                    $refund = AdditionalRefund::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->where('id_option_additional', $adds->id)
                        ->sum('total_');

                    $grosSale = $adds->harga * $itmAdsSold;
                    $RefgrosSale = $refund * $refundSum;
                    $NetSales = $grosSale  - $RefgrosSale;
                    $totalGrandGrosSalesAdds += $grosSale;
                    $totalGrandRefudAdds += $refund;
                    $totalNetAdds += $NetSales;
                }
            } else {
                $Bulan = Carbon::now()->isoFormat('MM');
                foreach ($menu as $itm) {

                    // harga menu detail order
                    $items = DetailOrder::where('created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->where('id_menu', $itm->id)->value('harga');
                    // total qty menu detail order
                    $itmsum = DetailOrder::where('created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->where('id_menu', $itm->id)->sum('qty');

                    // sum total discount
                    $totalDiscount = Discount_detail_order::where('discount_detail_order.created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->join('detail_order', 'discount_detail_order.id_detail_order', '=', 'detail_order.id')
                        ->where('detail_order.id_menu', $itm->id)
                        ->sum('discount_detail_order.total_discount');

                    // sum total refund discount
                    $refundDisCountSum = DiscountMenuRefund::where('created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->where('id_menu', $itm->id)->sum('nominal_dis');

                    // sum qty refund
                    $SumRefund = RefundOrderMenu::where('created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->where('id_menu', $itm->id)->sum('qty');
                    // harga item refund
                    $hargaRefund = RefundOrderMenu::where('created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->where('id_menu', $itm->id)->value('harga');

                    // total qty menu detail order
                    $itemsold = $itmsum;
                    //gross sales menu
                    $harga =  $items * $itemsold;
                    //gross refund item
                    $totalRefund = $SumRefund *  $hargaRefund;
                    //gross discount
                    $disTotal = $totalDiscount - $refundDisCountSum;
                    //Net Seles
                    $netSales = $harga - $disTotal - $totalRefund;

                    $totalGrandGrosSalesMenu += $harga;
                    $totalGrandDisMenu += $disTotal;
                    $totalGrandRefudMenu += $totalRefund;
                    $totalNetMenu += $netSales;
                }

                foreach ($additional as $adds) {

                    $itmAdsSold = Additional_menu_detail::where('additional_menu.created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->join('detail_order', 'additional_menu.id_detail_order', '=', 'detail_order.id')
                        ->where('additional_menu.id_option_additional', $adds->id)
                        ->sum('detail_order.qty');

                    $refundSum = AdditionalRefund::where('additional_refund.created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->join('refund_menu_order', 'additional_refund.id_refund_menu', '=', 'refund_menu_order.id')
                        ->where('id_option_additional', $adds->id)->sum('refund_menu_order.qty');


                    $refund = AdditionalRefund::where('created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->where('id_option_additional', $adds->id)
                        ->sum('total_');

                    $grosSale = $adds->harga * $itmAdsSold;
                    $RefgrosSale = $refund * $refundSum;
                    $NetSales = $grosSale  - $RefgrosSale;
                    $totalGrandGrosSalesAdds += $grosSale;
                    $totalGrandRefudAdds += $refund;
                    $totalNetAdds += $NetSales;
                }
            }


            $taxpb1 = Taxes::where('nama', 'PB1')->first();
            $service = Taxes::where('nama', 'Service Charge')->first();

            $allGrandSales =  $totalGrandGrosSalesMenu + $totalGrandGrosSalesAdds;
            $allGrandDis = $totalGrandDisMenu + $totalGrandDisAdds;
            $allGrandRefund = $totalGrandRefudMenu + $totalGrandRefudAdds;
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

    public function ExportPaymentMethode(Request $request){
        if (Sentinel::check()) {
            $paymentMetode = TypePayment::all();
            $paymentData = [];
            $menu = Menu::all();
            $additional = OptionModifier::all();
            $totalGrandRefudMenu = 0;
            $totalGrandRefudAdds = 0;
            $disRefun = 0;
            $allGrandSales = 0;
            $allGrandDis = 0;
            $allGrandRefund = 0;
            $allGrandNet = 0;
            $TotalGrand = 0;
            $gradaddsTotal = 0;
            $totalOrders =0;
            $totalPembayarans = 0;

            if ($request->has('start_date') && $request->has('end_date')) {
                $start_date = date('Y-m-d', strtotime($request->input('start_date')));
                $end_date = date('Y-m-d', strtotime($request->input('end_date') . ' +1 day'));

                foreach ($paymentMetode as $payment) {

                    $totalTransaksi = Orders::where('id_type_payment', $payment->id)->where('created_at', '>=', $start_date)
                        ->where('created_at', '<', $end_date)->count();

                    $totalGrand = Orders::where('id_type_payment', $payment->id)->where('created_at', '>=', $start_date)
                        ->where('created_at', '<', $end_date)
                        ->sum('total_order');

                    $refund = RefundOrderMenu::where('refund_menu_order.created_at', '>=', $start_date)->where('refund_menu_order.created_at', '<', $end_date)
                        ->join('orders', 'refund_menu_order.id_order', 'orders.id')
                        ->where('orders.id_type_payment', $payment->id)->sum('refund_menu_order.refund_nominal');

                    $discountRefund = DiscountMenuRefund::where('discount_refund.created_at', '>=', $start_date)->where('discount_refund.created_at', '<', $end_date)
                        ->join('refund_menu_order', 'discount_refund.id_refund_menu', 'refund_menu_order.id')
                        ->join('orders', 'refund_menu_order.id_order', 'orders.id')->where('orders.id_type_payment', $payment->id)
                        ->sum('discount_refund.nominal_dis');

                    $hargarefundAdds = AdditionalRefund::where('additional_refund.created_at', '>=', $start_date)->where('additional_refund.created_at', '<', $end_date)
                        ->join('refund_menu_order', 'additional_refund.id_refund_menu', 'refund_menu_order.id')
                        ->join('orders', 'refund_menu_order.id_order', 'orders.id')
                        ->where('orders.id_type_payment', $payment->id)
                        ->sum('additional_refund.total_');

                    $totalRef = $refund + $hargarefundAdds;
                    $subRef = $totalRef - $discountRefund;

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
                        'paymentMethod' => $payment->nama,
                        'totalOrder' =>  $totalTransaksi,
                        'totalPembayaran' => $GandTotal
                    ];
                }
            } else {
                $tanggal_mulai = Carbon::now()->isoFormat('MM');
                foreach ($paymentMetode as $payment) {

                    $totalTransaksi = Orders::where('id_type_payment', $payment->id)->where('created_at', 'LIKE', '%-' . $tanggal_mulai . '-%')->count();
                    $totalGrand = Orders::where('id_type_payment', $payment->id)->where('created_at', 'LIKE', '%-' . $tanggal_mulai . '-%')->sum('total_order');

                    $refund = RefundOrderMenu::where('refund_menu_order.tanggal', 'LIKE', '%-' . $tanggal_mulai . '-%')
                        ->join('orders', 'refund_menu_order.id_order', 'orders.id')
                        ->where('orders.id_type_payment', $payment->id)->sum('refund_menu_order.refund_nominal');

                    $discountRefund = DiscountMenuRefund::where('discount_refund.created_at', 'LIKE', '%-' . $tanggal_mulai . '-%')
                        ->join('refund_menu_order', 'discount_refund.id_refund_menu', 'refund_menu_order.id')
                        ->join('orders', 'refund_menu_order.id_order', 'orders.id')->where('orders.id_type_payment', $payment->id)
                        ->sum('discount_refund.nominal_dis');

                    $hargarefundAdds = AdditionalRefund::where('additional_refund.created_at', 'LIKE', '%-' . $tanggal_mulai . '-%')
                        ->join('refund_menu_order', 'additional_refund.id_refund_menu', 'refund_menu_order.id')
                        ->join('orders', 'refund_menu_order.id_order', 'orders.id')
                        ->where('orders.id_type_payment', $payment->id)
                        ->sum('additional_refund.total_');

                    $totalRef = $refund + $hargarefundAdds;
                    $subRef = $totalRef - $discountRefund;

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
                        'paymentMethod' => $payment->nama,
                        'totalOrder' =>  $totalTransaksi,
                        'totalPembayaran' => $GandTotal
                    ];
                }
            }

            return Excel::download(new PaymentMethodeExport(
                $paymentData,
                $totalGrandRefudMenu,
                $totalGrandRefudAdds,
                $disRefun,
                $allGrandSales,
                $allGrandDis,
                $allGrandRefund,
                $allGrandNet,
                $TotalGrand,
                $gradaddsTotal,
                $totalOrders,
                $totalPembayarans,

            ), 'Laporan Payment Methode.xlsx');
        }else{
            return redirect()->route('login');
        }
    }

    public function ExportSelesType(Request $request){
        if (Sentinel::check()) {

            $typeSales = SalesType::all();
            $SumTotal = 0;
            $SalesData = [];
            $totalOrders =0;
            $totalPembayarans = 0;

            if ($request->has('start_date') && $request->has('end_date')) {

                $start_date = date('Y-m-d', strtotime($request->input('start_date')));
                $end_date = date('Y-m-d', strtotime($request->input('end_date') . ' +1 day'));

                foreach ($typeSales as $types) {
                    //count menu yang sesuai type sales
                    $typesId = $types->id;
                    $countItemTypeSales = DetailOrder::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->where('id_sales_type', $types->id)->count();

                    $SumTotal = Orders::whereHas('details', function ($query) use ($typesId) {
                        $query->where('id_sales_type', $typesId);
                    })->where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->sum('total_order');


                    $totalRefund = RefundOrderMenu::whereHas('detail_order', function ($query) use ($typesId) {
                        $query->where('id_sales_type', $typesId);
                    })
                        ->where('created_at', '>=', $start_date)->where('created_at', '<', $end_date) // Ganti dengan tanggal mulai yang sesuai
                        ->sum('refund_nominal');


                    $refundAddsSum = AdditionalRefund::whereHas('Refund', function ($query) use ($typesId) {
                        $query->whereHas('detail_order', function ($query) use ($typesId) {
                            $query->where('id_sales_type', $typesId);
                        });
                    })->where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->sum('total_');

                    $refundDisCountSum = DiscountMenuRefund::whereHas('Refund', function ($query) use ($typesId) {
                        $query->whereHas('detail_order', function ($query) use ($typesId) {
                            $query->where('id_sales_type', $typesId);
                        });
                    })->where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->sum('nominal_dis');


                    $taxpb1 = Taxes::where('nama', 'PB1')->first();
                    $service = Taxes::where('nama', 'Service Charge')->first();

                    $PB1 = $taxpb1->tax_rate / 100;
                    $Service = $service->tax_rate / 100;

                    $grossRef = $totalRefund + $refundAddsSum;
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

                    $SalesData[] = [
                        'Sales Type' => $types->name,
                        'totalOrder' =>  $countItemTypeSales,
                        'Total' => $GandTotal,

                    ];
                }
            } else {
                $tanggal_mulai = Carbon::now()->isoFormat('MM');
                foreach ($typeSales as $types) {
                    //count menu yang sesuai type sales
                    $typesId = $types->id;
                    $countItemTypeSales = DetailOrder::where('created_at', 'LIKE', '%-' . $tanggal_mulai . '-%')->where('id_sales_type', $types->id)->count();

                    $SumTotal = Orders::whereHas('details', function ($query) use ($typesId) {
                        $query->where('id_sales_type', $typesId);
                    })->where('created_at', 'LIKE', '%-' . $tanggal_mulai . '-%')
                        ->sum('total_order');


                    $totalRefund = RefundOrderMenu::whereHas('detail_order', function ($query) use ($typesId) {
                        $query->where('id_sales_type', $typesId);
                    })
                        ->where('tanggal', 'LIKE', '%-' . $tanggal_mulai . '-%') // Ganti dengan tanggal mulai yang sesuai
                        ->sum('refund_nominal');


                    $refundAddsSum = AdditionalRefund::whereHas('Refund', function ($query) use ($typesId) {
                        $query->whereHas('detail_order', function ($query) use ($typesId) {
                            $query->where('id_sales_type', $typesId);
                        });
                    })->where('created_at', 'LIKE', '%-' . $tanggal_mulai . '-%')
                        ->sum('total_');

                    $refundDisCountSum = DiscountMenuRefund::whereHas('Refund', function ($query) use ($typesId) {
                        $query->whereHas('detail_order', function ($query) use ($typesId) {
                            $query->where('id_sales_type', $typesId);
                        });
                    })->where('created_at', 'LIKE', '%-' . $tanggal_mulai . '-%')
                        ->sum('nominal_dis');


                    $taxpb1 = Taxes::where('nama', 'PB1')->first();
                    $service = Taxes::where('nama', 'Service Charge')->first();

                    $PB1 = $taxpb1->tax_rate / 100;
                    $Service = $service->tax_rate / 100;

                    $grossRef = $totalRefund + $refundAddsSum;
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

                    $SalesData[] = [
                        'Sales Type' => $types->name,
                        'totalOrder' =>  $countItemTypeSales,
                        'Total' => $GandTotal,

                    ];
                }
            }

            return Excel::download(new SalesTypeExport(
                $SalesData,
                $totalOrders,
                $totalPembayarans

            ), 'Laporan Sales.xlsx');
        }else{
            return redirect()->route('login');
        }
    }

    public function ExportItemSales(Request $request){
         if (Sentinel::check()) {

            $menu = Menu::all();
            $additional = OptionModifier::all();
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

            if ($request->has('start_date') && $request->has('end_date')) {

                $start_date = date('Y-m-d', strtotime($request->input('start_date')));
                $end_date = date('Y-m-d', strtotime($request->input('end_date') . ' +1 day'));

                foreach ($menu as $itm) {
                    $items = DetailOrder::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                    ->where('id_menu', $itm->id)->value('harga');

                    $itmsum = DetailOrder::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                    ->where('id_menu', $itm->id)->sum('qty');
                    // dd($itmsum);

                    $totalDiscount = Discount_detail_order::join('detail_order', 'discount_detail_order.id_detail_order', '=', 'detail_order.id')
                    ->where('detail_order.id_menu', $itm->id)->where('detail_order.created_at', '>=', $start_date)
                        ->where('detail_order.created_at', '<', $end_date)
                        ->sum('discount_detail_order.total_discount');


                    $varian = DetailOrder::join('varian_menu', 'detail_order.id_varian', '=', 'varian_menu.id')
                    ->where('detail_order.created_at', '>=', $start_date)->where('detail_order.created_at', '<', $end_date)
                    ->where('detail_order.id_menu', $itm->id)->pluck('nama');

                    $refundDisCountSum = DiscountMenuRefund::where('discount_refund.created_at', '>=', $start_date)
                        ->where('discount_refund.created_at', '<', $end_date)
                        ->where('id_menu', $itm->id)->sum('nominal_dis');

                    $SumRefund = RefundOrderMenu::where('id_menu', $itm->id)->where('created_at', '>=', $start_date)
                        ->where('created_at', '<', $end_date)->sum('qty');

                    $hargaRefund = RefundOrderMenu::where('id_menu', $itm->id)->where('created_at', '>=', $start_date)
                        ->where('created_at', '<', $end_date)->value('harga');


                    $harga = $items * $itmsum;
                    $totalRefund = $hargaRefund * $SumRefund;

                    $disTotal = $totalDiscount - $refundDisCountSum;
                    $netSales = $harga - $disTotal - $totalRefund;

                    $itemSalesMenu[] = [
                        'Name' => $itm,
                        'itemSold' => $itmsum,
                        'itemrefund' => $SumRefund,
                        'GrossSalse' => $harga,
                        'Discount' => $disTotal,
                        'Refund' => $totalRefund,
                        'NetSales' => $netSales
                    ];
                }

                foreach ($additional as $adds) {

                    $itmAdsSold = Additional_menu_detail::join('detail_order', 'additional_menu.id_detail_order', '=', 'detail_order.id')
                    ->where('additional_menu.created_at', '>=', $start_date)->where('additional_menu.created_at', '<', $end_date)
                    ->where('additional_menu.id_option_additional', $adds->id)
                    ->sum('detail_order.qty');

                    $totalDiscount = Discount_detail_order::join('detail_order', 'discount_detail_order.id_detail_order', '=', 'detail_order.id')
                    ->where('detail_order.id_menu', $itm->id)->where('detail_order.created_at', '>=', $start_date)->where('detail_order.created_at', '<', $end_date)
                    ->sum('discount_detail_order.total_discount');

                    $refundSum = AdditionalRefund::join('refund_menu_order', 'additional_refund.id_refund_menu', '=', 'refund_menu_order.id')
                    ->where('additional_refund.created_at', '>=', $start_date)->where('additional_refund.created_at', '<', $end_date)
                    ->where('id_option_additional', $adds->id)->sum('refund_menu_order.qty');


                    $refund = AdditionalRefund::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                    ->where('id_option_additional', $adds->id)
                    ->sum('harga');

                    $grosSale = $adds->harga * $itmAdsSold;
                    $grosRefund = $refund * $refundSum;

                    $NetSales = $grosSale  - $grosRefund;

                    $itemSalesAdss[] = [
                        'Name' => $adds,
                        // 'variasi' => $itm->varian->nama,
                        'category' => '',
                        'item Sold' => $itmAdsSold,
                        'item refund' => $refundSum,
                        'Gross Salse' => $grosSale,
                        'Refund' => $grosRefund,
                        'Net Sales' => $NetSales
                    ];
                }
            } else {
                $tanggal_mulai = Carbon::now()->isoFormat('MM');
                foreach ($menu as $itm) {
                    $items = DetailOrder::where('created_at', 'LIKE', '%-' . $tanggal_mulai . '-%')->where('id_menu', $itm->id)->value('harga');

                    $itmsum = DetailOrder::where('created_at', 'LIKE', '%-' . $tanggal_mulai . '-%')->where('id_menu', $itm->id)->sum('qty');
                    // dd($itmsum);

                    $totalDiscount = Discount_detail_order::join('detail_order', 'discount_detail_order.id_detail_order', '=', 'detail_order.id')
                    ->where('detail_order.id_menu', $itm->id)->where('detail_order.created_at', 'LIKE', '%-' . $tanggal_mulai . '-%')
                        ->sum('discount_detail_order.total_discount');


                    $varian = DetailOrder::join('varian_menu', 'detail_order.id_varian', '=', 'varian_menu.id')->where('detail_order.created_at', 'LIKE', '%-' . $tanggal_mulai . '-%')
                        ->where('detail_order.id_menu', $itm->id)->pluck('nama');

                    $refundDisCountSum = DiscountMenuRefund::where('discount_refund.created_at', 'LIKE', '%-' . $tanggal_mulai . '-%')
                        ->where('id_menu', $itm->id)->sum('nominal_dis');

                    $SumRefund = RefundOrderMenu::where('id_menu', $itm->id)->where('created_at', 'LIKE', '%-' . $tanggal_mulai . '-%')->sum('qty');
                    $hargaRefund = RefundOrderMenu::where('id_menu', $itm->id)->where('created_at', 'LIKE', '%-' . $tanggal_mulai . '-%')->value('harga');


                    $harga = $items * $itmsum;
                    $totalRefund = $hargaRefund * $SumRefund;

                    $disTotal = $totalDiscount - $refundDisCountSum;
                    $netSales = $harga - $disTotal - $totalRefund;

                    $itemSalesMenu[] = [
                        'Name' => $itm,
                        'itemSold' => $itmsum,
                        'itemrefund' => $SumRefund,
                        'GrossSalse' => $harga,
                        'Discount' => $disTotal,
                        'Refund' => $totalRefund,
                        'NetSales' => $netSales
                    ];
                }

                foreach ($additional as $adds) {

                    $itmAdsSold = Additional_menu_detail::join('detail_order', 'additional_menu.id_detail_order', '=', 'detail_order.id')
                    ->where('additional_menu.created_at', 'LIKE', '%-' . $tanggal_mulai . '-%')
                        ->where('additional_menu.id_option_additional', $adds->id)
                        ->sum('detail_order.qty');

                    $totalDiscount = Discount_detail_order::join('detail_order', 'discount_detail_order.id_detail_order', '=', 'detail_order.id')
                    ->where('detail_order.id_menu', $itm->id)->where('detail_order.created_at', 'LIKE', '%-' . $tanggal_mulai . '-%')
                        ->sum('discount_detail_order.total_discount');

                    $refundSum = AdditionalRefund::join('refund_menu_order', 'additional_refund.id_refund_menu', '=', 'refund_menu_order.id')
                    ->where('additional_refund.created_at', 'LIKE', '%-' . $tanggal_mulai . '-%')
                        ->where('id_option_additional', $adds->id)->sum('refund_menu_order.qty');


                    $refund = AdditionalRefund::where('created_at', 'LIKE', '%-' . $tanggal_mulai . '-%')
                        ->where('id_option_additional', $adds->id)
                        ->sum('harga');

                    $grosSale = $adds->harga * $itmAdsSold;
                    $grosRefund = $refund * $refundSum;

                    $NetSales = $grosSale  - $grosRefund;

                    $itemSalesAdss[] = [
                        'Name' => $adds,
                        // 'variasi' => $itm->varian->nama,
                        'category' => '',
                        'item Sold' => $itmAdsSold,
                        'item refund' => $refundSum,
                        'Gross Salse' => $grosSale,
                        'Refund' => $grosRefund,
                        'Net Sales' => $NetSales
                    ];
                }
            }

            return Excel::download(new ItemSalesExport(

                $itemSalesAdss,
                $itemSalesMenu,
                $totalItemSoldMenu,
                $totalItemRefundMenu ,
                $totalGrossMenu,
                $totalDiscountMenu ,
                $totalRefundMenu,
                $totalNetMenu ,
                $totalItemSoldAdds,
                $totalItemRefundAdds ,
                $totalGrossAdds ,
                $totalDiscountAdds ,
                $totalRefundAdds ,
                $totalNetAdds ,

            ), 'Laporan Item Report.xlsx');
         }else{
             return redirect()->route('login');
         }
    }

    public function ExportModifier(Request $request){
         if (Sentinel::check()) {

            $additional = OptionModifier::all();
            $itemSalesAdss = [];
            $qty = 0;
            $Gross = 0;
            $Dis = 0;
            $ref = 0;
            $netSels= 0;

            if ($request->has('start_date') && $request->has('end_date')) {

                $start_date = date('Y-m-d', strtotime($request->input('start_date')));
                $end_date = date('Y-m-d', strtotime($request->input('end_date') . ' +1 day'));

                foreach ($additional as $adds) {

                    $addsId = $adds->id;

                    $itmAdsSold = Additional_menu_detail::join('detail_order', 'additional_menu.id_detail_order', '=', 'detail_order.id')
                    ->where('additional_menu.created_at', '>=', $start_date)->where('additional_menu.created_at', '<', $end_date)
                    ->where('additional_menu.id_option_additional', $adds->id)
                    ->sum('detail_order.qty');

                    $refundSum = AdditionalRefund::join('refund_menu_order', 'additional_refund.id_refund_menu', '=', 'refund_menu_order.id')
                    ->where('additional_refund.created_at', '>=', $start_date)->where('additional_refund.created_at', '<', $end_date)
                    ->where('id_option_additional', $adds->id)->sum('refund_menu_order.qty');

                    $discount =  Discount_detail_order::whereHas('id_Detail', function ($query) use ($addsId) {
                        $query->where('id_option_additional', $addsId);
                    })->where('discount_detail_order.created_at', '>=', $start_date)->where('discount_detail_order.created_at', '<', $end_date)
                    ->join('discount', 'discount_detail_order.id_discount', 'discount.id')->sum('discount.rate_dis');

                    $refund = AdditionalRefund::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                    ->where('id_option_additional', $adds->id)
                    ->sum('harga');

                    $disRefund = DiscountMenuRefund::whereHas('refundDis', function ($query) use ($addsId) {
                        $query->where('id_option_additional', $addsId);
                    })->where('discount_refund.created_at', '>=', $start_date)->where('discount_refund.created_at', '<', $end_date)
                    ->join('discount', 'discount_refund.id_discount', 'discount.id')->sum('discount.rate_dis');

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
            }else {
                $Bulan = Carbon::now()->isoFormat('MM');
                foreach ($additional as $adds) {

                    $addsId = $adds->id;

                    $itmAdsSold = Additional_menu_detail::join('detail_order', 'additional_menu.id_detail_order', '=', 'detail_order.id')
                    ->where('additional_menu.created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->where('additional_menu.id_option_additional', $adds->id)
                        ->sum('detail_order.qty');

                    $refundSum = AdditionalRefund::join('refund_menu_order', 'additional_refund.id_refund_menu', '=', 'refund_menu_order.id')
                    ->where('additional_refund.created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->where('id_option_additional', $adds->id)->sum('refund_menu_order.qty');

                    $discount =  Discount_detail_order::whereHas('id_Detail', function ($query) use ($addsId) {
                        $query->where('id_option_additional', $addsId);
                    })->join('discount', 'discount_detail_order.id_discount', 'discount.id')->sum('discount.rate_dis');

                    $refund = AdditionalRefund::where('created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->where('id_option_additional', $adds->id)
                        ->sum('harga');

                    $disRefund = DiscountMenuRefund::whereHas('refundDis', function ($query) use ($addsId) {
                        $query->where('id_option_additional', $addsId);
                    })->join('discount', 'discount_refund.id_discount', 'discount.id')->sum('discount.rate_dis');

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
            }

            return Excel::download(new ModifierSalesExport(
                $itemSalesAdss,
                $qty ,
                $Gross ,
                $Dis ,
                $ref ,
                $netSels,
            ), 'Laporan Modifier.xlsx');

         }else{
             return redirect()->route('login');
         }
    }

    public function ExportDiscount(Request $request){
          if (Sentinel::check()) {
            $Discount = Discount::all();

            $dataDiscount = [];
            $count = 0;
            $Gross = 0;
            $ref = 0;
            $netSels= 0;

            if ($request->has('start_date') && $request->has('end_date')) {

                $start_date = date('Y-m-d', strtotime($request->input('start_date')));
                $end_date = date('Y-m-d', strtotime($request->input('end_date') . ' +1 day'));
                foreach ($Discount as $dis) {

                    $countDis = Discount_detail_order::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)->where('id_discount', $dis->id)->count();

                    $grossDis = Discount_detail_order::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)->where('id_discount', $dis->id)->sum('total_discount');

                    $refundDisCount = DiscountMenuRefund::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)->where('id_discount', $dis->id)->sum('nominal_dis');

                    $netDis = $grossDis - $refundDisCount;

                    $dataDiscount[] = [
                        'nama' => $dis,
                        'count' => $countDis,
                        'Gross' => $grossDis,
                        'refund' => $refundDisCount,
                        'Net' => $netDis
                    ];
                }
            } else {
                $Bulan = Carbon::now()->isoFormat('MM');
                foreach ($Discount as $dis) {

                    $countDis = Discount_detail_order::where('created_at', 'LIKE', '%-' . $Bulan . '-%')->where('id_discount', $dis->id)->count();

                    $grossDis = Discount_detail_order::where('created_at', 'LIKE', '%-' . $Bulan . '-%')->where('id_discount', $dis->id)->sum('total_discount');

                    $refundDisCount = DiscountMenuRefund::where('created_at', 'LIKE', '%-' . $Bulan . '-%')->where('id_discount', $dis->id)->sum('nominal_dis');

                    $netDis = $grossDis - $refundDisCount;

                    $dataDiscount[] = [
                        'nama' => $dis,
                        'count' => $countDis,
                        'Gross' => $grossDis,
                        'refund' => $refundDisCount,
                        'Net' => $netDis
                    ];
                }
            }

            return Excel::download(new DiscountSalesExport(
                $dataDiscount,
                $count,
                $Gross,
                $ref,
                $netSels,
            ), 'Laporan Discount Sales.xlsx');
          }else{
            return redirect()->route('login');
          }
    }

    public function ExportTaxes(Request $request){
          if (Sentinel::check()) {
            $taxes = Taxes::all();
            $dataTax = [];
            $totalTax = 0;

            if ($request->has('start_date') && $request->has('end_date')) {

                $start_date = date('Y-m-d', strtotime($request->input('start_date')));
                $end_date = date('Y-m-d', strtotime($request->input('end_date') . ' +1 day'));

                foreach ($taxes as $tax) {
                    $Order = Orders::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)->sum('subtotal');
                    $refund = RefundOrderMenu::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)->sum('refund_nominal');
                    $discountRefund = DiscountMenuRefund::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)->sum('nominal_dis');
                    $addsRefund = AdditionalRefund::where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)->sum('total_');

                    $netGross = ($Order + $discountRefund) - ($refund + $addsRefund);

                    $taxs = $tax->tax_rate / 100;
                    $nominal = $netGross * $taxs;

                    $dataTax[] = [
                        'Taxs' => $tax,
                        'Net' => $netGross,
                        'taxTotal' => $nominal
                    ];
                }
            } else {
                $Bulan = Carbon::now()->isoFormat('MM');
                foreach ($taxes as $tax) {
                    $Order = Orders::where('created_at', 'LIKE', '%-' . $Bulan . '-%')->sum('subtotal');
                    $refund = RefundOrderMenu::where('created_at', 'LIKE', '%-' . $Bulan . '-%')->sum('refund_nominal');
                    $discountRefund = DiscountMenuRefund::where('created_at', 'LIKE', '%-' . $Bulan . '-%')->sum('nominal_dis');
                    $addsRefund = AdditionalRefund::where('created_at', 'LIKE', '%-' . $Bulan . '-%')->sum('total_');

                    $netGross = ($Order + $discountRefund) - ($refund + $addsRefund);

                    $taxs = $tax->tax_rate / 100;
                    $nominal = $netGross * $taxs;

                    $dataTax[] = [
                        'Taxs' => $tax,
                        'Net' => $netGross,
                        'taxTotal' => $nominal
                    ];
                }
            }

            return Excel::download(new TaxesSalesExport(
                $dataTax,
                $totalTax,
            ), 'Laporan Taxes Sales.xlsx');
          }else{
             return redirect()->route('login');
          }
    }

    public function ExportCategory(Request $request){
         if (Sentinel::check()) {

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

            if ($request->has('start_date') && $request->has('end_date')) {

                $start_date = date('Y-m-d', strtotime($request->input('start_date')));
                $end_date = date('Y-m-d', strtotime($request->input('end_date') . ' +1 day'));
                // $start_date = date('Y-m-d', strtotime('2023-08-01'));
                // $end_date = date('Y-m-d', strtotime('2023-08-31'. ' +1 day'));

                foreach ($subcategory as $cat) {
                    $idCat = $cat->id;
                    $hargaData = [];

                    $hargaMenu = DetailOrder::whereHas('menu.subKategori', function ($query) use ($idCat) {
                        $query->where('id', $idCat);
                    })->where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->get();

                    foreach ($hargaMenu as $data) {

                        $subTotal = 0;
                        $harga = $data->harga;
                        $qty = $data->qty;
                        $total = $harga * $qty;
                        $subTotal += $total;
                        $hargaData[] = [
                            'harga' => $subTotal
                        ];
                    }

                    // $hargaData = collect($hargaData);

                    $qtySold = DetailOrder::whereHas('menu.subKategori', function ($query) use ($idCat) {
                        $query->where('id', $idCat);
                    })->where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->sum('qty');

                    $discount = Discount_detail_order::whereHas('Detail_order.menu.subKategori', function ($query) use ($idCat) {
                        $query->where('id', $idCat);
                    })->where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->sum('total_discount');

                    $qtyRefund = RefundOrderMenu::whereHas('menu.subKategori', function ($query) use ($idCat) {
                        $query->where('id', $idCat);
                    })->where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->sum('qty');

                    $hargaRefund = RefundOrderMenu::whereHas('menu.subKategori', function ($query) use ($idCat) {
                        $query->where('id', $idCat);
                    })->where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->sum('refund_nominal');

                    $discountRef = DiscountMenuRefund::whereHas('menu.subKategori', function ($query) use ($idCat) {
                        $query->where('id', $idCat);
                    })->where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->sum('nominal_dis');

                    // $harga = $qtySold * $hargaMenu;
                    // $totalRefund = $hargaRefund * $qtyRefund;

                    $disTotal = $discount - $discountRef;
                    // $netSales = $hargaMenu - $disTotal - $hargaRefund;
                    $hargaTotal = 0;
                    foreach ($hargaData as $data) {
                        $hargaTotal += $data['harga'];
                    }
                    $netSales = $hargaTotal - $disTotal - $hargaRefund;
                    $kategori[] = [
                        'Name' => $cat,
                        'itemSold' => $qtySold,
                        'itemrefund' => $qtyRefund,
                        'GrossSalse' => $hargaTotal,
                        'Discount' => $disTotal,
                        'Refund' => $hargaRefund,
                        'data Harga' => $hargaTotal,
                        'NetSales' => $netSales
                    ];
                }


                foreach ($groupModifier as $modCat) {

                    $IdModCat = $modCat->id;


                    $hargaModCat = Additional_menu_detail::whereHas('optional_Add.groupModif', function ($query) use ($IdModCat) {
                        $query->where('id', $IdModCat);
                    })->sum('total');

                    $qtySoldModcat = Additional_menu_detail::whereHas('optional_Add.groupModif', function ($query) use ($IdModCat) {
                        $query->where('id', $IdModCat);
                    })->sum('qty');

                    $qtyRefundMod = AdditionalRefund::whereHas('additionOps.groupModif', function ($query) use ($IdModCat) {
                        $query->where('id', $IdModCat);
                    })->where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
                        ->sum('qty');

                    $nominalRefund = AdditionalRefund::whereHas('additionOps.groupModif', function ($query) use ($IdModCat) {
                        $query->where('id', $IdModCat);
                    })->where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)
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
            } else {
                $Bulan = Carbon::now()->isoFormat('MM');

                foreach ($subcategory as $cat) {
                    $idCat = $cat->id;
                    $hargaData = [];

                    $hargaMenu = DetailOrder::whereHas('menu.subKategori', function ($query) use ($idCat) {
                        $query->where('id', $idCat);
                    })->where('created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->get();
                    //  dd($hargaMenu);
                    foreach ($hargaMenu as $data) {
                        //
                        $subTotal = 0;
                        $harga = $data->harga;
                        $qty = $data->qty;
                        $total = $harga * $qty;
                        $subTotal += $total;

                        $hargaData[] = [
                            'harga' =>  $subTotal
                        ];
                    }

                    // $hargaData = collect($hargaData);

                    $qtySold = DetailOrder::whereHas('menu.subKategori', function ($query) use ($idCat) {
                        $query->where('id', $idCat);
                    })->where('created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->sum('qty');

                    $discount = Discount_detail_order::whereHas('Detail_order.menu.subKategori', function ($query) use ($idCat) {
                        $query->where('id', $idCat);
                    })->where('created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->sum('total_discount');

                    $qtyRefund = RefundOrderMenu::whereHas('menu.subKategori', function ($query) use ($idCat) {
                        $query->where('id', $idCat);
                    })->where('created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->sum('qty');

                    $hargaRefund = RefundOrderMenu::whereHas('menu.subKategori', function ($query) use ($idCat) {
                        $query->where('id', $idCat);
                    })->where('created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->sum('refund_nominal');

                    $discountRef = DiscountMenuRefund::whereHas('menu.subKategori', function ($query) use ($idCat) {
                        $query->where('id', $idCat);
                    })->where('created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->sum('nominal_dis');

                    // $harga = $qtySold * $hargaMenu;
                    $totalRefund = $hargaRefund * $qtyRefund;

                    $disTotal = $discount - $discountRef;
                    //$netSales = $hargaMenu - $disTotal - $hargaRefund;
                    $hargaTotal = 0;
                    foreach ($hargaData as $data) {
                        $hargaTotal += $data['harga'];
                    }

                    $netSales = $hargaTotal - $disTotal - $hargaRefund;

                    $kategori[] = [
                        'Name' => $cat,
                        'itemSold' => $qtySold,
                        'itemrefund' => $qtyRefund,
                        'GrossSalse' => $hargaTotal,
                        'Discount' => $disTotal,
                        'Refund' => $hargaRefund,
                        'data Harga' => $hargaTotal,
                        'NetSales' => $netSales
                    ];
                }


                foreach ($groupModifier as $modCat) {

                    $IdModCat = $modCat->id;

                    $hargaModCat = Additional_menu_detail::where('additional_menu.created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->whereHas('optional_Add.groupModif', function ($query) use ($IdModCat) {
                            $query->where('id', $IdModCat);
                        })->sum('additional_menu.total');


                    $qtySoldModcat = Additional_menu_detail::where('additional_menu.created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->whereHas('optional_Add.groupModif', function ($query) use ($IdModCat) {
                            $query->where('id', $IdModCat);
                        })->sum('additional_menu.qty');


                    $qtyRefundMod = AdditionalRefund::whereHas('additionOps.groupModif', function ($query) use ($IdModCat) {
                        $query->where('id', $IdModCat);
                    })->where('created_at', 'LIKE', '%-' . $Bulan . '-%')
                        ->sum('qty');

                    $nominalRefund = AdditionalRefund::whereHas('additionOps.groupModif', function ($query) use ($IdModCat) {
                        $query->where('id', $IdModCat);
                    })->where('created_at', 'LIKE', '%-' . $Bulan . '-%')
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
                        'NetSales' => $NetSales,
                        //'dataHarga' => $data
                    ];
                }
            }

            return Excel::download(new CategoryExport(
                $kategori,
                $modifier,
                $totalItemSoldMenu,
                $totalItemRefundMenu,
                $totalGrossMenu,
                $totalDiscountMenu ,
                $totalRefundMenu ,
                $totalNetMenu ,
                $totalItemSoldAdds ,
                $totalItemRefundAdds ,
                $totalGrossAdds ,
                $totalDiscountAdds ,
                $totalRefundAdds ,
                $totalNetAdds ,
                $totalNominalKat,
            ), 'Laporan Categori Sales.xlsx');
         }else{
            return redirect()->route('login');
         }

    }
}

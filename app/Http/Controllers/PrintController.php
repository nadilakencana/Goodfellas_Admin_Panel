<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Sentinel;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Symfony\Component\Process\Process;
use App\Models\Orders;
use App\Models\DetailOrder;
use App\Models\Discount_detail_order;
use App\Models\Additional_menu_detail;
use App\Models\TaxOrder;
use App\Models\Cash;
use App\Models\Sift;
use App\Models\TypePayment;
use App\Models\DiscountMenuRefund;
use App\Models\RefundOrderMenu;
use App\Models\Taxes;
use Illuminate\Support\Facades\DB;
use charlieuki\ReceiptPrinter\ReceiptPrinter as ReceiptPrinter;


class PrintController extends Controller
{
	
	public function printBill($id){
		if(Sentinel::check()){
			$order = Orders::where('id', $id)->first();
            $detail = DetailOrder::where('id_order', $order->id)->get();
			$taxList = TaxOrder::where('id_order', $id)->get();
			
			$mid = '';
			$store_name = '';
			$store_address = '';
			$store_phone = '';
			$store_email = '';
			$store_website = '';
			$printLoc = '';
			$receiptNo = $order->kode_pemesanan;
			if(isset($order->no_meja)){
				$tableNo = $order->no_meja;
			}else{
				$tableNo = '-';
			}
			$subtotal = $order->subtotal;
			$pb1 = 0;
			$service = 0;
			$total = $order->total_order;
			
			$itemToPrint = [];
			$taxesInfo = [];
			
			foreach($detail as $dtl){
				$obj = [];
				$obj['name'] = $dtl->menu->nama_menu;
				$obj['qty']= $dtl->qty;
				$obj['price'] = $dtl->total;
				$obj['addition'] = [];
				
				if(!empty($dtl->id_sales_type)){
					$obj['type'] = $dtl->salesType->name;
				}else{
					$obj['type'] = '-';
				}
				
				$obj['note'] = $dtl->catatan;
				if(!empty($dtl->id_varian)){
					$obj['varian'] = $dtl->varian->nama ;
				}else{
					$obj['varian'] = '';
				}
				
				$obj['discount'] = [];
				foreach($dtl->Discount_menu_order as $diskon){
					$diskObj = [];
					$diskObj['text'] = $diskon->discount->nama;
					$diskObj['nominal'] = $diskon->total_discount;
					array_push($obj['discount'], $diskObj);
				}
				
				foreach($dtl->AddOptional_order as $adds){
					$addObj = [];
					$addObj['text'] = $adds->optional_Add->name;
					$addObj['nominal'] = $adds->total;
					
					array_push($obj['addition'], $addObj);
				}
				
				array_push($itemToPrint, $obj);
			}
			
			foreach($taxList as $taxes){
				$taxObj = [];
				$taxObj['text'] = $taxes->tax->nama.' '.$taxes->tax->tax_rate.' %';
				$taxObj['nominal'] = 'Rp. '.$taxes->total_tax;
				
				array_push($taxesInfo, $taxObj);
			}
			
			$bookInfo = [];
			if(isset($order->id_booking)){
				$sisa = $order->total_order - $order->booking->nominal_dp;
				$bookInfo["nominal"] = $order->booking->nominal_dp;
				$bookInfo["sisa"] = $sisa;
				if($sisa > 0){
					$bookInfo["text"] = "Sisa Bayar";
				}else{
					$bookInfo["text"] = "Lebih Bayar";
				}
			}
			
			$paymentInfo = [];
			if(isset($order->id_type_payment)){
				$paymentInfo['text'] = $order->payment->nama;
				$paymentInfo['nominal'] = $order->cash;
				$paymentInfo['change'] = $order->change_;
			}
			
			
			$printer = new ReceiptPrinter;
			$printer->init(
				config('receiptprinter.connector_type'),
				'192.168.1.37'
			);
			// $printer->init(
			// 	config('receiptprinter.connector_type'),
			// 	'192.168.0.89'
			// );
			
			$printer->setStore(
				$mid, 
				$store_name, 
				$store_address, 
				$store_phone, 
				$store_email, 
				$store_website, 
				$receiptNo, 
				$tableNo, 
				$subtotal, 
				$pb1, 
				$service, 
				$total,
				$printLoc
			);
			
			foreach ($itemToPrint as $item) {
				$printer->addItem(
					$item['name'],
					$item['qty'],
					$item['price'],
					$item['addition'],
					$item['type'],
					$item['note'],
					$item['varian'],
					$item['discount']
				);
			}
			
			//dd($itemToPrint);
			
			$printer->printBill(true, $taxesInfo, $paymentInfo, $bookInfo);
			if($order->id_type_payment !== null){
				$printer->printBill(true, $taxesInfo, $paymentInfo, $bookInfo);
			}
			return response()->json([
				'success' => 1,
				'message' => 'succesfully print bill',
				'data' => $itemToPrint
			]);
			
		}else{
			return redirect()->route('login');
        }
	}
	
	public function printTicket($id){
		if(Sentinel::check()){
			$order = Orders::where('id', $id)->first();
            $detail = DetailOrder::where('id_order', $order->id)->where(function ($query) use ($order) {
                $query->whereRaw('created_at > (SELECT IFNULL(MAX(last_print), 0) FROM detail_order WHERE id_order = '.$order->id.')')
                    ->orWhereNull('last_print');
			})->get();
			
			$totalDetail = DetailOrder::where('id_order', $order->id)->orWhereNull('last_print')->get();
			
			$mid = '';
			$store_name = '';
			$store_address = '';
			$store_phone = '';
			$store_email = '';
			$store_website = '';
			$receiptNo = $order->kode_pemesanan;
			if(isset($order->no_meja)){
				$tableNo = $order->no_meja;
			}else{
				$tableNo = '-';
			}
			// dd($tableNo);
			$subtotal = 0;
			$pb1 = 0;
			$service = 0;
			$total = 0;
			$printLoc = 'Tiket';

			$itemToPrint = [];
			// dd($detail);
			// if(count($detail) > 0){
				foreach($detail as $dtl){
					$obj = [];
					$obj['name'] = $dtl->menu->nama_menu;
					$obj['qty']= $dtl->qty;
					$obj['price'] = '';
					$obj['addition'] = [];
					$obj['type'] = $dtl->salesType->name;
					$obj['note'] = $dtl->catatan;
					if(!empty($dtl->id_varian)){
						$obj['varian'] = $dtl->varian->nama ;
					}else{
						$obj['varian'] = '';
					}
					
					$obj['discount'] = [];
					foreach($dtl->AddOptional_order as $adds){
						$addObj = [];
						$addObj['text'] = $adds->optional_Add->name;
						$addObj['nominal'] = '';
						
						array_push($obj['addition'], $addObj);
					}
					
					array_push($itemToPrint, $obj);
				}
			// }else{
			// 	foreach($totalDetail as $dtl){
			// 		$obj = [];
			// 		$obj['name'] = $dtl->menu->nama_menu;
			// 		$obj['qty']= $dtl->qty;
			// 		$obj['price'] = '';
			// 		$obj['addition'] = [];
			// 		$obj['type'] = $dtl->salesType->name;
			// 		$obj['note'] = $dtl->catatan;
			// 		if(!empty($dtl->id_varian)){
			// 			$obj['varian'] = $dtl->varian->nama ;
			// 		}else{
			// 			$obj['varian'] = '';
			// 		}
					
			// 		$obj['discount'] = [];
			// 		foreach($dtl->AddOptional_order as $adds){
			// 			$addObj = [];
			// 			$addObj['text'] = $adds->optional_Add->name;
			// 			$addObj['nominal'] = '';
						
			// 			array_push($obj['addition'], $addObj);
			// 		}
					
			// 		array_push($itemToPrint, $obj);
			// 	}
			// }
			
			if (count($itemToPrint) !== 0){
				$printer = new ReceiptPrinter;
				$printer->init(
					config('receiptprinter.connector_type'), // network 
					'192.168.1.37' // ke ip printer kasir
				);
				// $printer->init(
				// 	config('receiptprinter.connector_type'), // network 
				// 	'192.168.0.89' // ke ip printer kasir
				// );
			
				$printer->setStore(
					$mid, 
					$store_name, 
					$store_address, 
					$store_phone, 
					$store_email, 
					$store_website, 
					$receiptNo, 
					$tableNo,
					$subtotal, 
					$pb1, 
					$service, 
					$total,
					$printLoc
				);
				
				foreach ($itemToPrint as $item) {
					$printer->addItem(
						$item['name'],
						$item['qty'],
						$item['price'],
						$item['addition'],
						$item['type'],
						$item['note'],
						$item['varian'],
						$item['discount']
					);
				}
				
				if(count($totalDetail) !== count($detail)){
				
					$printer->printAdditional();
					
				}else{
					$printer->printAdditional();
				}
				
				return response()->json([
					'success' => 1,
					'message' => 'succesfully print ticket',
					'data' => $itemToPrint
				]);
			}else{
				return response()->json([
					'success' => 1,
					'message' => 'Tidak Ada Order Update untuk di print',
					
				]);
			}
			
			
			
		}else{
			return redirect()->route('login');
        }
	}
	
	public function printUlangKitchen($id){
        if(Sentinel::check()){

            $order = Orders::where('id', $id)->first();
            $detail = DetailOrder::where('id_order', $order->id)->whereHas('menu', function ($query) {
                $query->where('id_kategori', 2);
            })->get();
			
			$mid = '';
			$store_name = '';
			$store_address = '';
			$store_phone = '';
			$store_email = '';
			$store_website = '';
			$receiptNo = $order->kode_pemesanan;
			$tableNo = $order->no_meja;
			$subtotal = 0;
			$pb1 = 0;
			$service = 0;
			$total = 0;
			$printLoc = 'Kitchen';
			$itemToPrint = [];
			foreach($detail as $dtl){
				$obj = [];
				$obj['name'] = $dtl->menu->nama_menu;
				$obj['qty']= $dtl->qty;
				$obj['price'] = '';
				$obj['addition'] = [];
				$obj['type'] = $dtl->salesType->name;
				$obj['note'] = $dtl->catatan;
				if(!empty($dtl->id_varian)){
					$obj['varian'] = $dtl->varian->nama ;
				}else{
					$obj['varian'] = '';
				}
				
				$obj['discount'] = '';
				foreach($dtl->AddOptional_order as $adds){
					$addObj = [];
					$addObj['text'] = $adds->optional_Add->name;
					$addObj['nominal'] = '';
					
					array_push($obj['addition'], $addObj);
				}
				
				array_push($itemToPrint, $obj);
			}
			
			
			if(count($itemToPrint) == 0){
				return response()->json([
					'success' => 1,
					'message' => 'tidak ada data kitchen',
				]);
			}else{
				$printer = new ReceiptPrinter;
				$printer->init(
					config('receiptprinter.connector_type'),
					'192.168.1.37'
				);
				// $printer->init(
				// 	config('receiptprinter.connector_type'),
				// 	'192.168.0.78'
				// );
				$printer->setStore(
					$mid, 
					$store_name, 
					$store_address, 
					$store_phone, 
					$store_email, 
					$store_website, 
					$receiptNo, 
					$tableNo,
					$subtotal,
					$pb1, 
					$service, 
					$total,
					$printLoc
				);
			
				foreach ($itemToPrint as $item) {
					$printer->addItem(
						$item['name'],
						$item['qty'],
						$item['price'],
						$item['addition'],
						$item['type'],
						$item['note'],
						$item['varian'],
						$item['discount']
					);
				}
				
				if(count($totalDetail) !== count($details)){
					$printer->printKitchen();
				}else{
					$printer->printKitchen();
				}
				
				return response()->json([
					'success' => 1,
					'message' => 'succesfully print kitchen order',
					'data' => $itemToPrint
				]);
			}
        }else{
             return redirect()->route('login');
        }
    }
	
	public function printKitchen($id){
        if(Sentinel::check()){

            $order = Orders::where('id', $id)->first();
            //$detail = DetailOrder::where('id_order', $order->id)->get();
			$lastPrint = DetailOrder::where('id_order', $order->id)->max('last_print');
			if(is_null($lastPrint)) $lastPrint = 0;
			
			//$detail = DetailOrder::leftJoin('menu', 'detail_order.id_menu', '=', 'menu.id')->where('detailOrder.created_at', '>', $lastPrint)->where('menu.id_kategori', 2)->get();
			
            $details = DetailOrder::where('id_order', $order->id)
            ->where(function ($query) use ($order) {
                $query->whereRaw('created_at > (SELECT IFNULL(MAX(last_print), 0) FROM detail_order WHERE id_order = '.$order->id.')')
                    ->orWhereNull('last_print');
            
                })->whereHas('menu', function ($query) {
                $query->where('id_kategori', 2);
            })->get();
			
			$totalDetail = DetailOrder::where('id_order', $order->id)->orWhereNull('last_print')->get();
			
			$mid = '';
			$store_name = '';
			$store_address = '';
			$store_phone = '';
			$store_email = '';
			$store_website = '';
			$receiptNo = $order->kode_pemesanan;
			if(isset($order->no_meja)){
				$tableNo = $order->no_meja;
			}else{
				$tableNo = '-';
			}
			$subtotal = 0;
			$pb1 = 0;
			$service = 0;
			$total = 0;
			$printLoc = 'Kitchen';

			$itemToPrint = [];
			foreach($details as $dtl){
				$obj = [];
				$obj['name'] = $dtl->menu->nama_menu;
				$obj['qty']= $dtl->qty;
				$obj['price'] = '';
				$obj['addition'] = [];
				$obj['type'] = $dtl->salesType->name;
				$obj['note'] = $dtl->catatan;
				if(!empty($dtl->id_varian)){
					$obj['varian'] = $dtl->varian->nama ;
				}else{
					$obj['varian'] = '';
				}
				
				$obj['discount'] = '';
				foreach($dtl->AddOptional_order as $adds){
					$addObj = [];
					$addObj['text'] = $adds->optional_Add->name;
					$addObj['nominal'] = '';
					
					array_push($obj['addition'], $addObj);
				}
				
				array_push($itemToPrint, $obj);
			}
			
			if(count($itemToPrint) == 0){
				return response()->json([
					'success' => 1,
					'message' => 'tidak ada data kitchen',
				]);
			}else{
				$printer = new ReceiptPrinter;
				$printer->init(
					config('receiptprinter.connector_type'),
					'192.168.1.37'
				);
				// $printer->init(
				// 	config('receiptprinter.connector_type'),
				// 	'192.168.0.78'
				// );
				$printer->setStore(
					$mid, 
					$store_name, 
					$store_address, 
					$store_phone, 
					$store_email, 
					$store_website, 
					$receiptNo, 
					$tableNo,
					$subtotal, 
					$pb1, 
					$service, 
					$total,
					$printLoc
				);
			
				foreach ($itemToPrint as $item) {
					$printer->addItem(
						$item['name'],
						$item['qty'],
						$item['price'],
						$item['addition'],
						$item['type'],
						$item['note'],
						$item['varian'],
						$item['discount']
					);
				}
				
				if(count($totalDetail) !== count($details)){
					$printer->printKitchen();
				}else{
					$printer->printKitchen();
				}
				
				return response()->json([
					'success' => 1,
					'message' => 'succesfully print kitchen order',
					'data' => $itemToPrint
				]);
			}
			
			
			
			//$items = new Array();

        }else{
             return redirect()->route('login');
        }
    }
	
	public function printShiftThermal($id){
		if(Sentinel::check()){
			
			$total_refund = 0;
            $total_pengeluaran = 0;
            $total_pemasukan = 0;
			
			$sift = Sift::findOrFail($id);
			
			$kas_out = Cash::where('id_sift', $id)->where('type', 'out-kas')->whereDate('tanggal', $sift->start_time)->get();
            $kas_in = Cash::where('id_sift', $id)->where('type', 'in-kas')->whereDate('tanggal', $sift->start_time)->get();
            $modal = Cash::where('id_sift', $id)->where('type','Start Sift')->whereDate('tanggal', $sift->start_time)->first();
            $endingSift = Cash::where('id_sift', $id)->where('type', 'End Sift')->whereDate('tanggal', $sift->start_time)->first();

            $cashtype = Orders::where('id_type_payment', 2)->where('deleted', 0)->whereDate('tanggal', $sift->start_time)->groupBy('tanggal')->sum('total_order');
            $bankTF = Orders::where('id_type_payment', 5)->where('deleted', 0)->whereDate('tanggal', $sift->start_time)->groupBy('tanggal')->sum('total_order');
            $grab = Orders::where('id_type_payment', 4)->where('deleted', 0)->whereDate('tanggal', $sift->start_time)->groupBy('tanggal')->sum('total_order'); 
            $ovo = Orders::where('id_type_payment', 3)->where('deleted', 0)->whereDate('tanggal', $sift->start_time)->groupBy('tanggal')->sum('total_order');
            $mandiri = Orders::where('id_type_payment', 6)->where('deleted', 0)->whereDate('tanggal', $sift->start_time)->groupBy('tanggal')->sum('total_order');
            $BCA = Orders::where('id_type_payment', 7)->where('deleted', 0)->whereDate('tanggal', $sift->start_time)->groupBy('tanggal')->sum('total_order');
			
			foreach($kas_in as $in){
				$total_pemasukan+=$in->nominal;
			}
			foreach($kas_out as $out){
				$total_pengeluaran+=$out->nominal;
			}
			
			$date = Carbon::now()->format('Y-m-d');
            $total_itemSold = 0;
            $total_itmRefund = 0;
            $refund_cash= 0;
            $EDCRefund = 0;
            $other_Refund = 0;   
            
			$order = Orders::whereDate('tanggal', $sift->start_time)->where('id_status', 2)->where('deleted', 0)->get();
         
			//get menu detail yang di jual
			$menu = DetailOrder::join('orders','detail_order.id_order','=','orders.id')
			->whereDate('orders.tanggal', $sift->start_time)
			->where('orders.id_status', 2 )
			->where('orders.deleted', 0)
			->get();
              
            //get data retur menu 
            $menu_retur = RefundOrderMenu::join('orders','refund_menu_order.id_order','=','orders.id')
            ->whereDate('orders.tanggal', $sift->start_time)->get();
           
            //total sales
            $itmsum = DetailOrder::join('orders','detail_order.id_order','=','orders.id')
            ->whereDate('orders.tanggal', $sift->start_time)
            ->where('orders.id_status', 2)
            ->where('orders.deleted', 0)
            ->sum('qty');
            
            //total qty yang refundrefund_menu_order
            $SumRefund = RefundOrderMenu::join('orders','refund_menu_order.id_order','=','orders.id')
            ->whereDate('orders.tanggal', $sift->start_time)->sum('qty');
            
            //total nominal refund payment cash
            $sumTotalCashRefund = RefundOrderMenu::whereHas('order', function($query) use ($sift) {
                                    $query->where('id_type_payment', 2)
                                        ->whereDate('tanggal', $sift->start_time);
                                })->sum('refund_nominal');
            
             //total nominal refund payment BCA
            $sumTotalBCA = RefundOrderMenu::whereHas('order', function($query) use ($sift) {
                                $query->where('id_type_payment', 7)
                                    ->whereDate('tanggal', $sift->start_time);
                            })->sum('refund_nominal');
             
             //total nominal refund payment Mandiri
            $sumTotalMandiri = RefundOrderMenu::whereHas('order', function($query) use ($sift) {
                                    $query->where('id_type_payment', 6)
                                        ->whereDate('tanggal', $sift->start_time);
                                })->sum('refund_nominal');
             
             //total nominal refund payment Ovo
            $sumTotalOvo = RefundOrderMenu::whereHas('order', function($query) use ($sift) {
                                $query->where('id_type_payment', 3)
                                    ->whereDate('tanggal', $sift->start_time);
                            })->sum('refund_nominal');
             
             //total nominal refund payment Bank Transfer
            $sumTotalbankTf = RefundOrderMenu::whereHas('order', function($query) use ($sift) {
                                    $query->where('id_type_payment', 5)
                                        ->whereDate('tanggal', $sift->start_time);
                                })->sum('refund_nominal');


            $taxpb1 = Taxes::where('nama', 'PB1')->first();
            $service = Taxes::where('nama', 'Service Charge')->first();  
            $PB1 = $taxpb1->tax_rate / 100;
            $Service = $service->tax_rate / 100;
                            
            // EDC
            $EDCRefund = $sumTotalBCA + $sumTotalMandiri;
            $nominalPb1EDC =  $EDCRefund * $PB1;
            $nominalServiceEDC = $EDCRefund * $Service;
                            
            // Cash
            $refund_cash = $sumTotalCashRefund;
            $nominalPb1Cash = $refund_cash * $PB1;
            $nominalServiceCash = $refund_cash * $Service ;
                
            // other
            $other_Refund =  $sumTotalOvo +  $sumTotalbankTf;
            $nominalPb1Other = $other_Refund * $PB1;
            $nominalServiceOther = $other_Refund * $Service ;
                    
            $grendRefunEDC = $EDCRefund + $nominalPb1EDC + $nominalServiceEDC;
            $grendRefundCash = $refund_cash +  $nominalPb1Cash +  $nominalServiceCash;
            $grendOther = $other_Refund +  $nominalPb1Other +  $nominalServiceOther;
			
			//create shift info
			$shiftInfo = [];
			$shiftInfo['shift_name'] = $sift->admin->nama;
			$shiftInfo['start_shift'] = $sift->start_time.' '.date("H:i", strtotime($sift->created_at));
			$shiftInfo['end_shift'] = '-';
			if(!empty($sift->end_time)){
				$shiftInfo['end_shift'] = $sift->end_time.' '.date("H:i", strtotime($sift->updated_at));
			}
			
			//create sales info
			$salesInfo = [];
			$salesInfo['soldList'] = [];
			$salesInfo['refundList'] = [];
			$salesInfo['sold_item'] = $itmsum;
			$salesInfo['refund_item'] = $SumRefund;
			
			// foreach($menu->sortBy('menu.nama_menu')->groupBy('menu.nama_menu') as $namaMenu => $items){
			// 	$menuName = [];
			// 	$menuName['text'] = $namaMenu;
			// 	$menuName['qty'] = $items->sum('qty');
				
			// 	array_push($salesInfo['soldList'], $menuName);
			// }
			foreach($menu->sortBy('menu.nama_menu')->groupBy(function ($item) {
				return $item->menu->nama_menu . ($item->varian ? ' (' . $item->varian->nama . ')' : '');
			}) as $namaMenu => $items) {
				$menuName = [];
				$menuName['text'] = $namaMenu; 
				$menuName['qty'] = $items->sum('qty'); 
			
				array_push($salesInfo['soldList'], $menuName); 
			}
			
			foreach($menu_retur->sortBy('menu.nama_menu')->groupBy(function ($item) {
				return $item->menu->nama_menu . ($item->varian ? ' (' . $item->varian->nama . ')' : '');
			}) as $namaMenu => $items) {
				$menuName = [];
				$menuName['text'] = $namaMenu; 
				$menuName['qty'] = $items->sum('qty'); 
			
				array_push($salesInfo['refundList'], $obj); 
			}
			
			// foreach($menu_retur->sortBy('menu.nama_menu')->groupBy('menu.nama_menu') as $namaMenu => $items){
			// 	$obj = [];
			// 	$obj['text'] = $namaMenu;
			// 	$obj['qty'] = $items->sum('qty');
				
			// 	array_push($salesInfo['refundList'], $obj);
			// }
			
			//create cash flow info
			$cashFlowInfo = [];
			$cashFlowInfo['start'] = 0;
			$cashFlowInfo['sales'] = 0;
			$cashFlowInfo['refund'] = 0;
			$cashFlowInfo['expense'] = 0;
			$cashFlowInfo['expense_list'] = [];
			$cashFlowInfo['income'] = 0;
			$cashFlowInfo['income_list'] = [];
			$cashFlowInfo['expectedEnd'] = 0;
			$cashFlowInfo['actualEnd'] = 0;
			
			if(empty($modal->nominal)){
				$cashFlowInfo['start'] = 0;
			}else{ 
				$cashFlowInfo['start'] = number_format($modal->nominal,0,',','.');
			}
			
			$cashFlowInfo['sales'] = number_format($cashtype, 0,',','.');
			$cashFlowInfo['refund'] = number_format($grendRefundCash, 0,',','.');
			$cashFlowInfo['expense'] = number_format( $total_pengeluaran, 0,'.','.');
			foreach($kas_out as $kas){
				$obj = [];
				$obj['text'] = $kas->deskripsi;
				$obj['nominal'] = number_format($kas->nominal, 0,'.','.');
				array_push($cashFlowInfo['expense_list'], $obj);
			}
			
			$cashFlowInfo['income'] = number_format($total_pemasukan, 0,'.','.');
			foreach($kas_in as $kas){
				$obj = [];
				$obj['text'] = $kas->deskripsi;
				$obj['nominal'] = number_format($kas->nominal, 0,'.','.');
				array_push($cashFlowInfo['income_list'], $obj);
			}
			
			$total_sift = ($modal->nominal + $cashtype + $total_pemasukan) -$total_pengeluaran - $grendRefundCash;
			$cashFlowInfo['expectedEnd'] = number_format($total_sift, 0,',','.');
			if(empty($endingSift->nominal)){
				$cashFlowInfo['actualEnd'] = 0;
			}else{
				$cashFlowInfo['actualEnd'] = number_format($endingSift->nominal,0,',','.');
			}
			
			$cashPaymentInfo = [];
			$cashPaymentInfo['sales'] = number_format($cashtype, 0,',','.');
			$cashPaymentInfo['refund'] = number_format($grendRefundCash, 0,',','.') ;
			$total_cash = $cashtype - $grendRefundCash;
			$cashPaymentInfo['expected'] = number_format($total_cash, 0,',','.');
			
			//$deliveryInfo, $edcInfo, $otherInfo, $totalActualInfo
			
			$deliveryInfo = [];
			$deliveryInfo['grab'] = number_format($grab, 0,',','.');
			$deliveryInfo['expected'] = number_format($grab, 0,',','.');
			$total_online = $grab;
			
			$edcInfo = [];
			$edcInfo['bca'] = 0;
			$edcInfo['mandiri'] = 0;
			$edcInfo['edc_refund'] = 0;
			$edcInfo['expected'] = 0;
			
			$edcInfo['bca'] = number_format($BCA, 0,',','.');
			$edcInfo['mandiri'] = number_format($mandiri, 0,',','.');
			$edcInfo['edc_refund'] = number_format($grendRefunEDC, 0,',','.');
			$edcInfo['expected'] = number_format(( $BCA + $mandiri) - $grendRefunEDC, 0,',', '.');
			$total_EDC =( $BCA + $mandiri) - $grendRefunEDC;
			
			$otherInfo = [];
			$otherInfo['ovo'] = 0;
			$otherInfo['bank_transfer'] = 0;
			$otherInfo['other_refund'] = 0;
			$otherInfo['expected'] = 0;
			
			$otherInfo['ovo'] = number_format($ovo, 0,',','.');
			$otherInfo['bank_transfer'] = number_format($bankTF, 0,',','.');
			$otherInfo['other_refund'] = number_format($grendOther, 0,',','.');
			$otherInfo['expected'] = number_format(($ovo + $bankTF) - $grendOther, 0,',','.');
			$total_other_payment = ($ovo + $bankTF) - $grendOther;
			
			$totalActualInfo = [];
			$totalActualInfo['expected'] = 0;
			$totalActualInfo['actual'] = 0;
			$totalActualInfo['difference'] = 0;
			$total_expected = $total_sift  + $total_online + $total_EDC + $total_other_payment;
            $total_actual = $total_expected;
	
			$totalActualInfo['expected'] = number_format($total_expected, 0, ',', '.');
			$totalActualInfo['actual'] = number_format($total_expected, 0, ',', '.');
			
			
			//dd($shiftInfo, $salesInfo, $cashFlowInfo, $cashPaymentInfo, $deliveryInfo, $edcInfo, $otherInfo, $totalActualInfo);
			
			//inisiasi printer
			$printer = new ReceiptPrinter;
			$printer->init(
				config('receiptprinter.connector_type'),
				'192.168.0.89'
			);
			
			$printer->printSift($shiftInfo, $salesInfo, $cashFlowInfo, $cashPaymentInfo, $deliveryInfo, $edcInfo, $otherInfo, $totalActualInfo);
			
			return response()->json([
				'success' => 1,
				'message' => 'succesfully print shift report',
			]);
		}else{
		  return redirect()->route('login');     
		}
	}
	
	public function printDataItemDeleteThermal(Request $request){
		if(Sentinel::check()){
			$order = Orders::where('id',$request->id_order)->first();
			$detailOrder = DetailOrder::where('id', $request->id)->get();
			
			$mid = '';
			$store_name = '';
			$store_address = '';
			$store_phone = '';
			$store_email = '';
			$store_website = '';
			$receiptNo = $order->kode_pemesanan;
			$tableNo = $order->no_meja;
			$subtotal = 0;
			$pb1 = 0;
			$service = 0;
			$total = 0;
			$printLoc = '';
			$itemToPrint = [];
			if(count($detailOrder) > 0){
				foreach($detailOrder as $dtl){
					$obj = [];
					$obj['name'] = $dtl->menu->nama_menu;
					$obj['qty']= $dtl->qty;
					$obj['price'] = '';
					$obj['addition'] = [];
					$obj['type'] = $dtl->salesType->name;
					$obj['note'] = $dtl->catatan;
					if(!empty($dtl->id_varian)){
						$obj['varian'] = $dtl->varian->nama ;
					}else{
						$obj['varian'] = '';
					}
					
					$obj['discount'] = [];
					foreach($dtl->AddOptional_order as $adds){
						$addObj = [];
						$addObj['text'] = $adds->optional_Add->name;
						$addObj['nominal'] = '';
						
						array_push($obj['addition'], $addObj);
					}
					
					array_push($itemToPrint, $obj);
				}
			}
			
			$printer = new ReceiptPrinter;
			$printer->init(
				config('receiptprinter.connector_type'),
				'192.168.1.37',
			);
			// $printer->init(
			// 	config('receiptprinter.connector_type'),
			// 	'192.168.0.78',
			// );
			
			$printer->setStore(
				$mid, 
				$store_name, 
				$store_address, 
				$store_phone, 
				$store_email, 
				$store_website, 
				$receiptNo, 
				$subtotal, 
				$tableNo, 
				$pb1, 
				$service, 
				$total,
				$printLoc
			);
			
			foreach ($itemToPrint as $item) {
				$printer->addItem(
					$item['name'],
					$item['qty'],
					$item['price'],
					$item['addition'],
					$item['type'],
					$item['note'],
					$item['varian'],
					$item['discount']
				);
			}
			

			$printer->printCancel();
			$deletedItem = $detailOrder;
			//$detailOrder->delete();
			return response()->json([
				'success' => 1,
				'message' => 'Print cancel order success',
				//'data' => $file,
				'detailItem' => $deletedItem,
			]);
		}else{
			return redirect()->route('login');
		}
	}
	
	public function testOpenNotepad(Request $request){

		$process = Process::fromShellCommandline('notepad.exe /P C:\Users\nadil\Dropbox\Dropbox GFI\cms.goodfellas\public\asset\assets\file_print\data.txt');
		//untuk notepad.exe nya tinggal diganti jadi python.exe ./namascript(kitchen / nota ) namafile
		$process->start();

		$process->waitUntil(function ($type, $output) {
			return response()->json([
				'success' => 1,
				'message' => 'Notepad Dibuka',
			]);
		});
	}
	
	public function testPembatalanKitchen(Request $request){
		$logo = "asset\assets\image\logo_small_p.png";
		
		$mid = '';
		$store_name = '';
		$store_address = '';
		$store_phone = '';
		$store_email = '';
		$store_website = '';
		$receiptNo = 'gNcdJ';
		$tableNo = '10';
		$subtotal = 0;
		$pb1 = 0;
		$service = 0;
		$total = 0;
		
		
		$printer = new ReceiptPrinter;
		$printer->init(
			config('receiptprinter.connector_type'),
			'192.168.0.89'
		);
		
		$printer->setLogo($logo);
		
		$printer->setStore($mid, $store_name, $store_address, $store_phone, $store_email, $store_website, $receiptNo, $tableNo, $subtotal, $pb1, $service, $total);
		
		$items = [
			[
				'name' => 'Indomie Rawon Pedas',
				'qty' => 1,
				'price' => '',
				'addition' => [
					[ 
						'text' => 'Telor Ceplok',
						'nominal' => '',
					]
				],
				'type' => 'Dine In',
				'note' => 'testing',
				'varian' => 'Rawon',
				'discount' => []
			]
		];
		
		foreach ($items as $item) {
			$printer->addItem(
				$item['name'],
				$item['qty'],
				$item['price'],
				$item['addition'],
				$item['type'],
				$item['note'],
				$item['varian'],
				$item['discount']
			);
		}
		
		
		
		$printer->printKitchenPembatalan();
	}
	
	public function testPrintKitchen(Request $request){
		$logo = "asset\assets\image\logo_small_p.png";
		
		$mid = '';
		$store_name = '';
		$store_address = '';
		$store_phone = '';
		$store_email = '';
		$store_website = '';
		$receiptNo = 'gNcdJ';
		$tableNo = '10';
		$subtotal = 0;
		$pb1 = 0;
		$service = 0;
		$total = 0;
		
		
		$printer = new ReceiptPrinter;
		$printer->init(
			config('receiptprinter.connector_type'),
			'192.168.0.89'
		);
		
		$printer->setLogo($logo);
		
		$printer->setStore($mid, $store_name, $store_address, $store_phone, $store_email, $store_website, $receiptNo, $tableNo, $subtotal, $pb1, $service, $total);
		
		$items = [
			[
				'name' => 'Indomie Rawon Pedas',
				'qty' => 2,
				'price' => '',
				'addition' => [
					[ 
						'text' => 'Telor Ceplok',
						'nominal' => '',
					]
				],
				'type' => 'Dine In',
				'note' => 'testing',
				'varian' => 'Rawon',
				'discount' => []
			],
			[
				'name' => 'Indomie Goreng',
				'qty' => 1,
				'price' => '',
				'addition' => [
					[ 
						'text' => 'Telur dadar / mata sapi',
						'nominal' => '',
					],
					[ 
						'text' => 'Kerupuk',
						'nominal' => '',
					]
				],
				'type' => 'Dine In',
				'note' => '',
				'varian' => '',
				'discount' => []
			],
		];
		
		foreach ($items as $item) {
			$printer->addItem(
				$item['name'],
				$item['qty'],
				$item['price'],
				$item['addition'],
				$item['type'],
				$item['note'],
				$item['varian'],
				$item['discount']
			);
		}
		
		
		
		$printer->printKitchen();
	}
	
	public function testPrintBill(Request $request){
		$logo = public_path("asset\assets\image\logo_small_p.png");
		
		$mid = '';
		$store_name = '';
		$store_address = '';
		$store_phone = '';
		$store_email = '';
		$store_website = '';
		$receiptNo = 'gNcdJ';
		$tableNo = '10';
		$subtotal = 192000;
		$pb1 = 19200;
		$service = 9600;
		$total = 172200;
		
		
		$printer = new ReceiptPrinter;
		$printer->init(
			config('receiptprinter.connector_type'),
			'192.168.88.22'
		);
		
		$printer->setStore($mid, $store_name, $store_address, $store_phone, $store_email, $store_website, $receiptNo, $tableNo, $subtotal, $pb1, $service, $total);
		
		$items = [
			[
				'name' => 'Indomie Rawon pedas',
				'qty' => 2,
				'price' => 76000,
				'addition' => [
					[ 
						'text' => 'expresso shot',
						'nominal' => 4000,
					]
				],
				'type' => 'Dine In',
				'note' => 'testing',
				'varian' => 'Cold',
				'discount' => []
			],
			[
				'name' => 'Saikoro Fried Rice',
				'qty' => 1,
				'price' => 60000,
				'addition' => [
					[ 
						'text' => 'Telur dadar / mata sapi',
						'nominal' => 8000,
					],
					[ 
						'text' => 'Saikoro',
						'nominal' => 10000,
					]
				],
				'type' => 'Dine In',
				'note' => '',
				'varian' => '',
				'discount' => [
					[
						'text' => 'Discount 1',
						'nominal' => 8100,
					],
					[
						'text' => 'Discount 2',
						'nominal' => 16200,
					],
					[
						'text' => 'Discount 3',
						'nominal' => 24300,
					],
				]
			],
			[
				'name' => 'Ayam bakar madu',
				'qty' => 1,
				'price' => 56000,
				'addition' => [
					[
					   'text'=>'Telur dadar / mata sapi',
					   'nominal' => 8000
					]
				],
				'type' => 'Dine In',
				'note' => '',
				'varian' => '',
				'discount' => []
			],
		];
		
		foreach ($items as $item) {
			$printer->addItem(
				$item['name'],
				$item['qty'],
				$item['price'],
				$item['addition'],
				$item['type'],
				$item['note'],
				$item['varian'],
				$item['discount'],
			);
		}
		
		$printer->setLogo($logo);
		
		$printer->printBill();
	}
	
	public function testPrintReceipt(Request $request){
		$mid = '123123456';
		$store_name = 'YOURMART';
		$store_address = 'Mart Address';
		$store_phone = '1234567890';
		$store_email = 'yourmart@email.com';
		$store_website = 'yourmart.com';
		$tax_percentage = 10;
		$transaction_id = 'TX123ABC456';
		$currency = 'Rp.';
		$image_path = 'logo.png';
		
		$items = [
			[
				'name' => 'French Fries (tera)',
				'qty' => 2,
				'price' => 65000,
			],
			[
				'name' => 'Roasted Milk Tea (large)',
				'qty' => 1,
				'price' => 24000,
			],
			[
				'name' => 'Honey Lime (large)',
				'qty' => 3,
				'price' => 10000,
			],
			[
				'name' => 'Jasmine Tea (grande)',
				'qty' => 3,
				'price' => 8000,
			],
		];
		
		$printer = new ReceiptPrinter;
		if($request->has('b')){
			$printer->init(
				config('receiptprinter.connector_type'),
				config('receiptprinter.connector_descriptor')
			);
		}else{
			$printer->init(
				config('receiptprinter.connector_type'), // printer iware ( network jg )
				config('receiptprinter.connector_descriptor') // ip iware
			);
		}
		
		
		$printer->setStore($mid, $store_name, $store_address, $store_phone, $store_email, $store_website, '', '');

		$printer->setCurrency($currency);
		
		foreach ($items as $item) {
			$printer->addItem(
				$item['name'],
				$item['qty'],
				$item['price']
			);
		}
		// Set tax
		$printer->setTax($tax_percentage);

		// Calculate total
		$printer->calculateSubTotal();
		$printer->calculateGrandTotal();

		// Set transaction ID
		$printer->setTransactionID($transaction_id);

		// Set logo
		// Uncomment the line below if $image_path is defined
		//$printer->setLogo($image_path);

		// Set QR code
		$printer->setQRcode([
			'tid' => $transaction_id,
		]);

		// Print receipt
		$printer->printReceipt();
	}
	
	
	public function testPageWidth(){
		$printer = new ReceiptPrinter;
		
		$printer->init(
			config('receiptprinter.connector_type'),
			config('receiptprinter.connector_descriptor')
		);
		
		$printer->testPrintPageWidth();
	}
	
	public function testPrintLogo(){
		$logo = public_path("asset\assets\image\logo_small_p.png");
		
		$printer = new ReceiptPrinter;
		
		$printer->init(
			config('receiptprinter.connector_type'),
			config('receiptprinter.connector_descriptor')
		);
		
		$printer->setLogo($logo);
		$printer->testPrintLogo();
	}
}

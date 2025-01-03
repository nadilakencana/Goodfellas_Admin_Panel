<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Cash;
use App\Models\Sift;
use App\Models\Orders;
use App\Models\TypePayment;
use Illuminate\Support\Carbon;
use App\Models\DetailOrder;
use App\Models\DiscountMenuRefund;
use App\Models\Order;
use App\Models\RefundOrder;
use App\Models\RefundOrderMenu;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use Sentinel;
use App\Models\Taxes;
use Illuminate\Support\Facades\DB;
class CashController extends Controller
{
    public function DataShift(){

        if(Sentinel::check()){
           $sift = Sift::all();
           return view('cash.dataSiftCash', compact('sift'));

       }else{
           return redirect()->route('login');
       }
      
   }

   public function startSift(Request $request){
       if(Sentinel::check()){
           $userId = Sentinel::getUser();
           $admin = $userId->id;

           $date = Carbon::now()->format('Y-m-d');

               $startSift = new Sift();
               $startSift->id_admin = $admin;
               $startSift->start_time = $date;
               $startSift->save();
           

           if($startSift->save()){

               $cash = new Cash();
               $cash->type = 'Start Sift';
               $cash->id_sift = $startSift->id;
               $cash->nominal = $request->nominal;
               $cash->tanggal = $date;
               $cash->id_admin = $admin ;
               $cash->save();

               return response()->json([
                   'success' => 1,
                   'message' => 'Data Start sift',
                   'data' => $startSift,
                   'cash' => $cash

               ]);
           }else{
               return redirect()->back()->with('error', 'Start sift Unsuccess to save');
           }


       }else{
           return redirect()->route('login');
       }
   }

   
    public function detailSift($id){
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
            $BRI = Orders::where('id_type_payment', 8)->where('deleted', 0)->whereDate('tanggal', $sift->start_time)->groupBy('tanggal')->sum('total_order');

            // dd($modal, $endingSift);
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
            $online_refund = 0;

            $order = Orders::whereDate('tanggal', $sift->start_time)->where('deleted', 0)->get();
                //get menu detail yang di jual
            $menu = DetailOrder::join('orders','detail_order.id_order','=','orders.id')
            ->whereDate('orders.tanggal', $sift->start_time)->where('orders.deleted', 0)->get();

            //get data retur menu 
            $menu_retur = RefundOrderMenu::join('orders','refund_menu_order.id_order','=','orders.id')
            ->whereDate('orders.tanggal', $sift->start_time)->get();


            //total sales
            $itmsum = DetailOrder::join('orders','detail_order.id_order','=','orders.id')
            ->whereDate('orders.tanggal', $sift->start_time)->where('orders.deleted', 0)
            ->where('orders.id_status', 2)->sum('qty');
            
            //total qty yang refundrefund_menu_order
            $SumRefund = RefundOrderMenu::join('orders','refund_menu_order.id_order','=','orders.id')
            ->whereDate('orders.tanggal', $sift->start_time)->sum('qty');
            
            //total nominal refund payment cash
            $RefundTotalCash = RefundOrder::whereHas('order', function($query) use ($sift) {
                    $query->where('id_type_payment', 2)
                    ->whereDate('tanggal', $sift->start_time);
            })->sum('total_retur');
            
                //total nominal refund payment BCA
            $RefundTotalBCA = RefundOrder::whereHas('order', function($query) use ($sift) {
                    $query->where('id_type_payment', 7)
                ->whereDate('tanggal', $sift->start_time);
            })->sum('total_retur');
        
            //total nominal refund payment Mandiri
            $RefundTotalMandiri = RefundOrder::whereHas('order', function($query) use ($sift) {
                $query->where('id_type_payment', 6)
                ->whereDate('tanggal', $sift->start_time);
            })->sum('total_retur');

            $RefundTotalBRI = RefundOrder::whereHas('order', function($query) use ($sift) {
                    $query->where('id_type_payment', 8)
                    ->whereDate('tanggal', $sift->start_time);
            })->sum('total_retur');
                
            //total nominal refund payment Ovo
           
            $RefundTotalOvo = RefundOrder::whereHas('order', function($query) use ($sift) {
                    $query->where('id_type_payment', 3)
                    ->whereDate('tanggal', $sift->start_time);
            })->sum('total_retur');
                
            //total nominal refund payment grab
            
            $RefundTotalGrab = RefundOrder::whereHas('order', function($query) use ($sift) {
                        $query->where('id_type_payment', 4)
                    ->whereDate('tanggal', $sift->start_time);
                })->sum('total_retur');
                
            //total nominal refund payment Bank Transfer
            
            $RefundTotalbankTf = RefundOrder::whereHas('order', function($query) use ($sift) {
                        $query->where('id_type_payment', 5)
                        ->whereDate('tanggal', $sift->start_time);
                })->sum('total_retur');
                // total nominal refund payment Online
           
            $RefundTotalOnline = RefundOrder::whereHas('order', function($query) use ($sift) {
                    $query->where('id_type_payment', 4)
                     ->whereDate('tanggal', $sift->start_time);
                })->sum('total_retur');
            
            
            $taxpb1 = Taxes::where('nama', 'PB1')->first();
            $service = Taxes::where('nama', 'Service Charge')->first();  
            $PB1 = $taxpb1->tax_rate / 100;
            $Service = $service->tax_rate / 100;
            
            // EDC
            $EDCRefund = $RefundTotalBCA + $RefundTotalMandiri + $RefundTotalBRI;
           
            // Cash
            $refund_cash = $RefundTotalCash;
            
            // other
            $other_Refund =  $RefundTotalOvo + $RefundTotalbankTf;
            
            // grab
            $grab_Refund =  $RefundTotalGrab;
            
            $total_itemSold = $itmsum + $SumRefund ;
            $total_itmRefund = $SumRefund;
            $grendRefunEDC = $EDCRefund;
            $grendRefundCash = $refund_cash;
            $grendOther = $other_Refund ;
            $grendGrab = $grab_Refund;

            return view('cash.detailSift', compact(
           'sift', 
           'kas_out',
           'kas_in',
           'total_pemasukan',
           'total_pengeluaran',
           'modal',
           'endingSift',
           'cashtype',
           'bankTF',
           'grab',
           'ovo',
           'mandiri',
           'BCA',
           'BRI',
           'total_itmRefund',
           'total_itemSold',
           'refund_cash',
           'EDCRefund',
           'other_Refund',
           'menu_retur',
           'grendRefunEDC',
           'grendRefundCash',
           'grendOther',
           'grendGrab',
           'grab_Refund',
           'RefundTotalOvo',
           'RefundTotalbankTf',
           'RefundTotalCash',
           'RefundTotalBCA',
           'RefundTotalMandiri',
           'RefundTotalBRI',
           'RefundTotalGrab'
           ));
        }else{
        return redirect()->route('login');         
        } 
    }


    public function EndSift(Request $request, $id){
       
       if(Sentinel::check()){

           $userId = Sentinel::getUser();
           $admin = $userId->id;
           $date = Carbon::now()->format('Y-m-d');

           $cash = new Cash();
           $cash->type = 'End Sift';
           $cash->id_sift = $id;
           $cash->nominal = $request->nominal;
           $cash->tanggal = $date;
           $cash->id_admin = $admin ;

            if($cash->save()){
               
               
                $endsift = Sift::where('id', $id)->first();

                $total_sift = 0;
                $total_pengeluaran = 0;
                $total_pemasukan = 0;
                $total_cash = 0;
                $total_online = 0;
                $total_itemSold = 0;
                $total_itmRefund = 0;
                $refund_cash= 0;
                $EDCRefund = 0;
                $other_Refund = 0; 
                $total_EDC = 0;
                $total_other_payment = 0;
                $total_expected = 0;
                $total_actual = 0;
                $defferent = 0;


                $kas_out = Cash::where('id_sift', $id)->where('type', 'out-kas')->whereDate('tanggal', $endsift->start_time)->get();
                $kas_in = Cash::where('id_sift', $id)->where('type', 'in-kas')->whereDate('tanggal', $endsift->start_time)->get();
                $modal = Cash::where('id_sift', $id)->where('type','Start Sift')->whereDate('tanggal', $endsift->start_time)->first();
                $endingSift = Cash::where('id_sift', $id)->where('type', 'End Sift')->whereDate('tanggal', $endsift->start_time)->first();


                $cashtype = Orders::where('id_type_payment', 2)->where('deleted', 0)->whereDate('tanggal', $endsift->start_time)->groupBy('tanggal')->sum('total_order');
                $bankTF = Orders::where('id_type_payment', 5)->where('deleted', 0)->whereDate('tanggal', $endsift->start_time)->groupBy('tanggal')->sum('total_order');
                $grab = Orders::where('id_type_payment', 4)->where('deleted', 0)->whereDate('tanggal', $endsift->start_time)->groupBy('tanggal')->sum('total_order'); 
                $ovo = Orders::where('id_type_payment', 3)->where('deleted', 0)->whereDate('tanggal', $endsift->start_time)->groupBy('tanggal')->sum('total_order');
                $mandiri = Orders::where('id_type_payment', 6)->where('deleted', 0)->whereDate('tanggal', $endsift->start_time)->groupBy('tanggal')->sum('total_order');
                $BCA = Orders::where('id_type_payment', 7)->where('deleted', 0)->whereDate('tanggal', $endsift->start_time)->groupBy('tanggal')->sum('total_order');

                    // dd($modal, $endingSift);
                foreach($kas_in as $in){
                    $total_pemasukan+=$in->nominal;
                }
                foreach($kas_out as $out){
                    $total_pengeluaran+=$out->nominal;
                }
          
         
                $date = Carbon::now()->format('Y-m-d');
                $order = Orders::whereDate('tanggal', $endsift->start_time)->get();

                
                //total sales
                $itmsum = DetailOrder::join('orders','detail_order.id_order','=','orders.id')
                ->whereDate('orders.tanggal', $endsift->start_time)->where('orders.deleted', 0)->sum('qty');

                $SumRefund = RefundOrderMenu::join('orders','refund_menu_order.id_order','=','orders.id')
                ->whereDate('orders.tanggal',$endsift->start_time)->sum('qty');

                foreach($order as $itm){
                    $idx = $itm->id;
                    
                    //total nominal refund payment cash
                    $sumTotalCashRefund = RefundOrderMenu::whereHas('order', function($query){
                        $query->where('id_type_payment', 2);
                    })->where('id_order', $itm->id)->sum('refund_nominal');
                    
                        //total nominal refund payment BCA
                    $sumTotalBCA = RefundOrderMenu::whereHas('order', function($query){
                        $query->where('id_type_payment', 7);
                    })->where('id_order', $itm->id)->sum('refund_nominal');
                        
                        //total nominal refund payment Mandiri
                    $sumTotalMandiri = RefundOrderMenu::whereHas('order', function($query){
                        $query->where('id_type_payment', 6);
                    })->where('id_order', $itm->id)->sum('refund_nominal');
                        
                        //total nominal refund payment Ovo
                    $sumTotalOvo = RefundOrderMenu::whereHas('order', function($query){
                        $query->where('id_type_payment', 3);
                    })->where('id_order', $itm->id)->sum('refund_nominal');
                        
                        //total nominal refund payment Bank Transfer
                    $sumTotalbankTf = RefundOrderMenu::whereHas('order', function($query){
                        $query->where('id_type_payment', 5);
                    })->where('id_order', $itm->id)->sum('refund_nominal');
                    
                        //total nominal refund payment grab
                    $sumTotalGrab= RefundOrderMenu::whereHas('order', function($query){
                        $query->where('id_type_payment', 4);
                    })->where('id_order', $itm->id)->sum('refund_nominal');

                        
                    
                    $refund_cash = $sumTotalCashRefund;
                    $EDCRefund = $sumTotalBCA + $sumTotalMandiri;
                    $other_Refund =  $sumTotalOvo +  $sumTotalbankTf;

                }

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

                // grab
                $grab_Refund =  $sumTotalGrab;
                $nominalPb1Grab = $grab_Refund * $PB1;
                $nominalServiceGrab = $grab_Refund * $Service ;


                $total_itemSold = $itmsum;
                $total_itmRefund = $SumRefund;

                $grendRefunEDC = $EDCRefund + $nominalPb1EDC + $nominalServiceEDC;
                $grendRefundCash = $refund_cash +  $nominalPb1Cash +  $nominalServiceCash;
                $grendOther = $other_Refund +  $nominalPb1Other +  $nominalServiceOther;
                $grendGrab = $grab_Refund + $nominalPb1Grab + $nominalServiceGrab;

                $total_sift = ($modal->nominal + $cashtype + $total_pemasukan) -$total_pengeluaran - $refund_cash;
                $total_cash = $cashtype - $grendRefundCash;
                $total_online = $grendGrab;
                $total_EDC =( $BCA + $mandiri) - $grendRefunEDC;
                $total_other_payment = ($ovo + $bankTF) - $grendOther;
                $total_expected = $total_sift + $total_cash + $total_online + $total_EDC + $total_other_payment;
                $total_actual =  $total_expected;
                $defferent = $total_expected - $total_actual;
                
                $endsift->end_time = $cash->tanggal;
                $endsift->total_expected =  $total_expected;
                $endsift->total_actual = $total_actual;
                $endsift->difference = $defferent;
                    
                $endsift->save();



               return response()->json([
                   'success' => 1,
                   'message' => 'Data End Sift',
                   'data' => $endsift,
                   'cash' => $cash

               ]);
            }else{
               return redirect()->back()->with('error', 'End sift Unsuccess to save');
            }

        }else{
           return redirect()->route('login');
        }
    }

    public function kas(Request $request){
        if(Sentinel::check()){
           $userId = Sentinel::getUser();
           $admin = $userId->id;
           $date = Carbon::now()->format('Y-m-d');

           $cash = new Cash();
           $cash->type = $request->type;
           $cash->nominal = $request->nominal;
           $cash->id_sift = $request->id_sift;
           $cash->deskripsi = $request->deskripsi;
           $cash->tanggal = $date;
           $cash->id_admin = $admin ;

            if($cash->save()){
               return response()->json([
                   'success' => 1,
                   'message' => 'Kas berhasil di simpan',
                   'data' => $cash
               ]);
            }else{
               return redirect()->back()->with('error', 'Cash Unsuccess to save');
            }

        }else{
           return redirect()->route('login');
        }
   }

   public function print_sift($id){
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

            // dd($modal, $endingSift);
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
            $RefundTotalCash = RefundOrder::whereHas('order', function($query) use ($sift) {
                    $query->where('id_type_payment', 2)
                    ->whereDate('tanggal', $sift->start_time);
            })->sum('total_retur');
            
                //total nominal refund payment BCA
            $RefundTotalBCA = RefundOrder::whereHas('order', function($query) use ($sift) {
                    $query->where('id_type_payment', 7)
                ->whereDate('tanggal', $sift->start_time);
            })->sum('total_retur');
        
            //total nominal refund payment Mandiri
            $RefundTotalMandiri = RefundOrder::whereHas('order', function($query) use ($sift) {
                $query->where('id_type_payment', 6)
                ->whereDate('tanggal', $sift->start_time);
            })->sum('total_retur');

            $RefundTotalBRI = RefundOrder::whereHas('order', function($query) use ($sift) {
                    $query->where('id_type_payment', 8)
                    ->whereDate('tanggal', $sift->start_time);
            })->sum('total_retur');
                
            //total nominal refund payment Ovo
           
            $RefundTotalOvo = RefundOrder::whereHas('order', function($query) use ($sift) {
                    $query->where('id_type_payment', 3)
                    ->whereDate('tanggal', $sift->start_time);
            })->sum('total_retur');
                
            //total nominal refund payment grab
            
            $RefundTotalGrab = RefundOrder::whereHas('order', function($query) use ($sift) {
                        $query->where('id_type_payment', 4)
                    ->whereDate('tanggal', $sift->start_time);
                })->sum('total_retur');
                
            //total nominal refund payment Bank Transfer
            
            $RefundTotalbankTf = RefundOrder::whereHas('order', function($query) use ($sift) {
                        $query->where('id_type_payment', 5)
                        ->whereDate('tanggal', $sift->start_time);
                })->sum('total_retur');
                // total nominal refund payment Online
           
            $RefundTotalOnline = RefundOrder::whereHas('order', function($query) use ($sift) {
                    $query->where('id_type_payment', 4)
                     ->whereDate('tanggal', $sift->start_time);
                })->sum('total_retur');
            

            $taxpb1 = Taxes::where('nama', 'PB1')->first();
            $service = Taxes::where('nama', 'Service Charge')->first();  
            $PB1 = $taxpb1->tax_rate / 100;
            $Service = $service->tax_rate / 100;
            
           // EDC
            $EDCRefund = $RefundTotalBCA + $RefundTotalMandiri + $RefundTotalBRI;
           
            // Cash
            $refund_cash = $RefundTotalCash;
            
            // other
            $other_Refund =  $RefundTotalOvo + $RefundTotalbankTf;
            
            // grab
            $grab_Refund =  $RefundTotalGrab;
            
            $total_itemSold = $itmsum + $SumRefund ;
            $total_itmRefund = $SumRefund;
            $grendRefunEDC = $EDCRefund;
            $grendRefundCash = $refund_cash;
            $grendOther = $other_Refund ;
            $grendGrab = $grab_Refund;
          

            return view('cash.print_sift', compact(
               'sift', 
               'kas_out',
               'kas_in',
               'total_pemasukan',
               'total_pengeluaran',
               'modal',
               'endingSift',
               'cashtype',
               'bankTF',
               'grab',
               'ovo',
               'mandiri',
               'BCA',
               'total_itmRefund',
               'total_itemSold',
               'refund_cash',
               'EDCRefund',
               'other_Refund',
               'menu',
               'menu_retur',
               'grendRefunEDC',
               'grendRefundCash',
               'grendOther',
               'grendGrab',
               'grab_Refund',
               'RefundTotalOvo',
               'RefundTotalbankTf',
               'RefundTotalCash',
               'RefundTotalBCA',
               'RefundTotalMandiri',
               'RefundTotalBRI',
               'RefundTotalGrab'
             
            ));
        }else{
           return redirect()->route('login');         
        }
   }

   public function Print_report(Request $request, $id) {
           try {

           $templateDoc = new TemplateProcessor('asset/assets/file_print/template-report.docx');
           $total_refund = 0;
           $total_pengeluaran = 0;
           $total_pemasukan = 0;
          
           $sift = Sift::findOrFail($id);
          
           $templateDoc->setValue('admin', $sift->admin->nama);
           $templateDoc->setValue('time_start', $sift->start_time.'-'.date("H:i", strtotime($sift->created_at)));
           $templateDoc->setValue('time_end', $sift->end_time.'-'.date("H:i", strtotime($sift->updated_at)));

           $kas_out = Cash::where('id_sift', $id)->where('type', 'out-kas')->whereDate('tanggal', $sift->start_time)->get();
           $kas_in = Cash::where('id_sift', $id)->where('type', 'in-kas')->whereDate('tanggal', $sift->start_time)->get();
           $modal = Cash::where('id_sift', $id)->where('type','Start Sift')->whereDate('tanggal', $sift->start_time)->first();
           $endingSift = Cash::where('id_sift', $id)->where('type', 'End Sift')->whereDate('tanggal', $sift->start_time)->first();
          
           $templateDoc->setValue('actual_cash', 'Rp. '.number_format($endingSift->nominal , 0,',','.'));

           $templateDoc->setValue('start_cash', 'Rp. '.number_format($modal->nominal , 0,',','.'));


           $cashtype = Orders::where('id_type_payment', 2)
           ->whereDate('tanggal', $sift->start_time)
           ->where('deleted', 0)
           ->groupBy('tanggal')->sum('total_order');

           $templateDoc->setValue('cash_sale', 'Rp. '.number_format($cashtype , 0,',','.'));
           
           $bankTF = Orders::where('id_type_payment', 5)
           ->whereDate('tanggal', $sift->start_time)
           ->groupBy('tanggal')
           ->where('deleted', 0)
           ->sum('total_order');

           $templateDoc->setValue('tf', 'Rp. '.number_format($bankTF , 0,',','.'));


           $grab = Orders::where('id_type_payment', 4)
           ->whereDate('tanggal', $sift->start_time)
           ->groupBy('tanggal')
           ->where('deleted', 0)
           ->sum('total_order'); 

           $templateDoc->setValue('grab', 'Rp. '.number_format($grab , 0,',','.'));
           $total_online = 0;
           $total_online = $grab;
           $templateDoc->setValue('total_online', 'Rp. '.number_format($total_online , 0,',','.'));

           $ovo = Orders::where('id_type_payment', 3)
           ->whereDate('tanggal', $sift->start_time)
           ->groupBy('tanggal')
           ->where('deleted', 0)
           ->sum('total_order');
           $templateDoc->setValue('ovo', 'Rp. '.number_format($ovo , 0,',','.'));

           
           $mandiri = Orders::where('id_type_payment', 6)
           ->whereDate('tanggal', $sift->start_time)
           ->groupBy('tanggal')
           ->where('deleted', 0)
           ->sum('total_order');
           $templateDoc->setValue('mandiri', 'Rp. '.number_format($mandiri , 0,',','.'));

           
           $BCA = Orders::where('id_type_payment', 7)
           ->whereDate('tanggal', $sift->start_time)
           ->groupBy('tanggal')
           ->where('deleted', 0)
           ->sum('total_order');
           $templateDoc->setValue('bca', 'Rp. '.number_format($BCA , 0,',','.'));

           
       $count_kas_in = count($kas_in);
       $templateDoc->cloneRow('desk_in', $count_kas_in);
          
       foreach($kas_in as $k => $in){
           $num = $k+1;
           $total_pemasukan+=$in->nominal;
           $templateDoc->setValue('desk_in#'.$num, $in->deskripsi);
           $templateDoc->setValue('nom_in#'.$num,'Rp. '.number_format($in->nominal, 0, ',', '.'));
       }
       $templateDoc->setValue('total_in', 'Rp. '.number_format($total_pemasukan , 0,',','.'));
       
           
       $count_kas_out = count($kas_out);
       $templateDoc->cloneRow('desk_out', $count_kas_out);
       foreach($kas_out as $k=> $out){
            $num = $k+1;
           $total_pengeluaran+=$out->nominal;
            $templateDoc->setValue('desk_out#'.$num, $out->deskripsi);
           $templateDoc->setValue('nom_out#'.$num,'Rp. '.number_format($out->nominal, 0, ',', '.'));
       }

       $templateDoc->setValue('total_out', 'Rp. '.number_format($total_pengeluaran , 0,',','.'));

           $date = Carbon::now()->format('Y-m-d');
           $total_itemSold = 0;
           $total_itmRefund = 0;
           $refund_cash= 0;
           $EDCRefund = 0;
           $other_Refund = 0;   
           $sumQty = 0;
           $sumQty_refund = 0;

           $order = Orders::whereDate('tanggal', $sift->start_time)->where('deleted', 0)->get();
           
            //get menu detail yang di jual
           $menu = DetailOrder::join('orders','detail_order.id_order','=','orders.id')
           ->whereDate('orders.tanggal', $sift->start_time)
           ->where('orders.deleted', 0)
           ->join('menu', 'detail_order.id_menu', '=', 'menu.id')
           ->groupBy('menu.nama_menu')
           ->select('menu.nama_menu', DB::raw('SUM(detail_order.qty) as total_qty'))
           ->get();

           $menu_length = count($menu);
           $templateDoc->cloneRow('nama_menu', $menu_length);
           foreach($menu as $k => $itm){
               $num = $k+1;
               $sumQty += $itm->qty;
               $templateDoc->setValue('nama_menu#'.$num, $itm->nama_menu);
               $templateDoc->setValue('qty#'.$num, $itm->total_qty);
           }
           //get data retur menu 
           $menu_retur = RefundOrderMenu::join('orders','refund_menu_order.id_order','=','orders.id')
           ->whereDate('orders.tanggal', $sift->start_time)
           ->where('orders.deleted', 0)
           ->join('menu', 'refund_menu_order.id_menu', '=', 'menu.id')
           ->groupBy('menu.nama_menu')
           ->select('menu.nama_menu', DB::raw('SUM(refund_menu_order.qty) as total_qty'))
           ->get();

           $refund_length = count($menu_retur);
           $templateDoc->cloneRow('nama_menu_refund', $refund_length);

           foreach($menu_retur as $k => $itms){
               $num = $k+1;
               $sumQty_refund += $itms->qty;
               $templateDoc->setValue('nama_menu_refund#'.$num, $itm->nama_menu);
               $templateDoc->setValue('qty_refund#'.$num, $itm->total_qty);
               
           }

           //total sales
           $itmsum = DetailOrder::join('orders','detail_order.id_order','=','orders.id')
           ->where('orders.deleted', 0)
           ->whereDate('orders.tanggal', $sift->start_time)
           ->sum('qty');
           
           //total qty yang refundrefund_menu_order
           $SumRefund = RefundOrderMenu::join('orders','refund_menu_order.id_order','=','orders.id')
           ->whereDate('orders.tanggal', $sift->start_time)->sum('qty');
           
           //total nominal refund payment cash
           $sumTotalCashRefund = RefundOrderMenu::whereHas('order', function($query) use ($sift) {
                                   $query->where('id_type_payment', 2)
                                       ->whereDate('tanggal', $sift->start_time);
                               })->sum('refund_nominal');
           $templateDoc->setValue('cash_refund', 'Rp. '.number_format($sumTotalCashRefund , 0,',','.'));



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
             
            
            $sumTotalGrab = RefundOrderMenu::whereHas('order', function($query) use ($sift) {
                    $query->where('id_type_payment', 4)
                        ->whereDate('tanggal', $sift->start_time);
            })->sum('refund_nominal');
            

           $total_itemSold = $itmsum;
           $templateDoc->setValue('total_sold', $total_itemSold);

           $total_itmRefund = $SumRefund;
           $templateDoc->setValue('total_refund', $total_itmRefund);
           
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
            
            // grab
            $grab_Refund =  $sumTotalGrab;
            $nominalPb1Grab = $grab_Refund * $PB1;
            $nominalServiceGrab = $grab_Refund * $Service ;
               
           $total_itemSold = $itmsum;
           $total_itmRefund = $SumRefund;
           $grendRefunEDC = $EDCRefund + $nominalPb1EDC + $nominalServiceEDC;
           $grendRefundCash = $refund_cash +  $nominalPb1Cash +  $nominalServiceCash;
           $grendOther = $other_Refund +  $nominalPb1Other +  $nominalServiceOther;
           $grendGrab = $grab_Refund + $nominalPb1Grab + $nominalServiceGrab;

           

           
           $templateDoc->setValue('refun_edc', 'Rp. '.number_format($EDCRefund , 0,',','.'));

           $other_Refund =  $sumTotalOvo +  $sumTotalbankTf;
           $total_sift = ($modal->nominal + $cashtype + $total_pemasukan) -$total_pengeluaran - $refund_cash;
           $templateDoc->setValue('expect_end', 'Rp. '.number_format($total_sift , 0,',','.'));
           $total_cash = 0;
           $total_cash = $cashtype - $refund_cash;
           $templateDoc->setValue('total_cash', 'Rp. '.number_format($total_cash , 0,',','.'));
          
           $total_EDC = 0;
           $total_EDC =( $BCA + $mandiri) - $EDCRefund;
           $templateDoc->setValue('total_edc', 'Rp. '.number_format($total_EDC , 0,',','.'));

           $total_other_payment = 0;
           $total_other_payment = ($ovo + $bankTF) - $other_Refund;
           $templateDoc->setValue('total_other', 'Rp. '.number_format($total_other_payment , 0,',','.'));

           $total_expected = 0;
           $total_actual = 0;
           $defferent = 0;
           $total_expected = $total_sift + $total_cash + $total_online + $total_EDC + $total_other_payment;
           $total_actual = $total_expected;
           $defferent = $total_expected - $total_actual;

           $templateDoc->setValue('all_total', 'Rp. '.number_format($total_expected , 0,',','.'));
           $templateDoc->setValue('all_actual_total', 'Rp. '.number_format($total_actual , 0,',','.'));
           $templateDoc->setValue('difference', 'Rp. '.number_format($defferent , 0,',','.'));

           $templateDoc->saveAs('asset/assets/sift/Sift-'.$sift->start_time.'.docx');
           $file_path = 'asset/assets/sift/Sift-'.$sift->start_time.'.docx';
           $file = basename($file_path);
           //dd($file);
               return response()->json([
                   'success' => 1,
                   'message' => 'file report success to save',
                   'file_sift' => $file
               ]);
           return redirect()->back();
           } catch (Exception $e) {
               echo 'Error: ' . $e->getMessage();
           }
          

   }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DetailOrder;

Use App\Models\Orders;
use App\Models\Point_User;
use App\Models\StatusOrder;
use App\Models\Notify_user;
use Sentinel;
use Carbon\Carbon;
use App\Events\MessageCreated;
use App\Models\Additional_menu_detail;
use App\Models\AdditionalRefund;
use App\Models\Discount_detail_order;
use App\Models\SalesType;
use App\Models\Taxes;
use App\Models\TaxOrder;
use App\Models\Menu;
use App\Models\RefundOrderMenu;
use App\Models\DiscountMenuRefund;
use App\Models\OptionModifier;
use App\Exports\LaporanPenjualanExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Aktivity;
use App\Models\RefundOrder;
use Illuminate\Support\Facades\Crypt;


class OrderController extends Controller

{
    public function kodePesanan($length = 5)
    {
            $str = 'RF';
            $charecters = array_merge(range('A', 'Z'), range('a', 'z'));
            $max = count($charecters) - 1;
            for ($i = 0; $i < $length; $i++) {
                $rand = mt_rand(0, $max);
                $str .= $charecters[$rand];
            }
            return $str;
    }

    public function indexOrder(Request $request){
        if(Sentinel::check()){
            if($request->has('startDate')){
                $startDate = $request->startDate;
                // $startDate = '2024-05-27';
                $endDate = $request->endDate;
                // $endDate = '2024-06-06';
            }
            else{
                $startDate = \Carbon\Carbon::now()->startOfDay()->toDateString();
                $endDate = \Carbon\Carbon::now()->endOfDay()->toDateString();
            }

            $tanggal_mulai = Carbon::now()->isoFormat('MM');

            $order_new = Orders::where('id_status', 1)->whereBetween('tanggal', [$startDate, $endDate])->where('deleted', 0)->orderBy('created_at', 'desc')->whereNotNull('id_user')->get();
            $order_new_nonUser = Orders::where('id_status', 1)->whereBetween('tanggal', [$startDate, $endDate])->where('id_user', null)->where('deleted', 0)->orderBy('created_at', 'desc')->get();
            // dd($order_new_nonUser);
            $order_selesai = Orders::where('id_status', 2)->whereBetween('tanggal', [$startDate, $endDate])->where('deleted', 0)->orderBy('created_at', 'desc')->get();
            $order_batal = Orders::where('deleted', 1)->whereBetween('tanggal', [$startDate, $endDate])->orderBy('created_at', 'desc')->get();
            
            $orderCount = Orders::where('id_status', 2)->whereBetween('tanggal', [$startDate, $endDate])->where('deleted', 0)->count();
            
            $orderSumTotal = Orders::where('id_status', 2)->whereBetween('tanggal', [$startDate, $endDate])->where('deleted', 0)->sum('total_order');
            
            $orderSumSubTotal = Orders::where('id_status', 2)->where('deleted', 0)->whereBetween('tanggal', [$startDate, $endDate])->sum('subtotal');
            
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
            //  - $grossRef - $refundDisCountSum;

            return view('Orders.index', compact(
                'order_new',
                'order_selesai',
                'order_batal',
                'order_new_nonUser',
                'orderCount',
                'orderSumTotal',
                'TotalGrand',
                'allGrandNet',
                'startDate',
                'endDate'
                ));
        }else{
            return redirect()->route('login');
        }


    }

    public function detailOrder($kode){
        if(Sentinel::check()){
            // $dec = decrypt($id);
            $detail = Orders::where('kode_pemesanan', $kode)->first();
            $status = StatusOrder::all();
            // $taxs = TaxOrder::where('id_order', $detail->id)->get();
            
            $taxs = Taxes::all();
            $refund = RefundOrderMenu::where('id_order', $detail->id)->get();
            $refundOrder = RefundOrder::where('id_order', $detail->id)->get()->map(function ($refund) {
                $refund->encrypted_id = Crypt::encryptString($refund->id); // Enkripsi ID
                return $refund;
            });

            $DisTotal=0;
            foreach($refund as $ref){
                $DisTotal = DiscountMenuRefund::where('id_refund_menu', $ref->id)->sum('nominal_dis');
            }

            //dd($totalDis);
            $totalItemRef =0;
            $addSum = 0;
            $additional = 0;

            // $totalDis = 0;
            $subtotal = 0;
            $totalTax = 0;


                return view('Orders.detail', compact('detail', 'status', 'subtotal', 'totalTax','taxs', 'refund', 'totalItemRef','additional','refundOrder', 'addSum', 'DisTotal'));

        } else {
            return redirect()->route('login');
        }

    }


    public function updateOrderStatus(Request $request, $kode){

        if(Sentinel::check()){
            // $dec = decrypt($id);
            $detail = Orders::where('kode_pemesanan', $kode)->first();
            $detail->id_status = $request->data['id_status'];
            $detail->save();

          if($request->data['id_status'] == '2' ){

                $total_order = intval($detail->subtotal);
                $point = ($total_order * 1) / 1000;
                // $order = Orders::where('id', $detail->id_order)->first();

                if(!!  $detail->id_user){

                    $totalPoint = Point_User::where('id_user', $detail->id_user)->OrderBy('id_user', 'DESC')->first();
                    $point_user = new Point_User();
                    $point_user ->id_user =  $detail ->id_user;
                    $point_user->id_order = $detail ->id;
                    $point_user->tanggal = Carbon::now()->toDateTimeString();
                    $point_user->point_in = $point;
                    $point_user->keterangan ='Points have entered'.$point.'point of the order code '.$detail->kode_pemesanan;

                    // dd($point_user);
                    $point_user->save();

                    $notify = new Notify_user();
                    $notify->id_user =  $detail->id_user;
                    $notify->message = $point_user->keterangan;
                    $notify->tanggal = Carbon::now()->toDateTimeString();
                    $notify->status = 'unread';
                    $notify->save();

                    $message = [
                        'message' => $point_user->keterangan
                      ];
                    event(new MessageCreated($message));
                }
            }



        return response()->json(['success' => 1, 'data'=>$detail]);
        }else{
            return redirect()->route('login');
        }

    }

    // Sales Type

    public function salestype(){
        if(Sentinel::check()){

            $type = SalesType::all();

        return view('SalesType.dataType', compact('type'));

        }else{
            return redirect()->route('login');
        }
    }

    public function createSalesType(){
        if(Sentinel::check()){

        return view('SalesType.CreateTypeSales');

        }else{
            return redirect()->route('login');
        }
    }

    public function postTypeSales(Request $request){
        if(Sentinel::check()){

            $request->validate([
                'name'=> 'required'
            ]);

            $type = new SalesType();
            $type ->name = $request->name;

            if($type->save()){
                return redirect()->route('data-SalesType')->with('Success','Data Sales type Berhasil di buat');
            }else{
                return redirect()->back()->with('fail','Data Sales type gagal di buat');
            }

        }else{
            return redirect()->route('login');
        }
    }

    public function EditSalesType($id){

        if(Sentinel::check()){

            $dec = decrypt($id);
            $type = SalesType::findOrFail($dec);

            return view('SalesType.EditTypeSales', compact('type'));

        }else{
            return redirect()->route('login');
        }
    }

    public function UpdateSalesType(Request $request, $id){

        if(Sentinel::check()){
            $request->validate([
                'name'=> 'required'
            ]);

            $dec = decrypt($id);
            $type = SalesType::findOrFail($dec);
            $type->name  = $request->name;

            if($type->save()){
                return redirect()->route('data-SalesType')->with('Success','Data Sales type Berhasil di Update');
            }else{
                return redirect()->back()->with('fail','Data Sales type gagal di Update');
            }

        }else{
            return redirect()->route('login');
        }
    }

    public function DeleteTypeSales($id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $type = SalesType::findOrFail($dec);

            $type->delete();

            return redirect()->back()->with('Success', 'Sales type Berhasil di Hapus');
        }else{
            return redirect()->route('login');
        }
    }

    public function DeleteOrder($id){
        if(Sentinel::check()){
            try {
                DB::beginTransaction();

                $dec = decrypt($id);
                $order = Orders::findOrfail($dec);
                // dd($order->id);
                $detail_order = DetailOrder::where('id_order', $order->id)->get();
                $tax_order = TaxOrder::where('id_order', $order->id)->get();
                $refundMenu = RefundOrderMenu::where('id_order', $order->id)->get();
                
                if(!empty($detail_order)){
                    foreach($detail_order as $detail){
                        $additional = Additional_menu_detail::where('id_detail_order', $detail->id)->get();
                        if(!empty($additional)){
                            foreach($additional as $adds){
                                $adds->delete();
                            }
                        }

                        $discount_menu = Discount_detail_order::where('id_detail_order', $detail->id)->get();
                        if(!empty($discount_menu)){
                            foreach($discount_menu as $discount){
                                $discount->delete();
                            }
                        }
                        $detail->delete();
                    }
                }

                if(!empty($tax_order)){
                    foreach($tax_order as $tax){
                        $tax->delete();
                    }
                }

                if(!empty($refundMenu)){
                    foreach($refundMenu as $refund){
                        $addRefund = AdditionalRefund::where('id_refund_menu', $refund->id)->get();
                        if(!empty($addRefund)){
                            foreach($addRefund as $refundAdd){
                                $refundAdd->delete();
                            }
                        }

                        $discountRef = DiscountMenuRefund::where('id_refund_menu', $refund->id)->get();
                        if(!empty($discountRef)){
                            foreach($discountRef as $dis){
                                $dis->delete();
                            }
                        }

                        $refund->delete();
                    }
                }

                $order->delete();

                DB::commit();
                return redirect()->back()->with('success', 'Data order berhasil dihapus');
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Error while deleting order: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus order');
            }
        } else {
            return redirect()->route('login');
        }
    }
    public function DeleteDataOrder(Request $request){
        if (Sentinel::check()) {
            $date = Carbon::now()->format('Y-m-d');
            $userId = Sentinel::getUser();
            $admin = $userId->id;

            

            $order = Orders::where('id', $request->id_order)->first();
            $order->deleted = 1;
            $order->deleted_at = Carbon::now()->toDateTimeString();
            $order->id_admin_deleted = $admin;
            $order->alasan_delete = $request->alasan_delete;
            $order->save();
             if($order){
                $refundOrder = RefundOrder::where('id_order', $order->id)->first();
                $refundOrder->deleted = 1;
                $refundOrder->id_admin_delete =  $order->id_admin_deleted;
                $refundOrder->alasan_delete= $order->alasan_delete;
                $refundOrder->deleted_at= $date;
                $refundOrder->save();
            }
            $detail = [
                'id' => $order->id,
                'name' => $order->name, // example property
                'status' => $order->status // example property
            ];
            
            $activity = new Aktivity();
            $activity->id_admin = $admin;
            $activity->keterangan = "Menghapus Data Order";
            $activity->detail = json_encode($detail);
            $activity->save();


            if($order->save()){
                return redirect()->back()->with('success', 'Order data has been successfully deleted');
            }else{
                return redirect()->back()->with('error', 'Order data failed to delete');

            }

        }else{
            return redirect()->route('login');
        }
    }

    public function refundMenuOrder(Request $request){
        
        if(Sentinel::check()){

         
            $userId = Sentinel::getUser();
            $admin = $userId->id;
            $date = Carbon::now()->format('Y-m-d');
            //menu di detail order yang ingin di hapus karena sudah di refund
            $menuDetail = $request->detail_menu;
            $menuRefund = $request->menu;
            $orders = Orders::where('id', $request->order_id)->first();
            // dd($menuDetail, $menuRefund);
        
            try{
                
                DB::beginTransaction();
                // dd($menuRefund);
                $refundOrder = new RefundOrder();
                // dd($refundOrder);
                $refundOrder->id_order = $orders->id;
                $refundOrder->name_bill = 'Refund-'.$orders->kode_pemesanan;
                $refundOrder->kode_refund = $this->kodePesanan();
                // dd($refundOrder->kode_refund);
                $refundOrder->subtotal = $request->subTotalrefund;
                $refundOrder->total_retur = $request->TotalRefund;
                $refundOrder->id_admin = $admin;
                $refundOrder->tanggal = $date;
                
                $refundOrder->save();

                if($refundOrder){
                    foreach ($menuRefund as $refund) {
                    
                            $itmRefund = new RefundOrderMenu();   
                            $itmRefund->id_order = $refund['id_order'];
                            $itmRefund->id_refund_order = $refundOrder->id;
                            $itmRefund->id_menu = $refund['id_menu'];
                            $itmRefund->refund_nominal = ($refund['harga_menu'] + $refund['adds']) * $refund['qty'] ;
                            $itmRefund->harga = $refund['harga_menu'];
                            $itmRefund->qty = $refund['qty'];
                            $itmRefund->catatan = $refund['catatan'];
                            $itmRefund->id_varian = $refund['varian'];
                            $itmRefund->id_admin = $admin;
                            $itmRefund->alasan_refund = $refund['alasan'];
                            $itmRefund->tanggal = Carbon::now()->toDateTimeString();
                            $itmRefund->save();
                    
                            if($itmRefund){
                                if (isset($refund['discount'])) {
                                    $disRefund = $refund['discount'];
                                    foreach ($disRefund as $dis) {
                                        $discount = new DiscountMenuRefund();
                                        $discount->id_refund_menu  = $itmRefund->id;
                                        $discount->id_menu = $dis['id_menu'];
                                        $discount->id_discount = $dis['idDiscount'];
                                        $discount->nominal_dis = $dis['nominalDis'];
                                        $discount->id_admin = $admin;
                                        $discount->save();
                                    }
                                }

                                if (isset($refund['additional'])) {
                                    $addRef = $refund['additional'];
                                    foreach ($addRef as $add) {
                                        $additional = new AdditionalRefund();
                                        $additional->id_refund_menu  = $itmRefund->id;
                                        $additional->id_menu = $add['id_menu'];
                                        $additional->id_option_additional = $add['id_add'];
                                        $additional->harga = $add['nominal'];
                                        $additional->qty = $add['qty'];
                                        $additional->total_ = $add['Total_adds'];
                                        $additional->tanggal = Carbon::now()->toDateTimeString();
                                        $additional->id_admin = $admin;
                                        $additional->save();
                                    }
                                }
                            }
                            
                            // dd($refund['discount']);
                    
                    }
                }
           

                foreach($menuDetail as $item){
                    $detail_menu = DetailOrder::where('id', $item["id_detail"])->first();

                    if ($detail_menu) {
                            // Jika detail menu memiliki ID yang sama dengan item refund
                        if ($detail_menu->id_order == $item['id_order'] && $detail_menu->id_menu == $item['id_menu']) {
                            // Kurangi qty detail menu dengan qty item refund
                            $detail_menu->qty -= $item['qty'];

                            // Jika setelah dikurangi qty menjadi 0 atau kurang, hapus data
                            if ($detail_menu->qty <= 0) {

                                $idDetailOrder = $detail_menu->id;
                                $detail_menu->delete();

                                Discount_detail_order::where('id_detail_order', $idDetailOrder)->delete();
                                Additional_menu_detail:: where('id_detail_order', $idDetailOrder)->delete();

                            } else {
                                $detail_menu->total = ($item['harga_menu'] + $item['adds']) *  $detail_menu->qty;
                                $detail_menu->save();
                            }
                        }
                    }

                        $discount = Discount_detail_order::where('id_detail_order', $detail_menu->id)->get();
                        $total_nominal = $detail_menu->total; // Initial total
                    
                        foreach ($discount as $discount) {
                            $rate = $discount->discount->rate_dis; // Discount rate as a percentage

                            // Calculate the discount based on the current nominal value
                            $current_discount = $total_nominal * ($rate / 100);

                            // Save the current discount amount in the model
                            $discount->total_discount = $current_discount;
                            $discount->save();

                        
                            $total_nominal -= $current_discount;
                        }

                        $addDetail = Additional_menu_detail:: where('id_detail_order', $detail_menu->id)->get();
                        
                        foreach($addDetail as $itmAdds){
                            
                            $qty = $itmAdds->qty ?? 0;
                            $total = $itmAdds->total ?? 0;
                            $harga = $total / $qty ;

                            $itmAdds->qty -= $item['qty'];

                            $itmAdds->total = $harga *  $itmAdds->qty;
                            $itmAdds->save();
                            
                        }


                }

          
                $sumDetailOrder = DetailOrder::where('id_order', $orders->id)->sum('total');
                $sumDiscount = Discount_detail_order::whereHas('Detail_order', function ($query) use ($orders) {
                    $query->where('id_order', $orders->id);
                })->sum('total_discount');

                $tax_order_pb1 = TaxOrder::where('id_order', $orders->id)->where('id_tax', 1)->first();
                $tax_order_pb1->total_tax = $request->tx_pb1;
                $tax_order_pb1->save();

                $tax_order_service = TaxOrder::where('id_order', $orders->id)->where('id_tax', 2)->first();
                $tax_order_service->total_tax = $request->tx_service;
                $tax_order_service->save();
            
                $subtotalNew = $sumDetailOrder - $sumDiscount ;
                $totalNew = $subtotalNew + $tax_order_pb1->total_tax + $tax_order_service->total_tax ;
                $orders->subtotal = $subtotalNew;
                $orders->total_order = $totalNew;
                $orders->cash = $totalNew;
                $orders->save();

                
                DB::commit();

                return response()->json([
                    'success' => 1,
                    'message' => 'refund di simpan'
                ],200);
            }catch(\Exception $e){
                DB::rollBack();
                return response()->json([
                    'success' => 0,
                    'message' => 'refund faild',
                    'error' => $e->getMessage()
                ],500);
            }
           
       }else{
           return redirect()->route('login');
       }

    }

    public function laporan(Request $request){
        // $order = Orders::whereBetween('created_at', [$tanggal_mulai, $tanggal_akhir])->get();
        if (Sentinel::check()) {
            $menu = Menu::all();
            $additional = OptionModifier::all();

            $tanggal_mulai = $request->input('start_date');
            $tanggal_akhir = $request->input('end_date');

            $tanggal_mulai = date('Y-m-d', strtotime($request->input('start_date')));
            $tanggal_akhir = date('Y-m-d', strtotime($request->input('end_date') . '+1 day'));

            foreach ($menu as $itm) {
                $items = DetailOrder::where('created_at', '>=', $tanggal_mulai)
                ->where('created_at', '<', $tanggal_akhir)
                ->where('id_menu', $itm->id)
                ->whereHas('orders', function($query) {
                    $query->where('delete', 0)->whereNull('deleted_at');
                })
                ->value('harga');
                $itmsum = DetailOrder::where('created_at', '>=', $tanggal_mulai)
                ->where('created_at', '<', $tanggal_akhir)
                ->where('id_menu', $itm->id)
                ->whereHas('orders', function($query) {
                    $query->where('delete', 0)->whereNull('deleted_at');
                })
                ->sum('qty');

                $totalDiscount = Discount_detail_order::join('detail_order', 'discount_detail_order.id_detail_order', '=', 'detail_order.id')
                ->where('detail_order.id_menu', $itm->id)
                ->where('detail_order.created_at', '>=', $tanggal_mulai)
                ->where('detail_order.created_at', '<', $tanggal_akhir)
                ->where('orders.deleted', 0) 
                ->sum('discount_detail_order.total_discount');


                $varian = DetailOrder::join('varian_menu', 'detail_order.id_varian', '=', 'varian_menu.id')
                    ->where('detail_order.created_at', '>=', $tanggal_mulai)
                    ->where('detail_order.created_at', '<', $tanggal_akhir)
                    ->where('detail_order.id_menu', $itm->id)
                    ->where('orders.deleted', 0) 
                    ->pluck('nama');

                $refundDisCountSum = DiscountMenuRefund::where('discount_refund.created_at', '>=', $tanggal_mulai)
                ->where('discount_refund.created_at', '<', $tanggal_akhir)
                ->where('id_menu', $itm->id)->sum('nominal_dis');

                $SumRefund = RefundOrderMenu::where('id_menu', $itm->id)->where('created_at', '>=', $tanggal_mulai)
                ->where('created_at', '<', $tanggal_akhir)->sum('qty');

                $hargaRefund = RefundOrderMenu::where('id_menu', $itm->id)->where('created_at', '>=', $tanggal_mulai)
                ->where('created_at', '<', $tanggal_akhir)->value('harga');

                $itemsold = $itmsum;
                $harga = $items * $itemsold;
                $totalRefund = $hargaRefund * $SumRefund;

                $disTotal = $totalDiscount - $refundDisCountSum;
                $netSales = $harga - $disTotal - $totalRefund;
            }

            foreach ($additional as $adds) {

                $itmAdsSold = Additional_menu_detail::join('detail_order', 'additional_menu.id_detail_order','=','detail_order.id')
                ->where('additional_menu.created_at', '>=', $tanggal_mulai)
                ->where('additional_menu.created_at', '<', $tanggal_akhir)
                ->where('additional_menu.id_option_additional', $adds->id)
                ->where('orders.delete', 0) 
                ->sum('detail_order.qty');

                $refundSum = AdditionalRefund::join('refund_menu_order', 'additional_refund.id_refund_menu','=','refund_menu_order.id')
                ->where('additional_refund.created_at', '>=', $tanggal_mulai)
                ->where('additional_refund.created_at', '<', $tanggal_akhir)
                ->where('id_option_additional', $adds->id)
                ->sum('refund_menu_order.qty');


                $refund = AdditionalRefund::where('created_at', '>=', $tanggal_mulai)->where('created_at', '<', $tanggal_akhir)
                ->where('id_option_additional', $adds->id)
                ->sum('harga');

                $grosSale = $adds->harga * $itmAdsSold;
                $grosRefund = $refund * $refundSum;

                $NetSales = $grosSale  - $grosRefund;
            }

            return Excel::download(new LaporanPenjualanExport(
                $menu,
                $tanggal_mulai,
                $tanggal_akhir,
                $items,
                $itemsold,
                $totalDiscount,
                $harga,
                $totalRefund,
                $netSales,
                $varian,
                $additional,
                $itmAdsSold,
                $refundSum,
                $refundDisCountSum,
                $refund,
                $grosSale,
                $NetSales,
            ), 'Laporan Item Sales.xlsx');
        } else {
            return redirect()->route('login');
        }

    }

    public function filterPeriode(Request $request){

        if (Sentinel::check()) {
            $tanggal_mulai = $request->start_date;
            $tanggal_akhir = $request->end_date;

            // Pastikan format tanggal sesuai dengan format yang diharapkan (Y-m-d)
            $tanggal_mulai = date('Y-m-d', strtotime($tanggal_mulai));
            $tanggal_akhir = date('Y-m-d', strtotime($tanggal_akhir));

            $order = Orders::whereBetween('created_at', [$tanggal_mulai, $tanggal_akhir])->where('id_status', 2)->get();

            return view('Orders.filter_data', compact('order'));
        } else {
            return redirect()->route('login');
        }

    }
}




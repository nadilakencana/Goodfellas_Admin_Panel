<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Menu;
use App\Models\Kategori;
use App\Models\StatusOrder;
use App\Models\SubKategori;
use App\Models\DetailOrder;
use App\Models\OptionModifier;
use App\Models\VarianMenu;
use App\Models\Discount;
use App\Models\Taxes;
use App\Models\TypePayment;
use App\Models\SalesType;
use App\Models\Discount_detail_order;
use App\Models\Additional_menu_detail;
use App\Models\TaxOrder;
use App\Models\Admin;
use Session;
use Sentinel;
use Carbon\Carbon;
use App\Models\Point_User;
use App\Models\Notify_user;
use App\Events\MessageCreated;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\DB;
use App\Models\Aktivity;

class POSController extends Controller
{

    public function kodePesanan($length = 5)
    {
        $str = '';
        $charecters = array_merge(range('A', 'Z'), range('a', 'z'));
        $max = count($charecters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $charecters[$rand];
        }
        return $str;
    }


    public function POSdashboard()
    {
        if (Sentinel::check()) {
            $itemMenu = Menu::where('custom', false)->where('delete_menu', 0)->get();
            $Category = Kategori::all();
            $subCategory = SubKategori::where('deleted', 0)->get();
            $discount = Discount::all();
            $payment = TypePayment::all();
            $typeOrder = SalesType::all();
            $taxs = Taxes::all();

            $billOrder = Orders::where('id_status', '1')->where('deleted', 0)->get();
            //  $dataBillServer = Http::get('https://admin.goodfellas.id/api/getDataOrder');
            //  $dataServer = $dataBillServer->json();
            //  $billServer = $dataServer['data'];

            $customItem = Menu::where('custom', 1)->where('delete_menu', 0)->get();
            $carts = Session::get('cart');

            $subtotal = 0;
            $totalDis = 0;


            if (Session::has('cart')) {

                if (isset($carts) === false) {
                    $carts = [];
                } else {
                    foreach ($carts as $cart) {
                        $subtotal = $subtotal + ($cart['harga'] + $cart['harga_addtotal']) * $cart['qty'];
                    }
                }
            } else {
            }
            //dd($additional, $Vars, $Discount);
            return view('POS.dashboard_POS', compact(
                'itemMenu',
                'Category',
                'subCategory',
                'discount',
                'payment',
                'typeOrder',
                'taxs',
                'carts',
                'subtotal',
                'customItem',
                'billOrder',
                // 'billServer',
                'totalDis',

            ));
        } else {
            return redirect()->route('login');
        }
    }

    public function DataBill()
    {
        $billOrder = Orders::where('id_status', '1')->where('deleted', 0)->get();

        return view('POS.part_lain.Daftar-Bill', compact('billOrder'));
    }

    public function dataDetailOrder()
    {
        try {
            $taxs = Taxes::all();
            $carts = Session::get('cart');

            $subtotal = 0;
            $totalDis = 0;
            if (Session::has('cart')) {
                if (isset($carts) === false) {
                    $carts = [];
                } else {
                    foreach ($carts as $cart) {
                        $subtotal = $subtotal + ($cart['harga'] + $cart['harga_addtotal']) * $cart['qty'];
                    }
                }
            }

            $view = view('POS.part_lain.view_detail_session_order', compact(
                'taxs',
                'carts',
                'subtotal',
                'totalDis',

            ))->render();

            return response()->json([
                'message' => 'success to fetch data session Detail order',
                'data' => [
                    'cart' => $carts,
                    'subtotal' => $subtotal,
                    'totalDis' => $totalDis,
                    'taxs' => $taxs

                ],
                'view' => $view
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Fail to fetch data session Detail order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function PartMenuDiscount()
    {
        if (Sentinel::check()) {
            $itemMenu = Menu::where('custom', false)->get();
            $Category = Kategori::all();
            $subCategory = SubKategori::all();
            //  $itemSubCategory = Menu::where('id_sub_kategori', $subCategory->id)->get();

            return view('POS.part_lain.menu_discount', compact(
                'itemMenu',
                'Category',
                'subCategory',
                //'itemSubCategory'
            ));
        } else {
            return redirect()->route('login');
        }
    }
    public function partMenuKat($id)
    {
        if (Sentinel::check()) {
            $itemMenu = Menu::where('id_kategori', $id)->where('custom', false)->get();
            $Category = Kategori::all();
            $subCategory = SubKategori::find($id);
            //  $itemSubCategory = Menu::where('id_sub_kategori', $subCategory->id)->get();

            return view('POS.part_lain.menu_kat', compact(
                'itemMenu',
                'Category',
                'subCategory',
                //'itemSubCategory'
            ));
        } else {
            return redirect()->route('login');
        }
    }

    public function PartAllMenu()
    {
        if (Sentinel::check()) {
            $itemMenu = Menu::where('custom', false)->get();
            $Category = Kategori::all();
            $subCategory = SubKategori::all();
            //  $itemSubCategory = Menu::where('id_sub_kategori', $subCategory->id)->get();
            return view('POS.part_lain.all_menu', compact(
                'itemMenu',
                'Category',
                'subCategory',
                //'itemSubCategory'
            ));
        } else {
            return redirect()->route('login');
        }
    }


    public function PartSubMenu($id)
    {
        if (Sentinel::check()) {
            $itemMenu = Menu::where('id_sub_kategori', $id)->where('custom', false)->get();
            $Category = Kategori::all();
            $subCategory = SubKategori::find($id);
            //  $itemSubCategory = Menu::where('id_sub_kategori', $subCategory->id)->get();

            return view('POS.part_lain.sub_menu', compact(
                'itemMenu',
                'Category',
                'subCategory',
                //'itemSubCategory'
            ));
        } else {
            return redirect()->route('login');
        }
    }

    public function getVariasi(Request $request)
    {
        if (Sentinel::check()) {
            $menu = $request->id_menu;

            $variasi = VarianMenu::where('id_menu', $menu)->get();
            return response()->json([
                'success' => 1,
                'message' => 'get data',
                'data' => $variasi,

            ]);
        } else {
            return redirect()->route('login');
        }
    }

    public function getOptionAdditional(Request $request)
    {
        if (Sentinel::check()) {
            $menu = $request->id_menu;
            $itemMenu = Menu::where('id', $menu)->first();
            $additional =  OptionModifier::where('id_group_modifier', $itemMenu->id_group_modifier)->get();
            return response()->json([
                'success' => 1,
                'message' => 'get data',
                'data' => $additional,
            ]);
        } else {
            return redirect()->route('login');
        }
    }

    public function addOrder(Request $request)
    {

        if (Sentinel::check()) {
            try {
                $menu = Menu::where('id', $request->get('id'))->first();

                $ex = false;
                $exId = 0;
                $cart = Session::get('cart');

                $count = 0;
                $currentPrice = 0;

                $currentPrice = $menu->harga;

                $cart[] = array(
                    'id' => $menu->id,
                    'nama_menu' => $menu->nama_menu,
                    'harga' => $request->get('harga'),
                    'qty' => $request->get('qty'),
                    'harga_addtotal' => $request->get('harga_addtotal'),
                    'variasi_id' => $request->get('variasi'),
                    'var_name' => $request->get('var_name'),
                    'additional' => $request->get('additional'),
                    'discount' => $request->get('discount'),
                    'catatan' => $request->get('catatan'),
                    'type_id' => $request->get('id_type_sales'),
                    'type_name' => $request->get('sales_name'),
                    'total_dis' => $request->get('total_dis'),

                );

                Session::put('cart', $cart);
                
                Session::save();
                $cart = Session::get('cart');
                //dd($cart);
                $count = count($cart);
                return response()->json([
                    'success' => 1,
                    'message' => 'Data Tersimpan di session cart',
                    'data' => [
                        'cart' => $cart,
                        'count' => $count
                    ]
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to fetch data detail order',
                    'data' => [
                        'cart' => $cart,
                        'count' => $count
                    ],
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function editOrder(Request $request)
    {

        if (Sentinel::check()) {
            try {
                $menu = Menu::where('id', $request->get('id'))->first();

                $ex = false;
                $exId = 0;
                $cart = Session::get('cart');
                // $option = Session::get('option');

                if ($cart) {
                    foreach ($cart as $key => $value) {
                        if ($key == $request->get('key')) {
                            $ex = true;
                            $exId = $key;
                            //dd($request->get('key'));

                        }
                    }
                }


                $count = 0;
                $currentPrice = 0;

                $currentPrice = $menu->harga;
                if ($ex == false) {
                    $cart[] = array(
                        'id' => $menu->id,
                        'nama_menu' => $menu->nama_menu,
                        'harga' => $request->get('harga'),
                        'qty' => $request->get('qty'),
                        'harga_addtotal' => $request->get('harga_addtotal'),
                        'variasi_id' => $request->get('variasi'),
                        'var_name' => $request->get('var_name'),
                        'additional' => $request->get('additional'),
                        'discount' => $request->get('discount'),
                        'catatan' => $request->get('catatan'),
                        'type_id' => $request->get('id_type_sales'),
                        'type_name' => $request->get('sales_name'),
                        'total_dis' => $request->get('total_dis'),

                    );
                } else {
                    $oldData = $cart[$exId];
                    $cart[$exId] = array(
                        'id' =>  $menu->id,
                        'nama_menu' => $menu->nama_menu,
                        'harga' => $request->get('harga'),
                        'qty' => $request->get('qty'),
                        'harga_addtotal' => $request->get('harga_addtotal'),
                        'variasi_id' =>  $request->get('variasi'),
                        'var_name' => $request->get('var_name'),
                        'additional' =>  $request->get('additional'),
                        'discount' => $request->get('discount'),
                        'catatan' =>  $request->get('catatan'),
                        'type_id' =>  $request->get('id_type_sales'),
                        'type_name' => $request->get('sales_name'),
                        'total_dis' =>  $request->get('total_dis'),
                    );
                }


                //dd($cart['additional']['nama']);
                Session::put('cart', $cart);
                Session::save();
                $cart = Session::get('cart');
                $count = count($cart);
                return response()->json([
                    'success' => 1,
                    'message' => 'Data terupdate',
                    'data' => [
                        'cart' => $cart,
                        'count' => $count
                    ],

                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to fetch edit item bill',
                    'data' => [
                        'cart' => $cart,
                        'count' => $count
                    ],
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function modifyBill(Request $request)
    {

        if (Sentinel::check()) {

            DB::beginTransaction();

            try {
                
                $order = Orders::where('id', $request->get('target_order'))->first();
                $menu = Menu::where('id', $request->get('id'))->first();

                if (!empty($request->get('target_detail'))) {
                    $detail = DetailOrder::where('id', $request->get('target_detail'))->where('id_order', $request->get('target_order'))->first();

                    if ($detail) {


                        $qty = $detail->qty == $request->get('qty');
                        $varianMenu =  $detail->id_varian == $request->get('variasi');
                        $catatanMenu =  $detail->catatan == $request->get('catatan');

                        
                        if (!($qty && $varianMenu && $catatanMenu)) {
                            if ($detail->update == 0) $detail->update = 1;
                            $detail->last_print = null;
                        } else {
                            $detail->last_print = $detail->last_print;
                        }
                    }
                } else {
                    $detail = new DetailOrder();
                }

                if (!empty($order)) {

                    if (empty($order->id_type_payment)) {

                        $detail->id_order = $order->id;
                        $detail->id_menu = $menu->id;
                        $detail->qty = $request->qty;
                        $detail->harga = $request->harga;
                        $detail->id_varian = $request->variasi;
                        $detail->id_sales_type = $request->id_type_sales;
                        $detail->catatan = $request->catatan;


                        $detail->total = ($request['harga'] + $request['harga_addtotal']) * $request['qty'];

                        $detail->save();

                        if ($detail->save()) {
                            if ($request->has('additional')) {

                                $additionals = Additional_menu_detail::where('id_detail_order', $detail->id)->get();
                                $isAdditionalMismatch = false;

                                foreach ($additionals as $additional) {
                                    $foundMatch = false;
                                    foreach ($request->get('additional') as $reqAdditional) {
                                        if (
                                            $additional->id_option_additional == $reqAdditional['id'] &&
                                            $additional->qty == $reqAdditional['qty']
                                        ) {
                                            $foundMatch = true;
                                            break;
                                        }
                                    }
                                    if (!$foundMatch) {
                                        $isAdditionalMismatch = true;
                                        break;
                                    }
                                }
                                
                                if ($isAdditionalMismatch) {
                                    if ($detail->update == 0) $detail->update = 1;
                                    $detail->last_print = null;
                                   
                                   
                                }
                                $detail->save();

                                foreach ($request->additional as $adds) {
                                    if (!empty($adds['id_detail'])) {
                                        $additional = Additional_menu_detail::where('id_option_additional', $adds['id'])
                                            ->where('id_detail_order', $adds['id_detail'])
                                            ->first();
                                    } else {
                                        $additional = null;
                                    }
                                    if (empty($additional->id)) {
                                        $additional = new Additional_menu_detail();
                                        
                                        if ($detail->update == 0) $detail->update = 1;
                                        $detail->last_print = null;
                                       
                                        $detail->save();
                                    }
                                    $additional->id_detail_order = $detail->id;
                                    $additional->id_option_additional = $adds['id'];
                                    $additional->qty = $adds['qty'];
                                    $additional->total = $adds['harga'] * $detail->qty;


                                    $additional->save();
                                    
                                }
                            }
                            if ($request->has('adds_delete')) {
                                foreach ($request->adds_delete as $addDelete) {
                                    $additional = Additional_menu_detail::where('id_option_additional', $addDelete['id'])->where('id_detail_order', $addDelete['id_detail'])->first();
                                    if (!empty($additional)) {
                                        $additional->delete();
                                    } else {
                                        return response()->json(['success' => 1, 'message' => 'Data sudah di hapus']);
                                    }
                                }
                            }

                            if ($request->has('discount')) {
                                
                                

                                foreach ($request->get('discount') as $discounts) {
                                    if (!empty($discounts['id_detail'])) {
                                        $Discount = Discount_detail_order::where('id_discount', $discounts['id'])->where('id_detail_order', $discounts['id_detail'])->first();

                                        // If no existing discount is found, create a new one
                                        if (!$Discount) {
                                            $Discount = new Discount_detail_order();
                                        }
                                    } else {
                                        $Discount = new Discount_detail_order();
                                    }

                                    $Discount->id_detail_order = $detail->id;
                                    $Discount->id_discount = $discounts['id'];
                                    $rateDis = $discounts['percent'] / 100;
                                    $Discount->total_discount = $discounts['nominal'];

                                    $Discount->save();
                                  
                                }
                            }

                            if ($request->has('dis_delete')) {
                                $deleted = false; // Flag to check if any data is deleted
                                $notDeleted = false; // Flag to check if any data is not deleted

                                foreach ($request->dis_delete as $DisDelete) {
                                    $Discount = Discount_detail_order::where('id_discount', $DisDelete['id'])
                                        ->where('id_detail_order', $DisDelete['id_detail'])
                                        ->first();

                                    if (!empty($Discount)) {
                                        $Discount->delete();
                                        $deleted = true;

                                        if ($Discount) {
                                            if ($detail->update == 0) {
                                                $detail->update = 1;
                                            }
                                            $detail->last_print = $detail->last_print;
                                            $detail->save();
                                        }
                                    } else {
                                        $notDeleted = true;
                                    }
                                }
                            }

                            $sumDetail = DetailOrder::where('id_order', $request->get('target_order'))->sum('total');
                            $sumDis = Discount_detail_order::whereHas('Detail_order', function ($query) use ($request) {
                                $query->where('id_order', $request->get('target_order'));
                            })->sum('total_discount');

                            $subTotal = $sumDetail - $sumDis;

                            $taxpb1 = Taxes::where('nama', 'PB1')->first();
                            $service = Taxes::where('nama', 'Service Charge')->first();
                            $PB1 = $taxpb1->tax_rate / 100;
                            $Service = $service->tax_rate / 100;
                            $nominalPb1 =  $subTotal * $PB1;
                            $nominalService = $subTotal * $Service;

                            $gradTotal = $subTotal + $nominalPb1 + $nominalService;

                            $order->subtotal = $subTotal;
                            $order->total_order = $gradTotal;

                            $order->save();
                        }

                        DB::commit();
                        return response()->json([
                            'success' => 1,
                            'message' => 'Data Tersimpan',
                            'data' => [
                                'order' => $order,
                                'detail_order' => [
                                    'detail_data' => $detail,
                                ]
                            ]
                        ], 200);
                    } else {
                         DB::commit();
                        return response()->json([
                            'success' => 0,
                            'message' => 'data order sudah payment',
                            'data' => $order
                        ], 200);
                    }
                }
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Failed to fetch data detail order',
                    'data' => [
                        'order' => $order,
                        'detail_order' => [
                            'detail_data' => $detail,
                            // 'additional'=> $additional,
                            // 'discount' => $Discount
                        ]
                    ],
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function updateOrder(Request $request)
    {
        if (Sentinel::check()) {
            try {
                $order = Orders::where('id', $request->get('target_order'))->first();
                $order->subtotal = $request->subtotal;
                $order->total_order = $request->total;

                $order->name_bill = $request->nama;

                $order->no_meja = $request->nomer;
                // dd($order);
                $order->save();


                if ($request->has('taxes') && is_array($request->taxes)) {
                    // dd($request->has('taxes'));
                    foreach ($request->taxes as $taxs) {
                        $taxes = TaxOrder::where('id_order', $order->id)
                            ->where('id_tax', $taxs['id'])
                            ->first();

                        if ($taxes) {
                            // Jika data sudah ada, update total_tax
                            $taxes->total_tax = $taxs['nominal'];
                            $taxes->save();
                        }
                    }
                }

                return response()->json([
                    'success' => 1,
                    'message' => 'Data Tersimpan',
                    'data' =>  $order

                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Faild to fetch update data',
                    'data' =>  $order,
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            return redirect()->route('login');
        }
    }
    // hapus item yang sudah ke save
    public function deletemodify(Request $request)
    {

        if (Sentinel::check()) {

            try {
                $Details = DetailOrder::where('id', $request->get('id'))->first();

                $order = Orders::where('id', $Details->id_order)->first();
                if (empty($order->id_type_payment)) {
                    return response()->json([
                        'success' => 1,
                        'message' => 'Data berhasil Dihapus',
                        'data' => $Details,

                    ], 200);
                } else {
                    return response()->json([
                        'success' => 0,
                        'message' => 'Data Gagal Dihapus bill sudah di payment',
                    ], 200);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to fetch data delete modify detail',
                    'data' => $Details,
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            return redirect()->route('login');
        }
    }
    public function afterPrintDelete(Request $request)
    {
        if (Sentinel::check()) {
            DB::beginTransaction();

            try {
                // Find the detail order by ID
                $detail = DetailOrder::where('id', $request->get('id'))->first();

                if (!empty($detail)) {
                    // Delete the detail order

                    if ($detail->delete()) {
                        // Delete related discount details
                        $discounts = Discount_detail_order::where('id_detail_order', $request->get('id'))->get();
                        foreach ($discounts as $discount) {
                            $discount->delete();
                        }

                        // Recalculate the order totals
                        $sumDetail = DetailOrder::where('id_order', $request->get('id_order'))->sum('total');
                        $sumDiscount = Discount_detail_order::whereHas('Detail_order', function ($query) use ($request) {
                            $query->where('id_order', $request->get('id_order'));
                        })->sum('total_discount');

                        $subTotal = $sumDetail - $sumDiscount;

                        // Calculate taxes
                        $taxPb1 = Taxes::where('nama', 'PB1')->first();
                        $serviceCharge = Taxes::where('nama', 'Service Charge')->first();

                        $PB1 = $taxPb1 ? $taxPb1->tax_rate / 100 : 0;
                        $service = $serviceCharge ? $serviceCharge->tax_rate / 100 : 0;

                        $nominalPb1 = $subTotal * $PB1;
                        $nominalService = $subTotal * $service;

                        $grandTotal = $subTotal + $nominalPb1 + $nominalService;

                        // Update the order totals
                        $order = Orders::where('id', $request->get('id_order'))->first();
                        if (!empty($order)) {
                            $order->subtotal = $subTotal;
                            $order->total_order = $grandTotal;
                            $order->save();
                        }

                        // Commit the transaction
                        DB::commit();

                        return response()->json([
                            'success' => 1,
                            'message' => 'Data berhasil dihapus',
                            'data' => [
                                'detail' => $detail,
                                'order' => $order
                            ]
                        ], 200);
                    } else {
                        return response()->json([
                            'success' => 0,
                            'message' => 'Data Gagal di hapus',
                            'data' => $e->getmessage()
                        ], 500);
                    }
                } else {
                    return response()->json([
                        'success' => 0,
                        'message' => 'Detail Order tidak ditemukan',
                        'data' => $detail
                    ]);
                }
            } catch (\Exception $e) {
                // Rollback the transaction if something goes wrong
                DB::rollback();

                return response()->json([
                    'success' => 0,
                    'message' => 'Terjadi kesalahan saat menghapus data',
                    'data' => $detail,
                    'error' => $e->getMessage(),
                ]);
            }
            // $Details = DetailOrder::where('id', $request->get('id'))->first();
            // $Details->delete();

            // if($Details->delete()){
            //         $discount = Discont_detail_order::where('id_detail_order', $request->get('id'))->get();
            //         foreach($discount as $dis){
            //             $dis->delete();
            //         }

            //         $sumDetail = DetailOrder::where('id_order', $request->get('id_order'))->sum('total');
            //         $sumDis = Discount_detail_order::whereHas('Detail_order', function($query) use ($request){
            //             $query->where('id_order', $request->get('id_order'));
            //         })->sum('total_discount');

            //         $subTotal = $sumDetail - $sumDis ;

            //         $taxpb1 = Taxes::where('nama', 'PB1')->first();
            //         $service = Taxes::where('nama', 'Service Charge')->first();  
            //         $PB1 = $taxpb1->tax_rate / 100;
            //         $Service = $service->tax_rate / 100;
            //         $nominalPb1 =  $subTotal * $PB1;
            //         $nominalService = $subTotal * $Service;

            //         $gradTotal = $subTotal + $nominalPb1 + $nominalService;

            //         $order = Orders::where('id', $request->get('id_order'))->first();
            //         $order->subtotal = $subTotal;
            //         $order->total_order = $gradTotal;

            //         $order->save();
            //         return response()->json([
            //             'success' => 1,
            //             'message' => 'Data berhasil Dihapus',
            //             'data' => $Details,
            //             'data_order' => $order,

            //         ]);
            // }

        } else {
            return redirect()->route('login');
        }
    }


    public function hapus(Request $request)
    {
        if (Sentinel::check()) {

            try {
                $cart = Session::get('cart');

                if ($cart) {
                    foreach ($cart as $key => $value) {
                        if ($key == $request->get('id')) {
                            unset($cart[$key]);
                        }
                    }
                }

                Session::put('cart', $cart);
                Session::save();
                $cart = Session::get('cart');

                return response()->json([
                    'success' => 1,
                    'message' => 'Data berhasil Dihapus',
                    'data' => $cart,
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => ' Failed to fatch delete item session',
                    'data' => $cart,
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function itemSplitBill(Request $request)
    {
        if (Sentinel::check()) {

            $refBill = $request->refId;
            // $request->refId;
            $Bill = Orders::where('id', $refBill)->first();
            $Details = DetailOrder::where('id_order', $Bill->id)->get();
            $orderBill = Session::put('current_order', $Bill->id);
            $taxs = Taxes::all();

            $totalDis = 0;
            $subtotal = 0;
            $nameAdds = 0;
            $hargaAdds = 0;
            $totalAdds = 0;

            foreach ($Details as $k =>  $cart) {
                foreach ($cart->Discount_menu_order as $discounts) {

                    $totalDis = +$discounts->discount->rate_dis;
                }
                foreach ($cart->AddOptional_order as $adds) {

                    $totalAdds = +$adds->optional_Add->harga;
                }


                $subtotal += $cart->total;
            }

            return view('POS.part_lain.detail_splitBill', compact(
                'Bill',
                'Details',
                'totalDis',
                'subtotal',
                'taxs',
                'orderBill',
                'nameAdds',
                'hargaAdds',
                'totalAdds'
            ));
        } else {
            return redirect()->route('login');
        }
    }

    public function splitBill(Request $request)
    {
        if (Sentinel::check()) {
            try {
                $userId = Sentinel::getUser();
                $admin = $userId->id;
                $rand = $this->kodePesanan();
                $date = Carbon::now()->format('Y-m-d');

                $order = Orders::where('id', $request->get('target_order'))->first();
                $order->subtotal = $request->subtotal;
                $order->total_order = $request->total;
                // dd($order);
                $order->save();

                $orderNew = new Orders();
                $orderNew->id_admin = $admin;
                $orderNew->name_bill = $order->name_bill . '-Split bill';
                $orderNew->id_booking = $order->id_booking;
                $orderNew->kode_pemesanan = $rand;
                $orderNew->no_meja = $order->no_meja;
                $orderNew->id_status = 1;
                $orderNew->subtotal = $request->subtotalNew;
                $orderNew->total_order = $request->totalNew;
                $orderNew->cash = $request->cash;
                $orderNew->tanggal = $date;
                $orderNew->change_ = $request->change;
                $orderNew->id_type_payment = $request->type_pyment;
                $orderNew->id_status = 2;

                //dd($orderNew);
                if ($orderNew->save()) {
                    // if($request->has('itms')){
                    $itms = $request->itms;
                    // dd($itms);
                    foreach ($itms as $itm) {
                        $itmDetail = DetailOrder::where('id', $itm['id_item'])->first();

                        if ($itm['qty'] == $itmDetail->qty) {

                            $itmDetail->id_order = $orderNew->id;
                            $itmDetail->save();
                        } else {
                            // new item if the split bill ID qty is not the same as the old qty
                            // and bill order id details split new qty in less with old qty and more update also total
                            // and additional updates of quantity, quantity and likes also order discount details

                            $itmDetail->qty = $itmDetail->qty - $itm['qty'];
                            $itmDetail->total = $itmDetail->total - $itm['jumlah'];

                            if ($itmDetail->save()) {
                                $itms_old_adds = Additional_menu_detail::where('id_detail_order', $itmDetail->id)->get();

                                foreach ($itms_old_adds as $adds_old) {
                                    $update_adds = Additional_menu_detail::where('id', $adds_old->id)->first();
                                    $priceAdds = OptionModifier::where('id', $update_adds->id_option_additional)->first();
                                    $update_adds->qty = $itmDetail->qty;
                                    $update_adds->total = $priceAdds->harga * $itmDetail->qty;

                                    $update_adds->save();
                                }

                                $itms_old_discount = Discount_detail_order::where('id_detail_order', $itmDetail->id)->get();

                                foreach ($itms_old_discount as $dis_old) {
                                    $update_dis = Discount_detail_order::where('id', $dis_old->id)->first();
                                    $rate_dis = Discount::where('id', $update_dis->id_discount)->first();
                                    $update_dis->total_discount = $itmDetail->total * ($rate_dis->rate_dis / 100);

                                    $update_dis->save();
                                }
                            }

                            $itmOrder = new DetailOrder();
                            $itmOrder->id_order = $orderNew->id;
                            $itmOrder->harga = $itm['harga'];
                            $itmOrder->total = $itm['jumlah'];
                            $itmOrder->id_menu = $itmDetail->id_menu;
                            $itmOrder->qty = $itm['qty'];
                            $itmOrder->catatan = $itmDetail->catatan;
                            $itmOrder->id_sales_type = $itmDetail->id_sales_type;
                             $itmOrder->id_varian = $itm['varian'];

                            if ($itmOrder->save()) {
                                if (isset($itm['Adds'])) {
                                    $Adds = $itm['Adds'];

                                    foreach ($Adds as $itmAdds) {
                                        $adds = new Additional_menu_detail();
                                        $adds->id_detail_order = $itmOrder->id;
                                        $adds->id_option_additional = $itmAdds['id'];
                                        $adds->qty = $itmOrder->qty;
                                        $adds->total = $itmAdds['hargaAds'] * $itmOrder->qty;

                                        $adds->save();
                                    }
                                }

                                if (isset($itm['Discount'])) {
                                    $discount = $itm['Discount'];

                                    foreach ($discount as $Itmdis) {
                                        $dis = new Discount_detail_order();
                                        $dis->id_detail_order = $itmOrder->id;
                                        $dis->id_discount = $Itmdis['id'];

                                        $rateDis = $Itmdis['rate'] / 100;
                                        $nominalDis = $itmOrder->total * $rateDis;
                                        $dis->total_discount = $nominalDis;

                                        $dis->save();
                                    }
                                }
                            }
                        }
                    }
                    // }
                }
                return response()->json([
                    'success' => 1,
                    'message' => 'Data Tersimpan',
                    'data' => [
                        'new_order' => $orderNew,
                        'items' => $itms
                    ]
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to split bill',
                    'data' => [
                        'new_order' => $orderNew,
                        'items' => $itms
                    ],
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            return redirect()->route('login');
        }
    }


    public function postOrderPOS(Request $request)
    {
        if (Sentinel::check()) {
            try {

                $date = Carbon::now()->format('Y-m-d');
                $userId = Sentinel::getUser();
                $admin = $userId->id;
                $rand = $this->kodePesanan();
                $paymentId = $request->Idpayment;

                $order = new Orders;
                $order->id_admin = $admin;
                $order->name_bill = $request->nama;
                $order->id_booking = $request->id_booking;
                $order->kode_pemesanan = $rand;
                $order->no_meja = $request->nomer;
                $order->subtotal = $request->subtotal;
                $order->total_order = $request->total;
                $order->tanggal = $date;
                $order->id_type_payment = $paymentId;
                $order->cash = $request->cash;
                $order->change_ = $request->change_;
                $order->total_order = $request->total;

                if (!empty($paymentId)) {
                    $order->id_status = 2;
                } else {
                    $order->id_status = 1;
                }

                $order->created_at = now();
                $order->updated_at = now();

                $order->save();

                $carts = Session::get('cart');
                //dd($carts);
                $id_order = $order->id;

                foreach ($carts as $cart) {
                    
                    $detail = new DetailOrder;
                    $detail->id_order = $id_order;
                    $detail->id_menu = $cart['id'];
                    $detail->qty = $cart['qty'];
                    $detail->harga = $cart['harga'];
                    $detail->id_varian = $cart['variasi_id'];
                    if (empty($cart['type_id'])) {
                        $detail->id_sales_type =  '4';
                    } else {
                        $detail->id_sales_type =  $cart['type_id'];
                    }

                    $detail->catatan = $cart['catatan'];
                    $detail->total = ($cart['harga'] + $cart['harga_addtotal']) * $cart['qty'];
                    $detail->created_at = now();
                    $detail->updated_at = now();
                    // dd($order, $detail );

                    if ($detail->save()) {
                        if (isset($cart['additional'])) {
                            foreach ($cart['additional'] as $adds) {
                                $additional = new Additional_menu_detail();
                                $additional->id_detail_order = $detail->id;
                                $additional->id_option_additional = $adds['id'];
                                $additional->qty = $detail->qty;
                                $additional->total = $adds['harga'] * $detail->qty;
                                $additional->save();
                            }
                        }
                        if (isset($cart['discount'])) {
                            foreach ($cart['discount'] as $discounts) {
                                $Discount = new Discount_detail_order();
                                $Discount->id_detail_order = $detail->id;
                                $Discount->id_discount = $discounts['id'];
                                $rateDis = $discounts['percent'] / 100;
                                $Discount->total_discount = $discounts['nominal'];
                                // dd($Discount);
                                $Discount->save();
                            }
                        }
                    }
                }

                $Tax = $request->taxes;

                foreach ($Tax as $taxs) {
                    $taxes = new TaxOrder();
                    $taxes->id_order  =  $id_order;
                    $taxes->id_tax = $taxs['id'];
                    $taxes->total_tax = $taxs['nominal'];
                    // dd($taxes);
                    $taxes->save();
                }


                $detail = DetailOrder::where('id_order', $order->id)->get();
                if(empty($detail)){
                    $detail = 0;
                }

                Session::forget('cart');
                return response()->json([
                    'success' => 1,
                    'message' => 'Order di Proses',
                    'data' => [
                        'order' => $order,
                        'detail' => $detail
                    ],

                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Failed to fetch data post order',
                    'data' => [
                        'order' => $order,
                        'detail' => $detail
                    ],
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            return redirect()->route('login');
        }
    }


    public function getDataBillDetail(Request $request)
    {
        if (Sentinel::check()) {

            try {
                $refBill = $request->refId;
                // $request->refId;
                $Bill = Orders::where('id', $refBill)->first();
                $Details = DetailOrder::where('id_order', $Bill->id)->get();
                $orderBill = Session::put('current_order', $Bill->id);
                $taxs = Taxes::all();

                $totalDis = 0;

                $subtotal = 0;
                $nameAdds = 0;
                $hargaAdds = 0;
                $totalAdds = 0;

                foreach ($Details as $k =>  $cart) {

                    foreach ($cart->Discount_menu_order as $discounts) {

                        $totalDis = +$discounts->discount->rate_dis;
                    }
                    foreach ($cart->AddOptional_order as $adds) {

                        $totalAdds = +$adds->optional_Add->harga;
                    }


                    $subtotal += $cart->total;
                }
                $view = view('POS.part_lain.detail_bill', compact(
                    'Bill',
                    'Details',
                    'totalDis',
                    'subtotal',
                    'taxs',
                    'orderBill',
                    'nameAdds',
                    'hargaAdds',
                    'totalAdds'
                ))->render();
                return response()->json([
                    "message" => "get data bill berhasil diambil",
                    "data" => [
                        'Bill' => $Bill,
                        'Details' => $Details,
                        'totalDis' => $totalDis,
                        'subtotal' => $subtotal,
                        'taxs' => $taxs,
                        'orderBill' => $refBill,
                        'nameAdds' => $nameAdds,
                        'hargaAdds' => $hargaAdds,
                        'totalAdds' => $totalAdds,
                    ],
                    'view' => $view
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to fetch data detail order',
                    "data" => [
                        'Bill' => $Bill,
                        'Details' => $Details,
                        'totalDis' => $totalDis,
                        'subtotal' => $subtotal,
                        'taxs' => $taxs,
                        'orderBill' => $refBill,
                        'nameAdds' => $nameAdds,
                        'hargaAdds' => $hargaAdds,
                        'totalAdds' => $totalAdds,
                    ],
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function clearSession()
    {
        Session::forget('cart');
        // return redirect()->back();
    }

    public function paymentProses(Request $request)
    {
        if (Sentinel::check()) {
            try {
                $paymentId = $request->Idpayment;
                $date = Carbon::now()->format('Y-m-d');

                if (!empty(Sentinel::getUser())) {
                    $userId = Sentinel::getUser();
                    $admin = $userId->id;
                } else {
                    $admin = $request->idUser;
                }

                $order = Orders::where('id', $request->id)->first();

                if ($order->id_admin == null) {
                    $order->id_admin = $admin;
                }

                $order->id_type_payment = $paymentId;
                $order->cash = $request->cash;
                $order->change_ = $request->change_;
                $order->total_order = $request->total;
                $order->id_status = 2;
                $order->tanggal = $date;
                $order->updated_at = now();
                $order->save();

                $total_order = intval($order->subtotal);
                $point = ($total_order * 1) / 1000;
                // $order = Orders::where('id', $detail->id_order)->first();

                if (!!$order->id_user) {

                    $totalPoint = Point_User::where('id_user', $order->id_user)->OrderBy('id_user', 'DESC')->first();
                    $point_user = new Point_User();
                    $point_user->id_user =  $order->id_user;
                    $point_user->id_order = $order->id;
                    $point_user->tanggal = Carbon::now()->toDateTimeString();
                    $point_user->point_in = $point;
                    $point_user->keterangan = 'Points reduced  ' . $point . 'to claim Vocher ' . $order->kode_pemesanan;

                    // dd($point_user);
                    $point_user->save();

                    $notify = new Notify_user();
                    $notify->id_user =  $order->id_user;
                    $notify->message = $point_user->keterangan;
                    $notify->tanggal = Carbon::now()->toDateTimeString();
                    $notify->status = 'unread';
                    $notify->save();

                    $message = [
                        'message' => $point_user->keterangan
                    ];
                    event(new MessageCreated($message));
                }
                return response()->json([
                    'success' => 1,
                    'message' => 'Order di Proses',
                    'data' => $order

                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Failed to fetch payment proses',
                    'data' => $order,
                    'error' => $e->getMessage()
                ], 500);
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function PrintBill($id)
    {
        if (Sentinel::check()) {
            // $xid = $request->idBill;

            $order = Orders::where('id', $id)->first();
            $detail = DetailOrder::where('id_order', $order->id)->get();
            $details = DetailOrder::where('id_order', $order->id)->whereHas('menu', function ($query) {
                $query->where('id_kategori', 2);
            })->get();
            // $taxs =TaxOrder::where('id_order', $order->id)->get();
            $taxs = Taxes::all();
            $totalDis = 0;
            $subtotal = 0;
            $totalTax = 0;

            foreach ($detail as $k =>  $cart) {

                foreach ($cart->Discount_menu_order as $discounts) {
                    // dd($discounts->get());
                    $totalDis = +$discounts->discount->rate_dis;
                }
                foreach ($cart->AddOptional_order as $adds) {

                    //  dd($nameAdds , $hargaAdds);
                }
                $subtotal = $subtotal + $cart['total'];
            }


            return view('POS.part_lain.print_bill', compact('order', 'detail', 'totalDis', 'subtotal', 'taxs', 'totalTax', 'details'));
        } else {
            return redirect()->route('login');
        }
    }

    public function printTiket($id)
    {
        if (Sentinel::check()) {

            $order = Orders::where('id', $id)->first();
            $detail = DetailOrder::where('id_order', $order->id)->where(function ($query) use ($order) {
                $query->whereRaw('created_at > (SELECT IFNULL(MAX(last_print), 0) FROM detail_order WHERE id_order = ' . $order->id . ')')
                    ->orWhereNull('last_print');
            })->get();

            // $taxs =TaxOrder::where('id_order', $order->id)->get();
            $taxs = Taxes::all();

            return view('POS.part_lain.print_tiket', compact('order', 'detail', 'taxs'));
        } else {
            return redirect()->route('login');
        }
    }
    public function printKitchen($id)
    {
        if (Sentinel::check()) {

            $order = Orders::where('id', $id)->first();
            $detail = DetailOrder::where('id_order', $order->id)->get();
            $details = DetailOrder::where('id_order', $order->id)
                ->where(function ($query) use ($order) {
                    $query->whereRaw('created_at > (SELECT IFNULL(MAX(last_print), 0) FROM detail_order WHERE id_order = ' . $order->id . ')')
                        ->orWhereNull('last_print');
                })->whereHas('menu', function ($query) {
                    $query->where('id_kategori', 2);
                })->get();

            $taxs = Taxes::all();

            return view('POS.part_lain.print_kitchen', compact('order', 'detail', 'taxs', 'details'));
        } else {
            return redirect()->route('login');
        }
    }

    public function updateLastPrint(Request $request, $id)
    {
        try {
            $print = $request->print;

            $order = Orders::where('id', $id)->first();

            if ($print == 'Tiket') {
                $detail = DetailOrder::where('id_order', $order->id)->where(function ($query) use ($order) {
                    $query->whereRaw('created_at > (SELECT IFNULL(MAX(last_print), 0) FROM detail_order WHERE id_order = ' . $order->id . ')')
                        ->orWhereNull('last_print');
                })->whereHas('menu', function ($query) {
                    $query->where('id_kategori', 1);
                })->get();

                foreach ($detail as $details) {
                    $details->last_print = Carbon::now();
                    $details->save();
                }
            } else if ($print == 'Kitchen') {
                $detail = DetailOrder::where('id_order', $order->id)->where(function ($query) use ($order) {
                    $query->whereRaw('created_at > (SELECT IFNULL(MAX(last_print), 0) FROM detail_order WHERE id_order = ' . $order->id . ')')
                        ->orWhereNull('last_print');
                })->whereHas('menu', function ($query) {
                    $query->where('id_kategori', 2);
                })->get();

                foreach ($detail as $details) {
                    $details->last_print = Carbon::now();
                    $details->save();
                }
            } else {
            }

            return response()->json([
                'success' => 1,
                'message' => 'Data last print di update',
                //'data' => $detail
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch update last print',
                // 'data' => $detail,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function printTodataServer(Request $request, $id)
    {
        $DataServe = Http::get('https://admin.goodfellas.id/api/getDataToPrint/' . $id);
        $data = $DataServe->json();
        $detail = $data['Detail'];
        $details = $data['details'];
        $order = $data['data'];
        $tax = $data['tax'];
        //dd($order['admin']['nama']);

        $totalDis = 0;
        $subtotal = 0;
        $totalTax = 0;

        foreach ($detail as $k =>  $cart) {

            foreach ($cart['discount_menu_order'] as $discounts) {
                // dd($discounts->get());
                $totalDis = +$discounts['discount']['rate_dis'];
            }
            foreach ($cart['add_optional_order'] as $adds) {

                //  dd($nameAdds , $hargaAdds);
            }
            $subtotal = $subtotal + $cart['total'];
        }

        return view('POS.part_lain.print_bill_data_server', compact('detail', 'order', 'tax', 'totalDis', 'subtotal', 'totalTax', 'details'));
    }


    public function ItemSplitTodataServer(Request $request, $id)
    {
        $DataServe = Http::get('https://admin.goodfellas.id/api/getDataToPrint/' . $id);
        $data = $DataServe->json();
        $Bill = $data['data'];
        $Details = $data['Detail'];
        $orderBill = $data['data']['id'];
        $taxs = $data['tax'];
        //dd($order['admin']['nama']);

        $totalDis = 0;
        $subtotal = 0;
        $nameAdds = 0;
        $hargaAdds = 0;
        $totalAdds = 0;

        foreach ($Details as $k =>  $cart) {

            foreach ($cart['discount_menu_order'] as $discounts) {
                // dd($discounts->get());
                $totalDis = +$discounts['discount']['rate_dis'];
            }
            foreach ($cart['add_optional_order'] as $adds) {

                //  dd($nameAdds , $hargaAdds);
            }
            $subtotal = +$cart['total'];
        }

        return view('POS.part_lain.detail_split_bill_server', compact(
            'Bill',
            'Details',
            'totalDis',
            'subtotal',
            'taxs',
            'orderBill',
            'nameAdds',
            'hargaAdds',
            'totalAdds'
        ));
    }

    public function printData(Request $request, $id)
    {
        $total_dis = 0;
        $type_file = $request->type;
        $billOrder = Orders::where('id', $id)->first();

        if ($type_file == 'bill') {
            $templateDoc = new TemplateProcessor('asset/assets/file_print/test-template.docx');
            $detailOrder = DetailOrder::where('id_order', $billOrder->id)->get();
        } else if ($type_file == 'Tiket') {
            $templateDoc = new TemplateProcessor('asset/assets/file_print/template-tiket.docx');
            $detailOrder = DetailOrder::where('id_order', $billOrder->id)->where(function ($query) use ($billOrder) {
                $query->whereRaw('created_at > (SELECT IFNULL(MAX(last_print), 0) FROM detail_order WHERE id_order = ' . $billOrder->id . ')')
                    ->orWhereNull('last_print');
            })->get();
        } else {
            $templateDoc = new TemplateProcessor('asset/assets/file_print/template-kitchen.docx');
            $detailOrder = DetailOrder::where('id_order', $billOrder->id)->where(function ($query) use ($billOrder) {
                $query->whereRaw('created_at > (SELECT IFNULL(MAX(last_print), 0) FROM detail_order WHERE id_order = ' . $billOrder->id . ')')
                    ->orWhereNull('last_print');
            })->whereHas('menu', function ($query) {
                $query->where('id_kategori', 2);
            })->get();
        }


        $templateDoc->setValue('date', $billOrder->created_at);
        $templateDoc->setValue('kode_order', $billOrder->kode_pemesanan);
        if (!empty($billOrder->no_meja)) {
            $templateDoc->setValue('no_meja', $billOrder->no_meja);
        } else {
            $templateDoc->setValue('no_meja', $billOrder->name_bill);
        }

        if ($type_file == 'bill') {

            $templateDoc->setValue('total', 'Rp.' . number_format($billOrder->total_order, 0, ',', '.'));
            $templateDoc->setValue('sub_total_order', 'Rp. ' . number_format($billOrder->subtotal, 0, ',', '.'));
        }


        $detailOrderLength = count($detailOrder);
        $templateDoc->cloneBlock('item_block', $detailOrderLength, true, true);

        foreach ($detailOrder as $k => $detail) {
            $num = $k + 1;
            $total_dis = 0;


            if ($type_file == 'Tiket' || $type_file == 'Kitchen') {
                if ($detail->update === 1) {
                    $templateDoc->setValue('update#' . $num, 'Item Update');
                } else {
                    $templateDoc->deleteRow('update#' . $num);
                }
            }
            $templateDoc->setValue('nama_menu#' . $num, $detail->menu->nama_menu);
            $templateDoc->setValue('qty#' . $num, $detail->qty);
            $templateDoc->setValue('total_row#' . $num, 'Rp. ' . number_format($detail->total, 0, ',', '.'));
            if (isset($detail->varian)) {
                $templateDoc->setValue('variasi#' . $num, $detail->varian->nama);
            } else {
                // $templateDoc->deleteRow('variasi#'.$num);
                $templateDoc->deleteRow('variasi#' . $num);
            }

            $additional = Additional_menu_detail::where('id_detail_order', $detail->id)->get();
            if ($additional->isNotEmpty()) {
                foreach ($additional as $add) {

                    $templateDoc->setValue('additional#' . $num, $add->optional_Add->name);
                    if ($type_file == 'bill') {
                        $templateDoc->setValue('harga_add#' . $num, number_format($add->optional_Add->harga, 0, ',', '.'));
                    }
                }
            } else {
                $templateDoc->deleteRow('additional#' . $num);
                // $templateDoc->deleteRow('harga_add#'.$num);
            }

            if (!empty($detail->id_sales_type)) {
                $templateDoc->setValue('type_order#' . $num, $detail->salesType->name);
            } else {
                $templateDoc->deleteRow('type_order#' . $num);
            }

            // discount
            if ($type_file == 'bill') {
                if ($detail->Discount_menu_order->isNotEmpty()) {
                    foreach ($detail->Discount_menu_order as $discount) {
                        $totalDis = +$discount->discount->rate_dis;

                        $nominalDis = 0;
                        $Dis = $totalDis / 100;
                        $nominalDis = str_replace(".", "",  $detail->total) * $Dis;
                        $total_dis += $nominalDis;
                        $templateDoc->setValue('discount#' . $num, 'Discount - ' . number_format($nominalDis, 0, ',', '.'));
                    }
                } else {
                    $templateDoc->deleteRow('discount#' . $num);
                }
            }


            if (isset($detail->catatan)) {
                $templateDoc->setValue('catatan#' . $num, $detail->catatan);
            } else {
                $templateDoc->deleteRow('catatan#' . $num);
            }
        }

        // tax bill
        if ($type_file == 'bill') {
            $tax = Taxes::all();
            $totalTax = 0;
            $count_tax = count($tax);
            $templateDoc->cloneRow('name_tax', $count_tax);

            foreach ($tax as $k => $taxs) {
                $num = $k + 1;
                $nominalTax = 0;
                $desimalTax = $taxs->tax_rate / 100;
                $nominalTax = str_replace(".", "", $billOrder->subtotal) * $desimalTax;
                $totalTax += $nominalTax;

                $templateDoc->setValue('name_tax#' . $num, $taxs->nama);
                $templateDoc->setValue('rate#' . $num, $taxs->tax_rate);
                $templateDoc->setValue('nominal#' . $num, 'Rp. ' . number_format($nominalTax, 0, ',', '.'));
            }
        }


        // booking order room
        if ($type_file == 'bill') {
            if (!empty($billOrder->id_booking)) {
                $sisaBayar = 0;
                $sisaBayar = $billOrder->total - $billOrder->booking->nominal_dp;

                $templateDoc->setValue('tagName', 'Deposit :');
                $templateDoc->setValue('nominal_dp', 'Rp' . number_format($billOrder->booking->nominal_dp, 0, ',', '.'));

                if ($sisaBayar > 0) {
                    $templateDoc->setValue('nama_DP', 'Sisa Bayar');
                } else {
                    $templateDoc->setValue('nama_DP', 'Lebih Bayar');
                }

                $templateDoc->setValue('sisa_bayar', 'Rp' . number_format($sisaBayar, 0, ',', '.'));
            } else {
                $templateDoc->deleteRow('tagName');

                $templateDoc->deleteRow('nama_DP');
            }

            if (!empty($billOrder->id_type_payment)) {

                $templateDoc->setValue('nama_payment', $billOrder->payment->nama);
                $templateDoc->setValue('cash', 'Rp' . number_format($billOrder->cash, 0, ',', '.'));
                $templateDoc->setValue('change', 'Change');
                $templateDoc->setValue('nominal_change', 'Rp' . number_format($billOrder->change_, 0, ',', '.'));
            } else {
                $templateDoc->deleteRow('nama_payment');

                $templateDoc->deleteRow('change');
            }
        }



        if ($type_file == 'bill') {
            $templateDoc->saveAs('asset/assets/bill/bill_' . $billOrder->kode_pemesanan . '.docx');
            $file_path = 'asset/assets/bill/bill_' . $billOrder->kode_pemesanan . '.docx';
        } else if ($type_file == 'Tiket') {
            $templateDoc->saveAs('asset/assets/bill/Tiket_' . $billOrder->kode_pemesanan . '.docx');
            $file_path = 'asset/assets/bill/Tiket_' . $billOrder->kode_pemesanan . '.docx';
        } else {
            $templateDoc->saveAs('asset/assets/bill/Kitchen_' . $billOrder->kode_pemesanan . '.docx');
            $file_path = 'asset/assets/bill/Kitchen_' . $billOrder->kode_pemesanan . '.docx';
        }
        $file = basename($file_path);
        return response()->json([
            'success' => 1,
            'message' => 'get data',
            'data' => $file,
        ]);
    }

    public function printDataItemDelete(Request $request)
    {
        $total_dis = 0;
        $type_file = $request->type;
        $billOrder = Orders::where('id', $request->id_order)->first();
        $subtotal = 0;
        $grandTotal = 0;


        $templateDoc = new TemplateProcessor('asset/assets/file_print/tamplate-item-delete.docx');
        $detailOrder = DetailOrder::where('id', $request->id)->first();



        $templateDoc->setValue('date', $billOrder->created_at);
        $templateDoc->setValue('kode_order', $billOrder->kode_pemesanan);

        if (!empty($billOrder->no_meja)) {
            $templateDoc->setValue('no_meja', $billOrder->no_meja);
        } else {
            $templateDoc->setValue('no_meja', $billOrder->name_bill);
        }
        $templateDoc->setValue('nama_menu', $detailOrder->menu->nama_menu);
        $templateDoc->setValue('qty', $detailOrder->qty);
        $templateDoc->setValue('total_row', 'Rp. ' . number_format($detailOrder->total, 0, ',', '.'));
        if (isset($detailOrder->varian)) {
            $templateDoc->setValue('variasi', $detailOrder->varian->nama);
        } else {
            // $templateDoc->deleteRow('variasi#'.$num);
            $templateDoc->deleteRow('variasi');
        }

        $additional = Additional_menu_detail::where('id_detail_order', $detailOrder->id)->get();

        $adds = count($additional);
        $templateDoc->cloneBlock('item_block', $adds, true, true);


        foreach ($additional as $k => $add) {
            $num = $k + 1;

            $templateDoc->setValue('additional#' . $num, $add->optional_Add->name);
        }


        if (!empty($detailOrder->id_sales_type)) {
            $templateDoc->setValue('type_order', $detailOrder->salesType->name);
        } else {
            $templateDoc->deleteRow('type_order');
        }


        if (isset($detailOrder->catatan)) {
            $templateDoc->setValue('catatan', $detailOrder->catatan);
        } else {
            $templateDoc->deleteRow('catatan');
        }





        $templateDoc->saveAs('asset/assets/bill/ItemDelete_' . $billOrder->kode_pemesanan . '.docx');
        $file_path = 'asset/assets/bill/ItemDelete_' . $billOrder->kode_pemesanan . '.docx';


        $file = basename($file_path);

        return response()->json([
            'success' => 1,
            'message' => 'get data to delete',
            'data' => $file,
            'detailItem' => $detailOrder,
        ]);
    }

    public function updateSalesTypeOnDetailOrder()
    {
        $detail = DetailOrder::where('id_sales_type', null)->get();

        foreach ($detail as $data) {
            $data->id_sales_type = 4;
            $data->save();
        }


        if ($data) {
            return response()->json([
                'message' => 'Data updated successfully',
                'data' => $detail
            ], 200);
        } else {
            return response()->json([
                'message' => 'failed updated ',
                'data' => $detail

            ], 500);
        }
    }

    public function Action_log(Request $request)
    {

        if (Sentinel::check()) {
            try {
                $userId = Sentinel::getUser();
                $admin = $userId->id;

                $activity = new Aktivity();
                $activity->id_admin = $admin;
                $activity->keterangan = $request->action;
                if (is_array($request->detail) || is_object($request->detail)) {
                    $activity->detail = json_encode($request->detail); // Konversi ke JSON
                } else {

                    return response()->json([
                        'message' => 'Invalid data format for detail field',
                    ], 422);
                }
                $activity->save();

                return response()->json([
                    'message' => 'Action log success',
                    'data' => $activity
                ], 201);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'failed request response action log',
                    'data' => $e->getMessage()
                ], 500);
            }
        } else {
            return redirect()->route('login');
        }
    }

    public function getDiscount()
    {
        $discount = Discount::all();

        return view('POS.part_lain.PopUpDiscount', compact('discount'));
    }
}

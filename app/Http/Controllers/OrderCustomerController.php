<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Kategori;
use App\Models\SubKategori;
use App\Models\SalesType;
use Illuminate\Support\Facades\Http;
use App\Models\DetailOrder;
use App\Models\OptionModifier;
use App\Models\VarianMenu;
use App\Models\Taxes;
use Illuminate\Support\Facades\DB;
use Session;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use App\Services\KodePesananService;
use Illuminate\Support\Carbon;
use App\Models\Orders;
use App\Models\Additional_menu_detail;
use App\Events\OrderCustomerCreate;
use App\Models\TaxOrder;
class OrderCustomerController extends Controller
{

    protected KodePesananService $kode_pesanan;

    public function __construct(KodePesananService $kode_pesanan){
        $this->kode_pesanan = $kode_pesanan;
    }

    public function index(Request $request){

        $itemMenu = Menu::where('custom', false)->where('delete_menu', 0)->get();
        $Category = Kategori::all();
        $subCategory = SubKategori::where('deleted', 0)->get();
        // $discount = Discount::all();
        // $payment = TypePayment::all();
        $typeOrder = SalesType::all();
        $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
        $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();
        $meja = $request->query('meja');
        Session::put('meja', $meja);
        Session::save();


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

        return view('CustomerOrder.main_content', compact('topSellingItems','Category','subCategory', 'typeOrder'));
    }

    public function category($slug){

        $Cat = Kategori::where('kategori_nama', $slug)->first();

        $ItemCats = Menu::where('id_kategori', $Cat->id)
        ->where('custom', false)->where('delete_menu', 0)->get();

        $subcat = SubKategori::where('id_kategori', $Cat->id)->get();

        return view('CustomerOrder.categoryMenu', compact('Cat', 'ItemCats','subcat'));

    }

    public function Subcat($slug){
       
        $subcat_tgt = SubKategori::where('slug', $slug)->first();
        $itemSub = Menu::where('id_sub_kategori', $subcat_tgt->id)->where('custom', false)->where('delete_menu', 0)->get();
        $subcat = SubKategori::where('id_kategori', $subcat_tgt->id_kategori)->get();
        
        return view('CustomerOrder.SubCategoryMenu', compact('subcat_tgt', 'itemSub', 'subcat'));
    }

   public function additional(Request $request){
        $xid = $request->ex;
        // dd($xid);
        $dec = decrypt($xid);
        $itemMenu = Menu::with(['varian','kategori'])->find($dec); 
        // dd($itemMenu, $dec, $xid);
        $carts = Session::get('cart');
        $itemEdit= 0 ;
        $totalHarga = 0;
        if ($carts) {
            foreach ($carts as $key => $value) {
                if ($value['id'] == $dec) {
                    $itemEdit = $carts[$key];
                }
            }
        }
        // dd($carts);

        if ($itemMenu && $itemMenu->varian->isNotEmpty()) {
            $varian = $itemMenu->varian;
        } else {
            $varian = null;
        }
       
        if($itemMenu->kategori->kategori_nama == 'Foods'){
            $additional = OptionModifier::where('id_group_modifier', 15)->get();
        }

        if($itemMenu->kategori->kategori_nama == 'Drinks'){
            $additional = OptionModifier::where('id_group_modifier', 16)->get();
        }

        $type_sales = SalesType::all();

        return view('CustomerOrder.part_lain_lain.pop_aditional', compact('varian', 'additional','type_sales','itemMenu', 'itemEdit','totalHarga'));
   }

   public function AddTocart(Request $request){
        try {

            $menu = Menu::where('id', $request->get('id'))->first();
            if($request->get('variasi') !== 0){
                $varian = VarianMenu::where('id', $request->get('variasi'))->first();
            }else{
                $varian= '';
            }
            
            $typeSales = SalesType::find($request->get('id_type_sales'));
            $ex = false;
            $exId = 0;
            $cart = Session::get('cart');
            $count = 0;
            $currentPrice = 0;
            $currentPrice = $menu->harga;
            $harga_menu = 0;


            
           

            $cart[] = array(
                'id' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'image' => $menu->image,
                'harga' => $request->get('harga'),
                'qty' => $request->get('qty'),
                'harga_addtotal' => $request->get('harga_addtotal'),
                'variasi_id' => $varian ? $varian->id : '',
                'var_name' => $varian ? $varian->nama : '',
                'additional' => $request->get('additional'),
                'catatan' => $request->get('catatan'),
                'type_id' => $typeSales->id,
                'type_name' => $typeSales->name,
             
            );

            Session::put('cart', $cart);
                
            Session::save();
            $cart = Session::get('cart');
            //dd($cart);
            $count = count($cart);
            return response()->json([
                'success' => 1,
                'message' => 'Item success',
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
   }

    public function editOrder(Request $request)
    {

       
            try {
                $menu = Menu::where('id', $request->get('id'))->first();
                if($request->get('variasi') !== 0){
                    $varian = VarianMenu::where('id', $request->get('variasi'))->first();
                }else{
                    $varian= '';
                }
                 $typeSales = SalesType::find($request->get('id_type_sales'));
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
                        'image' => $menu->image,
                        'harga' => $request->get('harga'),
                        'qty' => $request->get('qty'),
                        'harga_addtotal' => $request->get('harga_addtotal'),
                        'variasi_id' => $varian ? $varian->id : '',
                        'var_name' => $varian ? $varian->nama : '',
                        'additional' => $request->get('additional'),
                        'discount' => $request->get('discount'),
                        'catatan' => $request->get('catatan'),
                        'type_id' => $typeSales->id,
                        'type_name' => $typeSales->name,
                        
                    );
                } else {
                    $oldData = $cart[$exId];
                    $cart[$exId] = array(
                        'id' =>  $menu->id,
                        'nama_menu' => $menu->nama_menu,
                        'image' => $menu->image,
                        'harga' => $request->get('harga'),
                        'qty' => $request->get('qty'),
                        'harga_addtotal' => $request->get('harga_addtotal'),
                        'variasi_id' => $varian ? $varian->id : '',
                        'var_name' => $varian ? $varian->nama : '',
                        'additional' =>  $request->get('additional'),
                        'discount' => $request->get('discount'),
                        'catatan' =>  $request->get('catatan'),
                        'type_id' => $typeSales->id,
                        'type_name' => $typeSales->name,
                        
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
       
    }

   public function cartSession(Request $request){
        $table = $request->has('table');
        $carts = Session::get('cart');
        $meja = Session::get('meja');
        $taxs = Taxes::all();
        // dd($carts);
        $subtotal = 0;

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

        return view('CustomerOrder.CartCustomer', compact('carts','subtotal','taxs','meja'));
   }

    public function hapus(Request $request)
    {
       
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
        
    }

    public function clearSession()
    {
        Session::forget('cart');
        // return redirect()->back();
    }

    public function PostOrderCustomer(Request $request)
    {
       DB::beginTransaction();
        try {

                $date = Carbon::now()->format('Y-m-d');
                
                $rand = $this->kode_pesanan->kodePesanan();
                $meja = Session::get('meja');

                $order = new Orders;
                $order->name_bill = $request->customer_name;
                $order->id_booking = $request->id_booking;
                $order->kode_pemesanan = $rand;
                $order->no_meja = $meja;
                $order->subtotal = $request->subtotal;
                $order->total_order = $request->total;
                $order->tanggal = $date;

                if (!empty($paymentId)) {
                    $order->id_status = 2;
                } else {
                    $order->id_status = 1;
                }

                $order->created_at = now();
                $order->updated_at = now();

                $order->save();

                $carts = Session::get('cart');
                // dd($carts);
                $cart = is_array($carts);
                $id_order = $order->id;
                $details = [];
                // if (is_array($carts) || is_object($carts)){
                foreach ($carts as $cart) {
                    
                    $detail = new DetailOrder;
                    $detail->id_order = $id_order;
                    $detail->id_menu = $cart['id'];
                    $detail->qty = $cart['qty'];
                    $detail->harga = $cart['harga'];
                    $detail->id_varian = ($cart['variasi_id'] === '' || $cart['variasi_id'] === null) ? null : $cart['variasi_id'];

                    if (empty($cart['type_id'])) {
                        $detail->id_sales_type =  '4';
                    } else {
                        $detail->id_sales_type =  $cart['type_id'];
                    }

                    $detail->catatan = $cart['catatan'];
                    $detail->total = ($cart['harga'] + $cart['harga_addtotal']) * $cart['qty'];
                    $detail->created_at = now();
                    $detail->updated_at = now();

                    // return dd($order, $detail );
                    $detail->save();
                    if ($detail) {
                        $details[] = $detail;
                        if (isset($cart['additional'])) {
                            foreach ($cart['additional'] as $adds) {
                                $additional = new Additional_menu_detail();
                                $additional->id_detail_order = $detail['id'];
                                $additional->id_option_additional = $adds['id'];
                                $additional->qty = $detail['qty'];
                                $additional->total = $adds['harga'] * $detail['qty'];
                                $additional->save();
                            }
                        }
                        
                    }
                }
                // }
               

                $Tax = $request->tax;
                // dd($Tax);

                foreach ($Tax as $taxs) {
                    $taxes = new TaxOrder();
                    $taxes->id_order  =  $id_order;
                    $taxes->id_tax = $taxs['xid'];
                    $taxes->total_tax = $taxs['nominal'];
                    // dd($taxes);
                    $taxes->save();
                }


                $detail = DetailOrder::where('id_order', $order->id)->get();
                if(empty($detail)){
                    $detail = 0;
                }

                Session::forget('cart');

                // event(new OrderCustomerCreate($order));

                 DB::commit();
                return response()->json([
                    'success' => 1,
                    'message' => 'Your order on proses',
                    'data' => [
                        'order' => $order,
                        'detail' => $detail
                    ],

                ], 200);
        } catch (\Exception $e) {
             DB::rollback();
            return response()->json([
                'success' => 0,
                'data' => [
                    'order' => $order,
                    'detail' => $cart
                ],
                'error' => $e->getMessage()
            ], 500);
        }
       
    }

}

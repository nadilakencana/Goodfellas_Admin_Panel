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
use Illuminate\Support\Facades\Log;

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

        $thresholds = [20, 10, 1];
        $topSellingItems = collect();

        foreach ($thresholds as $threshold) {
            $topSellingItems = DetailOrder::select('id_menu', DB::raw('SUM(qty) as total_qty'), DB::raw('AVG(harga) as avg_price'))
                ->whereHas('order', function ($query) use ($startDate, $endDate) {
                    $query->where('id_status', 2)
                        ->where('deleted', 0)
                        ->whereBetween('tanggal', [$startDate, $endDate]);
                })
                ->groupBy('id_menu')
                ->having('total_qty', '>=', $threshold)
                ->orderByDesc('total_qty')
                ->with('menu')
                ->get();
            
            if ($topSellingItems->isNotEmpty()) {
                break;
            }
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
        $itemMenu = Menu::with(['varian','kategori','additional'])->find($dec); 
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
            $varian = $itemMenu->varian->where('active', 1 );
        } else {
            $varian = null;
        }
       
        if($itemMenu->kategori->kategori_nama == 'Foods'){
            if($itemMenu->additional){
                $additional = $itemMenu->additional->OptionModifier()->where('active', 1)->get();
            }else{
                $additional = [];
            }
        }

        if($itemMenu->kategori->kategori_nama == 'Drinks'){
            if($itemMenu->additional){
                $additional = $itemMenu->additional->OptionModifier()->where('active', 1)->get();
            }else{
                $additional = [];
            }
        }

        $type_sales = SalesType::all();

        return view('CustomerOrder.part_lain_lain.pop_aditional', compact('varian', 'additional','type_sales','itemMenu', 'itemEdit','totalHarga'));
   }

   public function AddTocart(Request $request){
        try {

            $menu = Menu::where('id', $request->get('id'))->where('active', 1)->first();

            if($menu->kategori->kategori_nama === 'Drinks'){
                if (!$menu) {
                    return response()->json([
                        'success' => 0,
                        'message' => 'This menu is currently unavailable.'
                    ], 400); 
                }
            }
           
            if($menu->kategori->kategori_nama === 'Foods'){
                if($menu->tipe_stok === 'Stok Bahan Baku'){
                        if ($menu->bahanBaku->stok_porsi < $request->get('qty')) {
                            return response()->json([
                                'success' => 0,
                                'message' => 'Stok tidak cukup silahkan setting ulang stok'
                            ], 500); 
                        }
                    }else{
                        if ($menu->stok < $request->get('qty')) {
                            return response()->json([
                                'success' => 0,
                                'message' => 'Stok tidak cukup silahkan setting ulang stok'
                            ], 500); 
                        }
                    }
            }

            if($request->get('variasi') !== 0){
                $varian = VarianMenu::where('id', $request->get('variasi'))->first();
            }else{
                $varian= '';
            }

            $stok = 0 ;
            if($menu->tipe_stok === 'Stok Bahan Baku'){
                $stok = $menu->bahanBaku->stok_porsi;
            }else{
                $stok = $menu->stok;
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
            $menu = Menu::where('id', $request->get('id'))->where('active', 1)->first();
            if($menu->kategori->kategori_nama === 'Drinks'){
                if (!$menu) {
                    return response()->json([
                        'success' => 0,
                            'message' => 'This menu is currently unavailable.'
                        ], 400); 
                }
            }
        
            if($menu->kategori->kategori_nama === 'Foods'){
               if($menu->tipe_stok === 'Stok Bahan Baku'){
                        if ($menu->bahanBaku->stok_porsi < $request->get('qty')) {
                            return response()->json([
                                'success' => 0,
                                'message' => 'Stok tidak cukup silahkan setting ulang stok'
                            ], 500); 
                        }
                    }else{
                        if ($menu->stok < $request->get('qty')) {
                            return response()->json([
                                'success' => 0,
                                'message' => 'Stok tidak cukup silahkan setting ulang stok'
                            ], 500); 
                        }
                    }
            }

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
            // dd($request->tax);

                $date = Carbon::now()->format('Y-m-d');
                
                $rand = $this->kode_pesanan->kodePesanan();
                $meja = Session::get('meja');
               
                if (!$meja) {
                    return response()->json([
                        'success' => 0,
                        'message' => 'Table number not found. Please rescan the QR Code at your table.'
                    ], 400); 
                }
               
                $order = Orders::where('name_bill', $request->customer_name)
                    ->where('no_meja', $meja)
                    ->where('id_status', 1)
                    ->where('deleted', 0)
                    ->first();

                $edit = (bool) $order;
                $subtotal = $request->subtotal;
                $total = $request->total;
                $txs = [];

                if ($order) {
                    $subtotal += $order->subtotal;
                    $txs = Taxes::all()->map(fn($tx) => [
                        'xid' => $tx->id,
                        'nominal' => $subtotal * ($tx->tax_rate / 100)
                    ])->toArray();
                    $total = $subtotal + array_sum(array_column($txs, 'nominal'));
                    TaxOrder::where('id_order', $order->id)->delete();
                    $order->update([
                        'subtotal' => $subtotal,
                        'total_order' => $total
                    ]);
                } else {
                    $order = Orders::create([
                        'name_bill' => $request->customer_name,
                        'kode_pemesanan' => $rand,
                        'no_meja' => $meja,
                        'subtotal' => $subtotal,
                        'total_order' => $total,
                        'tanggal' => $date,
                        'id_status' => 1
                    ]);
                }

                $carts = Session::get('cart');
                $menuIds = array_column($carts, 'id');
                $menus = Menu::with('kategori')->whereIn('id', $menuIds)->get()->keyBy('id');
                $details = [];

                foreach ($carts as $cart) {
                    $menu = $menus->get($cart['id']);
                    if (!$menu) {
                        throw new \Exception("Menu dengan ID {$cart['id']} tidak ditemukan");
                    }
                    
                    $kategori = $menu->kategori->kategori_nama;
                    if ($kategori === 'Foods') {
                        // if (!$menu->active || ($menu->active && $menu->stok < $cart['qty'])) {
                        //     return response()->json([
                        //         'success' => 0,
                        //         'message' => $menu->stok < $cart['qty'] 
                        //             ? "This Menu {$menu->nama_menu} is not sufficient."
                        //             : "This Menu {$menu->nama_menu} is currently unavailable."
                        //     ], 400);
                        // }
                        // $menu->decrement('stok', $cart['qty']);
                        // Get actual stock based on tipe_stok
                        
                        $stokTersedia = $menu->tipe_stok === 'Stok Bahan Baku' 
                            ? ($menu->bahanBaku ? $menu->bahanBaku->stok_porsi : 0)
                            : $menu->stok;
                        
                        if (!$menu->active || $stokTersedia < $cart['qty']) {
                            return response()->json([
                                'success' => 0,
                                'message' => !$menu->active 
                                    ? "Menu {$menu->nama_menu} tidak tersedia"
                                    : "Stok menu {$menu->nama_menu} tidak mencukupi. Tersedia: {$stokTersedia}, Dibutuhkan: {$cart['qty']}"
                            ], 400);
                        }
                        
                        // Use StokService for stock reduction
                        $stokService = new \App\Services\StokService();
                        $result = $stokService->prosesOrder($menu->id, $cart['qty'], $orderId ?? null);
                        
                        if (!$result['success']) {
                            return response()->json([
                                'success' => 0,
                                'message' => $result['message']
                            ], 400);
                        }
                    } elseif ($kategori === 'Drinks' && !$menu->active) {
                        return response()->json([
                            'success' => 0,
                            'message' => "This Menu {$menu->nama_menu} is currently unavailable."
                        ], 400);
                    }
                    
                    
                    
                    $detail = DetailOrder::create([
                        'id_order' => $order->id,
                        'id_menu' => $cart['id'],
                        'qty' => $cart['qty'],
                        'harga' => $cart['harga'],
                        'id_varian' => empty($cart['variasi_id']) ? null : $cart['variasi_id'],
                        'id_sales_type' => $cart['type_id'] ?: '4',
                        'catatan' => $cart['catatan'],
                        'total' => ($cart['harga'] + $cart['harga_addtotal']) * $cart['qty']
                    ]);

                    $details[] = $detail;

                    if (!empty($cart['additional'])) {
                        $additionals = array_map(fn($add) => [
                            'id_detail_order' => $detail->id,
                            'id_option_additional' => $add['id'],
                            'qty' => $detail->qty,
                            'total' => $add['harga'] * $detail->qty,
                            'created_at' => now(),
                            'updated_at' => now()
                        ], $cart['additional']);
                        Additional_menu_detail::insert($additionals);
                    }
                }

                $Tax = $edit ? $txs : $request->tax;
                // dd($Tax);
                if ($Tax) {
                    $taxData = array_map(fn($tax) => [
                        'id_order' => $order->id,
                        'id_tax' => $tax['xid'],
                        'total_tax' => $tax['nominal'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ], $Tax);
                    TaxOrder::insert($taxData);
                }

                Session::forget('cart');

                event(new OrderCustomerCreate($order->toArray()));
                Log::info('Event OrderCustomerCreate dikirim', ['order' => $order->id]);
                DB::commit();
                return response()->json([
                    'success' => 1,
                    'message' => 'Your order on proses',
                    'data' => [
                        'order' => $order,
                        'detail' => $details ?: 0
                    ]
                ], 200);
        } catch (\Exception $e) {
             DB::rollback();
            return response()->json([
                'success' => 0,
                // 'data' => [
                //     'order' => $order,
                //     'detail' => $cart
                // ],
                'error' => $e->getMessage()
            ], 500);
        }
       
    }

    public function searchMenu(Request $request){
        $request->validate([
            'search' => 'required|string|min:2',
        ]);

        $search = $request->input('search');

        try{

            $menu = Menu::where('nama_menu', 'LIKE', '%'.$search.'%')
            ->with(['kategori','subKategori'])
            ->where('delete_menu', 0)->where('custom', 0)->get();
            // dd($menu);

            $kategoriIds = $menu->pluck('kategori.id')->unique()->all();


            $subkat = SubKategori::whereIn('id_kategori', $kategoriIds)->get();

            return response()->json([
                'data' => $menu
            ],200);
            
        }catch (\Exception $e){
            return response()->json([
                'error' => $e->getMessage()

            ],500);
        }
       

        
        
       
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Kategori;
use App\Models\SubKategori;
use App\Models\SalesType;
use Illuminate\Support\Facades\Http;
use App\Models\DetailOrder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class OrderCustomerController extends Controller
{
    public function index(){

        $itemMenu = Menu::where('custom', false)->where('delete_menu', 0)->get();
        $Category = Kategori::all();
        $subCategory = SubKategori::where('deleted', 0)->get();
        // $discount = Discount::all();
        // $payment = TypePayment::all();
        $typeOrder = SalesType::all();
        $startDate = \Carbon\Carbon::now()->startOfMonth()->toDateString();
        $endDate = \Carbon\Carbon::now()->endOfMonth()->toDateString();



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

    public function category(Request $request, $slug){

        $category = $request->exid;
        $dec_cat = decrypt($category);

        $Cat = Kategori::where('id', $dec_cat)->first();

        $ItemCats = Menu::where('id_kategori', $Cat->id)
        ->where('custom', false)->where('delete_menu', 0)->get();

        $subcat = SubKategori::where('id_kategori', $Cat->id)->get();

        return view('CustomerOrder.categoryMenu', compact('Cat', 'ItemCats','subcat'));

    }

    public function Subcat(Request $request){
        $subCat = $request->exid;
        $dec_sub = decrypt($subCat);
        $subcat = SubKategori::where('id', $dec_sub)->first();
        $itemSub = Menu::where('id_sub_kategori', $subCat->id)->get();
        
        return view('CustomerOrder.SubCategoryMenu', compact('subcat', 'itemSub'));
    }

}

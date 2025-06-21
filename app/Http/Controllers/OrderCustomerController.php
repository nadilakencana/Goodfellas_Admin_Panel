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

    // public function fetchPosts()
    // {
    //     try {
    //         // endpoint yang mau di pake di taro di sini 
    //         $response = Http::get('https://jsonplaceholder.typicode.com/posts');

    //         //  apakah request berhasil 
    //         if ($response->successful()) {

    //             $data = $response->json(); // Mengubah response menjadi json array


    //             // pilih salah satau return nya 
                
    //             // kalo mau testing dulu di postman pake return seperti ini 
    //             return response()->json([
    //                 'message' => 'Data posts berhasil diambil',
    //                 'data' => $data
    //             ], 200);

    //             // kalo hasilnya mau di tampilin di blade 
    //             return view('sempel.view', compact('data'));

    //         } else {
    //             // Jika request tidak berhasil
    //             return response()->json([
    //                 'message' => 'Gagal mengambil data posts dari API eksternal',
    //                 'status_code' => $response->status(),
    //                 'error' => $response->body()
    //             ], $response->status());
    //         }

    //     } catch (\Exception $e) {
    //         // Menangani error jika terjadi masalah koneksi atau lainnya
    //         return response()->json([
    //             'message' => 'Terjadi kesalahan saat menghubungi API: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

}

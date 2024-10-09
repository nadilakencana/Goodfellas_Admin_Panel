<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
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
use App\Models\BookingTempat;
use App\Models\User;

class ApiGetDataServerController extends Controller
{
    public function postDataServer(Request $request){
        $client = new Client();
        $response =$client->get('https://admin.goodfellas.id/api/getDataOrder');
        $data = json_decode($response->getBody()->getContents(), true);
        //   dd($data);
        $Data = $data['data'];
        foreach($Data as $dt){

            $Orders = Orders::where('kode_pemesanan', $dt['kode_pemesanan'])->first();
            // check if code order in the local tabel order  of the same server as the api null 
            if($Orders == null){
                 //check if data user dosn't null
                if(!empty($dt['user'])){
                    $user = $dt['user'];
                    $Users = User::where('email', $user['email'])->first();

                    //check if data user null , we create new data user
                    if($Users == null){
                        $Users = new User();
                        $Users->nama = $user['nama'];
                        $Users->email = $user['email'];
                        $Users->no_hp = $user['no_hp'];
                        // echo($Users);
                       $Users->save();
                    }else{
                        echo('user local server '. $Users);
                    }
                }
                
                $statusAPI = $dt['status'];
                $Status = StatusOrder::where('status_order','like', '%'.$statusAPI['status_order'].'%')->first();
                //if status order in local server null , we create new data status order for local server
                if($Status == null){
                    $Status = new StatusOrder();
                    $Status -> status_order = $statusAPI['status_order'];
                    // echo($Status);
                    $Status ->save();
                }else{
                    echo('Local server Status '. $Status);
                }
                
                if(!empty($dt['booking'])){
                    $bookingApi = $dt['booking'];
                    $booking = BookingTempat::where('kode_boking', 'like','%'.$bookingApi['kode_boking'].'%')->first();

                    // check if code booking in the local tabel booking server of the same server api null
                    if($booking == null){
                        $booking = new BookingTempat();
                        $booking -> id_user = $Users->id;
                        $booking -> kode_boking = $bookingApi['kode_boking'];
                        $booking -> id_room = $bookingApi['id_room'];
                        $booking -> tanggal_booking = $bookingApi['tanggal_booking'];
                        $booking -> type_time = $bookingApi['type_time'];
                        $booking -> jam_booking = $bookingApi['jam_booking'];
                        $booking -> nominal_dp = $bookingApi['nominal_dp'];
                        $booking -> bukti_pembayaran = $bookingApi['bukti_pembayaran'];
                        $booking -> id_status = $Status->id;
                       
                        // echo($booking);
                        $booking->save();
                    }else{
                        echo('local server data Booking '. $booking);
                    }
                }
                


                $Orders = new Orders();
                $Orders -> id_user = $Users->id;
                $Orders -> name_bill = $dt['name_bill'];
                $Orders -> id_booking = $booking->id;
                $Orders -> kode_pemesanan = $dt['kode_pemesanan'];
                $Orders -> no_meja = $dt['no_meja'];
                $Orders -> id_status = $Status->id;
                $Orders -> id_type_payment = $dt['id_type_payment'];
                $Orders -> subtotal = $dt['subtotal'];
                $Orders -> total_order = $dt['total_order'];
               

                // echo($Orders);
                $Orders->save();
            }else{
                echo('local server data order sudah ada '. $Orders);
            }

           $detail = $dt['details'];
        
            if(!empty($detail)){
                foreach($detail as $details){
                     $detail_order = DetailOrder::where('id_order', $Orders->id)->first();
            
                    //if data detail order on the value null
                    if($detail_order == null){
                        $detail_order = new DetailOrder();
                        $detail_order->id_order = $Orders->id;
                        $detail_order->harga = $details['harga'];
                        $detail_order->total = $details['total'];
                        $detail_order->id_menu = $details['id_menu'];
                        $detail_order->id_varian = $details['id_varian'];
                        $detail_order->qty = $details['qty'];
                        $detail_order->catatan = $details['catatan'];
                        
                        $detail_order->save();
                    }else{
                            echo('details orders in the local server '.$detail_order);
                    }

                    $add_optional = $details['add_optional_order'];
                    if(!empty($add_optional)){
                        $newAddOptional_order = new Additional_menu_detail();
                        $newAddOptional_order->id_detail_order = $details->id;
                        $newAddOptional_order->id_option_additional = $add_optional['id_option_additional'];
                        $newAddOptional_order->qty = $add_optional['qty'];
                        $newAddOptional_order->total = $add_optional['total'];

                        $newAddOptional_order->save();
                    }

                    $discount = $details['discount_menu_order'];
                    if(!empty($discount)){
                        $newDiscount = new Discount_detail_order();
                        $newDiscount->id_detail_order = $Orders->id;
                        $newDiscount->id_discount = $discount['id_discount'];
                        $newDiscount->total_discount = $discount['total_discount'];
                        $newDiscount->save();
                    }
                }
                   
            }
           
           
            
        }
       
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sentinel;
use Carbon\Carbon;
use App\Models\Discount;
use App\Models\TypePayment;
class PaymentController extends Controller
{

    // payment type

    public function TypePayment(){
        if(Sentinel::check()){
            $payment = TypePayment::all();
            return view('TypePayment.data', compact('payment'));
        }else{
            return redirect()->route('login');
        }
    }

    public function CreateDataPaymentType(){
        if(Sentinel::check()){
            // $payment = TypePayment::all();
            return view('TypePayment.CreateData');
        }else{
            return redirect()->route('login');
        }
    }


    public function postDataPaymentType(Request $request){
        if(Sentinel::check()){

            $request->validate([
                'nama' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',

            ]);

            $payment = new TypePayment();
            $payment->nama = $request->nama;

            if($file = $request->hasFile('image')){
                $file =$request->file('image');
                $fileName = $file->getClientOriginalName();
                $destination = public_path().'/asset/assets/image/typePayment';
                if(!file_exists($destination)){
                    mkdir($destination, 0777, true);
                }
                $file->move($destination, $fileName);
                $payment->image ='https://admin.goodfellas.id/asset/assets/image/typePayment/'.$fileName;
            }

            if($payment->save()){
                return redirect()->route('data-Payment')->with('Success', 'Type Payment Berhasil Di tambahkan');
            }else{
                return redirect()->back()->with('faild', 'Type Payment gagal di tambahkan');
            }
        }else{
            return redirect()->route('login');
        }
    }

    public function editDataTypePayment($id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $payment = TypePayment::findOrFail($dec);
            return view('TypePayment.EditData', compact('payment'));
        }else{
            return redirect()->route('login');
        }
    }

    public function updateDataTypePayment(Request $request, $id){
        if(Sentinel::check()){
            $request->validate([
                'nama' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',

            ]);

            $dec = decrypt($id);
            $payment = TypePayment::findOrFail($dec);
            $payment->nama = $request->nama;

            if($file = $request->hasFile('image')){
                $file =$request->file('image');
                $fileName = $file->getClientOriginalName();
                $destination = public_path().'/asset/assets/image/typePayment';
                if(!file_exists($destination)){
                    mkdir($destination, 0777, true);
                }
                $file->move($destination, $fileName);
                $payment->image ='https://admin.goodfellas.id/asset/assets/image/typePayment/'.$fileName;
            }

            if($payment->save()){
                return redirect()->route('data-Payment')->with('Success', 'Type Payment Berhasil Di Update');
            }else{
                return redirect()->back()->with('faild', 'Type Payment gagal di Update');
            }
        }else{
            return redirect()->route('login');
        }
    }

    public function deleteTypePayment($id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $payment = TypePayment::findOrFail($dec);

            $payment->delete();

            return redirect()->back()->with('Success', 'Type Berhasil di Hapus');
        }else{
            return redirect()->route('login');
        }

    }

    // discount

    public function Discount(){
        if(Sentinel::check()){
            $Dis = Discount::all();
            return view('Discount.data', compact('Dis'));
        }else{
            return redirect()->route('login');
        }
    }

    public function CreateDataDis(){
        if(Sentinel::check()){
            return view('Discount.CreateData');
        }else{
            return redirect()->route('login');
        }
    }

    public function PostDataDiscount( Request $request){
        if(Sentinel::check()){

            $request->validate([
                'nama' => 'required',
                'rate_dis' => 'required'
            ]);

            $dis = new Discount();
            $dis->nama = $request->nama;
            $dis->rate_dis = $request->rate_dis;


            if($dis->save()){
                return redirect()->route('data-discount')->with('Success', 'Discount Type Berhasil Di tambahkan');
            }else{
                return redirect()->back()->with('faild', 'Discount Type gagal di tambahkan');
            }
        }else{
            return redirect()->route('login');
        }
    }

    public function EditDataDis($id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $dis = Discount::findOrFail($dec);
            return view('Discount.EditData', compact('dis'));
        }else{
            return redirect()->route('login');
        }
    }

    public function UpdateDataDiscount( Request $request, $id){
        if(Sentinel::check()){

            $request->validate([
                'nama' => 'required',
                'rate_dis' => 'required'
            ]);

            $dec = decrypt($id);
            $dis = Discount::findOrFail($dec);
            $dis->nama = $request->nama;
            $dis->rate_dis = $request->rate_dis;

            if($dis->save()){
                return redirect()->route('data-discount')->with('Success', 'Discount Type Berhasil Di Update');
            }else{
                return redirect()->back()->with('faild', 'Discount Type gagal di Update');
            }
        }else{
            return redirect()->route('login');
        }
    }

    public function deleteDiscount($id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $dis = Discount::findOrFail($dec);

            $dis->delete();

            return redirect()->back()->with('Success', 'Discount Type Berhasil di Hapus');
        }else{
            return redirect()->route('login');
        }

    }

}

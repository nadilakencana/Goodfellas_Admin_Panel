<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\VocherClaimUser;
use App\Models\VocherGift;
use App\Models\User;
use Sentinel;
use Carbon\Carbon;

class VocherGiftController extends Controller
{
    //
    public function data(){
        if(Sentinel::check()){
            $vocher = VocherGift::all();


            return view('Vocher_Gift.data_vocher', compact('vocher'));
        }else{
            return redirect()->route('login');
        }
    }

    public function create_data(){
        if(Sentinel::check()){
            return view('Vocher_Gift.create_data');
        }else{
            return redirect()->route('login');
        }

    }

    public function post_create_data(Request $request){
        if(Sentinel::check()){
            //  dd($request);
            $request->validate([
                'nama_vocher' => 'required',
                'slug_vocher' => 'required',
                'detail' => 'required',
                'term_condition' => 'required',
                'point_reward' =>'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg'
            ]);

            $vocher = VocherGift::create($request->all());
            $vocher->image = $request->image;

            if($file = $request->hasFile('image')){
                $file =$request->file('image');
                $fileName = $file->getClientOriginalName();
                $destination = public_path().'/asset/assets/image/Vocher/';
                if(!file_exists($destination)){

                    mkdir($destination, 0777, true);
                }
                $file->move($destination, $fileName);
                $vocher->image = 'https://localhost:8000/asset/assets/image/Vocher/'.$fileName;
            }

            if($vocher->save()){
                return redirect()->route('vocher-gif')->with('Success', 'Berhasil menambahkan data Vocher');
            }else{
                return redirect()->back()->with('faild', 'Gagal menambahkan data , cek Kembali');
            }
        }else{
            return redirect()->route('login');
        }


    }

     public function Edit_data($id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $vocher = VocherGift::findOrFail($dec);
            return view('Vocher_Gift.edit_data', compact('vocher'));
        }else{
            return redirect()->route('login');
        }

     }
    public function post_Edit_data(Request $request, $id){
        if(Sentinel::check()){
            $request->validate([
                'nama_vocher' => 'required',
                'slug_vocher' => 'required',
                'detail' => 'required',
                'term_condition' => 'required',
                'point_reward' =>'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg'
            ]);

            $dec = decrypt($id);
            $vocher = VocherGift::findOrFail($dec);
            $vocher->nama_vocher= $request->nama_vocher;
            $vocher->detail= $request->detail;
            $vocher->term_condition= $request->term_condition;
            $vocher->point_reward= $request->point_reward;
            // $vocher->image= $request->image;
            if($file = $request->hasFile('image')){
                $file =$request->file('image');
                $fileName = $file->getClientOriginalName();
                $destination = public_path().'/asset/assets/image/Vocher/';
                if(!file_exists($destination)){

                    mkdir($destination, 0777, true);
                }
                $file->move($destination, $fileName);
                $vocher->image = 'http://localhost:8000/asset/assets/image/Vocher/'.$fileName;
            }
            if($vocher->save()){
                return redirect()->route('vocher-gif')->with('Success', 'Berhasil Mengedit data Vocher');
            }else{
                return redirect()->back()->with('faild', 'Gagal Mengedit data , cek Kembali');
            }
        }else{
            return redirect()->route('login');
        }
    }

    public function deleteData(Request $request, $id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $vocher = VocherGift::findOrFail($dec);
            $vocher->delete();
            return redirect()->back()->with('Success', 'Data Vocher berhasil di hapus');
        }else{
            return redirect()->route('login');
        }


    }

    public function claimVocherUser(){


        return view('Vocher_Gift.claimVocherUser');
    }

    public function detailclaim(Request $request){

        $vocherClaim = VocherClaimUser::where('kode_qr', $request->kode)->first();

        return view('Vocher_Gift.detail_claim', compact('vocherClaim'));
    }

    public function claimUserVocher(Request $request){

         if(Sentinel::check()){
            $userId = Sentinel::getUser();
            $admin = $userId->id;
            // dd($admin);
            $vocherClaim = VocherClaimUser::where('id', $request->xid)->first();
            $vocherClaim->id_admin = $userId->id;
            $vocherClaim-> flag = 'Vocher has been claimed';
            $vocherClaim-> tanggal_tukar = Carbon::now()->toDateTimeString();
            $vocherClaim -> save();

              return response()->json([
                    'success' => 1,
                    'message' => 'Vocher di claim',

                ]);
        }else{
            return redirect()->route('login');
        }



    }
}

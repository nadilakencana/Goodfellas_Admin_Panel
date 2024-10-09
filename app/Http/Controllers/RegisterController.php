<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Cartalyst\Sentinel\Sentinel;
use Illuminate\Support\Facades\Auth;
class RegisterController extends Controller
{
    public function regis(){
        return view('Auth.registrasi');
    }


    public function pushRegist( Request $request){



        $request->validate([

            'nama' => 'required',

            'email' => 'required|email:dns|unique:admin',

            'password' => 'required|min:6',



        ]);

        $datauser= [

            'nama' => $request->nama,

            'email' => $request->email,

            'password' => bcrypt($request->password),

            'id_level' => 1

        ];



        // dd($datauser);



       $user= Admin::create($datauser);

      if($user){
        return redirect('/')->with('Success', 'Registration Successfull!');
      }else{
        return redirect()->back()->with('error', 'failed to Regist');
      }
        

    }
}

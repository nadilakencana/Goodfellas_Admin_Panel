<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Sentinel;
use Illuminate\Support\Facades\Hash;
Use Session;
use App\Models\User;
use App\Models\Level;

class AuthController extends Controller
{
    public function login(){
        return view('Auth.login');

    }

    public function pushlogin(Request $request){
        $admin = Admin::where('email', $request->email)->first();
		// dd($admin['id'] != $admin->id);

		if(isset($admin)){
            // dd($admin);
			$credentials = [
				'email'    => $request->email,
				'password' => $request->password,
				'id' => $admin->id
			];
            $user = Sentinel::findById($admin->id);
            // dd($user);
            // Sentinel::login($user);
            // return redirect()->route('Dashboard');
			if (Sentinel::authenticate($credentials)) {

				if($request->remember){
					Sentinel::loginAndRemember($user);
				}else{
					Sentinel::login($user);
				}


				return redirect()->route('Dashboard');
			}else{
				return Redirect::back()->with('error', 'Password salah');
			}

		}else{
			return Redirect::back()->with('error', 'Akun tidak terdaftar');
		}


    }

	public function logOut(){
		Sentinel::logout();
		Session::flush();

		return redirect()->route('login');
	}

    public function DataUser(){
         if(Sentinel::check()){
            $user = User::all();

            return view('DataUser.dataUser', compact('user'));

        }else{
            return redirect()->route('login');
        }
    }

    public function DataAdmin(){
         if(Sentinel::check()){
            $admin = Admin::all();

            return view('dataAdmin.dataAdmin', compact('admin'));

        }else{
            return redirect()->route('login');
        }
    }
    public function editDataAdmin($id){
         if(Sentinel::check()){
            $dec = decrypt($id);
            $admin = Admin::findOrFail($dec);
            $level = Level::all();

            return view('dataAdmin.updateData', compact('admin', 'level'));

        }else{
            return redirect()->route('login');
        }
    }


    public function udpdateDataAdmin(Request $request, $id){
         if(Sentinel::check()){
            $request->validate([
                'nama'=> 'required',
                'email'=> 'required',
                'id_level' => 'required'
            ]);
            $dec = decrypt($id);
            $admin = Admin::findOrFail($dec);
            $admin->nama = $request->nama;
            $admin->email = $request->email;
            $admin->id_level = $request->id_level;

            if($admin->save()){
                return redirect()->route('dataAdmin')->with('Success','Data Admin Berhasil di update ');
            }else{
                return redirect()->back()->with('faild', 'Data gagal di update, cek kembali data');
            }



        }else{
            return redirect()->route('login');
        }
    }



    public function deleteDataAdmin($id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $admin = Admin::findOrFail($dec);
            $admin->delete();

          return redirect()->back()->with('success', 'Data berhasil di hapus');
        }else{
            return redirect()->route('login');
        }
    }

    public function ResetPassword(Request $request){
        //  if(Sentinel::check()){
            $this->validate($request, ['email'=> 'required']);
            $this->validate($request,['password' => 'required']);

            $email = $request->email;

            if($email){
                $admin = Admin::where('email','LIKE','%'.$email.'%')->first();
                $admin ->password = bcrypt($request->password);

                $admin->save();
            }

            if($admin->save()){
                return redirect()->route('dataAdmin')->with('Success','Password has been updated.');
            }else{
                return redirect()->back()->with('faild', 'Password failed to update.');
            }


        // }else{
        //     return redirect()->route('login');
        // }

    }

    public function levelLog(){
        if(Sentinel::check()){
            $levelLog = Level::all();

            return view('LevelLog.dataLevel', compact('levelLog'));

        }else{
            return redirect()->route('login');
        }
    }

    public function createLevel(Request $request){
        if(Sentinel::check()){

            $request->validate([
                'level'=> 'required'
            ]);

            $level = Level::create($request->all());

            if($level->save()){
                return redirect()->route('LevelLog')->with('Success','Level User Berhasil di update ');
            }else{
                return redirect()->back()->with('faild','Kategori gagal di update ');
            }
        }else{
            return redirect()->route('login');
        }
    }

    public function UpdateLevel(Request $request, $id){
        if(Sentinel::check()){

            $request->validate([
                'level'=> 'required'
            ]);

            $level = Level::find($id);
            $level ->level = $request->level;

            if($level->save()){
                return redirect()->route('LevelLog')->with('Success','Level User Berhasil di Tambahkan ');
            }else{
                return redirect()->back()->with('faild','Kategori gagal di Tambahkan ');
            }

        }else{
            return redirect()->route('login');
        }
    }

    public function DeteletLevel($id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $level = Level::find($dec);
            if($level->delete()){

                return redirect()->route('LevelLog')->with('Success','Level User Berhasil di hapus ');

            }else{

                return redirect()->back()->with('faild','Kategori gagal di Tambahhapuskan ');

            }

        }else{
            return redirect()->route('login');
        }
    }


}

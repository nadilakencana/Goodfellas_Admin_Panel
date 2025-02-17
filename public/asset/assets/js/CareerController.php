<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Career;
use App\Models\CVApply;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Storage;


class CareerController extends Controller
{
    public function career(){

        $career = Career::all();

        $agent = new Agent();

        if($agent->isDesktop()){
            return view('Dekstop.career',compact('career'));

		}else if($agent->isTablet()){
            return view('Tablet.career', compact('career'));
            
        }else{
            return view('Mobile.career',compact('career'));
		}

    }

    public function cvapply( Request $request){

        $request->validate([
            'name' => 'required',
            'email' => 'required|email:dns',
            'phone' => 'required|numeric',
            'file_cv' => 'file',
        ]);

        $cv = new CVApply;
        $cv->name = $request->name;
        $cv->email = $request->email;
        $cv->phone = $request->phone;
        if ($request->file_cv){
            Storage::delete('public/'. $cv->file_cv);
            $cv['file_cv'] = $request->file('file_cv')->store('/cv', 'public');
        }

        if($cv->save()){
            return redirect()->route('career')->with('success', 'CV Kamu Berhasil Dikirim  ');
        }else{
            return redirect()->back()->with('error', 'Gagal Mengirim CV');
        }


    }
}

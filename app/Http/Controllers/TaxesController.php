<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Taxes;
use App\Models\TaxOrder;
use Sentinel;
use Carbon\Carbon;

class TaxesController extends Controller
{
    public function dataTax(){
        if(Sentinel::check()){
            $tax = Taxes::all();
            return view('Tax.dataTax', compact('tax'));
        }else{
            return redirect()->route('login');
        }
    }

    public function createTax(){
        if(Sentinel::check()){
            // $tax = Taxes::all();
            return view('Tax.CreateTax');
        }else{
            return redirect()->route('login');
        }
    }

    public function postTax(Request $request){
        if(Sentinel::check()){
            // $tax = Taxes::all();
            $request->validate([
                'nama'=> 'required',
                'tax_rate' => 'required'
            ]);

            $tax = new Taxes();
            $tax->nama = $request->nama;
            $tax->tax_rate = $request->tax_rate;

            if($tax->save()){
                return redirect()->route('data-tax')->with('Success','Taxes Berhasil di Tambahkan ');
            }else{
                return redirect()->back()->with('faild','Taxes gagal di Tambahkan ');
            }

        }else{
            return redirect()->route('login');
        }
    }

    public function EditDataTax($id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $tax = Taxes::findOrFail($dec);
            return view('Tax.EditTax', compact('tax'));
        }else{
            return redirect()->route('login');
        }
    }

    public function UpdateDataTax(Request $request, $id){
        if(Sentinel::check()){
            // $tax = Taxes::all();
            $request->validate([
                'nama'=> 'required',
                'tax_rate' => 'required'
            ]);

            $dec = decrypt($id);
            $tax = Taxes::findOrFail($dec);
            $tax->nama = $request->nama;
            $tax->tax_rate = $request->tax_rate;

            if($tax->save()){
                return redirect()->route('data-tax')->with('Success','Taxes Berhasil di Edit ');
            }else{
                return redirect()->back()->with('faild','Taxes gagal di Update cek kembali data ');
            }

        }else{
            return redirect()->route('login');
        }
    }

    public function deleteTax($id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $tax = Taxes::findOrFail($dec);

            $tax->delete();

        return redirect()->back()->with('Success','Tax Berhasil di hapus ');
        }else{
            return redirect()->route('login');
        }
    }
}

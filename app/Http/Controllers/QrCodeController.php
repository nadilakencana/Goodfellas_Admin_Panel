<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QRTable;
use Sentinel;

class QrCodeController extends Controller
{
    public function index(){
        if(Sentinel::check()){
          
            $dataTable = QRTable::all();
            return view('Table_QR.dataTabel', compact('dataTable'));

       }else{
           return redirect()->route('login');
       }
    }

    public function CraateQRTable(){
        if(Sentinel::check()){
            $dataTable = QRTable::all();
            return view('Table_QR.FormCreate', compact('dataTable'));

       }else{
           return redirect()->route('login');
       }
    }

    public function PostQRTable(Request $request){
        if(Sentinel::check()){
            try{
                $request->validate([
                    'meja' => 'required'
                ]);

                $url = 'http://192.168.89.108:8000/Order/Customer';

                $data = new QRTable();
                $data->meja = $request->meja;
                $data->link = $url.'?meja='.$request->meja;
                $data->save();

                return redirect()->route('Qr-table')->with('success', 'Qr Table Berhasil di buat');

            }catch(\Exception $e){
                return redirect()->route('Qr-table')->with('fail', 'Qr Table gagal di buat');
            }
            

       }else{
           return redirect()->route('login');
       }
    }

    public function QRDetail(Request $request){

        $xid = $request->xid;
        $dec = decrypt($xid);

        $table = QRTable::findOrFail($dec);

        return view('Table_QR.QrTabel', compact('table'));
    }


    public function DeleteTable($xid){

        $dec = decrypt($xid);
        $data = QRTable::findOrFail($dec);
        $data->delete();

        if($data){
            return redirect()->back()->with('success', 'Table berhasil dihapus');
        }else{
            return redirect()->back()->with('fail', 'Table gagal di hapus');
        }
    }
}

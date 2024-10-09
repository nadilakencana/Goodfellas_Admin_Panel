<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Models\Kategori;

use Sentinel;

class KategoriController extends Controller

{

    public function indexKat(){
        if(Sentinel::check()){
            $kategori = Kategori::all();
            return view('Kategori.index', compact('kategori'));
        }else{
            return redirect()->route('login');
        }
    }


    public function createKat(){
        if(Sentinel::check()){
            return view('Kategori.create');

        }else{
            return redirect()->route('login');
        }

    }


    public function pushKat(Request $request){
        if(Sentinel::check()){
            $request->validate([
                'kategori_nama'=> 'required'
            ]);

            $kategori = Kategori::create($request->all());

            if($kategori->save()){

                return redirect()->route('kategori')->with('Success','Kategori Berhasil di Tambahkan ');

            }else{

                return redirect()->back()->with('faild','Kategori gagal di Tambahkan ');

            }

        }else{
            return redirect()->route('login');
        }

    }



    public function editKat($id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $kategori= Kategori::find($dec);
            return view('Kategori.edit', compact('kategori'));
        }else{
            return redirect()->route('login');
        }


    }



    public function UpadateKategori(Request $request, $id){
        if(Sentinel::check()){
            $request->validate([

                'kategori_nama'=> 'required'

            ]);

            $dec = decrypt($id);
            $kategori = Kategori::findOrFail($dec);
            $kategori->kategori_nama = $request->kategori_nama;

            if($kategori->save()){

                return redirect()->route('kategori')->with('Success','Kategori Berhasil di Upadete ');

            }else{

                return redirect()->back()->with('faild','Kategori gagal di Upadete');

            }

        }else{
            return redirect()->route('login');
        }

    }

    public function deleteKat($id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $kategori = Kategori::findOrFail($dec);

        $kategori->delete();

        return redirect()->back()->with('Success','Kategori Berhasil di hapus ');
        }else{
            return redirect()->route('login');
        }
    }

}


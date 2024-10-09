<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use App\Models\Kategori;

use App\Models\SubKategori;

use Sentinel;

class SubKategoriController extends Controller

{

    public function indexSubKat(){
        if(Sentinel::check()){
            $subKat = SubKategori::all();
            return view('SubKategori.index', compact('subKat'));
        }else{
            return redirect()->route('login');
        }


    }



    public function createSubKat(){
        if(Sentinel::check()){
            $kategori = Kategori::all();

            return view('SubKategori.create', compact('kategori'));
        }else{
            return redirect()->route('login');
        }


    }



    public function pushSubKat(Request $request){
        if(Sentinel::check()){
            $request->validate([

                'sub_kategori' =>'required',
                'slug' => 'required',
                'id_kategori' =>'required'

            ]);

            $subKat = new SubKategori();
            $subKat->sub_kategori = $request->sub_kategori;
            $subKat->slug = $request->slug;
            $subKat->id_kategori = $request->id_kategori;

            if($subKat->save()){
                return redirect()->route('subkategori')->with('Success', 'Subkategori Berhasil di Tambahkan');
            }else{
                return redirect()->back()->with('faild', 'Subkategori gagal di Tambahkan');
            }
        }else{
            return redirect()->route('login');
        }


    }



    public function editSubKat($id){
        if(Sentinel::check()){
        $dec = decrypt($id);
        $kategori = Kategori::all();

        $subKat = SubKategori::find($dec);



        return view('SubKategori.edit', compact('kategori','subKat'));
        }else{
            return redirect()->route('login');
        }


    }



    public function UpdateSubKat(Request $request, $id){
        if(Sentinel::check()){
            $request->validate([

                'sub_kategori' =>'required',
                'slug' => 'required',
                'id_kategori' =>'required'

            ]);


            $dec = decrypt($id);
            $subKat = SubKategori::findOrFail($dec);
            $subKat->sub_kategori = $request->sub_kategori;
            $subKat->slug = $request->slug;
            $subKat->id_kategori = $request->id_kategori;
            if($subKat->save()){
                return redirect()->route('subkategori')->with('Success', 'Subkategori Berhasil di Update');
            }else{
                return redirect()->back()->with('faild', 'Subkategori gagal di Update');
            }
        }else{
            return redirect()->route('login');
        }

    }


    public function deleteSubKat($id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $subKat = SubKategori::findOrFail($dec);

            $subKat->delete();

            return redirect()->back()->with('Success', 'Subkategori Berhasil di Hapus');
        }else{
            return redirect()->route('login');
        }




    }

}


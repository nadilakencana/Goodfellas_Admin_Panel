<?php



namespace App\Http\Controllers;

use App\Models\DetailOrder;
use App\Models\GroupModifier;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Kategori;
use App\Models\SubKategori;
use App\Models\VarianMenu;
use App\Models\BahanBaku;
use App\Models\MenuResep;
use Sentinel;
use Illuminate\Support\Facades\Http;
class MenuController extends Controller

{

    public function indexMenu(){
        if(Sentinel::check()){
            $menu = Menu::where('custom', false)->where('delete_menu', 0)->orderBy('id', 'DESC')->get();
            return view('Menu.index', compact('menu'));
        }else{
            return redirect()->route('login');
        }

    }

    public function createMenu(){

        if(Sentinel::check()){
            $kat = Kategori::all();
            $sub_kat =SubKategori::all();
            $additional = GroupModifier::all();
            $bahan_baku = BahanBaku::all();
            return view('Menu.create', compact('kat','sub_kat', 'additional', 'bahan_baku'));
        }else{
            return redirect()->route('login');
        }
    }

    public function PushCreate(Request $request){

        // dd($request);
        if(Sentinel::check()){

            $variasi = [];
            $request->validate([
                'nama_menu'=> 'required',
                'slug' => 'required',
                'deskripsi' => 'required',
                'harga' => 'required',
                'id_kategori' => 'required',
                'id_sub_kategori' => 'required',
                'promo' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',

            ]);

            $menu = Menu::create($request->all());
            $menu->image= $request->image;
            $menu->custom= false;
            $menu->id_group_modifier= $request->id_group_modifier;
            $menu->stok= $request->stok;
            $menu->stok_minimum = $request->stok_minimun;
            $menu->tipe_stok = $request->tipe_stok;
            $menu->id_bahan_baku = $request->id_bahan_baku;
            if($request->active == null){
                $menu->active= 0;
            }else{
                $menu->active= $request->active;
            }
            
            $menu->id_kategori = $request->id_kategori;
            $menu->id_sub_kategori = $request->id_sub_kategori;
            $menu->promo = $request->promo;
            $menu->custom= false;

            if($file = $request->hasFile('image')){
                $file =$request->file('image');
                $fileName = $file->getClientOriginalName();
                $destination = public_path().'/asset/assets/image/menu/';
                if(!file_exists($destination)){
                    mkdir($destination, 0777, true);
                }
                $file->move($destination, $fileName);
                $menu->image = $fileName;
            }

            // dd($menu);

            if( $menu->save()){
                if($menu->kategori->kategori_nama === 'Foods'){
                    if($menu->tipe_stok === 'Stok Bahan Baku'){
                        $menu_resep = new MenuResep();
                        $menu_resep->id_menu = $menu->id;
                        $menu_resep->id_bahan_baku = $request->id_bahan_baku;
                        $menu_resep->save();
                    }
                }
                
                if($request->has('variasi')){
                    $var_menu = $request->variasi;
                    foreach($var_menu as $variasi){
                        $var = new VarianMenu();
                        $var->id_menu = $menu->id;
                        $var->nama = $variasi['nama'];
                        $var->harga = $variasi['harga'];
                        $var->active = $variasi['active'];
                        $var->save();
                        $variasi[] = [$variasi];
                    }
                }

                return redirect()->route('menu')->with('Success', 'Menu Berhasil Di Tambahkan ');
            }else{

                return redirect()->back()->with('faild', 'Menu gagal di Tambahkan');
            }

        }else{
            return redirect()->route('login');
        }

    }

    public function editMenu($id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $menu = Menu::where('id', $dec)->first();
            $kat = Kategori::all();
            $sub_kat =SubKategori::all();
            $variasi = VarianMenu::where('id_menu', $menu->id)->get();
            $additional = GroupModifier::all();
            $bahan_baku = BahanBaku::all();
            return view('Menu.edit', compact('menu','kat','sub_kat','variasi','additional','bahan_baku'));
        }else{
            return redirect()->route('login');
        }

    }

    public function updateMenu(Request $request, $id){
        if(Sentinel::check()){

            $variasi_menu =[];

            $request->validate([
                'nama_menu'=> 'required',
                'slug' => 'required',
                'deskripsi' => 'required',
                'harga' => 'required',
                'id_kategori' => 'required',
                'id_sub_kategori' => 'required',
                'promo' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg'

            ]);
            $dec = decrypt($id);
            $menu = Menu::findOrFail($dec);
            $menu->nama_menu = $request->nama_menu;
            $menu->slug = $request->slug;
            $menu->deskripsi = $request->deskripsi;
            $menu->harga = $request->harga;
            $menu->id_kategori = $request->id_kategori;
            $menu->id_sub_kategori = $request->id_sub_kategori;
            $menu->promo = $request->promo;
            $menu->custom= false;
            $menu->id_group_modifier= $request->id_group_modifier;
            $menu->tipe_stok = $request->tipe_stok;

            if($request->tipe_stok === 'Stok Bahan Baku'){
                $menu->stok= 0;
                $menu->stok_minimum = 1;
            }else{
                $menu->stok = $request->stok;
                $menu->stok_minimum = $request->stok_minimun;
            }

            $menu->id_bahan_baku = $request->id_bahan_baku;
           if($request->active == null){
                $menu->active= 0;
            }else{
                $menu->active= $request->active;
            }

            // $menu->image = $request->image;
            if($file = $request->hasFile('image')){
                $file =$request->file('image');
                $fileName = $file->getClientOriginalName();
                $destination = public_path().'/asset/assets/image/menu';
                if(!file_exists($destination)){
                    mkdir($destination, 0777, true);
                }
                $file->move($destination, $fileName);
                $menu->image = $fileName;
            }

            if( $menu->save()){
                if($menu->kategori->kategori_nama === 'Foods'){
                    if($menu->tipe_stok === 'Stok Bahan Baku'){
                        $menu_resep = MenuResep::where('id_menu', $menu->id)->first();
                        if(!$menu_resep){
                            $menu_resep = new MenuResep();
                        }
                        $menu_resep->id_menu = $menu->id;
                        $menu_resep->id_bahan_baku = $request->id_bahan_baku;
                        $menu_resep->save();
                    }
                }
                if($request->has('variasi')){
                    $var_menu = $request->variasi;
                    // dd($var_menu);
                    foreach($var_menu as $variasi){

                        if(array_key_exists('id', $variasi)){

                            $var =  VarianMenu::where('id', $variasi['id'])->first();

                            if($variasi['nama'] === 'Delete'){
                                $var->delete();
                            }else{
                                $var->id_menu = $menu->id;
                                $var->nama = $variasi['nama'];
                                $var->harga = $variasi['harga'];
                                if($variasi['active'] == null){
                                     $var->active= 0;
                                }else{
                                    $var->active= $variasi['active'];
                                }
                                // $var->active = $variasi['active'];
                                $var->save();

                            }

                        }else{
                            $var =  new VarianMenu();
                            $var->id_menu = $menu->id;
                            $var->nama = $variasi['nama'];
                            $var->harga = $variasi['harga'];
                            if($variasi['active'] == null){
                                 $var->active= 0;
                            }else{
                                $var->active= $variasi['active'];
                            }
                            $var->save();

                        }


                    $variasi_menu[] = [$variasi];
                    }
                }
                
                $menu = $menu->toArray();

                return redirect()->route('menu')->with('Success', 'Menu Berhasil Di Update');
            }else{
                return redirect()->back()->with('faild', 'Menu gagal di Update');
            }
        }else{
            return redirect()->route('login');
        }


    }

    public function deleteMenu(Request $request, $id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            // dd($dec);
            $menu = Menu::findOrFail($dec);

            if($menu){
                $menu->delete_menu = 1;
                $menu->save();

                $varian = VarianMenu::where('id_menu', $menu)->get();

                foreach($varian as $var){
                    $varUp = VarianMenu::where('id', $var->id)->first();
                    $varUp->deleted = 1;
                    $varUp->save();
                }

                return redirect()->back()->with('Success', 'Menu berhasil di hapus');
            }else{
                 return redirect()->back()->with('error', 'Menu gagal di hapus');
            }
          
        }else{
            return redirect()->route('login');
        }


    }


    public function bahanBaku(){
        if(Sentinel::check()){
            $bahan_baku = BahanBaku::all();
            return view ('Menu.bahanBaku', compact('bahan_baku'));
        }else{
            return redirect()->route('login');
        }

       
    }

    public function createBahanBaku(){
        if(Sentinel::check()){
            return view('Menu.createBahanbaku');
        }else{
            return redirect()->route('login');
        }

    }

    public function pushCreateBahanBaku(Request $request){
        if(Sentinel::check()){
            $request->validate([
                'nama_bahan' => 'required',
                'stok_porsi' => 'required',
                'stok_minimum' => 'required',
            ]);

            $bahan_baku = new BahanBaku();
            $bahan_baku->nama_bahan = $request->nama_bahan;
            $bahan_baku->stok_porsi = $request->stok_porsi;
            $bahan_baku->stok_minimum = $request->stok_minimum;

            if($bahan_baku->save()){
                return redirect()->route('bahanBaku')->with('Success', 'Bahan Baku Berhasil Di Tambahkan');
            }else{
                return redirect()->back()->with('faild', 'Bahan Baku gagal di Tambahkan');
            }
        }else{
            return redirect()->route('login');
        }

    }

    public function editBahanBaku($id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $bahan_baku = BahanBaku::where('id', $dec)->first();
            return view('Menu.editBahanbaku', compact('bahan_baku'));
        }else{
            return redirect()->route('login');
        }

    }

    public function updateBahanBaku(Request $request, $id){
        if(Sentinel::check()){
            $request->validate([
                'nama_bahan' => 'required',
                'stok_porsi' => 'required',
                'stok_minimum' => 'required',
            ]);

            $dec = decrypt($id);
            $bahan_baku = BahanBaku::where('id', $dec)->first();
            $bahan_baku->nama_bahan = $request->nama_bahan;
            $bahan_baku->stok_porsi = $request->stok_porsi;
            $bahan_baku->stok_minimum = $request->stok_minimum;

            if($bahan_baku->save()){
                return redirect()->route('bahanBaku')->with('Success', 'Bahan Baku Berhasil Di Update');
            }else{
                return redirect()->back()->with('faild', 'Bahan Baku gagal di Update');
            }
        }else{
            return redirect()->route('login');
        }

    }

    public function deleteBahanBaku(Request $request, $id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $bahan_baku = BahanBaku::where('id', $dec)->first();

            if($bahan_baku->delete()){
                return redirect()->back()->with('Success', 'Bahan Baku Berhasil Di Hapus');
            }else{
                return redirect()->back()->with('faild', 'Bahan Baku gagal di Hapus');
            }
        }else{
            return redirect()->route('login');
        }

    }


}


<?php



namespace App\Http\Controllers;

use App\Models\DetailOrder;
use App\Models\GroupModifier;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Kategori;
use App\Models\SubKategori;
use App\Models\VarianMenu;
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
            return view('Menu.create', compact('kat','sub_kat', 'additional'));
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
                if($request->has('variasi')){
                    $var_menu = $request->variasi;
                    // dd($option);
                    foreach($var_menu as $variasi){
                        // dd($option_modif);
                        $var = new VarianMenu();
                        $var->id_menu = $menu->id;
                        $var->nama = $variasi['nama'];
                        $var->harga = $variasi['harga'];
                       // dd($var);
                        $var->save();

                        $variasi[] = [$variasi];
                    }
                }

                // dd($var_menu);
                $localServerUrl = 'https://admin.goodfellas.id/api/new-menu';

                $dataArray = $menu->toArray();

                $response = Http::post($localServerUrl, [
                    'Menu' => $dataArray,
                    'variasi' => $variasi
                ]);
                // dd($response->body());
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
            return view('Menu.edit', compact('menu','kat','sub_kat','variasi','additional'));
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
                                $var->save();

                                //  $variasi_menu[] = [$variasi];

                            }

                        }else{
                            $var =  new VarianMenu();
                            $var->id_menu = $menu->id;
                            $var->nama = $variasi['nama'];
                            $var->harga = $variasi['harga'];
                            $var->save();



                        }


                    $variasi_menu[] = [$variasi];
                    }
                }
                //  dd($var);
                $localServerUrl = 'https://admin.goodfellas.id/api/update-data-menu';
                // $dataMenu = Menu::with('varian', 'varian.Menu','additional')->get();

                $menu = $menu->toArray();

                //   dd($variasi_menu);
                $response = Http::post($localServerUrl, [
                    'Menu' => $menu,
                    'variasi' => $variasi_menu,
                ]);
                //dd($response->body());
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
                $detailOrder = DetailOrder::where('id_menu', $menu->id)->get();
                if($detailOrder){
                    return redirect()->back()->with('error', 'Menu ini tidak bisa di hapus karena sudah memiliki penjualan');
                }else{

                    $menu->delete_menu = 1;
                    $menu->save();

                    return redirect()->back()->with('Success', 'Menu berhasil di hapus');

                }
            }
           

          
        }else{
            return redirect()->route('login');
        }


    }





}


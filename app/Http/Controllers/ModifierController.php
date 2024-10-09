<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupModifier;
use App\Models\OptionModifier;
use App\Models\Admin;
use Sentinel;
use Carbon\Carbon;
use PHPUnit\TextUI\XmlConfiguration\Group;
use Illuminate\Support\Facades\Http;

class ModifierController extends Controller
{
    public function dataModif(){
        if(Sentinel::check()){
            $modif = GroupModifier::all();
            // $option_modif = OptionModifier::where('id_group_modifier', $modif->id)->get();

            return view('GroupModifier.data', compact('modif'));
        }else{
            return redirect()->route('login');
        }
    }

    public function CreateGroup(){
        if(Sentinel::check()){
            $modif = GroupModifier::all();
            // $option_modif = OptionModifier::where('id_group_modifier', $modif->id)->get();

            return view('GroupModifier.create_modifier', compact('modif'));
        }else{
            return redirect()->route('login');
        }
    }

    public function postCreateGroupModif(Request $request){
        if(Sentinel::check()){

            $modif_group = new GroupModifier();
            $modif_group->name = $request->name;

            // dd($modif_group);
            if($modif_group->save()){

                if($request->has('option_modif')){
                    $option = $request->option_modif;
                    // dd($option);
                    foreach($option as $option_modif){
                        // dd($option_modif);
                        $op_mod = new OptionModifier();
                        $op_mod->id_group_modifier = $modif_group->id;
                        $op_mod->name = $option_modif['name'];
                        $op_mod->harga = $option_modif['harga'];
                        $op_mod->save();

                    }

                }

                $localServerUrl = 'https://admin.goodfellas.id/api/create-modifier';

                $modif_group = $modif_group->toArray();

                $response = Http::post($localServerUrl, [
                    'modifier' => $modif_group,
                    'option' => $option,
                ]);

                dd($response->body());

                return redirect()->route('dataModifier')->with('Success', 'Data Group Modifier dan Option Berhasil Ditambahkan');
            }else{
                return redirect()->back()->with('Faild', 'Gagal menambahkan data baru group');
            }


        }else{
            return redirect()->route('login');
        }
    }

    public function editDataGroup($id){

        if(Sentinel::check()){
            $dec = decrypt($id);
            $group = GroupModifier::FindOrfail($dec);
            $option_modif = OptionModifier::where('id_group_modifier', $group->id)->get();

            return view('GroupModifier.edit_modifier', compact('group', 'option_modif'));
        }else{
            return redirect()->route('login');
        }

    }

    public function postEditGroup(Request $request, $id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $group = GroupModifier::FindOrfail($dec);

            $group->name = $request->name;

            if($group->save()){

                if($request->has('option_modif')){
                    $option = $request->option_modif;
                    foreach($option as $option_modif){
                        if(array_key_exists('id', $option_modif)){

                            $op_mod =  OptionModifier::where('id', $option_modif['id'])->first();
                            if($option_modif['name'] === 'Delete'){
                                $op_mod ->delete();
                            }else{
                                $op_mod->id_group_modifier = $group->id;
                                $op_mod->name = $option_modif['name'];
                                $op_mod->harga = $option_modif['harga'];
                                $op_mod->save();
                            }

                        }else{
                            $op_mod =  new OptionModifier();
                            $op_mod->id_group_modifier = $group->id;
                            $op_mod->name = $option_modif['name'];
                            $op_mod->harga = $option_modif['harga'];
                            $op_mod->save();
                        }
                    }
                }
                return redirect()->route('dataModifier')->with('Success', 'Data Group Modifier dan Option Berhasil Di update');
            }else{
                return redirect()->back()->with('Faild', 'Gagal menambahkan data update group');
            }

        }else{
            return redirect()->route('login');
        }
    }

    public function detailData(Request $request){

        if(Sentinel::check()){

            if($request->id){
                $group = GroupModifier::where('id', $request->id)->first();
                $option_modif = OptionModifier::where('id_group_modifier', $group->id)->get();

                return response()->json([
                    'status'=> 1,
                    'data' => $group,
                    'option' => $option_modif,
                    'message' => 'data Berhasil Ditampilkan'
                ]);
            }

        }else{
            return redirect()->route('login');
        }
    }

    public function hapusData(Request $request, $id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $group = GroupModifier::findOrFail($dec);
            $group->delete();

            return redirect()->back()->with('Success', 'Group Modifier berhasil di hapus');
        }else{
            return redirect()->route('login');
        }
    }

}

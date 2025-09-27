<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Sentinel;
use App\Models\VIPRoom;
use App\Models\BookingTempat;
use App\Models\StatusOrder;
class VipRoomController extends Controller

{

    public function indexRoom(){
        if(Sentinel::check()){
            $room = VIPRoom::all();

            return view('Vip_Room.index', compact('room'));
        }else{
            return redirect()->route('login');
        }
    }

    public function createRoom(){
        if(Sentinel::check()){


            return view('Vip_Room.create');
        }else{
            return redirect()->route('login');
        }
    }

    public function pushCreate(Request $request){

        if(Sentinel::check()){

            $request->validate([
                'type_room' => 'required',
                'deskripsi' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
                'min_dp'=> 'required',
                'slug_room' => 'required'
            ]);


            $room = new VIPRoom();
            $room->type_room = $request->type_room;
            $room->deskripsi = $request->deskripsi;
            $room->image = $request->image;
            $room->min_dp = $request->min_dp;
            $room->slug_room = $request->slug_room;

            if($file = $request->hasFile('image')){

                $file =$request->file('image');
                $fileName = $file->getClientOriginalName();
                $destination = public_path().'/asset/assets/image/room/';
                if(!file_exists($destination)){
                    mkdir($destination, 0777, true);
                }
                $file->move($destination, $fileName);
                $room->image = 'http://127.0.0.1:8080/asset/assets/image/room/'.$fileName;

            }
            // dd($room);
            if($room->save()){
                return redirect()->route('Vip-Room')->with('Success', 'Data Room Berhasil di tambahkan ');
            }else{
                return redirect()->back()->with('error', 'Data Room gagal di tambahkan ');
            }
        }else{
            return redirect()->route('login');
        }

    }


    public function editRoom($id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $room = VIPRoom::find( $dec);
            return view('Vip_Room.edit', compact('room'));
        }else{
            return redirect()->route('login');
        }
    }

    public function updateRoom(Request $request, $id){
        if(Sentinel::check()){
            $request->validate([
                'type_room' => 'required',
                'deskripsi' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
                'min_dp'=> 'required',
                'slug_room' => 'required'
            ]);

            $dec = decrypt($id);
            $room = VIPRoom::find($dec);
            $room->type_room = $request->type_room;
            $room->deskripsi = $request->deskripsi;
            $room->image = $request->image;
            $room->min_dp = $request->min_dp;
            $room->slug_room = $request->slug_room;

            if($file = $request->hasFile('image')){
                $file =$request->file('image');
                $fileName = $file->getClientOriginalName();
                $destination = public_path().'/asset/assets/image/room/';
                if(!file_exists($destination)){
                    mkdir($destination, 0777, true);
                }
                $file->move($destination, $fileName);

                $room->image = 'http://127.0.0.1:8080/asset/assets/image/room/'.$fileName;

            }
            if($room->save()){
                return redirect()->route('Vip-Room')->with('Success', 'Data Room Berhasil di Edit ');
            }else{
                return redirect()->back()->with('error', 'Data Room gagal di Edit ');
            }
        }else{
            return redirect()->route('login');
        }
    }


    public function DeleteRoom($id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $room = VIPRoom::findOrFail($dec);

            $room->delete();

            return redirect()->back()->with('Success', 'data berhasil di hapus');
        }else{
            return redirect()->route('login');
        }
    }


    public function DataBooking(){
        if(Sentinel::check()){

           $data_booking = BookingTempat::all();
            $booking_new = BookingTempat::where('id_status', 1)->get();
            $booking_end = BookingTempat::where('id_status', 2)->get();
            $booking_cancel = BookingTempat::where('id_status', 3)->get();
            return view('booking_room.index', compact('data_booking','booking_new','booking_end','booking_cancel'));
        }else{
            return redirect()->route('login');
        }
    }

    public function detailBooking($kode){
        if(Sentinel::check()){
            $data_booking = BookingTempat::where('kode_boking', $kode)->first();
            $status = StatusOrder::all();

            return view('booking_room.detail', compact('data_booking','status'));
        }else{
            return redirect()->route('login');
        }
    }

     public function updateBookingStatus(Request $request, $kode){

        if(Sentinel::check()){
            // $dec = decrypt($id);
            $detail = BookingTempat::where('kode_boking', $kode)->first();;
            $detail->id_status = $request->data['id_status'];
            $detail->save();

        return response()->json(['success' =>1, 'data'=>$detail]);
        }else{
            return redirect()->route('login');
        }



    }

    public function DeletedataBook($id){
        if(Sentinel::check()){
            $dec = decrypt($id);
            $room = BookingTempat::findOrFail($dec);
            $room->delete();
            return redirect()->back()->with('Success', 'data berhasil di hapus');
        }else{
            return redirect()->route('login');
        }
    }

}


@extends('layout.master')
@section('content')

<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Detail Booking Room</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/Dashboard') }}">Home</a></li>
            <li class="breadcrumb-item "><a href="{{ route('data.booking') }}">Data Booking Room</a></li>
            <li class="breadcrumb-item active">Detail Booking Room</li>
          </ol>
        </div>
      </div>
    </div>
</section>

<section class="content-header">
    <div class="container-fluid" style="display: flex; flex-direction:column; align-items:center;">
        <div class="card" style="width: 55%;">
            <div class="card-body data" data-id="{{ $data_booking->kode_boking }}">
                <div class="header-card" style="display: flex;">
                    <h5 class="card-title">Tanggal Booking : {{ $data_booking->tanggal_booking }}</h5>
                    <h5 class="card-title" style="padding: 0px 7px;">Kode : {{ $data_booking->kode_boking }}</h5>
                </div>

              <p class="card-text pt-2" style="margin-bottom: 0.1rem;">Name Member : {{ $data_booking->user->nama }}</p>
              <p class="card-text ">No Handphone : {{ $data_booking->user->no_hp }}</p>
              <p class="card-text " style="margin-bottom: 0.1rem;">Type Room : {{ $data_booking->room->type_room }}</p>
              <p class="card-text " style="margin-bottom: 0.1rem;">Duration: {{ $data_booking->type_time }}</p>
              <p class="card-text"style="margin-bottom: 0.1rem;">Booking Hour :  {{ $data_booking->jam_booking }}:00</p>
              <div class="col">
              <p class="card-text " style="font-weight: 800">Proof of payment</p>
                <div class="image-bukti">
                    <img src="{{$data_booking->bukti_pembayaran}}" alt="">
                </div>
                <div class="col mt-3">

                    <label >Status</label>

                    <select class="form-control" aria-label="Default select example" name="id_status" id="status" >

                        <option value="{{ $data_booking->id_status }}" selected>

                            {{ $data_booking->status->status_order}}

                        </option>

                        @foreach ($status as $st )

                            @if(old('status_order') == $st->id)

                            <option value="{{ $st->id }}" selected>

                                {{ $st -> status_order}}

                            </option>

                            @else

                            <option value="{{ $st->id }}" >

                                {{ $st -> status_order}}

                            </option>

                            @endif
                        @endforeach

                    </select>

                </div>

              </div>
            </div>
          </div>
    </div>
</section>

@stop

@section('script')

<script>

    $(()=>{

        $('#status').on('change', function(e){

            var kode = $('.data').attr('data-id');

            //$(this).submit();

            UpadateStatusOrder(kode)

        });

    })

    function UpadateStatusOrder(kode){

        data = {};

        data['id_status'] = $('#status option:selected').attr('value');

        var url ="{{ route('update-status-booking','') }}";

        console.log(data['id_status']);

        baseURL = url + '/'+ kode

        $.ajax({

            url : baseURL,

            method: 'POST',

            type: 'json',

            data:{

                data:data,

                _token : "{{ csrf_token() }}"
            },

            success: function(data){

                console.log(data);

            }

        }).fail(function(data){

            console.log(data);

        })

    }



</script>

@stop

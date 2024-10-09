@extends('layout.master')
@section('content')


<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Place Booking Data</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/Dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Place Booking Data</li>
          </ol>
        </div>
      </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col">
            <div class="card">
              <div class="card-header">
                 <div class="tab-navigation">
                    <div class="tab" target-panel="panel1" order="1">New Booking </div>
                    <div class="tab" target-panel="panel2" order="2"> Booking End </div>
                    <div class="tab" target-panel="panel3" order="3">  Cancel </div>
                  </div>

              </div>
              </div>

              <!-- /.card-header -->
              <div class="card-body" style="overflow: scroll;">
               <div class="tab-panel-container">
                    <div class="panel active" data-panel="panel1" panel-order="1">
                        <table class="table table-bordered" id="table1">
                            <thead>
                              <tr>
                                <th style="width: 10px">NO</th>
                                <th>Kode Booking</th>
                                <th>Name Customer</th>
                                <th>No Handphone</th>
                                <th>Date Booking</th>
                                <th>Type Room</th>
                                <th>Action</th>

                              </tr>
                            </thead>
                            <tbody>
                              @php $no=1; @endphp

                              @foreach ( $booking_new  as $data )
                              <tr>
                                  <td>{{ $no++ }}</td>
                                  <td>
                                      <div class="menu">
                                          <p class="nama-menu">
                                              {{ $data->kode_boking }}
                                          </p>
                                      </div>
                                  </td>
                                  <td>{{ $data->user->nama }}</td>
                                  <td>{{ $data->user->no_hp }}</td>
                                  <td>{{ $data->tanggal_booking }}</td>
                                  <td>{{ $data->room->type_room }}</td>

                                  <td>
                                      <div class="text-center">
                                          <a href="{{route('detail.booking', $data->kode_boking )}}" type="button" class="btn btn-block btn-warning mb-2">
                                              Detail
                                          </a>
                                          <form action="{{route('delete-data-booking',encrypt( $data->id))}}" method="POST">

                                                @csrf

                                                @method('delete')

                                                <button  class="btn btn-block btn-danger">

                                                    Delete

                                                </button>

                                            </form>
                                      </div>
                                  </td>
                                </tr>
                              @endforeach

                            </tbody>
                        </table>
                    </div>
                    <div class="panel" data-panel="panel2" panel-order="2">
                        <table class="table table-bordered" id="table1">
                            <thead>
                              <tr>
                                <th style="width: 10px">NO</th>
                                <th>Kode Booking</th>
                                <th>Name Customer</th>
                                <th>No Handphone</th>
                                <th>Date Booking</th>
                                <th>Type Room</th>
                                <th>Action</th>

                              </tr>
                            </thead>
                            <tbody>
                              @php $no=1; @endphp

                              @foreach ($booking_end  as $data_end )
                              <tr>
                                  <td>{{ $no++ }}</td>
                                  <td>
                                      <div class="menu">
                                          <p class="nama-menu">
                                              {{ $data_end->kode_boking }}
                                          </p>
                                      </div>
                                  </td>
                                  <td>{{ $data_end->user->nama }}</td>
                                  <td>{{ $data_end->user->no_hp }}</td>
                                  <td>{{ $data_end->tanggal_booking }}</td>
                                  <td>{{ $data_end->room->type_room }}</td>

                                  <td>
                                      <div class="text-center">
                                          <a href="{{route('detail.booking', $data_end->kode_boking )}}" type="button" class="btn btn-block btn-warning mb-2">
                                              Detail
                                          </a>
                                           <form action="{{route('delete-data-booking',encrypt($data_end->id))}}" method="POST">

                                                @csrf

                                                @method('delete')

                                                <button  class="btn btn-block btn-danger">

                                                    Delete

                                                </button>

                                            </form>

                                      </div>
                                  </td>
                                </tr>
                              @endforeach

                            </tbody>
                        </table>
                    </div>
                    <div class="panel" data-panel="panel3" panel-order="3">
                        <table class="table table-bordered" id="table1">
                            <thead>
                              <tr>
                                <th style="width: 10px">NO</th>
                                <th>Kode Booking</th>
                                <th>Name Customer</th>
                                <th>No Handphone</th>
                                <th>Date Booking</th>
                                <th>Type Room</th>
                                <th>Action</th>

                              </tr>
                            </thead>
                            <tbody>
                              @php $no=1; @endphp

                              @foreach ($booking_cancel  as $cancel )
                              <tr>
                                  <td>{{ $no++ }}</td>
                                  <td>
                                      <div class="menu">
                                          <p class="nama-menu">
                                              {{  $cancel->kode_boking }}
                                          </p>
                                      </div>
                                  </td>
                                  <td>{{  $cancel->user->nama }}</td>
                                  <td>{{  $cancel->user->no_hp }}</td>
                                  <td>{{  $cancel->tanggal_booking }}</td>
                                  <td>{{  $cancel->room->type_room }}</td>

                                  <td>
                                      <div class="text-center">
                                          <a href="{{route('detail.booking',$cancel->kode_boking )}}" type="button" class="btn btn-block btn-warning mb-2">
                                              Detail
                                          </a>
                                          <form action="{{route('delete-data-booking',encry($cancel->id))}}" method="POST">

                                                @csrf

                                                @method('delete')

                                                <button  class="btn btn-block btn-danger">

                                                    Delete

                                                </button>

                                            </form>
                                      </div>
                                  </td>
                                </tr>
                              @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">

              </div>
            </div>
            <!-- /.card -->
          </div>
      </div>
    </div>
</section>
@stop

@section('script')
<script>
  $(()=>{
    $('.tab').on('click', function(e){
      var target = $(this).attr('target-panel'); // ambil target panel yang mau di aktifkan
      //semua tab navigation di nonaktifkan baru kemudian yang diklik di bedakan
      $('.tab').removeClass('active');
      $(this).addClass('active');

      // sembunyikan semua panel lalu yang sesuai dengan tab navigation baru dimunculkan
      $('.panel').hide();
      $(`.panel[data-panel="${target}"]`).show();
    })
  })

</script>
@stop


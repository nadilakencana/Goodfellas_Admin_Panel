@extends('layout.master')

@section('content')
@if(Sentinel::check())
@php
  $admin = Sentinel::getUser();
@endphp
@endif
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Order</h1>
        </div>
        <div class="col-sm-12">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/Dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Order</li>
          </ol>

        </div>

      </div>

    </div>

</section>

<section class="content">
  <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
                <div class="tab-navigation">
                  <div class="tab" target-panel="panel1" order="1">New Member </div>
                  <div class="tab" target-panel="panel2" order="2">New  Non Member </div>
                  <div class="tab active" target-panel="panel3" order="3"> Finish </div>
                  <div class="tab" target-panel="panel4" order="4">  Cancel </div>
                </div>

              </div>

              <!-- /.card-header -->

              <div class="card-body">
                <div class="tab-panel-container">
                    {{--  order baru member terdaftar  --}}
                  <div class="panel " data-panel="panel1" panel-order="1">
                    <div class="search">
                        <input type="text" class="box-search" id="search-input" placeholder="Search">
                        <div class="icon-search" id="first">
                            <img src="{{ asset('asset/assets/image/icon/icon _search_.png') }}" alt="" srcset="">
                        </div>
                    </div>
                    <table class="table table-bordered" id="data-table1">
                      <thead>
                        <tr>
                          <th style="width: 10px">NO</th>

                          <th>Order Code</th>
                          <th>Table Number</th>
                          <th>Date</th>
                          <th>Total</th>
                          <th>Action View</th>
                        </tr>

                      </thead>
                      <tbody id="table-data">
                        @php $no=1; @endphp
                        @foreach ($order_new as $orders )
                            {{--  @foreach ($orders->detail as $detail )  --}}
                            <tr>
                                <td>{{ $no++ }}</td>

                                <td>{{ $orders->kode_pemesanan }}</td>
                                <td>{{ $orders->no_meja }}</td>
                                <td>{{ $orders->created_at }}</td>
                                <td>{{number_format(  $orders->total_order, 0, ',','.')}}</td>
                                <td>
                                    <div class="text-center">
                                    <a href="{{route('detail.order',($orders->kode_pemesanan))}}" type="button" class="btn btn-block btn-warning mb-2">
                                        Detail
                                    </a>
                                    <div id-item="{{ $orders->id }}" type="button" class="btn btn-block btn-danger deleted-order-member mb-2">
                                      Delete
                                    </div>
                                    </div>
                                </td>
                            </tr>
                            {{--  @endforeach  --}}
                          @endforeach
                      </tbody>

                    </table>
                  </div>
                  {{--  order baru  non member  --}}
                  <div class="panel " data-panel="panel2" panel-order="2">
                    <div class="search">
                        <input type="text" class="box-search" id="search-input1" placeholder="Search">
                        <div class="icon-search" id="first">
                            <img src="{{ asset('asset/assets/image/icon/icon _search_.png') }}" alt="" srcset="">
                        </div>
                    </div>
                    <table class="table table-bordered" >
                        <thead>
                          <tr>
                            <th style="width: 10px">NO</th>
                            <th>Order Code</th>
                            <th>Table Number</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Action View</th>
                          </tr>

                        </thead>
                        <tbody id="data-table2">
                          @php $no=1; @endphp
                          @foreach ($order_new_nonUser as $orders )
                              {{--  @foreach ($orders->detail as $detail )  --}}
                              <tr>
                                  <td>{{ $no++ }}</td>

                                  <td>{{ $orders->kode_pemesanan }}</td>
                                  <td>{{ $orders->no_meja }}</td>
                                  <td>{{ $orders->created_at }}</td>
                                  <td>{{number_format(  $orders->total_order, 0, ',','.')}}</td>
                                  <td>
                                    <div class="text-center">
                                    <a href="{{route('detail.order',($orders->kode_pemesanan))}}" type="button" class="btn btn-block btn-warning mb-2">
                                        Detail
                                    </a>
                                    <div id-item="{{ $orders->id }}" type="button" class="btn btn-block btn-danger deleted-order-non-member mb-2">
                                      Delete
                                    </div>
                                    </div>
                                </td>
                              </tr>
                              {{--  @endforeach  --}}
                          @endforeach
                        </tbody>

                      </table>
                      
                  </div>
                  {{--  order finish  --}}
                  <div class="panel active" data-panel="panel3" panel-order="3">
                    <div class="head-layoute-tabel">
                      <div class="head-sub1">
                          <div class="search">
                              <input type="text" class="box-search" id="search-input2" placeholder="Search">
                              <div class="icon-search" id="first">
                                  <img src="{{ asset('asset/assets/image/icon/icon _search_.png') }}" alt="" srcset="">
                              </div>
                          </div>
                          <div class="date-custom">
                              
                            <div class="col">
                              <div class="d-flex justify-content-center align-items-center gap-5">
                                <label for="daterange" class="mx-3">Periode: </label>
                                <input type="text" name="daterange" class="form-control mx-3" />
                                <div class="filter mt-0">
                                  <img src="{{ asset('asset/assets/image/icon/filter_icon.png') }}" alt="" srcset="">
                                </div>
                              </div>

                            </div>
                          </div>
                         {{-- @if ($admin->level->level == 'Supervisor' || $admin->level->level == 'Manager' || $admin->level->level == 'Developer' ||
                          $admin->level->level == 'Directure')
                              <div class="btn-export-data">
                                  Export
                              </div>
                          @endif --}}

                      </div>
                      @if ($admin->level->level == 'Supervisor' || $admin->level->level == 'Manager' || $admin->level->level == 'Developer' ||
                          $admin->level->level == 'Directure')
                      <div class="head-sub2">
                          <div class="box-sum-transaksi">
                              <div class="sum-transaksi">
                                  <div class="jml-trans">
                                     {{  $orderCount }}
                                  </div>
                                  <div class="txt-name-trans">Transaction</div>
                              </div>
                              <div class="sum-transaksi">
                                  <div class="jml-trans">
                                      Rp. {{number_format( $TotalGrand, 0, ',','.')  }}
                                  </div>
                                  <div class="txt-name-trans">Total Collected</div>
                              </div>
                              <div class="sum-transaksi">
                                  <div class="jml-trans">
                                      Rp. {{number_format(  $allGrandNet, 0, ',','.')  }}
                                  </div>
                                  <div class="txt-name-trans">Net Sales</div>
                              </div>
                          </div>
                      </div>
                      @endif
                    </div>
                    <div class ="data-tabel-card" style ="overflow-y: scroll;overflow:auto;height: 500px">

                      <table class="table table-bordered" >

                        <thead>

                          <tr>
                              <th style="width: 10px">NO</th>
                              <th>Order Code</th>
                              <th>Table Number</th>
                              <th>Date</th>
                              <th>Total</th>
                              <th>Action View</th>

                          </tr>

                        </thead>

                        <tbody class="body-tbl-data" id="data-table3">

                          @php $no=1; @endphp

                          @foreach ($order_selesai as $end )

                          <tr class="tbl-data">
                              <td>{{ $no++ }}</td>

                              <td>{{ $end ->kode_pemesanan }}</td>
                              <td>{{ $end ->no_meja }}</td>
                              <td>{{date("d/m/Y", strtotime($end->created_at))  }}</td>
                              <td>{{number_format(  $end->total_order, 0, ',','.')}}</td>
                              <td>
                                  <div class="text-center">
                                  <a href="{{route('detail.order',($end->kode_pemesanan))}}" type="button" class="btn btn-block btn-warning mb-2">
                                      Detail
                                  </a>
                                  <div id-item="{{ $end->id }}" type="button" class="btn btn-block btn-danger deleted-order-finish mb-2">
                                    Delete
                                  </div>
                                  </div>
                              </td>
                          </tr>

                          @endforeach



                        </tbody>

                      </table>
                    </div>
                    
                  </div>
                  {{--  order di batalkan  --}}
                  <div class="panel " data-panel="panel4" panel-order="4">
                    <table class="table table-bordered" >
                      <thead>
                        <tr>
                            <th style="width: 10px">NO</th>
                            <th>Order Code</th>
                            <th>Table Number</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Action View</th>
                        </tr>
                      </thead>
                      <tbody>

                        @php $no=1; @endphp
                        @foreach ($order_batal as $cencel )
                          <tr>
                              <td>{{ $no++ }}</td>

                              <td>{{ $cencel->kode_pemesanan }}</td>
                              <td>{{ $cencel->no_meja }}</td>
                              <td>{{ $cencel->created_at }}</td>
                              <td>{{number_format(  $cencel->total_order, 0, ',','.')}}</td>
                              <td>
                                <div class="text-center">
                                    <a href="{{route('detail.order',($cencel->kode_pemesanan))}}" type="button" class="btn btn-block btn-warning mb-2">
                                        Detail
                                    </a>
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
<div class="popup-name-bill" style="display: none">
  <div class="position-card">
    <div class="card-colum-input" style="position: fixed;
    margin-top: 250px;">
      <div class="header-card d-flex justify-content-end">
        <div class="close">X</div>
      </div>
      <div class="form-group">
        <label for="" class="form-label">Mengapa ingin menghapus data ini?</label>
        <textarea type="text" class="form-control descript" rows="3"></textarea>
      </div>
     
      {{--  all save   --}}
      <div class="btn-selesai" xid="">
        <p class="text-btn-act save-bill" style="margin: 0px; text-align: center; ">Selesai</p>
      </div>

    </div>
  </div>
</div>
@stop
@section('script')
<script>
  $(()=>{

    @if(request()->has('daterange'))
      $('input[name="daterange"]').daterangepicker({
        locale: {
            format: 'YYYY-MM-DD',
            separator: " / "
          },
          startDate: '{{$startDate}}',
          endDate: '{{$endDate}}'
      });
    @else
        var DateTime = luxon.DateTime;
        var dt = DateTime.now();
        var current = dt.toFormat('yyyy-MM-dd');
          $('.date_search #date').val(current);
          
          $('input[name="daterange"]').daterangepicker({
          locale: {
            format: 'YYYY-MM-DD',
            separator: " / "
          },
          startDate: '{{$startDate}}',
          endDate: '{{$endDate}}'
        });
    @endif

    //panel tab
    $('.tab').on('click', function(e){
      var target = $(this).attr('target-panel'); // ambil target panel yang mau di aktifkan
      //semua tab navigation di nonaktifkan baru kemudian yang diklik di bedakan
      $('.tab').removeClass('active');
      $(this).addClass('active');

      // sembunyikan semua panel lalu yang sesuai dengan tab navigation baru dimunculkan
      $('.panel').hide();
      $(`.panel[data-panel="${target}"]`).show();
    });

    // export data
    $('body').on('click', '.btn-export-data', function(){
        $('.date-form').submit();
    })

    //filter
    // $('.filter').on('click', function(e){
    //   var $target = $('.body-tbl-data');
    //   $target.find('.tbl-data').remove();

    //   filterData();
    // });
    $('.filter').click(function(e){
        e.preventDefault();
        filterReportDate();
    });

    $('.deleted-order-member, .deleted-order-non-member, .deleted-order-finish').on('click', function(e){
      var id_itm = $(this).attr('id-item');
      $('.btn-selesai').attr('xid', id_itm);
      $('.popup-name-bill').show();
    });

    $(".popup-name-bill").click(function(event){
      if(!$(event.target).closest('.card-colum-input').length) {
          $(this).fadeOut();
      }
    });

    $('.header-card .close').on('click', function(){
      $('.popup-name-bill').hide();
    })

    $('.btn-selesai').on('click', function(){
      var xid = $(this).attr('xid');
      Deteled_Order(xid)
    })

    //tester function laporan filter
    function getData(){
            let URL = "{{ route('laporan') }}";
            var start = $('input.date-start').val();
            var end = $('input.date-end').val();
            $.ajax({
                url: URL,
                data: {
                    start_date: start,
                    end_date: end,
                    },
                method: 'GET',
                success: function(result){
                    console.log(result);

                }
            }).fail(function(result){
                console.log(result);
            });
    }

    //function filter data
    //  function filterData(){
    //     var $target = $('.date-custom');
    //     var start = $target.find('input.date-start').val();
    //     var end = $target.find('input.date-end').val();

    //     $.ajax({
    //         url: '{{ route('filter') }}',
    //         data: {
    //             start_date:start,
    //             end_date: end,
    //         },
    //         method: 'GET',
    //         type: 'json',
    //         success: function(result){
    //             var $elm = $('.body-tbl-data');
    //             $elm.html('');
    //             var no = 1;
               
    //             $elm.append(result);
                       
                
    //         }
    //     }).fail(function(data){
    //         console.log(data);
    //     });

    //  }

    function filterReportDate(){
      var daterange = $('input[name="daterange"]').val();
      var daterange = daterange.split(" / ");
      var startDate = daterange[0];
      var endDate = daterange[1];
      var url = "{{route('order')}}";
      url = url+'?startDate='+startDate+'&endDate='+endDate;
      // updateChart(startDate, endDate);
    
      window.location = url;
    }

     function Deteled_Order(xid){
      var descript = $('textarea.descript').val();
      var url = "{{ route('delete-order') }}"
      var dataDelete = {
        _token : "{{ csrf_token() }}",
        alasan_delete: descript,
        id_order : xid
      };

      console.log(dataDelete);

      $.post(url, dataDelete).done(function(data){
        $('.popup-name-bill').hide();
        $('textarea.descript').val('');
          window.location.reload();
        console.log(data)
      }).fail(function(data){
        console.log('error', data)
      })
    }

  });



</script>
@stop



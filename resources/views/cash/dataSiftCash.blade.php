@extends('layout.master')
@section('content')

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Report Sift</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ url('/Dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">Report Sift</li>
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
            <h3 class="card-title">Report Sift</h3>
            <div class="d-flex flex-row-reverse mx-2 gap-2">
              <div class="btn btn-success mb-2 start_sift" data-type="start_sift">
                Start Sift
              </div>

            </div>
          </div>
          <!-- /.card-header -->

          <div class="card-body" style="overflow: overlay;">
            <table class="table table-bordered" id="table1">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Start Time</th>
                  <th>End Time</th>
                  <th>Total Expected</th>
                  <th>Total Actual</th>
                  <th>Difference</th>
                  <th style="width:200px">Action</th>
                </tr>
              </thead>
              <tbody style="font-size: 12px;">
                @php $no=1; @endphp
                @foreach ($sift as $sift )
                <tr>

                  <td class="data-sift" xid="{{ $sift->id }}" style="cursor: pointer">
                    {{ $sift->admin->nama }}
                  </td>
                  <td>
                    {{ $sift->created_at }}
                  </td>
                  <td>
                    @if(!empty($sift->end_time))
                    {{ $sift->end_time }} - {{ date("H:i", strtotime($sift->updated_at)) }}
                    @else
                    -
                    @endif
                  </td>
                  <td>
                    Rp.{{number_format( $sift->total_expected, 0,',','.') }}
                  </td>
                  <td>
                    Rp.{{number_format( $sift->total_actual, 0,',','.')}}

                  </td>
                  <td>
                    Rp.{{number_format( $sift->difference, 0,',','.')}}

                  </td>
                  <td class="flex " style="display: flex;justify-content:center;gap:10px;font-size:10px">
                    <a href="{{ route('print_sift', $sift->id) }}" class="btn btn-primary" style="font-size:13px">Print</a>
                    @if(empty($sift->end_time))
                    <div class="btn btn-danger end_sift" data-type="end_sift" xid="{{ $sift->id }}" style="font-size:13px">
                      End Sift
                    </div>
                    <div class="btn btn-danger delete" data-type="delete" xid="{{ $sift->id }}" style="font-size:13px">
                      Delete
                    </div>
                    @endif
                  </td>

                </tr>
                @endforeach
              </tbody>
            </table>
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
    <div class="card-colum-input">
      <div class="header-card d-flex justify-content-end">
        <div class="close">X</div>
      </div>
      <div class="form-group">
        <label for="" class="form-label">Modal Sift</label>
        {{-- //input deskrip kas --}}
        <input type="text" class="form-control deskripsi mb-2" placeholder="Description Kas" style="display: none">
        <input type="text" placeholder="nominal" class="form-control cash-nominal-input"
          oninput="formatRupiah(this)">

        <input type="text" class="modal modal_sift" style="display: none">
        {{-- nominal kas --}}
        <input type="text" class="kas" style="display: none">
      </div>
      {{-- action kas --}}
      <div class="btn-acction-kas  mb-2" style="display: none">
        <div class="btn act-kas btn-danger out-kas" data-type="out-kas">Out</div>
        <div class="btn act-kas btn-primary in-kas" data-type="in-kas">In</div>
      </div>
      {{-- all save --}}
      <button class="btn btn-selesai" xid="" data-type="" disabled>
        Selesai
      </button>

    </div>
  </div>
</div>

<div class="pop-up-01" style="display: none">
  <div class="position-object">
    <div class="card-01">
      <div class="header-card d-flex justify-content-between">
        <div class="txt-tittle" style="font: 25px">Detail Sift</div>
        <div class="close-card">X</div>
      </div>
      <div class="body-card-01">

      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  let loadPhase = false;

  $(()=>{
    const dropdown = $('.dropdown');
    dropdown.on('click', function(){
        $(this).find('.sub-menu').slideToggle("fast");
    })

  $('.start_sift[data-type="start_sift"]').on('click', function(){
    var $tgt = $('.popup-name-bill');
    $tgt.show();
     //CekCashNominal()
    $tgt.find('.form-group .form-label').text('Modal Sift');
    $tgt.find('input.modal').attr('placeholder', 'nominal modal sift')
    .addClass('modal_sift').removeClass('endSift');
     $tgt.find('input.form-control.deskripsi').hide();
    $('.btn-acction-kas').hide()
    $('.btn-selesai').attr('data-type', 'start_sift');
  })

  $('body').on('click','.end_sift[data-type="end_sift"]', function(){
    var $tgt = $('.popup-name-bill');
    var xid =$(this).attr('xid');
    $tgt.show();
    //CekCashNominal()
    $tgt.find('.form-group .form-label').text('End Sift');
    $tgt.find('input.modal').attr('placeholder', 'nominal actual end sift')
    .addClass('endSift').removeClass('modal_sift');
    $tgt.find('input.form-control.deskripsi').hide();
    $('.btn-acction-kas').hide()

    $('.btn-selesai').attr('data-type', 'endSift');
    $('.btn-selesai').attr('xid', xid);
  })

  $('.header-card .close').on('click', function(){
    $('.popup-name-bill').hide();
  })
  $(".popup-name-bill").click(function(event){
        if(!$(event.target).closest('.card-colum-input').length) {
            $(this).fadeOut();
        }
    });
 

  $('.close-card').on('click', function(){
     $('.pop-up-01').hide();
  })
  $(".pop-up-01").click(function(event){
        if(!$(event.target).closest('.position-object .card-01').length) {
            $(this).fadeOut();
        }
    });
 
  $('.btn-selesai').on('click', function(){
    var type = $(this).attr('data-type');
    var xid = $(this).attr('xid');
    if(loadPhase){
        console.log('Process is already running. Please wait.');
        return;
    }
    loadPhase = true;
    const $button = $(this);
    $button.prop('disabled', true).text('Processing...');

    console.log(type)
    if(type == 'kas'){
      kas(xid, $button)
    }else{
       postSift(type, $button);
    }
   
  })

  $('body').on('click','.data-sift', function(){
     var xid = $(this).attr('xid');

     detailSift(xid);
  });

  $('body').on('click', '.Kas', function(){
    var $tgt = $('.popup-name-bill');
    //mengambil id dari dta sift
    var idx = $(this).attr('dt-id');
    $tgt.show();
    //CekCashNominal()
    $tgt.find('.form-group .form-label').text('Kas');
    $tgt.find('input.cash-nominal-input').attr('placeholder', 'nominal kas');
    $tgt.find('input.form-control.deskripsi').show();
    //menyimpan id sift ke btn untuk di simpan saat post
    $tgt.find('.btn-selesai').attr('xid', idx);
    $('.btn-acction-kas').show();
    $('.btn-selesai').attr('data-type', 'kas');
   
  })

  $('body').on('click','.btn-acction-kas .out-kas', function(){
      var $tgt = $(this);
      var type = $tgt.attr('data-type');
      $tgt.removeClass('btn-danger').addClass('active');

      var $in = $('.btn-acction-kas .in-kas');

      if($in.hasClass('active')){
          $in.removeClass('active').addClass('btn-primary')
      }

   // $('.btn-selesai').attr('data-type', type);
  })
  $('.btn-acction-kas .in-kas').on('click', function(){
      var $tgt = $(this);
      var type = $tgt.attr('data-type');
      $tgt.removeClass('btn-primary').addClass('active');
       var $out = $('.btn-acction-kas .out-kas');

     if($out.hasClass('active')){
        $out.removeClass('active').addClass('btn-danger')
    }
    //$('.btn-selesai').attr('data-type', type);
  })



})

function detailSift(id){
  var url = '{{ route('detail_sift', '') }}' +'/'+ id;

  $.ajax({
    url: url,
    data: {idx: id},
    method: 'GET',
    success: function(result){
      $('.pop-up-01').show();
      $('.card-01 .body-card-01').empty();
      $(result).appendTo('.card-01 .body-card-01');
      //mengirim id dari data sift 
      $('body .data-detial .Kas').attr('dt-id', id);
    }
  }).fail(function(result){
    console.log(result);
  })
   
}

function postSift(type, $button){

  var nominal = $('.cash-nominal-input').val();
  var convert = nominal.replace(/\D/g, '');

  if(type == 'start_sift'){
    var url = '{{ route('start_sift') }}';

    var startSift = $('input.modal.modal_sift').val(convert);
    var value = startSift.val();
    console.log(value);
    var PostData = {
     _token : "{{ csrf_token() }}",
     nominal: value,
    }

  }
  if(type == 'endSift'){

    var id = $('.btn-selesai').attr('xid');
    var url = '{{ route('end_sift', '') }}' + '/'+ id;
    var endSift = $('input.modal.endSift').val(convert);
    var value = endSift.val();
    console.log(value);

    var PostData = {
     _token : "{{ csrf_token() }}",
     nominal: value,
    }

  }
  

  console.log(PostData);

  $.post(url, PostData).done(function(data){
    alert('Done');
    $('.popup-name-bill').hide();
      window.location.reload();
    console.log(data)
  }).fail(function(data){
    console.log('error', data)
  }).always(function () {
     // Reset loadPhase and button state
      loadPhase = false;
      $button.prop('disabled', false).text('Selesai');
  });
  


}


function kas(id, $button){
  var $tgt = $('.popup-name-bill');
  var deskripsi = $tgt.find('input.deskripsi').val();
  var nominal = $('.cash-nominal-input').val();
  var convert = nominal.replace(/\D/g, '');
  var $kas_nominal = $tgt.find('input.kas').val(convert);
  var tgt_nominal = $kas_nominal.val();
  var $btn_in = $('.btn-acction-kas .in-kas');

  if($btn_in.hasClass('active')){
    var type = $('.btn-acction-kas .in-kas').attr('data-type');
  }else{
    var type = $('.btn-acction-kas .out-kas').attr('data-type');

  }

  var dataPostCash ={
      _token : "{{ csrf_token() }}",
      type: type,
      nominal: tgt_nominal,
      deskripsi: deskripsi,
      id_sift: id
  };

  console.log(dataPostCash);
  $.post('{{ route('kas') }}', dataPostCash).done(function(data){
    alert(data.message);
    $('.popup-name-bill').hide();
    $('.pop-up-01').hide();
    detailSift(id)
  }).fail(function(data){
    console.log('error', data);
  }).always(function () {
     // Reset loadPhase and button state
      loadPhase = false;
      $button.prop('disabled', false).text('Selesai');
  });
}

function formatRupiah(input) {
      // Menghilangkan semua karakter non-digit
    let nominal = input.value.replace(/\D/g, '');
    // Format nominal menjadi format Rupiah yang sesuai
    const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    });
    // Update nilai input dengan format Rupiah
    const formattedValue = nominal === '' ? '' : formatter.format(nominal); // Handle nilai kosong
    input.value = formattedValue;
    
    // Cek nominal dengan fungsi tambahan
    CekCashNominal(input);

    // Log nilai nominal (menggunakan nilai yang telah diformat)
    console.log(input.value);
}

function CekCashNominal(input){
    console.log(input.value)
    if (input.value === "Rp0" || input.value.trim() === "" || input.value === "0") {
        // Nonaktifkan tombol
        $('.btn-selesai').prop('disabled', true);
    } else {
        // Aktifkan tombol
        $('.btn-selesai').prop('disabled', false);
    }
}



</script>
@stop
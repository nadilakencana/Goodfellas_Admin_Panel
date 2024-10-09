@extends('layout.master')
@section('content')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Claim Vocher Gift Member</h1>
        </div>

        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/Dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Claim Vocher Gift Member</li>
          </ol>
        </div>
      </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="card">
              <div class="card-header">
                <h3 class="card-title">Detail Vocher Gift</h3>
                <div class="d-flex flex-row-reverse">

                </div>
              </div>
              <div class="card-body" style="overflow: overlay;">
                    <div class="part-input-code">
                        <input type="text" class="input-cede-barcode" placeholder=" scan barcode vocher user" autofocus>

                    </div>
                    <div class="content-detail-claim">

                    </div>
              </div>
            </div>
    </div>
</section>
@stop
@section('script')
<script>
    $(()=>{
         $('body').on('keypress','.input-cede-barcode',function(e){
            var key = e.which;

            if(key == 13){
                var kode = $(this).val();

                 let URL ="{{ route('vocher') }}";

                    var pram = {
                        kode: kode,
                    };
                $.get(URL, pram, function(result){

                    $(result).appendTo('.content-detail-claim');
                }).fail(function(result){
                    console.log(result);
                });

            }


        });

        $('body').on('click', '.btn-claim.vcr', function(){
            var xid = $(this).attr('xid');

                let URL ="{{ route('vocher-claim') }}";

                var pram = {
                    _token : "{{ csrf_token() }}",
                    xid: xid,
                };
                 $.post(URL, pram).done(function(data){

                    //alert(data.message);
                    alert('Vocher successfully claimed');
                    location.reload();

                }).fail(function(data){
                        console.log('error', data);
                 });
        });

    })
</script>
@stop

@extends('layout.master')
@section('content')
<div class="card card-primary">

    <div class="card-header">
      <h3 class="card-title">Edit Modifier</h3>
    </div>

    @if (session()->has('faild'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('faild') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    {{--  form start  --}}

      <div class="card-body">
            <div class="form-group">
            <label for="" class="form-label">Modifier Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror nama" id="exampleInputEmail1" placeholder="Modifier Name" name="name" value="{{ $group->name }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="card-header mt-2 gap-3">
                <h3 class="card-title">Option Modifier</h3>
                <div class="btn btn-primary ml-5 add-option" > +Add Option</div>
            </div>
            <div class="option-modifier">
                @foreach ( $option_modif as $k => $op )
                    <div class="row option" xid="{{ $k }}">
                        <div class="col-sm-5">
                            <div class="form-group option-modifier">
                                <label for="" class="form-label">Option Name</label>
                                <input type="text" class="form-control nama_option" id="exampleInputEmail1" placeholder="Name" name="option_modif[{{ $k }}][name]" xid="{{ $k }}" value="{{ $op->name }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group option-modifier">
                                <label for="" class="form-label">Price</label>
                                <input type="text" class="form-control harga" id="exampleInputEmail1" placeholder="Price" name="option_modif[{{ $k }}][harga]" xid="{{ $k }}" value="{{ $op->harga }}">
                                @error('harga')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-2 mt-4 ">
                            <div class="btn btn-danger hapus" xid="{{ $k }}">Delete</div>
                        </div>
                    </div>
                @endforeach
            </div>



      </div>

     {{--  card-body  --}}

      <div class="card-footer">
        <button  class="btn btn-primary save">Save Data</button>
      </div>

    <div class="form-submit">
        <form action="{{route('update-data-modifier', encrypt($group->id))}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="name" class="name_modifier"/>
            @foreach ( $option_modif as $k => $op )

            <input type="hidden" name="option_modif[{{ $k }}][id]" class="op_id" value="{{ $op->id }}"/>
            <input type="hidden" name="option_modif[{{ $k }}][name]" class="op_name" value="{{ $op->name }}"/>
            <input type="hidden" name="option_modif[{{ $k }}][harga]" class="op_harga" value="{{ $op->harga }}" />
            @endforeach


        </form>
    </div>

</div>
@stop

@section('script')
<script>
    $(()=>{

        var $rowInput = $('.row option');
        var idxRow = 0;

        @if(count($option_modif) > 0)
        var idxRow = {{ $k }};
        $('.btn.add-option').on('click', function(){
            idxRow++;
            addRowOption(idxRow);
            console.log('btn var ')
        });

        @endif


        $('.card-footer button.save').on('click', function(e){
            postData();
            e.stopPropagation();
            e.preventDefault();
        })

        function addRowOption(idx){
            var $content = $('.card-body');
            var $form = $('.form-submit form ');

            $form.append(`<input type="hidden" name="option_modif[${idx}][name]" class="op_name">`);
            $form.append(`<input type="hidden" name="option_modif[${idx}][harga]" class="op_harga">`);

            $content.append(
                `<div class="row option" xid="${idx}">`+
                    `<div class="col-sm-5">`+
                        `<div class="form-group option-modifier">`+
                            `<label for="" class="form-label">Option Name</label>`+
                            `<input type="text" class="form-control nama_option" id="exampleInputEmail1" placeholder="Name" name="option_modif[${idx}][name]" xid="${idx}">`+
                            `@error('name')`+
                                `<small class="text-danger"></small>`+
                           ` @enderror`+
                        `</div>`+
                    `</div>`+
                    `<div class="col-sm-5">`+
                        `<div class="form-group option-modifier">`+
                            `<label for="" class="form-label">Price</label>`+
                            `<input type="text" class="form-control harga" id="exampleInputEmail1" placeholder="Price" name="option_modif[${idx}][harga]" xid="${idx}">`+
                            `@error('harga')`+
                                `<small class="text-danger"></small>`+
                            `@enderror`+
                        `</div>`+
                    `</div>`+
                    `<div class="col-sm-2 mt-4">`+
                        `<div class="btn btn-danger hapus" xid="${idx}">Delete</div>`+
                    `</div>`+
                `</div>`
                );

        }

        $('body').on('click','.row.option .hapus' ,function(){
            var $elm = $(this);
            var idx = $elm.attr('xid');

            var $content = $('.card-body');
            var $form = $('.form-submit form');

            let konfirmasi = confirm('sure you want to delete this line?');

            if(konfirmasi){
                $content.find(`.row.option[xid="${idx}"]`).remove();
               $form.find(`input[name="option_modif[${idx}][name]"]`).val('Delete');
            }

        });


    });

    function postData(){
        var $content = $('.card-body');
        var $form = $('.form-submit form');
        var $row = $content.find('.row.option');

        var groupModifier = $content.find('.form-group input.nama').val();

        $form.find('input.name_modifier').val(groupModifier);

        $row.each(function(){
            var $elm = $(this);
            var idx = $elm.attr('xid');

            var namaOption = $elm.find(`.form-group.option-modifier input[name="option_modif[${idx}][name]"]`).val();
            var hargaOption = $elm.find(`.form-group.option-modifier input[name="option_modif[${idx}][harga]"]`).val();

            $form.find(`input[name="option_modif[${idx}][name]"]`).val(namaOption);
            $form.find(`input[name="option_modif[${idx}][harga]"]`).val(hargaOption);
            //console.log(namaOption, hargaOption, nama);
        });

        $('.form-submit form').submit();
    }

</script>
@stop

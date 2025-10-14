@extends('layout.master')

@section('content')





<div class="card card-primary">

    <div class="card-header">

      <h3 class="card-title">Create New Menu</h3>

    </div>

    @if (session()->has('faild'))

      <div class="alert alert-danger alert-dismissible fade show" role="alert">

        {{ session('faild') }}

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

      </div>

    @endif

    <!-- form start -->

    <form action="{{route('push.menu')}}" method="post" enctype="multipart/form-data">

      @csrf

      <div class="card-body">
        {{-- <div class="form-group sw-custom">
             <label for="" class="form-label">Menu Custom</label>
             <input type="checkbox" name="custom" id="swbtn" class="toggle" value="">
        </div> --}}
        <div class="form-group">
          <label for="" class="form-label">Menu Name</label>
          <input type="text" class="form-control @error('nama') is-invalid @enderror " id="exampleInputEmail1" placeholder="Menu Name" name="nama_menu">
          @error('nama_menu')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
          <label for="" class="form-label">Slug</label>
          <input type="text" class="form-control @error('slug') is-invalid @enderror " id="exampleInputEmail1" placeholder="Nama-Menu" name="slug">
          @error('slug')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
        <label for="" class="form-label">Description</label>

            <textarea class="form-control @error('deskripsi') is-invalid @enderror" type="text" name="deskripsi" placeholder="Menu Detail" id="detail_barang" style="height: 100px"value="{{ old('deskripsi') }}" required></textarea>

            @error('deskripsi')

                <small class="text-danger">{{ $message }}</small>

            @enderror

        </div>
        <div class="form-group">

            <label for="" class="form-label">Price Menu</label>

            <input type="text" class="form-control @error('harga') is-invalid @enderror " id="exampleInputEmail1" placeholder="Price" name="harga">

            @error('slug')

                  <small class="text-danger">{{ $message }}</small>

            @enderror

        </div>
        <div class="form-group">
            <label>Type Stok</label>
            <select class="custom-select rounded-0" name="tipe_stok" id="tipe_stok">
                <option value="" selected>--Type Stok--</option>
                <option value="Stok Manual" >Stok Manual</option>
                <option value="Stok Bahan Baku">Stok Bahan Baku</option>
            </select>
        </div>

        <div class="form-group" id="bahan_baku_group" style="display: none;">
            <label>Bahan Baku</label>
            <select class="custom-select rounded-0" name="id_bahan_baku" id="id_bahan_baku">
                <option value="">--Pilih Bahan Baku--</option>
                @foreach ($bahan_baku as $bahan )
                    <option value="{{ $bahan->id }}">
                        {{ $bahan->nama_bahan}}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group" id="stok_manual_group" style="display: none;">
            <label for="" class="form-label">Stok Menu</label>
            <input type="number" class="form-control @error('stok') is-invalid @enderror" name="stok" placeholder="Stok Menu">
            @error('stok')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        
        <div class="form-group" id="stok_minimum_group" style="display: none;">
            <label for="" class="form-label">Stok Minimum</label>
            <input type="number" class="form-control @error('stok_minimum') is-invalid @enderror" name="stok_minimum" placeholder="Minimum Stok">
            @error('stok_minimum')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group sw-custom">
             <label for="" class="form-label">Menu Active</label>
             <input type="checkbox" name="active" id="swbtn" class="toggle" value="1" checked>
        </div>
        <div class="form-group">
            <label for="" class="form-label">Promo</label>
            <input type="text" class="form-control @error('promo') is-invalid @enderror " id="exampleInputEmail1" placeholder="Promo" name="promo">
            @error('promo')
                  <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <select class="custom-select rounded-0"  name="id_kategori" id="exampleSelectRounded0" >
                <option value="">--Select Category--</option>
                @foreach ($kat as $kategori )
                    @if(old('kategori_nama') == $kategori->id)
                    <option value="{{ $kategori->id }}" selected>
                        {{ $kategori->kategori_nama}}
                    </option>
                    @else
                    <option value="{{ $kategori->id }}">
                        {{ $kategori->kategori_nama}}
                    </option>
                    @endif
                @endforeach
            </select>

        </div>
        <div class="form-group">
            <label>Sub Kategori</label>
            <select class="custom-select rounded-0" aria-label="Default select example" name="id_sub_kategori" id="exampleSelectRounded0" >
                <option value="">--Select Sub Category--</option>
                @foreach ($sub_kat as $sk )
                    @if(old('sub_kategori') == $sk->id)
                    <option value="{{ $sk->id }}" selected>
                        {{ $sk -> sub_kategori}}
                    </option>
                    @else
                    <option value="{{ $sk->id }}">
                        {{ $sk -> sub_kategori}}
                    </option>
                    @endif
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label>Additional</label>
            <select class="custom-select rounded-0" aria-label="Default select example" name="id_group_modifier" id="exampleSelectRounded0" >
                <option value="">--Select Addtional--</option>
                @foreach ( $additional as $add )

                    <option value="{{ $add ->id }}">
                        {{ $add  -> name}}
                    </option>

                @endforeach
            </select>
        </div>
        <div class="form-group">
          <label for="exampleInputFile">Image Menu</label>
          <div class="input-group">
            <div class="custom-file">
              <input type="file" class="custom-file-input @error('image') is-invalid @enderror " id="exampleInputFile" name="image">
              <label class="custom-file-label" for="exampleInputFile">Choose file</label>
            </div>
            @error('image')
                  <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
        </div>
        <div class="card-header mt-2 gap-3">
            <h3 class="card-title">Variasi Menu</h3>
            <div class="btn btn-primary ml-5 add-option" > +Add Option</div>
        </div>

      </div>

      <!-- /.card-body -->
      <div class="card-footer">

        <button type="submit" class="btn btn-primary">Save Data</button>

      </div>

    </form>

  </div>

@stop

@section('script')
<script>
    $(()=>{
        var $rowInput = $('.row option');
        var idxRow = 0;

        //tambah row
        $('.btn.add-option').on('click', function(){
            idxRow++;
            addRowOption(idxRow);
        });

        $('body').on('change', '.toggle', function(){
			$(this).attr('value', $(this).prop('checked') ? 1 : 0);
		});

        // Handle stock type change
        $('#tipe_stok').on('change', function(){
            var selectedType = $(this).val();
            
            if(selectedType === 'Stok Bahan Baku') {
                $('#bahan_baku_group').show();
                $('#stok_manual_group').hide();
                $('#stok_minimum_group').hide();
            } else if(selectedType === 'manual stok') {
                $('#bahan_baku_group').hide();
                $('#stok_manual_group').show();
                $('#stok_minimum_group').show();
            } else {
                $('#bahan_baku_group').hide();
                $('#stok_manual_group').hide();
                $('#stok_minimum_group').hide();
            }
        });

        // Initialize on page load
        $('#tipe_stok').trigger('change');



        //hapus row
        $('body').on('click','.row.option .hapus' ,function(){
            var $elm = $(this);
            var idx = $elm.attr('xid');

            var $content = $('.card-primary');
            var $form = $content.find('form .card-body');

            let konfirmasi = confirm('sure you want to delete this line?');

            if(konfirmasi){
                $form.find(`.row.option[xid="${idx}"]`).remove();

            }

        });


        function addRowOption(idx){
            var $content = $('.card-primary');
            var $form = $content.find('form .card-body');

            $form.append(
                `<div class="row option" xid="${idx}">`+
                    `<div class="col-sm-3">`+
                        `<div class="form-group variasi">`+
                            `<label for="" class="form-label">Variasi</label>`+
                            `<input type="text" class="form-control nama" id="exampleInputEmail1" placeholder="Name"  name="variasi[${idx}][nama]" xid="${idx}">`+
                            `@error('nama')`+
                                `<small class="text-danger"></small>`+
                           ` @enderror`+
                        `</div>`+
                    `</div>`+
                    `<div class="col-sm-3">`+
                        `<div class="form-group variasi">`+
                            `<label for="" class="form-label">Price</label>`+
                            `<input type="text" class="form-control harga" id="exampleInputEmail1" placeholder="Price" name="variasi[${idx}][harga]" xid="${idx}">`+
                            `@error('harga')`+
                                `<small class="text-danger"></small>`+
                            `@enderror`+
                        `</div>`+
                    `</div>`+
                    `<div class="col-sm-3">`+
                        `<div class="form-group variasi">`+
                            `<label for="" class="form-label">Varian Active</label>`+
                            `<input type="checkbox" name="variasi[${idx}][active]" id="swbtn" class="toggle" value="1" checked>`+
                        `</div>`+
                    `</div>`+
                    `<div class="col-sm-2 mt-4">`+
                        `<div class="btn btn-danger hapus" xid="${idx}">Delete</div>`+
                    `</div>`+
                `</div>`
                );

        }


    });
</script>
@stop


@extends('layout.master')

@section('content')

<div class="card card-primary">

    <div class="card-header">

      <h3 class="card-title">Edit Sub Category Menu</h3>

    </div>

    @if (session()->has('faild'))

      <div class="alert alert-danger alert-dismissible fade show" role="alert">

        {{ session('faild') }}

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

      </div>

    @endif

    <!-- form start -->

    <form action="{{route('update.subKat', $subKat->id )}}" method="post" enctype="multipart/form-data">

      @csrf
      @method('put')
      <div class="card-body">

        <div class="form-group">

          <label for="" class="form-label">Name Sub Category</label>

          <input type="text" class="form-control @error('sub_kategori') is-invalid @enderror " id="exampleInputEmail1" placeholder="Name Sub " name="sub_kategori" value="{{ $subKat->sub_kategori}}">

          @error('sub_kategori')

                <small class="text-danger">{{ $message }}</small>

            @enderror

        </div>
        <div class="form-group">

          <label for="" class="form-label">Slug Sub Category</label>

          <input type="text" class="form-control @error('slug') is-invalid @enderror " id="exampleInputEmail1" placeholder="Slug-Sub" name="slug" value="{{ $subKat->slug}}">

          @error('slug')

                <small class="text-danger">{{ $message }}</small>

            @enderror

        </div>

        <div class="form-group col-md-4">

            <label>Category</label>

            <select class="custom-select rounded-0"  name="id_kategori" id="exampleSelectRounded0" >
                    <option value="{{$subKat->id_kategori}}">{{ $subKat->kategori->kategori_nama}}</option>
                @foreach ($kategori as $kat )

                    @if(old('kategori_nama') ==  $kat->id)

                    <option value="{{  $kat->id }}" selected>

                        {{  $kat -> kategori_nama}}

                    </option>

                    @else

                    <option value="{{  $kat->id }}">

                        {{  $kat -> kategori_nama}}

                    </option>

                    @endif

                @endforeach

            </select>

        </div>
      </div>

      <!-- /.card-body -->



      <div class="card-footer">

        <button type="submit" class="btn btn-primary">Save Data</button>

      </div>

    </form>

  </div>

@stop


@extends('layout.master')

@section('content')

<div class="card card-primary">

    <div class="card-header">
      <h3 class="card-title">Create New Bahan Baku</h3>
    </div>

    @if (session()->has('faild'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('faild') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- form start -->

    <form action="{{route('push.bahanBaku')}}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="card-body">
        <div class="form-group">
          <label for="" class="form-label">Bahan Baku</label>
          <input type="text" class="form-control @error('nama_bahan') is-invalid @enderror " id="exampleInputEmail1" placeholder="Bahan baku makanan" name="nama_bahan">
            @error('nama_bahan')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="" class="form-label">Stok</label>
            <input type="number" class="form-control @error('stok_porsi') is-invalid @enderror " id="exampleInputEmail1" placeholder="stok" name="stok_porsi">
            @error('stok_porsi')
                  <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="" class="form-label">Stok Minimum</label>
            <input type="number" class="form-control @error('stok_minimum') is-invalid @enderror " id="exampleInputEmail1" placeholder="stok minimun" name="stok_minimum">
            @error('stok_minimum')
                  <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        
      </div>

      <!-- /.card-body -->



      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Save Data</button>
      </div>

    </form>

</div>

@stop


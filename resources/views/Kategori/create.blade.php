@extends('layout.master')

@section('content')

<div class="card card-primary">

    <div class="card-header">
      <h3 class="card-title">Create New Menu Category</h3>
    </div>

    @if (session()->has('faild'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('faild') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- form start -->

    <form action="{{route('kat.push')}}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="card-body">
        <div class="form-group">
          <label for="" class="form-label">Menu Category</label>
          <input type="text" class="form-control @error('kategori_nama') is-invalid @enderror " id="exampleInputEmail1" placeholder="Category Name" name="kategori_nama">
            @error('kategori_nama')
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


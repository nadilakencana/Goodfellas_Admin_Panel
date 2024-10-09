@extends('layout.master')
@section('content')
<div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">Create Taxes</h3>
    </div>

    @if (session()->has('faild'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('faild') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>

    @endif

    <!-- form start -->

    <form action="{{route('post-data-tax')}}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="card-body">
        <div class="form-group">
          <label for="" class="form-label">Name Tax</label>
          <input type="text" class="form-control @error('nama') is-invalid @enderror " id="exampleInputEmail1" placeholder="Name Tax " name="nama">
          @error('nama')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
          <label for="" class="form-label">Rate Tax</label>
          <input type="text" class="form-control @error('tax_rate') is-invalid @enderror " id="exampleInputEmail1" placeholder="Rate Tax" name="tax_rate">
          @error('tax_rate')
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


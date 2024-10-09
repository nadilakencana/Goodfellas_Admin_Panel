@extends('layout.master')
@section('content')
<div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">Create Discount</h3>
    </div>

    @if (session()->has('faild'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('faild') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>

    @endif

    <!-- form start -->

    <form action="{{route('post-data-discount')}}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="card-body">
        <div class="form-group">
          <label for="" class="form-label">Discount Name</label>
          <input type="text" class="form-control @error('nama') is-invalid @enderror " id="exampleInputEmail1" placeholder="Discount Name " name="nama">
          @error('nama')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
          <label for="" class="form-label">Discount Rate</label>
          <input type="text" class="form-control @error('rate_dis') is-invalid @enderror " id="exampleInputEmail1" placeholder="Discount Rate" name="rate_dis">
          @error('rate_dis')
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


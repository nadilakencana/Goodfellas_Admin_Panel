@extends('layout.master')
@section('content')
<div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">Edit Discount</h3>
    </div>

    @if (session()->has('faild'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('faild') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>

    @endif

    <!-- form start -->

    <form action="{{route('update-data-discount', encrypt($dis->id))}}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="card-body">
        <div class="form-group">
          <label for="" class="form-label">Tax Name</label>
          <input type="text" class="form-control @error('nama') is-invalid @enderror " id="exampleInputEmail1" placeholder="Name " name="nama" value="{{ $dis->nama }}">
          @error('nama')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
          <label for="" class="form-label">Rate Discount</label>
          <input type="text" class="form-control @error('rate_dis') is-invalid @enderror " id="exampleInputEmail1" placeholder="Rate Discount" name="rate_dis" value="{{ $dis->rate_dis }}">
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


@extends('layout.master')
@section('content')
<div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">Create Type Payment</h3>
    </div>

    @if (session()->has('faild'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('faild') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>

    @endif

    <!-- form start -->

    <form action="{{route('post-data-payment')}}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="card-body">
        <div class="form-group">
          <label for="" class="form-label">Type Payment</label>
          <input type="text" class="form-control @error('nama') is-invalid @enderror " id="exampleInputEmail1" placeholder="Type Payment" name="nama">
          @error('nama')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="exampleInputFile">Image Payment</label>
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
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Save Data</button>
      </div>

    </form>

  </div>

@stop


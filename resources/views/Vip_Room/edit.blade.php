@extends('layout.master')

@section('content')

<div class="card card-primary">

    <div class="card-header">

      <h3 class="card-title">Edit Data Room </h3>

    </div>

    @if (session()->has('error'))

      <div class="alert alert-danger alert-dismissible fade show" role="alert">

        {{ session('error') }}

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

      </div>

    @endif

    <!-- form start -->

    <form action="{{route('update.room', encrypt($room->id))}}" method="post" enctype="multipart/form-data">
        @method('put')
      @csrf

      <div class="card-body">

        <div class="form-group">

          <label for="" class="form-label">Type Room</label>

          <input type="text" class="form-control @error('type_room') is-invalid @enderror " id="exampleInputEmail1" placeholder="Type room" name="type_room" value="{{$room->type_room}}">

          @error('type_room')

                <small class="text-danger">{{ $message }}</small>

            @enderror

        </div>



        <div class="form-group">

          <label for="" class="form-label">Slug</label>

          <input type="text" class="form-control @error('slug_room') is-invalid @enderror " id="exampleInputEmail1" placeholder="Slug-Room" name="slug_room" value="{{$room->slug_room}}">

          @error('slug_room')

                <small class="text-danger">{{ $message }}</small>

            @enderror

        </div>

        <div class="col-md-8 form-group">

        <label for="" class="form-label">Description</label>

            <textarea class="form-control @error('deskripsi') is-invalid @enderror" type="text" name="deskripsi" placeholder="Detail Menu" id="detail_barang" style="height: 100px"value="{{ old('deskripsi') }}" required>{{$room->deskripsi}}</textarea>

            @error('deskripsi')

                <small class="text-danger">{{ $message }}</small>

            @enderror

        </div>

        <div class="form-group">

            <label for="" class="form-label">Minimum Dp</label>

            <input type="text" class="form-control @error('min_dp') is-invalid @enderror " id="exampleInputEmail1" placeholder="Minimal Dp" name="min_dp" value="{{$room->min_dp}}">

            @error('minl_dp')

                  <small class="text-danger">{{ $message }}</small>

              @enderror

        </div>

        <div class="form-group">

          <label for="exampleInputFile">Image Room</label>

          <div class="input-group">

            <div class="custom-file">

              <input type="file" class="custom-file-input @error('image') is-invalid @enderror " id="exampleInputFile" name="image" value="{{$room->image}}">

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


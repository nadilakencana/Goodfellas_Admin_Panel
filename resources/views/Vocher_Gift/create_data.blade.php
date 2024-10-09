@extends('layout.master')

@section('content')

<div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">Create Vocher</h3>
    </div>
    @if (session()->has('faild'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('faild') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>

    @endif

    <form action="{{route('create-vocher')}}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="card-body">
        <div class="form-group">
          <label for="" class="form-label">Vocher Name</label>
          <input type="text" class="form-control @error('nama_vocher') is-invalid @enderror " id="exampleInputEmail1" placeholder="Vocher Name" name="nama_vocher">
          @error('nama_vocher')
                <small class="text-danger">{{ $message }}</small>
            @enderror

        </div>
        <div class="form-group">
          <label for="" class="form-label">Slug</label>
          <input type="text" class="form-control @error('slug_vocher') is-invalid @enderror " id="exampleInputEmail1" placeholder="Vocher-Name" name="slug_vocher">
          @error('slug_vocher')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="col-md-8 form-group">
            <label for="" class="form-label">Detail</label>
            <textarea class="form-control @error('detail') is-invalid @enderror" type="text" name="detail" placeholder="keterangan dari vocher"  style="height: 100px" required></textarea>
            @error('detail')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="col-md-8 form-group">
            <label for="" class="form-label">Syarat&Ketentuan</label>
            <textarea class="form-control @error('term_condition') is-invalid @enderror" type="text" name="term_condition" placeholder="Syarat & Ketentuan Vocher"  style="height: 100px" required></textarea>
            @error('term_condition')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="" class="form-label">Point</label>
            <input type="text" class="form-control @error('point_reward') is-invalid @enderror " id="exampleInputEmail1" placeholder="Point" name="point_reward">
            @error('point_reward')
                  <small class="text-danger">{{ $message }}</small>
              @enderror
        </div>

        <div class="form-group">
          <label for="exampleInputFile">Image Vocher</label>
          <div class="input-group">
            <div class="custom-file">
              <input type="file" class="custom-file-input @error('image') is-invalid @enderror "  name="image">
              <label class="custom-file-label" for="exampleInputFile">Choose file</label>
            </div>
            @error('image')
                  <small class="text-danger">{{ $message }}</small>
              @enderror
          </div>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Save Data</button>
      </div>
    </form>
  </div>
@stop


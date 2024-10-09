@extends('layout.master')
@section('content')
<div class="card card-primary">
    <div class="card-header">
      <h3 class="card-title">Edit Data Admin </h3>
    </div>

    @if (session()->has('faild'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('faild') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>

    @endif

    <!-- form start -->

    <form action="{{route('udpdateDataAdmin', encrypt($admin->id))}}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="card-body">
        <div class="form-group">
          <label for="" class="form-label">Name</label>
          <input type="text" class="form-control @error('nama') is-invalid @enderror " value="{{ $admin->nama }}" id="exampleInputEmail1" placeholder="Name Admin " name="nama">
          @error('nama')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
          <label for="" class="form-label">Email</label>
          <input type="email" class="form-control @error('email') is-invalid @enderror " value="{{ $admin->email }}" id="exampleInputEmail1" placeholder="Email admin" name="email">
          @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
         <div class="form-group col-md-4">

            <label>Level Admin</label>

            <select class="custom-select rounded-0"  name="id_level" id="exampleSelectRounded0" >

                @foreach ($level as $lv )

                    @if($admin->id_level ==  $lv->id)

                    <option value="{{  $lv->id }}" selected>

                        {{  $lv -> level}}

                    </option>

                    @else

                    <option value="{{  $lv->id }}" >

                        {{  $lv -> level}}

                    </option>

                    @endif

                @endforeach

            </select>

        </div>
      </div>

      <!-- /.card-body -->

      <div class="card-footer">
        <button type="submit" class="btn btn-primary">Save Data</button>
        <div class="btn reset-password">Reset Password</div>
      </div>

    </form>

  </div>

    <div class="pop-reset-password">
        <div class="position-card">
            <div class="card-form-reset">
                <div class="header-card">
                    <p class="header">Update Password</p>
                    <p class="close-card">X</p>
                </div>
                <form action="{{ route('ResetPassword') }}" method="post">
                     @csrf
                    <div class="form-group">
                        <label for="" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror "  id="exampleInputEmail1" placeholder="Registered Email" name="email">
                        @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                    </div>
                    <div class="form-group">
                        <label for="" class="form-label">New Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror "  id="exampleInputEmail1" placeholder="New Password" name="password">
                        @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                    </div>
                    <div class="btn-action">
                        <button type="submit" class="update-pass">Update New Password</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@stop
@section('script')
<script>
    $(()=>{
        $('.pop-reset-password').hide();

        $('.reset-password').on('click', function(){
             $('.pop-reset-password').show();
        })
        $('.close-card').on('click', function(){
             $('.pop-reset-password').hide();
        })
    })
</script>
@stop


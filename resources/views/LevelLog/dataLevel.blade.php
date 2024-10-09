@extends('layout.master')
@section('content')

<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Level Admin </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/Dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active"> Level Admin </li>
          </ol>
        </div>
      </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Level Admin</h3>
                <div class="d-flex flex-row-reverse">
                    <a href="#" type="button" class="btn btn-success mb-2 tambah-level">
                        Add Data
                    </a>
                </div>
              </div>
              <!-- /.card-header -->

              <div class="card-body" style="overflow: overlay;">
                <table class="table table-bordered" id="table1">
                  <thead>

                    <tr>
                      <th style="width: 10px">NO</th>
                      <th>Level</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $no=1; @endphp
                    @foreach ( $levelLog as $dt )
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>
                            {{ $dt->level }}
                        </td>
                        <td>
                            <div class="flex-lg-wrap">
                                <a href="#" type="button" data-id ="{{ $dt->id }}" data={{ $dt->level }} class="btn btn-block btn-warning mb-2 edit-level">
                                    Edit
                                </a>
                                 <form action="{{ route('DeteletLevel', encrypt($dt->id) ) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button  class="btn btn-block btn-danger">
                                            Delete
                                        </button>
                                </form>

                            </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
              </div>
            </div>
            <!-- /.card -->
          </div>
      </div>
    </div>
</section>
 <div class="pop-reset-password">
        <div class="position-card">
            <div class="card-form-reset">
                <div class="header-card">
                    <p class="header"></p>
                    <p class="close-card">X</p>
                </div>
                <form action="" method="post" class="form-level">
                     @csrf
                    <div class="form-group">
                        <label for="" class="form-label">Level</label>
                        <input type="text" class="form-control level @error('level') is-invalid @enderror "  id="exampleInputEmail1" placeholder="Level" name="level">
                        @error('level')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                    </div>

                    <div class="btn-action">
                        <button type="submit" class="update-pass">Save</button>
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

        $('.tambah-level').on('click', function(){
            $('.pop-reset-password').show();
            $('.pop-reset-password .header').text('Create  New Level ')
            var $form = $('.form-level');
            $form.attr('action', "{{ route('createLevel') }}");
            $form.find('input.level').attr('value', '');

        });
        $('.close-card').on('click', function(){
             $('.pop-reset-password').hide();
        });

        $('.edit-level').on('click', function(){
            $('.pop-reset-password').show();
            $('.pop-reset-password .header').text('Update Level ')
            var idx = $(this).attr('data-id');
            var data = $(this).attr('data');
            var $form = $('.form-level');
            $form.attr('action', "{{ route('UpdateLevel', '') }}"+'/'+idx);
            $form.find('input.level').attr('value', data);
        });
    });
</script>
@stop


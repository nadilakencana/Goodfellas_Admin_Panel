@extends('layout.master')
@section('content')

<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Data User </h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/Dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Data User </li>
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
                <h3 class="card-title">Data User</h3>
              </div>
              <!-- /.card-header -->

              <div class="card-body" style="overflow: overlay;">
                <table class="table table-bordered" id="table1">
                  <thead>

                    <tr>
                      <th style="width: 10px">NO</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>No Handphone</th>
                      {{--  <th>Action</th>  --}}
                    </tr>
                  </thead>
                  <tbody>
                    @php $no=1; @endphp
                    @foreach ( $user as $dt )
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>
                            {{ $dt->nama }}
                        </td>
                        <td>
                            {{ $dt->email }}
                        </td>
                        <td>
                            @if($dt->no_hp == '')
                                -
                            @endif
                            {{ $dt->no_hp }}
                        </td>

                        {{--  <td>
                            <div class="flex-lg-wrap">
                                <a href="{{route('edit-data-tax', encrypt($taxes->id))}}" type="button" class="btn btn-block btn-warning mb-2">
                                    Edit
                                </a>
                                <form action="{{route('delete-data-tax',encrypt($taxes->id))}}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button  class="btn btn-block btn-danger">
                                            Delete
                                        </button>
                                </form>

                            </div>
                        </td>  --}}
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

@stop

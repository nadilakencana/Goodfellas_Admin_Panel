@extends('layout.master')
@section('content')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Data Vocher Gift</h1>
        </div>

        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/Dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Data Vocher Gift</li>
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
                <h3 class="card-title">Data Vocher Gift</h3>
                <div class="d-flex flex-row-reverse">
                    <a href="{{route('form-create-vocher')}}" type="button" class="btn btn-success mb-2">
                        Add Data
                    </a>
                </div>
              </div>
              <div class="card-body" style="overflow: overlay;">
                <table class="table table-bordered" id="table1">
                  <thead>
                    <tr>
                      <th style="width: 10px">NO</th>
                      <th>Vocher Name</th>
                      <th>Point</th>
                      <th>Detail</th>
                      <th>Action</th>
                    </tr>
                  </thead>

                  <tbody>
                    @php $no=1; @endphp
                    @foreach ($vocher as $data )
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>
                                <div class="menu">
                                    <p class="nama-menu">
                                        {{ $data->nama_vocher }}
                                    </p>
                                    <div class="image">
                                        <img src="{{ $data->image }}" alt="" srcset="">
                                    </div>
                                </div>
                            </td>
                            <td>{{ $data->point_reward }}</td>
                            <td>{{ $data->detail }}</td>
                            <td>
                                <div class="text-center">
                                    <a href="{{ route('edit-data-vocher', encrypt($data->id)) }}" type="button" class="btn btn-block btn-warning mb-2">
                                        Edit
                                    </a>
                                    <form action="{{ route('hapus-vocher', encrypt($data->id)) }}" method="POST">
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
            </div>
          </div>
      </div>
    </div>
</section>
@stop

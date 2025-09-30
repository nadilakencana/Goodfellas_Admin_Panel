@extends('layout.master')
@section('content')

<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Menu List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/Dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Menu List</li>
          </ol>
        </div>
      </div>
    </div>
</section>
  @if (session()->has('error'))

      <div class="alert alert-danger alert-dismissible fade show" role="alert">

        {{ session('error') }}

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

      </div>

  @endif
  @if (session()->has('Success'))

      <div class="alert alert-success alert-dismissible fade show" role="alert">

        {{ session('Success') }}

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

      </div>

  @endif
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Menu List</h3>
                <div class="d-flex flex-row-reverse">
                    <a href="{{route('create.menu')}}" type="button" class="btn btn-success mb-2">
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
                      <th>Menu Name</th>
                     
                      <th>Category</th>
                      <th>Sub Category</th>
                      <th>Bahan Baku</th>
                      <th>Stok</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $no=1; @endphp
                    @foreach ($menu as $mn )
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>
                            <div class="menu">
                                <p class="nama-menu">
                                    {{ $mn->nama_menu }}
                                </p>
                                <p class="nama-menu">
                                   Rp. {{$mn->harga}}
                                </p>
                            </div>
                        </td>
                        <td>{{  $mn->kategori->kategori_nama}}</td>
                        <td>@if(isset($mn->subKategori->sub_kategori)){{ $mn->subKategori->sub_kategori }}@else -  @endif</td>
                        <td>@if(!empty($mn->id_bahan_baku)){{ $mn->bahanBaku->nama_bahan }}@else - @endif</td>
                        <td>@if(!empty($mn->id_bahan_baku)) {{ $mn->bahanBaku->stok_porsi }} @else {{$mn->stok}} @endif</td>
                        <td>
                            <div class="text-center">
                                <a href="{{route('edit.menu', encrypt($mn->id))}}" type="button" class="btn btn-block btn-warning mb-2">
                                    Edit
                                </a>
                                <form action="{{route('delete',encrypt($mn->id))}}" method="POST">
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

@stop




@extends('layout.master')

@section('content')

<section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Bahan Baku</h1>

        </div>

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <li class="breadcrumb-item"><a href="#">Home</a></li>

            <li class="breadcrumb-item active">Bahan Baku</li>

          </ol>

        </div>

      </div>

    </div>

</section>

<section class="content">

    <div class="container-fluid">

      <div class="row">

        <div class="col-md-12">

            <div class="card">

              <div class="card-header">

                <h3 class="card-title">Bahan Baku</h3>



                <div class="d-flex flex-row-reverse">

                    <a href="{{route('create.bahanBaku')}}" type="button" class="btn btn-success mb-2">

                        Add Data

                      </a>

                </div>

              </div>



              <!-- /.card-header -->

              <div class="card-body">

                <table class="table table-bordered">

                  <thead>

                    <tr>

                      <th style="width: 10px">NO</th>

                      <th>Bahan Baku</th>

                      <th>Stok</th>
                      <th>Stok Minimal</th>

                    </tr>

                  </thead>

                  <tbody>

                    @php $no=1; @endphp

                    @foreach ($bahan_baku as $bahan )

                    <tr>

                        <td>{{ $no++ }}</td>

                        <td>{{ $bahan->nama_bahan }}</td>
                        <td>{{ $bahan->stok_porsi }}</td>
                        <td>{{ $bahan->stok_minimum }}</td>

                        <td>
                            <div class="text-center">
                                <a href="{{route('edit.bahanBaku', encrypt($bahan->id))}}" type="button" class="btn btn-block btn-warning mb-2">
                                    Edit
                                </a>

                                <form action="{{route('delete.bahanBaku',encrypt($bahan->id))}}" method="POST">
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




@extends('layout.master')

@section('content')

<section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Menu Category</h1>

        </div>

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <li class="breadcrumb-item"><a href="#">Home</a></li>

            <li class="breadcrumb-item active">Menu Category</li>

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

                <h3 class="card-title">Menu Category</h3>



                <div class="d-flex flex-row-reverse">

                    <a href="{{route('create.kat')}}" type="button" class="btn btn-success mb-2">

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

                      <th>Category</th>

                      <th>Action</th>

                    </tr>

                  </thead>

                  <tbody>

                    @php $no=1; @endphp

                    @foreach ($kategori as $kat )

                    <tr>

                        <td>{{ $no++ }}</td>

                        <td>{{ $kat->kategori_nama }}</td>

                        <td>

                            <div class="text-center">

                                <a href="{{route('edit.kat', encrypt($kat->id))}}" type="button" class="btn btn-block btn-warning mb-2">

                                    Edit

                                </a>



                                    <form action="{{route('delete.kat',encrypt($kat->id))}}" method="POST">

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




@extends('layout.master')

@section('content')


<section class="content-header">

    <div class="container-fluid">

      <div class="row mb-2">

        <div class="col-sm-6">

          <h1>Data Room Vip</h1>

        </div>

        <div class="col-sm-6">

          <ol class="breadcrumb float-sm-right">

            <li class="breadcrumb-item"><a href="{{ url('/Dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active">Data Room Vip</li>

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

                <h3 class="card-title">Data Room Vip</h3>



                <div class="d-flex flex-row-reverse">

                    <a href="{{route('create.room')}}" type="button" class="btn btn-success mb-2">

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

                      <th>Type Room</th>

                      <th>Deskripsi</th>

                      <th>Image</th>

                      <th>Minimum Dp</th>

                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $no=1; @endphp
                    @foreach ($room as $Rm )
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>
                            <div class="menu">
                                <p class="nama-menu">
                                    {{ $Rm->type_room }}
                                </p>
                            </div>

                        </td>

                        <td>{{ $Rm->deskripsi}}</td>

                        <td>
                            <div class="image">

                                <img src="{{$Rm->image }}" alt="" srcset="">

                            </div>
                        </td>

                        <td>{{number_format($Rm->min_dp, 0,",",".") }}</td>



                        <td>

                            <div class="text-center">

                                <a href="{{route('edit.room', encrypt($Rm->id))}}" type="button" class="btn btn-block btn-warning mb-2">

                                    Edit

                                </a>



                                    <form action="{{route('delete.room',encrypt($Rm->id))}}" method="POST">

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




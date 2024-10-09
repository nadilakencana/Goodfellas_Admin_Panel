@extends('layout.master')
@section('content')

<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Discount</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/Dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Discount</li>
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
                <h3 class="card-title">Discount</h3>
                <div class="d-flex flex-row-reverse">
                    <a href="{{route('Create-data-discount')}}" type="button" class="btn btn-success mb-2">
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
                      <th>Discount Name</th>
                      <th>Discount Rate</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $no=1; @endphp
                    @foreach ( $Dis as $dis )
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>
                            {{ $dis->nama }}
                        </td>
                        <td>
                            {{ $dis->rate_dis }} &#37;
                        </td>
                        <td>
                            <div class="flex-lg-wrap">
                                <a href="{{route('edit-type-discount', encrypt($dis->id))}}" type="button" class="btn btn-block btn-warning mb-2">
                                    Edit
                                </a>
                                <form action="{{route('delete-data-discount',encrypt($dis->id))}}" method="POST">
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

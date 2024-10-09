@extends('layout.master')
@section('content')

<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Type Payment</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/Dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Type Payment</li>
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
                <h3 class="card-title">Type Payment</h3>
                <div class="d-flex flex-row-reverse">
                    <a href="{{route('Create-data-payment')}}" type="button" class="btn btn-success mb-2">
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
                      <th>Type Name</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $no=1; @endphp
                    @foreach (  $payment as $type )
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>
                            {{ $type->nama}}
                        </td>
                        <td>
                            <div class="flex-lg-wrap">
                                <a href="{{route('edit-type-payment', encrypt($type->id))}}" type="button" class="btn btn-block btn-warning mb-2">
                                    Edit
                                </a>
                                <form action="{{route('delete-data-payment',encrypt($type->id))}}" method="POST">
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

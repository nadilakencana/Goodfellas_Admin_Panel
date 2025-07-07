@extends('layout.master')
@section('content')

<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Data Table Cafe Goodfellas</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/Dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Table List</li>
          </ol>
        </div>
      </div>
    </div>
</section>
  @if (session()->has('fail'))

      <div class="alert alert-danger alert-dismissible fade show" role="alert">

        {{ session('fail') }}

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
                <h3 class="card-title">Table List</h3>
                <div class="d-flex flex-row-reverse">
                    <div class="btn btn-success mb-2 cusrdor-pointer create-new">
                        Add Data
                    </div>
                </div>
              </div>
              <!-- /.card-header -->

              <div class="card-body" style="overflow: overlay;">
                <table class="table table-bordered" id="table1">
                  <thead>

                    <tr>
                      <th style="width: 10px">NO</th>
                      <th>Table</th>
                      <th>QR</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $no=1; @endphp
                    @foreach ($dataTable as $table )
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td> {{ $table->meja }}</td>
                        <td>  <p class="card-text text-center"><?php echo  DNS2D::getBarcodeHTML(
                                    $table->link, 'QRCODE', 6,6); ?>
                                </p>
                        </td>
                        <td>
                            <div class="text-center">
                                <div xid="{{encrypt($table->id)}}" type="button" class="btn btn-block btn-warning mb-2 open-qr">
                                    Download QR
                                </div>
                                <form action="" method="POST">
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

<div class="pop-up-01" style="display: none">
  <div class="position-object">
    <div class="card-01">
      <div class="header-card d-flex justify-content-between">
        <div class="txt-tittle" style="font: 25px">Create Data Table</div>
        <div class="close-card">X</div>
      </div>
      <div class="body-card-01">

      </div>
    </div>
  </div>
</div>
@stop
@section('script')
<script src="{{asset('asset/assets/js/QRTbale.js')}}"></script>

@stop




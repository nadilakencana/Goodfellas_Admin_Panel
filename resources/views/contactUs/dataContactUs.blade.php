@extends('layout.master')
@section('content')

<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Inbox Contact Us</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/Dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Inbox Contact Us</li>
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
                <h3 class="card-title">Inbox Contact Us</h3>
                {{--  <div class="d-flex flex-row-reverse">
                    <a href="{{route('create.menu')}}" type="button" class="btn btn-success mb-2">
                        Add Data
                    </a>
                </div>  --}}
              </div>
              <!-- /.card-header -->

              <div class="card-body" style="overflow: overlay;">
                <table class="table table-bordered" id="table1">
                  <thead>

                    <tr>
                      <th style="width: 10px">NO</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Phone</th>
                      <th>Message</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $no=1; @endphp
                    @foreach ($contact as $ct )
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>
                           {{ $ct->name }}
                        </td>
                        <td>{{  $ct->email}}</td>
                        <td>{{  $ct->phone}}</td>
                        <td>{{ $ct->message }}</td>
                       
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




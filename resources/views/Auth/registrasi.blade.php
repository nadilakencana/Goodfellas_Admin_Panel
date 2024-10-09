<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registrasi</title>
    <link rel="stylesheet" href="{{ asset('asset/tamplate/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/tamplate/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/tamplate/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/tamplate/plugins/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/assets/css/style.css') }}">
</head>
<body class="hold-transition login-page">

    <div class="login-box">
        <div class="login-logo">
         Goodfellas
        </div>
        <!-- /.login-logo -->
        <div class="card">
          <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <form action="{{ route('push.regist') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="input-group mb-3">
                  <input type="text" class="form-control @error('nama') is-invalid @enderror " placeholder="Full name" name="nama">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-user"></span>
                    </div>
                  </div>

                </div>
                @error('nama')
                <small class="text-danger">{{ $message }}</small>
                @enderror

                <div class="input-group mb-3">
                  <input type="email" class="form-control @error('email') is-invalid @enderror " placeholder="Email" name="email">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-envelope"></span>
                    </div>
                  </div>

                </div>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                <div class="input-group mb-3">
                  <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-lock"></span>
                    </div>
                  </div>

                </div>
                @error('password')
                <small class="text-danger">{{ $message }}</small>
                  @enderror
                
                <div class="row">

                  <div class="col">
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                  </div>
                  <!-- /.col -->
                </div>
              </form>


            {{--  <div class="social-auth-links text-center mb-3">
              <p>- OR -</p>
              <a href="#" class="btn btn-block btn-primary">
                <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
              </a>
              <a href="#" class="btn btn-block btn-danger">
                <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
              </a>
            </div>  --}}
            <!-- /.social-auth-links -->

            {{--  <p class="mb-1">
              <a href="forgot-password.html">I forgot my password</a>
            </p>
            <p class="mb-0">
              <a href="register.html" class="text-center">Register a new membership</a>
            </p>  --}}
          </div>
          <!-- /.login-card-body -->
        </div>
      </div>


    <script src="{{ asset('asset/tamplate/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/js/adminlte.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/js/pages/dashboard2.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/simple-datatables/simple-datatables.js') }}"></script>
    <script>
        // Simple Datatable
            let table1 = document.querySelector('#table1');
            let dataTable = new simpleDatatables.DataTable(table1);
    </script>

    @yield('script')
</body>
</html>


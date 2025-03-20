<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('asset/tamplate/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/tamplate/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/tamplate/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/tamplate/plugins/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="{{ asset('asset/daterangepicker-master/daterangepicker.css') }}">
    {{--  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">  --}}
    <link rel="stylesheet" href="{{ asset('asset/assets/css/custom.css') }}">

    <script src="{{ asset('asset/tamplate/plugins/jquery/jquery-3.7.1.min.js') }}"></script>

</head>
<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    {{-- <div class="pop-notification">
        <div class="position-object">
             <div class="card">
                <div class="text-pop">
                    <p class="text">tekan ok agar notifikasi suara berbunyi</p>
                </div>
                <div class="action-pop">
                    <button type="focus()" class="btn btn-primary confirm-notif">Ok</button>
                </div>
            </div>
        </div>
       
    </div> --}}
    <div class="wrapper">
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="{{ asset('asset/assets/image/LOGO PUTIH.png') }}" alt="AdminLTELogo" height="60" width="60">
        </div>
        @include('layout.navbar')
        @include('layout.sidebar')
        <div class="content-wrapper">
            @yield('content')
        </div>
        <div id="triggerElementId"></div>
    </div>
	{{-- <iframe class="frameHolder" src="{{route('notif')}}" allow="autoplay" style="display:none;"></iframe> --}}


    
    <script src="{{ asset('asset/tamplate/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/js/adminlte.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/chart.js/Chart.js') }}"></script>
    <script src="{{ asset('asset/tamplate/js/pages/dashboard2.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script src="{{ asset('asset/assets/js/custom_js.js') }}"></script>
    <script src="{{ asset('asset/tamplate/plugins/moment/moment.min.js') }}"></script>
    {{--  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>  --}}
    <script src="{{ asset('asset/daterangepicker-master/daterangepicker.min.js') }}"></script>
    {{--  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>  --}}
    <script src="{{ asset('asset/assets/js/luxon.min.js') }}"></script>
    {{--  <script src="https://cdn.jsdelivr.net/npm/luxon@3.4.4/build/global/luxon.min.js"></script>  --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>
        // Simple Datatable
            let table1 = document.querySelector('#table1');
            let dataTable = new simpleDatatables.DataTable(table1);

            $(function () {
                bsCustomFileInput.init();

              });
    </script>
     {{--  check connetion  --}}

   

    @yield('script')
</body>
</html>

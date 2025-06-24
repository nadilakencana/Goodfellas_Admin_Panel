<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Orders Menu</title>
    <script src="{{ asset('asset/tamplate/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/tamplate/css/bootstrap.min.css') }}">
    <script src="{{ asset('asset/tamplate/js/bootstrap.bundel.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/assets/css/styleCustomerPage.css') }}">

<body style="background-color: rgb(200, 199, 199)">
    <div class="container-fluid  content-wrapper">
        <div class="row">
            <div class="col-12 px-0 col-sm-8 col-md-4 mx-auto">
                
                @include('CustomerOrder.navbar')
                <div class="main-content bg-white p-0">
                    @yield('content_order')
                </div>
                @include('CustomerOrder.footer-nav')
               
            </div>
        </div>
    </div>
</body>
@yield('script-order')
</html>

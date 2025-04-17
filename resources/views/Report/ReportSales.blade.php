@extends('layout.master')
@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Report Sales</h1>
            </div>

            <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/Dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Report Sales</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="head-sub1">
            <div class="date-custom">
                <div class="d-flex justify-content-center align-items-center gap-5">
                    <label for="daterange" class="mx-3">Periode: </label>
                    <input type="text" name="daterange" class="form-control mx-3" />
                    <div class="filter mt-0">
                        <img src="{{ asset('asset/assets/image/icon/filter_icon.png') }}" alt="" srcset="">
                    </div>
                </div>
            </div>

            <div class="btn-export-data">
                Export >
                <div class="drop-down-menu">
                    <ul class="list-export">
                        <li class="list">
                            <div class="export" id="SalesSummary">
                                Sales Summary
                            </div>
                        </li>
                        <li class="list">
                            <div class="export" id="grossProfit">
                                Gross Profit
                            </div>
                        </li>
                        <li class="list">
                            <div class="export" id="paymentMethod">
                                Payment Methods
                            </div>
                        </li>
                        <li class="list">
                            <div class="export" id="salesType">
                                Sales Type
                            </div>
                        </li>
                        <li class="list">
                            <div class="export" id="ItmSales">
                                Item Sales
                            </div>
                        </li>
                        <li class="list">
                            <div class="export" id="Category">
                                Category Sales
                            </div>
                        </li>
                        <li class="list">
                            <div class="export" id="modifier">
                                Modifier Sales
                            </div>
                        </li>
                        <li class="list">
                            <div class="export" id="discount">
                                Discount
                            </div>
                        </li>
                        <li class="list">
                            <div class="export" id="taxes">
                                Taxes
                            </div>
                        </li>
                        <li class="list">
                            <div class="export" id="transactionDetail">
                                Detail Transaction
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="pannel-report">
                    <div class="tab-sidebar">
                        <div class="tab-sdr active" target-panel="panel1" order="1">Sales Summary </div>
                        <div class="tab-sdr" target-panel="panel2" order="2">Gross Profit </div>
                        <div class="tab-sdr" target-panel="panel3" order="3">Payment Methods </div>
                        <div class="tab-sdr" target-panel="panel4" order="4">Sales Type </div>
                        <div class="tab-sdr" target-panel="panel5" order="5">Item Sales </div>
                        <div class="tab-sdr" target-panel="panel9" order="9">Category Sales</div>
                        <div class="tab-sdr" target-panel="panel6" order="6">Modifier Sales </div>
                        <div class="tab-sdr" target-panel="panel7" order="7">Discount </div>
                        <div class="tab-sdr" target-panel="panel8" order="8">Taxes </div>

                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-panel-container">
                    {{-- sales Summary --}}
                    <div class="panel active" data-panel="panel1" panel-order="1">
                        <div class="card-body  p-0">
                            <div class="header-title">Sales Summary</div>
                            <div class="body-summary">
                                <div class="label">
                                    <div class="title-label">Gross Sales</div>
                                    {{-- {{ $totalGrandGrosSalesMenu }}
                                    {{ $totalGrandGrosSalesAdds }}
                                    {{ $grosSale }} --}}
                                    <div class="nominal-lebel">Rp. {{number_format( $allGrandSales, 0, ',','.')}}</div>
                                </div>
                                <div class="label">
                                    <div class="title-label">Discounts</div>
                                    <div class="nominal-lebel">(Rp. {{number_format( $allGrandDis, 0, ',','.')}})</div>
                                </div>
                                <div class="label">
                                    <div class="title-label">Refunds</div>
                                    <div class="nominal-lebel">(Rp. {{number_format( $allGrandRefund, 0, ',','.')}})
                                    </div>
                                </div>
                                <div class="line"></div>
                                <div class="label font-bold">
                                    <div class="title-label">Net Sales</div>
                                    <div class="nominal-lebel">Rp. {{number_format( $allGrandNet, 0, ',','.')}}</div>
                                </div>
                                <div class="label">
                                    <div class="title-label">Tax</div>
                                    <div class="nominal-lebel">Rp. {{number_format( $totalTax, 0, ',','.')}}</div>
                                </div>
                                <div class="line"></div>
                                <div class="label font-bold">
                                    <div class="title-label">Total Collected</div>
                                    <div class="nominal-lebel">Rp. {{number_format( $TotalGrand, 0, ',','.')}}</div>
                                </div>
                                <div class="line"></div>
                            </div>
                        </div>

                    </div>
                    {{-- Gross Profit --}}
                    <div class="panel " data-panel="panel2" panel-order="2">

                    </div>
                    {{-- Payment Method --}}
                    <div class="panel " data-panel="panel3" panel-order="3">

                    </div>
                    {{-- Sales Type --}}
                    <div class="panel " data-panel="panel4" panel-order="4">

                    </div>
                    {{-- Item Sales --}}
                    <div class="panel " data-panel="panel5" panel-order="5">

                    </div>
                    {{-- Modifier --}}
                    <div class="panel " data-panel="panel6" panel-order="6">

                    </div>
                    {{-- Discount --}}
                    <div class="panel " data-panel="panel7" panel-order="7">

                    </div>
                    {{-- Tax --}}
                    <div class="panel " data-panel="panel8" panel-order="8">

                    </div>
                    {{-- Category --}}
                    <div class="panel " data-panel="panel9" panel-order="9">

                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@stop
@section('script')
<script>
    $(()=>{
        @if(request()->has('daterange'))
            $('input[name="daterange"]').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD',
                separator: " / "
                },
                startDate: '{{$startDate}}',
                endDate: '{{$endDate}}'
            });
        @else
        var DateTime = luxon.DateTime;
        var dt = DateTime.now();
        var current = dt.toFormat('yyyy-MM-dd');
            $('.date_search #date').val(current);
            
            $('input[name="daterange"]').daterangepicker({
            locale: {
            format: 'YYYY-MM-DD',
            separator: " / "
            },
            startDate: '{{$startDate}}',
            endDate: '{{$endDate}}'
        });
        @endif
        $('.btn-export-data').on('click', function(){
            $(this).find('.drop-down-menu').slideToggle("fast");

        });

         $('.tab-sdr').on('click', function(e){
            var target = $(this).attr('target-panel'); // ambil target panel yang mau di aktifkan
            //semua tab navigation di nonaktifkan baru kemudian yang diklik di bedakan
            $('.tab-sdr').removeClass('active');
            $(this).addClass('active');

            // sembunyikan semua panel lalu yang sesuai dengan tab navigation baru dimunculkan
            $('.panel').hide();
            $(`.panel[data-panel="${target}"]`).show();
        });

        $('.tab-sdr[target-panel="panel1"]').on('click', function(){
           var $tgt = $(`.panel[data-panel='panel1']`);
           $tgt.find('.card-body').remove();
            getSalesSummary();
        });
        $('.tab-sdr[target-panel="panel2"]').on('click', function(){
           var $tgt = $(`.panel[data-panel='panel2']`);
           $tgt.find('.card-body').remove();
            getGrossProfit();
        });

        $('.tab-sdr[target-panel="panel3"]').on('click', function(){
           var $tgt = $(`.panel[data-panel='panel3']`);
           $tgt.find('.card-body').remove();
            getPayment();
        });

        $('.tab-sdr[target-panel="panel4"]').on('click', function(){
           var $tgt = $(`.panel[data-panel='panel4']`);
           $tgt.find('.card-body').remove();
            getSales();
        })
        $('.tab-sdr[target-panel="panel5"]').on('click', function(){
           var $tgt = $(`.panel[data-panel='panel5']`);
           $tgt.find('.card-body').remove();
            getItemSales();
        })

        $('.tab-sdr[target-panel="panel6"]').on('click', function(){
           var $tgt = $(`.panel[data-panel='panel6']`);
           $tgt.find('.card-body').remove();
            getModifier();
        })
        $('.tab-sdr[target-panel="panel7"]').on('click', function(){
           var $tgt = $(`.panel[data-panel='panel7']`);
           $tgt.find('.card-body').remove();
            getDiscount();
        })
        $('.tab-sdr[target-panel="panel8"]').on('click', function(){
           var $tgt = $(`.panel[data-panel='panel8']`);
           $tgt.find('.card-body').remove();
            getTaxes();
        })

        $('.tab-sdr[target-panel="panel9"]').on('click', function(){
           var $tgt = $(`.panel[data-panel='panel9']`);
           $tgt.find('.card-body').remove();
            getCategory();
        });

        $('#SalesSummary').on('click', function(){
            exportReport($(this), 1);
        })

        $('#grossProfit').on('click', function(){
            exportReport($(this), 2);
        })
        $('#paymentMethod').on('click', function(){
             exportReport($(this), 3);
        })
        $('#salesType').on('click', function(){
            exportReport($(this), 4);
        })
        $('#ItmSales').on('click', function(){
             exportReport($(this), 5);
        })
        $('#Category').on('click', function(){
             exportReport($(this), 6);
        })
        $('#modifier').on('click', function(){
             exportReport($(this), 7);
        })
        $('#discount').on('click', function(){
            exportReport($(this), 8);
            //exportSalesSummary();
        })
        $('#taxes').on('click', function(){
             exportReport($(this), 9);
            //exportSalesSummary();
        })
        $('#transactionDetail').on('click', function(){
             exportReport($(this), 10);
            //exportSalesSummary();
        })

        $('.filter').click(function(e){
            e.preventDefault();
            filterReportDate();
        });

        function filterReportDate(){
            var daterange = $('input[name="daterange"]').val();
            var daterange = daterange.split(" / ");
            var startDate = daterange[0];
            var endDate = daterange[1];
            var url = "{{route('report')}}";
            url = url+'?startDate='+startDate+'&endDate='+endDate;
            
            window.location = url;
        }

        function exportReport($elm,condition){
            var daterange = $('input[name="daterange"]').val();
            var daterange = daterange.split(" / ");
            var startDate = daterange[0];
            var endDate = daterange[1];
           

            if(condition == 1){
                var url = "{{ route('salesSummary') }}";
                url = url+'?startDate='+startDate+'&endDate='+endDate;
                
            }else if(condition == 2){
                var url = "{{ route('grossprofit') }}";
                url = url+'?startDate='+startDate+'&endDate='+endDate;
               
            }else if(condition == 3){
                var url = "{{ route('PaymentMethod') }}";
                url = url+'?startDate='+startDate+'&endDate='+endDate;
                
            }else if(condition == 4){
                var url = "{{ route('SalesType') }}";
                url = url+'?startDate='+startDate+'&endDate='+endDate;
               
            }else if(condition == 5){
                var url = "{{ route('ItemSales') }}";
                url = url+'?startDate='+startDate+'&endDate='+endDate;
                
            }else if(condition == 6){
               
                var url = "{{ route('category') }}";
                url = url+'?startDate='+startDate+'&endDate='+endDate;
            }else if(condition == 7){
               
                var url = "{{ route('modifier') }}";
                url = url+'?startDate='+startDate+'&endDate='+endDate;
            }else if(condition == 8){
                var url =  "{{ route('discount') }}";
                url = url+'?startDate='+startDate+'&endDate='+endDate;
                
            }else if(condition == 9){
               
                var url =  "{{ route('taxes') }}";
                url = url+'?startDate='+startDate+'&endDate='+endDate;
            }else{
                var url = "{{ route('detail-transaction') }}";
                url = url + '?startDate='+startDate+'&endDate='+endDate;
            }

             var dt = {
                _token : "{{ csrf_token() }}",
                startDate: startDate,
                endDate: endDate
            }
           
            //$.ajax({
            //    url: url,
            //    method: 'post', 
            //    data: dt,
            //    xhrFields: {
            //        responseType: 'blob'  
            //    },
            //   success: function(data, status, xhr) {
            //        if (data && data instanceof Blob) {
            //            var disposition = xhr.getResponseHeader('Content-Disposition');
            //            var matches = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(disposition);
            //            var filename = (matches != null && matches[1]) ? matches[1].replace(/['"]/g, '') : 'file.xlsx';
//
            //            // Membuat Blob untuk file Excel
            //            var blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            //            var link = document.createElement('a');
            //            var url = window.URL.createObjectURL(blob);
//
            //            link.href = url;
            //            link.download = filename;
            //            document.body.appendChild(link);
            //            link.click();
//
            //            // Membersihkan objek URL dan elemen link setelah selesai
            //            window.URL.revokeObjectURL(url);
            //            document.body.removeChild(link);
            //        } else {
            //            console.error('Unexpected response data format:', data);
            //        }
            //    },
            //    error: function(xhr, status, error) {
            //        console.error('Download error:', error);
            //        if (xhr.response && xhr.response instanceof Blob) {
            //            var reader = new FileReader();
            //            reader.onload = function() {
            //                console.error('Download error:', reader.result);
            //            };
            //            reader.readAsText(xhr.response); // Baca Blob sebagai teks
            //        } else {
            //            console.error('Unexpected response type or error:', xhr.responseText || error);
            //        }
            //    }
            //}).fail(function(xhr) {
            //    console.log('Request failed:', xhr.responseText);
            //});

            $.ajax({ 
                url: url,
                method: 'post', 
                data: dt,
                xhrFields: {
                    responseType: 'blob'  // tetap blob karena ekspor file
                },
                success: function(data, status, xhr) {
                    if (data && data instanceof Blob) {
                        var disposition = xhr.getResponseHeader('Content-Disposition');
                        var matches = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/.exec(disposition);
                        var filename = (matches != null && matches[1]) ? matches[1].replace(/['"]/g, '') : 'file.xlsx';

                        // Membuat Blob untuk file Excel
                        var blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                        var link = document.createElement('a');
                        var url = window.URL.createObjectURL(blob);

                        link.href = url;
                        link.download = filename;
                        document.body.appendChild(link);
                        link.click();

                        // Bersihkan
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(link);
                    } else {
                        console.error('Unexpected response data format:', data);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Status:', xhr.status);
                    console.error('Error:', error);

                    if (xhr.response && xhr.response instanceof Blob) {
                        // Baca isi blob (misal dump Laravel atau pesan error)
                        var reader = new FileReader();
                        reader.onload = function() {
                            try {
                                // Coba parse kalau bentuk JSON
                                var json = JSON.parse(reader.result);
                                console.error('Parsed error response:', json);
                            } catch (e) {
                                // Kalau bukan JSON, tampilkan sebagai teks biasa (misal hasil dump())
                                console.error('Raw error response:\n', reader.result);
                            }
                        };
                        reader.readAsText(xhr.response);
                    } else {
                        console.error('Unexpected error response:', xhr.responseText || error);
                    }
                }
            }).fail(function(xhr) {
                console.warn('Request failed with status:', xhr.status);
                console.warn('Response:', xhr.responseText);
            });

        }


        function getSalesSummary() {
            var daterange = $('input[name="daterange"]').val();
            var daterange = daterange.split(" / ");
            var startDate = daterange[0];
            var endDate = daterange[1];
            var url = "{{route('fileterSalesSummary')}}";
            url = url+'?startDate='+startDate+'&endDate='+endDate;

           
            var dt = {
                startDate: startDate,
                endDate: endDate
            }
           
            $.ajax({
                url: url,
                method: 'GET', // Letakkan 'method' di luar blok kondisi if-else
                data: dt,
                success: function(result) {

                    $(result).appendTo(`.panel[data-panel='panel1']`);
                },
                error: function(result) {
                    console.log(result);
                }
            });
        }

        function getGrossProfit() {
            var daterange = $('input[name="daterange"]').val();
            var daterange = daterange.split(" / ");
            var startDate = daterange[0];
            var endDate = daterange[1];
            var url = "{{ route('GrossProfit') }}";
            url = url+'?startDate='+startDate+'&endDate='+endDate;

           
            var dt = {
                startDate: startDate,
                endDate: endDate
            }
           
            $.ajax({
                url: url,
                method: 'GET', // Letakkan 'method' di luar blok kondisi if-else
                data: dt,
                success: function(result) {
                    $(result).appendTo(`.panel[data-panel='panel2']`);

                },
                error: function(result) {
                    console.log(result);
                }
            });
        }

        function getPayment(){
           var daterange = $('input[name="daterange"]').val();
            var daterange = daterange.split(" / ");
            var startDate = daterange[0];
            var endDate = daterange[1];
            var url = "{{ route('payment') }}";
            url = url+'?startDate='+startDate+'&endDate='+endDate;

           
            var dt = {
                startDate: startDate,
                endDate: endDate
            }
           
            $.ajax({
                url: url,
                method:'GET',
                data: dt,
                success: function(result){
                     $(result).appendTo(`.panel[data-panel='panel3']`);

                }

            }).fail(function(result){
                console.log(result);
            })
        }

        function getSales(){
            var daterange = $('input[name="daterange"]').val();
            var daterange = daterange.split(" / ");
            var startDate = daterange[0];
            var endDate = daterange[1];
            var url = "{{ route('SelesType') }}";
            url = url+'?startDate='+startDate+'&endDate='+endDate;

            var dt = {
                startDate: startDate,
                endDate: endDate
            }

            $.ajax({
                url: url,
                method:'GET',
                data: dt,
                success: function(result){
                     $(result).appendTo(`.panel[data-panel='panel4']`);

                }

            }).fail(function(result){
                console.log(result);
            })
        }

        function getItemSales(){
            var daterange = $('input[name="daterange"]').val();
            var daterange = daterange.split(" / ");
            var startDate = daterange[0];
            var endDate = daterange[1];
            var url = "{{ route('Item-Sales') }}";
            url = url+'?startDate='+startDate+'&endDate='+endDate;

            var dt = {
                startDate: startDate,
                endDate: endDate
            }

          
            $.ajax({
                url: url,
                method:'GET',
                data: dt,
                success: function(result){
                     $(result).appendTo(`.panel[data-panel='panel5']`);

                }

            }).fail(function(result){
                console.log(result);
            })
        }

        function getModifier(){
            var daterange = $('input[name="daterange"]').val();
            var daterange = daterange.split(" / ");
            var startDate = daterange[0];
            var endDate = daterange[1];
            var url = "{{ route('Modifier') }}";
            url = url+'?startDate='+startDate+'&endDate='+endDate;

            var dt = {
                startDate: startDate,
                endDate: endDate
            }

           
            $.ajax({
                url: url,
                method:'GET',
                data: dt,
                success: function(result){
                     $(result).appendTo(`.panel[data-panel='panel6']`);

                }

            }).fail(function(result){
                console.log(result);
            })
        }
        function getDiscount(){
            var daterange = $('input[name="daterange"]').val();
            var daterange = daterange.split(" / ");
            var startDate = daterange[0];
            var endDate = daterange[1];
            var url = "{{ route('Discount') }}";
            url = url+'?startDate='+startDate+'&endDate='+endDate;

            var dt = {
                startDate: startDate,
                endDate: endDate
            }

           
            $.ajax({
                url: url,
                method:'GET',
                data: dt,
                success: function(result){
                     $(result).appendTo(`.panel[data-panel='panel7']`);

                }

            }).fail(function(result){
                console.log(result);
            })
        }
        function getTaxes(){
            var daterange = $('input[name="daterange"]').val();
            var daterange = daterange.split(" / ");
            var startDate = daterange[0];
            var endDate = daterange[1];
            var url = "{{ route('Taxes') }}";
            url = url+'?startDate='+startDate+'&endDate='+endDate;

            var dt = {
                startDate: startDate,
                endDate: endDate
            }

            
            $.ajax({
                url: url,
                method:'GET',
                data: dt,
                success: function(result){
                     $(result).appendTo(`.panel[data-panel='panel8']`);

                }

            }).fail(function(result){
                console.log(result);
            })
        }

        function getCategory(){
            var daterange = $('input[name="daterange"]').val();
            var daterange = daterange.split(" / ");
            var startDate = daterange[0];
            var endDate = daterange[1];
            var url = "{{ route('Category') }}";
            url = url+'?startDate='+startDate+'&endDate='+endDate;

            var dt = {
                startDate: startDate,
                endDate: endDate
            }

            
            $.ajax({
                url: url,
                method:'GET',
                data: dt,
                success: function(result){
                     $(result).appendTo(`.panel[data-panel='panel9']`);

                }

            }).fail(function(result){
                console.log(result);
            })
        }


    });
</script>

@stop
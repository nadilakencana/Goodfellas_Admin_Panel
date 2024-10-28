<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Sift</title>
    <link rel="stylesheet" href="{{ asset('asset/tamplate/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/tamplate/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/tamplate/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/tamplate/plugins/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script type="text/javascript" src="{{ asset('asset/assets/js/custom_js.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <link rel="stylesheet" href="{{ asset('asset/assets/css/custom.css') }}">
    <style>
        .card-print {
            width: 243px;
            padding: 10px;
            font-size: 10px;
        }
    </style>
</head>

<body>

    <div class="card-print">
        <a class="btn-action mb-3">
            <div class="btn btn-success print" dt-id="{{  $sift->id }}">Print</div>
        </a>
        <div class="data-detial">
            <div class="header mt-3">
                <div class="dt-header d-flex justify-content-between">
                    <label for="">Name</label>
                    <p class="text-detail">{{ $sift->admin->nama }}</p>
                </div>
                <div class="dt-header d-flex justify-content-between">
                    <label for="">Starting Sift</label>
                    <p class="text-detail date" date="{{ $sift->start_time }}">{{ $sift->start_time }}-{{ date("H:i", strtotime($sift->created_at)) }}
                    </p>
                </div>
                <div class="dt-header d-flex justify-content-between">
                    <label for="">Ending Sift</label>
                    <p class="text-detail">@if(empty($sift->end_time)) - @else{{ $sift->end_time }} {{ date("H:i",
                        strtotime($sift->updated_at)) }} @endif</p>
                </div>
            </div>
            {{-- //sales --}}
            <div class="part-title">Sales</div>
            @foreach ($menu->sortBy('menu.nama_menu')->groupBy(function ($item) {
                return $item->menu->nama_menu . ($item->varian ? ' (' . $item->varian->nama . ')' : '');
            }) as $namaMenu => $items)
                <div class="itm-detail d-flex justify-content-between">
                    <label for="">{{ $namaMenu }}</label>
                    <p class="text-detail mb-0">{{ $items->sum('qty') }}</p>
                </div>
            @endforeach
            {{-- @foreach ($menu->sortBy('menu.nama_menu')->groupBy('menu.nama_menu') as $namaMenu => $items)
            <div class="itm-detail d-flex justify-content-between">
                <label for="">{{ $namaMenu }}</label>
                <p class="text-detail mb-0">{{ $items->sum('qty') }}</p>
            </div>
            @endforeach --}}

            <div class="itm-detail d-flex justify-content-between mb-2">
                <label for="">Sold Item</label>
                <p class="text-detail">{{ $total_itemSold }}</p>
            </div>

            @foreach ($menu_retur->sortBy('menu.nama_menu')->groupBy(function ($item) {
                return $item->menu->nama_menu . ($item->varian ? ' (' . $item->varian->nama . ')' : '');
            }) as $namaMenu => $items)
                <div class="itm-detail d-flex justify-content-between">
                    <label for="">{{ $namaMenu }}</label>
                    <p class="text-detail mb-0">{{ $items->sum('qty') }}</p>
                </div>
            @endforeach
            {{-- @foreach ($menu_retur->sortBy('menu.nama_menu')->groupBy('menu.nama_menu') as $namaMenu => $items)
            <div class="itm-detail d-flex justify-content-between">
                <label for="">{{ $namaMenu }}</label>
                <p class="text-detail mb-0">{{ $items->sum('qty') }}</p>
            </div>
            @endforeach --}}
            <div class="itm-detail d-flex justify-content-between">
                <label for="">Refund Item</label>
                <p class="text-detail">{{ $total_itmRefund }}</p>
            </div>
            {{-- //cash --}}
            <div class="part-title">Cash</div>
            @php
            $total_sift = 0;
            @endphp
            <div class="itm-detail d-flex justify-content-between">
                <label for="">Starting Cash</label>
                <p class="text-detail">Rp. @if(empty($modal->nominal))0 @else {{ number_format($modal->nominal,
                    0,',','.')
                    }} @endif</p>
            </div>
            <div class="itm-detail d-flex justify-content-between">
                <label for="">Cash Sales</label>
                <p class="text-detail">Rp.{{ number_format($cashtype, 0,',','.') }}</p>
            </div>
            <div class="itm-detail d-flex justify-content-between">
                <label for="">Cash Refund</label>
                <p class="text-detail">Rp.{{ number_format($grendRefundCash, 0,',','.') }}</p>
            </div>
            <div class="itm-detail dropdown ">

                <div href="" class="d-flex justify-content-between">
                    <label for="">Total Expence</label>
                    <div class="arow d-flex align-items-center">
                        <p class="text-detail mx-3 mb-0">(Rp. {{ number_format( $total_pengeluaran, 0,'.','.') }})
                        </p>
                    </div>
                </div>
                <div class="sub-menu" id="sub-item">
                    @foreach ($kas_out as $kas )
                    <div class="itm-detail d-flex justify-content-between">
                        <div>{{ $kas->deskripsi }}</div>
                        <p class="text-detail">(Rp. {{ number_format($kas->nominal, 0,'.','.') }})</p>
                    </div>

                    @endforeach

                </div>
            </div>
            <div class="itm-detail dropdown">

                <div href="" class=" d-flex justify-content-between">
                    <label for="">Total Income</label>
                    <div class="arow d-flex align-items-center">
                        <p class="text-detail mx-3 mb-0">(Rp. {{ number_format($total_pemasukan, 0,'.','.') }})</p>
                    </div>
                </div>
                <div class="sub-menu" id="sub-item">

                    @foreach ($kas_in as $kas )
                    <div class="itm-detail d-flex justify-content-between">
                        <div>{{ $kas->deskripsi }}</div>
                        <p class="text-detail">(Rp. {{ number_format($kas->nominal, 0,'.','.') }})</p>
                    </div>
                    @endforeach


                </div>
            </div>
            @php
            $total_sift = ($modal->nominal + $cashtype + $total_pemasukan) -$total_pengeluaran - $grendRefundCash;
            @endphp
            <div class="itm-detail d-flex justify-content-between">
                <label for="">Expected Ending Cash</label>
                <p class="text-detail">Rp. {{ number_format($total_sift, 0,',','.') }}</p>
            </div>
            <div class="itm-detail d-flex justify-content-between">
                <label for="">Actual Ending Cash</label>
                <p class="text-detail">Rp.@if(empty($endingSift->nominal) ) 0 @else {{
                    number_format($endingSift->nominal,
                    0,',','.') }} @endif</p>
            </div>

            {{-- //cash --}}

            <div class="part-title">Cash</div>
            <div class="itm-detail d-flex justify-content-between">
                <label for="">Cash Sales</label>
                <p class="text-detail">Rp. {{ number_format($cashtype, 0,',','.') }}</p>
            </div>
            <div class="itm-detail d-flex justify-content-between">
                <label for="">Cash Refund</label>
                <p class="text-detail">(Rp.{{ number_format($grendRefundCash, 0,',','.') }})</p>
            </div>
            @php
            $total_cash = 0;
            $total_cash = $cashtype - $grendRefundCash;
            @endphp
            <div class="itm-detail d-flex justify-content-between">
                <label for="">Expected Cash Payment</label>
                <p class="text-detail">Rp. {{ number_format($total_cash, 0,',','.') }}</p>
            </div>

            {{-- //online --}}

            <div class="part-title">Online Delivery</div>
            <div class="itm-detail d-flex justify-content-between">
                <label for="">Grab</label>
                <p class="text-detail">Rp. {{ number_format($grab, 0,',','.') }}</p>
            </div>
            {{-- <div class="itm-detail d-flex justify-content-between">
                <label for="">Gojek</label>
                <p class="text-detail">Name</p>
            </div> --}}
            @php
            $total_online = 0;
            $total_online = $grab;
            @endphp
            <div class="itm-detail d-flex justify-content-between">
                <label for="">Expected Online Delivery</label>
                <p class="text-detail">Rp. {{ number_format($grab, 0,',','.') }}</p>
            </div>
            {{-- //EDC --}}
            <div class="part-title">EDC</div>
            <div class="itm-detail d-flex justify-content-between">
                <label for="">BCA</label>
                <p class="text-detail">Rp. {{ number_format($BCA, 0,',','.') }}</p>
            </div>
            <div class="itm-detail d-flex justify-content-between">
                <label for="">Mandiri</label>
                <p class="text-detail">Rp. {{ number_format($mandiri, 0,',','.') }}</p>
            </div>
            <div class="itm-detail d-flex justify-content-between">
                <label for="">BRI</label>
                <p class="text-detail">Rp. {{ number_format($BRI, 0,',','.') }}</p>
            </div>
            <div class="itm-detail d-flex justify-content-between">
                <label for="">EDC Refund</label>
                <p class="text-detail">(Rp. {{ number_format($grendRefunEDC, 0,',','.') }})</p>
            </div>
            @php
            $total_EDC = 0;
            $total_EDC =( $BCA + $mandiri + $BRI) - $grendRefunEDC;
            @endphp
            <div class="itm-detail d-flex justify-content-between">
                <label for="">Expected EDC Payment</label>
                <p class="text-detail">Rp. {{ number_format($total_EDC, 0,',','.') }}</p>
            </div>
            {{-- //Other --}}
            <div class="part-title">Other</div>
            <div class="itm-detail d-flex justify-content-between">
                <label for="">OVO</label>
                <p class="text-detail">Rp. {{ number_format($ovo, 0,',','.') }}</p>
            </div>
            <div class="itm-detail d-flex justify-content-between">
                <label for="">Bank Transfer</label>
                <p class="text-detail">Rp. {{ number_format($bankTF, 0,',','.') }}</p>
            </div>
            <div class="itm-detail d-flex justify-content-between">
                <label for="">Refund Other</label>
                <p class="text-detail">Rp. {{ number_format($grendOther, 0,',','.') }}</p>
            </div>

            @php
            $total_other_payment = 0;
            $total_other_payment = ($ovo + $bankTF) - $grendOther;
            @endphp
            <div class="itm-detail d-flex justify-content-between">
                <label for="">Expected Other Payment</label>
                <p class="text-detail">Rp. {{ number_format($total_other_payment, 0,',','.') }}</p>
            </div>

            {{-- //Total --}}

            @php
            $total_expected = 0;
            $total_actual = 0;
            $defferent = 0;
            $total_expected = $total_sift  + $total_online + $total_EDC + $total_other_payment;
            $total_actual = $total_expected;
            $defferent = $total_expected - $total_actual;
            @endphp
            <div class="part-title">Total</div>
            <div class="itm-detail d-flex justify-content-between">
                <label for="">Total Expected</label>
                <p class="text-detail">Rp. {{ number_format($total_expected, 0,',','.') }}</p>
            </div>
            <div class="itm-detail d-flex justify-content-between">
                <label for="">Total Actual</label>
                <p class="text-detail">Rp. {{ number_format($total_actual, 0,',','.') }}</p>
            </div>
            <div class="itm-detail d-flex justify-content-between">
                <label for="">Difference</label>
                <p class="text-detail">Rp. {{ number_format($defferent, 0,',','.') }}</p>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://markswindoll.github.io/js/FileSaver.js"></script>
    <script>
        $(()=>{
            $('.print').on('click', function(){
                var idx = $(this).attr('dt-id');
                PrintReport(idx);
            });
        
        function PrintReport(id){
            var URL = "{{ route('print.shift.thermal', '') }}" + '/' + id;
            $.ajax({
                url: URL,
                method: 'GET',
                success: function(data){
                    console.log(data.file_sift);
                    alert(data.message);

                    var name = data.file_sift;

                    //GetPrint('sift', name)
                    //window.location.reload();

                }
            }).fail(function(data){
                    console.log('error', data);
            });
        }

        function GetPrint(type, FileName){

            var URL = 'http://192.168.88.22:3377/print-file?type='+type+'&filename='+FileName;

            $.get(URL, function(result){
                console.log(result);
            }).fail(function(result){
                console.log(result);
            })
        }
     });
    </script>
</body>

</html>
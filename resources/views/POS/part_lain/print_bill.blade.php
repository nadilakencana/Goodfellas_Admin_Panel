<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PrintBill</title>
    <script src="{{ asset('asset/tamplate/plugins/jquery/jquery.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="{{ asset('asset/assets/css/custom.css') }}">


    <style>
        * {
            font-family: 'Inter', sans-serif;
            font-family: 'Open Sans', sans-serif;
        }

        .layoute-bill {
            width: 260px;
           /* border: 1px solid;*/
            padding: 10px;

        }

        .header-bill {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            justify-content: center;
            font-size: 13px;
            /* font-weight: bolder; */
        }

        img.logo {
            width: 37%;
        }

        .addres-store {
            text-align: center;
        }

        .body-bill {
            display: flex;
            flex-direction: column;
            margin-top: 10px;
            /* font-weight: bolder; */
        }

        .head-bill {
            display: flex;
            flex-direction: column;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .order-data {
            display: flex;
            justify-content: space-between;
        }

        .part-itm {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .detail-itm p {
            margin: 0;
        }

        .detail-itm {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
            color: rgb(2, 2, 2);
            font-size: 13px;
            /* font-weight: bolder; */
        }
        .footer-bill {
            /* font-weight: bolder; */
        }
        .total {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px
        }

        .total.taxes {
            font-size: 12px;
            color: rgb(14, 14, 14);
            /* font-weight: bolder; */
        }

        .txt-total.service-change {
            display: flex;
            font-size: 12px;
            gap: 3px;
        }

        .txt-price-total.total {
            font-weight: 600;
        }

        .itms-order {
            display: flex;
            justify-content: space-between;
        }

        p.presentage {
            margin: 0;
        }

        .cash {
            display: flex;
            justify-content: space-between;
        }

        .name-itms {
            width: 100px;
        }

        .btn-action {
            display: flex;
            gap: 10px;
        }

        .button-save-pdf {
            cursor: pointer;
            background: #b32424;
            padding: 10px;
            width: 112px;
            margin: 20px;
            text-align: center;
            color: white;
            border-radius: 10px;
        }

        .button-kitchen, .button-tiket {
            cursor: pointer;
            background: #2471b3;
            padding: 10px;
            width: 112px;
            margin: 20px;
            text-align: center;
            color: white;
            border-radius: 10px;
        }
        .button-kitchen{
            background: #2471b3;
        }
        .button-tiket{
            background: #24b370;
        }
    </style>
</head>

<body>

    <div class="btn-action">
        <div class="button-save-pdf bill" xid="{{ $order->id }}" id="cetakButton">Print Bill</div>
        <div class="button-tiket" xid="{{ $order->id }}">Bill Tiket</div>
        <div class="button-kitchen" xid="{{ $order->id }}">Bill Kitchen</div>
    </div>

    <div class="layoute-bill" id="bill">
        <div class="section-bill">
            <div class="content-bill">
                <div class="detail-bill">
                    <div class="header-bill">
                        <img src="{{ asset('asset/assets/image/LOGO BLACK.png') }}" alt="" class="logo">
                        <div class="name-store">Goodfellas Coffe Bintaro</div>
                        <div class="addres-store">Kebayoran Arcade 5 Blok F5 No. 19, Bintaro Sektor 7, Kota Tangerang
                            Selatan, Banten, 15224</div>
                        <div class="phone-store"> +62 85847575713</div>
                    </div>
                    <div class="body-bill">
                        <div class="head-bill">
                            <div class="order-data">
                                <div class="txt-order-dt">{{ $order->created_at }}</div>
                                <div class="txt-order-dt"></div>
                            </div>
                            <div class="order-data">
                                <div class="txt-order-dt">Receipt Number</div>
                                <div class="txt-order-dt kode">{{ $order->kode_pemesanan }}</div>
                            </div>
                            @if($order->id_admin == '')
                            <div class="order-data">
                                <div class="txt-order-dt">Collected By</div>
                                <div class="txt-order-dt">@if($order->id_admin == null) - @else{{ $order->admin->nama
                                    }}@endif</div>
                            </div>
                            @endif
                            <div class="order-data">
                                <div class="txt-order-dt">Number Tabel</div>
                                <div class="txt-order-dt">{{ $order->no_meja }}</div>
                            </div>
                        </div>
                        @php
                        $total_dis = 0;
                        @endphp
                        <div class="detail-order">
                            @foreach ($detail as $k => $cart )
                            <div class="part-itm">
                                @php
                                    $totalHarga = 0;
                                    $discountTotal = 0;
                                    $total_sub= 0;
                                    $total = 0;
    
                                    $discountTotal = $totalDis ;
                                    $float_dis = $discountTotal / 100;
                                    $totalHarga = $cart['qty'] * $cart['harga'] ;
                                    $subtotalDisCount = $totalHarga * $float_dis;
                                    $total = $totalHarga - $subtotalDisCount;
                                    $total_sub += $total;
                                @endphp
                                <div class="itms-order">
                                    <div class="name-itms">{{ $cart->menu->nama_menu }}</div>
                                    <div class="qty-itm">{{ $cart->qty }}</div>
                                    <div class="total-prc">{{number_format( $cart['total'], 0, ',','.')}}</div>
                                </div>
                                <div class="detail-itm">
                                    @if(!@empty($cart['id_varian']))
                                        <p class="option varian-op">{{ $cart->varian->nama }}</p>
                                    @else
                                    @endif
    
                                    @foreach($cart->AddOptional_order as $adds)
    
                                    @if(!@empty( $adds ))
    
                                        <p class="option add-op" id_adds="">
                                            {{ $adds->optional_Add->name }} - <span class="price-add">{{ $adds->optional_Add->harga }}</span>
                                        </p>
    
                                    @else
                                    @endif
    
                                    @endforeach
    
    
                                    @if(!@empty($cart['id_sales_type']))
                                        <p class="option status-order" idx="{{ $cart['id_sales_type'] }}">
                                            {{ $cart->salesType->name }}
                                        </p>
                                    @else
                                    @endif
    
                                    @foreach($cart ->Discount_menu_order as $discounts)
                                        @php
                                            $totalDis =+ $discounts->discount->rate_dis;
                                        @endphp
                                    @if(!@empty($totalDis))
                                        @php
                                            $nominalDis = 0;
                                            $Dis = $totalDis /100 ;
                                            $nominalDis = str_replace(".", "",$cart['total']) * $Dis ;
                                            $total_dis += $nominalDis;
                                        // dd($total_dis);
                                        @endphp
                                        <p class="option status-order" dis="{{ $totalDis }}">Discount - {{ number_format($nominalDis, 0, ',','.') }}</p>
                                    @else
                                    @endif
                                    @endforeach
    
                                    @if(!@empty($cart['catatan']))
                                        <p class="option status-order">{{ $cart['catatan'] }}</p>
                                    @else
                                    @endif
                                </div>
                            </div>
                            @endforeach
    
                        </div>
                    </div>
                    <div class="footer-bill">
                        <div class="footer-sub-total">
                            <div class="total">
                                @php
                                    $sub_total = 0;
                                    $sub_total = $subtotal - $total_dis;
                                @endphp
                                <div class="txt-total subtotal">Subtotal:</div>
                                <div class="txt-price-total subtotal">Rp.{{number_format( $sub_total, 0, '.','.') }}</div>
                            </div>
    
                            @php
                                $totalTax = 0;
                            @endphp
                            @foreach ($taxs as $tax )
                                @php
                                    $nominalTax = 0;
                                    $desimalTax = $tax->tax_rate / 100;
                                    $nominalTax = str_replace(".", "", $sub_total) * $desimalTax;
                                    $totalTax += $nominalTax;
                                @endphp
                            <div class="total taxes" idx="{{ $tax-> id }}">
                                <div class="txt-total service-change">{{ $tax->nama }} <p class="presentage">{{
                                        $tax->tax_rate }} %</p>:</div>
                                <div class="txt-price-total nominal-tax" style="color: rgb(8, 8, 8);font-size: 12.5px;
                                    font-weight: bolder;">Rp.{{
                                    number_format( $nominalTax, 0, '.','.') }}</div>
                            </div>
    
                            @endforeach
    
                            @php
                                $total = 0;
                                $total = $sub_total + $totalTax;
                            @endphp
    
                            <div class="total">
                                <div class="txt-total total">Total:</div>
                                <div class="txt-price-total total" data-total="{{ $total }}">Rp.{{ number_format( $total, 0,
                                    '.','.') }}</div>
                            </div>
                            @if(!empty($order->id_booking))
                                @php
                                    $sisaBayar = 0;
                                    $sisaBayar = $total - $order->booking->nominal_dp;
                                @endphp
                            <div class="total">
                                <div class="txt-total total">Deposit:</div>
                                <div class="txt-price-total ">{{ number_format( $order->booking->nominal_dp, 0, '.','.') }}
                                </div>
                            </div>
                            <div class="total">
                                <div class="txt-total total">@if($sisaBayar > 0 ) Sisa Bayar: @else Lebih Bayar : @endif
                                </div>
                                <div class="txt-price-total @if($sisaBayar > 0 ) sisa-bayar @endif"
                                    data-total="{{ $sisaBayar }}">{{ number_format( $sisaBayar, 0, '.','.') }}</div>
                            </div>
                            @endif
                            @if(!empty ($order->id_type_payment) )
                            <div class="cash">
                                <div class="txt-cash">{{ $order->payment->nama }}</div>
                                <div class="cash-nominal">Rp. {{ number_format( $order->cash, 0, ',','.') }}</div>
                            </div>
                            <div class="cash">
                                <div class="txt-cash">Change</div>
                                <div class="cash-nominal">Rp. {{ number_format( $order->change_, 0, ',','.') }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="popup-name-bill" style="display: none">
        <div class="position-card">
            <div class="card-colum-input">
                <div class="header-card">
                    <div class="txt-tittle">Print sedang diproses..</div>
                    <div class="total-payment"></div>
                </div>
                <div class="form-group">
                   <p>Tunggu Sebentar..</p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var throttledButtonClick;
        var canClick;
         $(()=>{
            canClick = true; 

            $('.button-kitchen').on('click', function(){
              var idx = $(this).attr('xid');
              getKitche(idx)
            });

            $('.bill').on('click', function(){
              var idx = $(this).attr('xid');
              printWord(idx, 'bill');
            });

            $('.button-tiket').on('click', function(){
                var idx = $(this).attr('xid');
                getTiket(idx)
            });

            function getTiket(id){
                let URL = "{{ route('print-tiket', '')}}"+'/'+id;
                $.get(URL, function(result){
                    var $target = $('.content-bill');
                    var $bill = $target.find('.detail-bill');
                    var $kitchen = $target.find('.detail-kitchen');
					var $ticket = $target.find('.detail-tiket').remove();
                    if($kitchen.length > 0){
                        $kitchen.remove();
                    }else{
                        $bill.hide();
                    }
                    $(result).appendTo($target);
                    printWord(id, 'Tiket')
                   
                }).fail(function(result){
                    console.log(result);
                })
            }

            function getKitche(id){
                let URL = "{{ route('print-kitchen', '')}}"+'/'+id;
                $.get(URL, function(result){
                    var $target = $('.content-bill');
                    var $bill = $target.find('.detail-bill');
                    var $tiket = $target.find('.detail-tiket');
					$target.find('.detail-kitchen').remove();
                    if($tiket.length > 0){
                        $tiket.remove();
                    }else{
                        $bill.hide();
                    }
                    $(result).appendTo($target);
                    printWord(id, 'Kitchen')
                   
                }).fail(function(result){
                    console.log(result);
                })
            }

            function updateLastPrint(xid, type){
                var Url = "{{ route('update_last_print', '')}}"+'/'+ xid;
                var dataPost = {
                    _token : "{{ csrf_token() }}",
                    print: type,
                };
                $.post(Url, dataPost).done(function(data){
                    // alert('Done');
                    console.log(data)
                    }).fail(function(data){
                        console.log('error', data);
                });
            }

            function printWord(id, type){
				var $target = $('.content-bill');
				var url = "{{ route('print', '') }}" + '/'+id;
				if(type == "Kitchen"){
					url = "{{ route('print-kitchen-thermal', '') }}" + '/'+id;
				}else if(type == "Tiket"){
					url = "{{ route('print-ticket-thermal', '') }}" + '/'+id;
				}else{
					url = "{{ route('print-bill-thermal', '') }}" + '/'+id;
					$target.find('.detail-kitchen').remove();
					$target.find('.detail-tiket').remove();
					$target.find('.detail-bill').show();
				}
               
                var kode = $('.head-bill .kode').text();
                var data = {
                    _token : "{{ csrf_token() }}",
                    type: type,
                }
                $.post(url,data ).done(function(result){
                    //console.log(result);
                    throttledButtonClick();
                    //console.log(type);
                    updateLastPrint(id, type)
                }).fail(function(result){
                    console.log(result);
                })
				/*
                var url = "{{ route('print', '') }}" + '/'+id;
                var kode = $('.head-bill .kode').text();
                var data = {
                    _token : "{{ csrf_token() }}",
                    type: type,
                }
                $.post(url,data ).done(function(result){
                    console.log(result);
                    throttledButtonClick();
                    var namefile = result.data;
                    console.log(type);
                    GetPrint(type, namefile, id)
                    updateLastPrint(id, type)
                }).fail(function(result){
                    console.log(result);
                })
				*/
            }


            function GetPrint(type, FileName, id){

                var URL = 'http://192.168.88.22:3377/print-file?type='+type+'&filename='+FileName;

                $.get(URL, function(result){
                    console.log(result.stdout);
                   
                }).fail(function(result){
                    console.log(result);
                })
            }

            function updateButtonClickStatus() {
                canClick = true;
            }
        
            function throttle(func, delay) {
                var timeout;
                
                return function() {
                    if (!timeout) {
                        func.apply(this, arguments);
                        timeout = setTimeout(function() {
                            timeout = null;
                        }, delay);
                    }
                };
            }

            throttledButtonClick = throttle(function() {
              
                if (!canClick) {
                    $('.popup-name-bill').fadeIn(); 
                    return;
                }
                
                canClick = false;
                
                $('.popup-name-bill').fadeIn(); // Menampilkan pop-up print sedang diproses
                console.log("Print sedang diproses...");
                
                setTimeout(function() {
                    $('.popup-name-bill').fadeOut(); // Menyembunyikan pop-up setelah selesai
                    console.log("Print selesai.");
                    updateButtonClickStatus();
                }, 1000);
            }, 1000);
          
        });



    </script>
</body>

</html>

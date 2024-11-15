@extends('layout.master')

@section('content')



<section class="content-header">

    <div class="container-fluid">

        <div class="row mb-2">

            <div class="col-sm-6">

                <h1>Order</h1>

            </div>

            <div class="col-sm-6">

                <ol class="breadcrumb float-sm-right">

                    <li class="breadcrumb-item"><a href="{{ url('/Dashboard') }}">Home</a></li>

                    <li class="breadcrumb-item "><a href="{{ url('/orders') }}">Order</a></li>

                    <li class="breadcrumb-item active">Detail Order</li>

                </ol>

            </div>

        </div>

    </div>
    <div class="act-button">
        <div class="btn-act-refund">
            Refund Item
        </div>
        <div class="btn print-bill"  data-id="{{ $detail->id }}">
            Print Bill
        </div>
    </div>

    <div class="act-btn">
        <input type="text" class="nominal-refund" autofocus>
        <div class="btn-refund" id-item="0">
            Refund
        </div>
    </div>

</section>



<section class="content-header">

    <div class="container-fluid" style="display: flex; flex-direction:column; align-items:center;">

        <div class="card " style="width: 90%">
            <div class="card-body data" data-id="{{ $detail->id }}">
                <div class="head-detail">
                    <div class="order-data">
                        <div class="txt-order-dt">Table Number</div>
                        <div class="txt-order-dt">{{ $detail->no_meja }}</div>
                    </div>
                    <div class="order-data">
                        <div class="txt-order-dt">{{ $detail->created_at }}</div>
                        <div class="txt-order-dt"></div>
                    </div>
                    <div class="order-data">
                        <div class="txt-order-dt">Receipt Number</div>
                        <div class="txt-order-dt"> @if($detail->id_booking){{ $detail->booking->kode_boking }}/ {{
                            $detail->kode_pemesanan }} @else {{ $detail->kode_pemesanan }} @endif</div>
                    </div>
                    <div class="order-data">
                        <div class="txt-order-dt">Collected By</div>
                        <div class="txt-order-dt">{{ $detail->admin->nama }}</div>
                    </div>
                    <div class="order-data">
                        <div class="txt-order-dt">Member Name</div>
                        <div class="txt-order-dt"> @if(isset($detail->user->nama)) {{ $detail->user->nama }} @else
                            Customer @endif </div>
                    </div>
                    <div class="order-data">
                        <div class="txt-order-dt">No Handphone</div>
                        <div class="txt-order-dt"> {{ $detail->no_hp }} </div>
                    </div>

                </div>

                @php
                    $total_itms = 0;
                    $total_items_refund = 0;
                    $total_dis = 0;
                    $sub_refund = 0;
                    $nominalDis = 0;
                    $subtotalRef = 0;
                    $totalDis=0;
                    $subtotal_order = 0;
                    $totalDisRef = 0;
                @endphp
                <div class="list-item-menu">

                    @foreach ($detail->details as $menu)
                    <div class="part-list mb-2" idx="{{ $menu->id_menu }}">
                        <div class="item">
                            <div class="detail-menu-order">
                                <p class="card-text nama" style="margin-bottom: 0.1rem;">
                                    @if(isset($menu->menu->nama_menu)) {{ $menu->menu->nama_menu }}@else Customer @endif
                                </p>

                                <p class="card-text harga-menu" style="margin-bottom: 0.1rem;"
                                    data-harga="{{ $menu->harga }}"> {{ $menu->harga }}</p>

                                <div class="control-qty">
                                    <a href="#" class="qty qty-minus">-</a>
                                    <input type="numeric" class="qty" value="{{ $menu->qty }}" max="{{ $menu->qty }}" />
                                    <a href="#" class="qty qty-plus">+</a>
                                </div>
                                <p class="card-text " style="font-weight: 800"> {{ number_format($menu->total,0,".",".")}}</p>

                            </div>
                            <div class="detail-itm">
                                @if(!@empty($menu['id_varian']))
                                <small class="option varian-op" id-varian="{{ $menu['id_varian'] }}">{{ $menu->varian->nama }}</small>
                                @else
                                <small class="option varian-op" id-varian=""></small>
                                @endif

                                @foreach($menu->AddOptional_order as $adds)

                                @if(!@empty( $adds ))

                                <small class="option add-op" id_adds="{{  $adds->optional_Add->id }}">
                                    {{ $adds->optional_Add->name }} - <span class="harga-adds"
                                        nominal="{{ $adds->optional_Add->harga }}">{{ $adds->optional_Add->harga
                                        }}</span>
                                </small>

                                @else
                                @endif

                                @endforeach


                                @if(!@empty($menu['id_sales_type']))
                                    <small class="option status-order" idx="{{ $menu['id_sales_type'] }}">
                                        {{ $menu->salesType->name }}
                                    </small>
                                @else
                                @endif

                                @foreach($menu ->Discount_menu_order as $discounts)
                                    @php
                                        $total_rate =+ $discounts->discount->rate_dis;
                                        $totalDis += $discounts->total_discount;
                                    @endphp
                              
                                   
                                    <small class="option discount" idx="{{ $discounts->discount->id }}" dis="{{ $discounts->discount->rate_dis }}" 
                                    nominal-dis="{{ $discounts->total_discount }}" data-idItem="{{ $discounts->id_detail_order }}">Discount {{ $discounts->discount->rate_dis }}%  - {{ number_format( $discounts->total_discount, 0, ',','.') }}</small>
                               
                                @endforeach

                                @if(!@empty($menu['catatan']))
                                    <small class="option status-order note">{{ $menu['catatan'] }}</small>
                                @else
                                @endif
                            </div>
                        </div>
                        <div class="act-edit">
                            <input type="checkbox" name="" id-item="{{ $menu->menu->id }}" id_detail="{{$menu->id}} "
                                class="check-edit">
                            <span class="checkmark"></span>
                        </div>
                    </div>
                        @php
                            $total_itms += $menu->total;
                        @endphp
                    @endforeach
                    {{--  {{ $totalDis }}
                    {{ $total_itms }}  --}}
                    @php
                        $subtotal_order = $total_itms - $totalDis;
                    @endphp
                </div>
                
                @if(!@empty($refund))
               
                <div class="header-refund mb-4" style="border-bottom: dashed;border-top: dashed; color: gray">
                    Item Refund
                </div>
                    
                <div class="list-item-menu mb-4">

                    @foreach ($refund as $menu)
                    <div class="part-list mb-2" idx="{{ $menu->id_menu }}">
                        <div class="item">
                            <div class="detail-menu-order" style="color: gray">
                                <p class="card-text nama" style="margin-bottom: 0.1rem;">
                                    @if(isset($menu->menu->nama_menu)) {{ $menu->menu->nama_menu }}@else @endif </p>

                                <p class="card-text harga-menu" style="margin-bottom: 0.1rem;"
                                    data-harga="{{ $menu->menu->harga }}"> {{ $menu->harga }}</p>

                                <div class="control-qty">
                                    <input type="numeric" class="qty" value="{{ $menu->qty }}" max="{{ $menu->qty }}"
                                        disabled />
                                </div>

                                @php

                                $additional = App\Models\AdditionalRefund ::where('id_menu', $menu->id_menu)->where('id_refund_menu', $menu->id)->get();
                                $discount = App\Models\DiscountMenuRefund::where('id_refund_menu',
                                $menu->id)->where('id_menu', $menu->id_menu)->get();
                                
                                @endphp
                                <p class="card-text " style="font-weight: 800"> {{number_format($menu['refund_nominal'],0,".",".") }}</p>

                            </div>
                            <div class="detail-itm">
                                @if(!@empty($menu['alasan_refund']))
                                <small class="option status-order note">{{ $menu['alasan_refund'] }}</small>
                                @else
                                @endif

                                @if(!@empty($menu['id_varian']))
                                <small class="option varian-op" id-varian="{{ $menu['id_varian'] }}">{{$menu->varian->nama }}</small>
                                @else
                                    <small class="option varian-op" id-varian=""></small>
                                @endif

                                @foreach($additional as $adds)
                                    @if(!@empty( $adds ))
                                        <small class="option add-op" id_adds="{{  $adds->id_option_additional }}">
                                            {{ $adds->additionOps->name }} - <span class="harga-adds"
                                                nominal="{{ $adds->additionOps->harga }}">{{ $adds->additionOps->harga }}</span>
                                        </small>
                                    @else
                                    @endif
                                @endforeach


                                @foreach($discount as $discounts)
                                        @php
                                            $totalDisRef += $discounts->nominal_dis;
                                        @endphp
                                   
                                <small class="option status-order discount" xid="{{ $discounts->id_discount }}">Discount
                                    - {{ number_format( $discounts->nominal_dis, 0, ',','.') }}</small>

                                @endforeach

                                @if(!@empty($menu['catatan']))
                                <small class="option status-order note">{{ $menu['catatan'] }}</small>
                                @else
                                @endif

                            </div>
                        </div>

                    </div>
                        @php
                            $sub_refund += $menu['refund_nominal'];
                           
                        @endphp
                    @endforeach
                   @php
                     $sub_refund =  $sub_refund - $totalDisRef;
                   @endphp
                   {{--  {{ $sub_refund }}
                   {{ $subtotal_order }}  --}}
                </div>


                @else

                @endif

                <div class="footer-sub-total">
                     
                   {{--  @php
                        $subtotal_order = $subtotal_order - $sub_refund;
                   @endphp  --}}
                  
                     
                    <div class="total">
                        <div class="txt-total subtotal">Subtotal:</div>
                        <div class="txt-price-total subtotal" data-subtotal="{{$subtotal_order}}">{{
                            number_format($subtotal_order,0,".",".") }}</div>
                    </div>
                     @php
                        $total_taxs = 0;
                        $grand_total = 0;
                    @endphp
                    @foreach ($taxs as $tax )
                        @php
                        $nominalTax = 0;
                        $desimalTax = $tax->tax_rate / 100;
                        $nominalTax = str_replace(".", "", $subtotal_order) * $desimalTax;
                        $totalTax += $nominalTax;
                        @endphp
                    <div class="total taxes" idx="{{ $tax-> id_tax}}">
                        <div class="txt-total service-change" data-tax-rate="{{$tax->rate}}">{{ $tax->nama }} <p
                                class="presentage">{{ $tax->tax_rate }} %</p>:</div>
                        <div class="txt-price-total nominal-tax" data-taxs="{{$nominalTax}}"
                            style="color: grey;font-size: 13px;">{{$nominalTax}}</div>
                    </div>
                    @php
                        $total_taxs += $nominalTax;
                    @endphp
                    @endforeach

                    @php
                        $grand_total = $subtotal_order + $total_taxs;
                    @endphp
                    <div class="total">
                        <div class="txt-total total">Total:</div>
                        <div class="txt-price-total total" data-total="{{ $grand_total }}">{{ number_format($grand_total, 0, '.','.') }}</div>
                    </div>

                    @if(!empty($detail->id_booking))
                        @php
                        $sisaBayar = 0;
                        $sisaBayar = $detail->total_order - $detail->booking->nominal_dp;
                        @endphp
                    <div class="total">
                        <div class="txt-total total">Deposit:</div>
                        <div class="txt-price-total ">{{ number_format( $detail->booking->nominal_dp, 0, '.','.') }}
                        </div>
                    </div>
                    <div class="total">
                        <div class="txt-total total">@if($sisaBayar > 0 ) Sisa Bayar: @else Lebih Bayar : @endif
                        </div>
                        <div class="txt-price-total @if($sisaBayar > 0 ) sisa-bayar @endif"
                            data-total="{{ $sisaBayar }}">{{ number_format( $sisaBayar, 0, '.','.') }}</div>
                    </div>
                    @endif
                    @if(!empty ($detail->id_type_payment) )
                    <div class="total">
                        <div class="txt-cash">{{ $detail->payment->nama }}</div>
                        <div class="cash-nominal">Rp. {{ number_format( $detail->cash, 0, ',','.') }}</div>
                    </div>
                    <div class="total">
                        <div class="txt-cash">Change</div>
                        <div class="cash-nominal">Rp. {{ number_format( $detail->change_, 0, ',','.') }}</div>
                    </div>
                    @endif
                </div>

                {{-- data refund item --}}
                @if(!@empty($refund))
                <div class="header-refund">
                    Item Refund
                </div>
               @php
                     $subtotalRef = 0;
                     $totalDis =0 ;
               @endphp
                <div class="list-item-menu">

                    @foreach ($refund as $menu)
                    <div class="part-list mb-2" idx="{{ $menu->id_menu }}">
                        <div class="item">
                            <div class="detail-menu-order">
                                <p class="card-text nama" style="margin-bottom: 0.1rem;">
                                    @if(isset($menu->menu->nama_menu)) {{ $menu->menu->nama_menu }}@else @endif </p>

                                <p class="card-text harga-menu" style="margin-bottom: 0.1rem;"
                                    data-harga="{{ $menu->menu->harga }}"> {{ $menu->harga }}</p>

                                <div class="control-qty">
                                    <input type="numeric" class="qty" value="{{ $menu->qty }}" max="{{ $menu->qty }}"
                                        disabled />
                                </div>

                                @php
                                $additional = App\Models\AdditionalRefund ::where('id_menu', $menu->id_menu)->where('id_refund_menu', $menu->id)->get();
                                $discount = App\Models\DiscountMenuRefund::where('id_refund_menu',$menu->id)->where('id_menu', $menu->id_menu)->get();
                                

                                @endphp
                                <p class="card-text " style="font-weight: 800"> {{
                                    number_format($menu['refund_nominal'],0,".",".") }}</p>

                            </div>
                            <div class="detail-itm">
                                @if(!@empty($menu['alasan_refund']))
                                <small class="option status-order note">{{ $menu['alasan_refund'] }}</small>
                                @else
                                @endif

                                @if(!@empty($menu['id_varian']))
                                <small class="option varian-op" id-varian="{{ $menu['id_varian']}}">{{
                                    $menu->varian->nama }}</small>
                                @else
                                <small class="option varian-op" id-varian=""></small>
                                @endif

                                @foreach($additional as $adds)
                                    @if(!@empty( $adds ))
                                    <small class="option add-op" id_adds="{{  $adds->id_option_additional }}">
                                        {{ $adds->additionOps->name }} - <span class="harga-adds"
                                            nominal="{{ $adds->additionOps->harga }}">{{ $adds->additionOps->harga }}</span>
                                    </small>
                                    @else
                                    @endif
                                @endforeach


                                @foreach($discount as $discounts)
                                    @php
                                        $totalDis += $discounts->nominal_dis;

                                    @endphp
                                    <small class="option status-order discount" xid="{{ $discounts->id_discount }}">Discount
                                    - {{ number_format( $discounts->nominal_dis, 0, ',','.') }}</small>
                                @endforeach

                                @if(!@empty($menu['catatan']))
                                    <small class="option status-order note">{{ $menu['catatan'] }}</small>
                                @else
                                @endif

                            </div>
                        </div>

                    </div>
                    @php
                        $subtotalRef += $menu['refund_nominal'];
                    @endphp
                    
                    @endforeach
                
                    @php
                        $subtotalRef = $subtotalRef - $totalDis ;
                    @endphp
                </div>

                <div class="footer-sub-total">

                    <div class="total">
                        <div class="txt-total subtotal">Subtotal:</div>
                        <div class="txt-price-total subtotal">{{ number_format($subtotalRef,0,".",".") }}</div>
                    </div>
                    @php
                    $totalTax = 0;
                    @endphp
                    @foreach ($taxs as $tax )
                        @php
                            $nominalTax = 0;
                            $desimalTax = $tax->tax_rate / 100;
                            $nominalTax = str_replace(".", "", $subtotalRef) * $desimalTax;
                            $totalTax += $nominalTax;
                        @endphp

                    <div class="total taxes" idx="{{ $tax->id}}">
                        <div class="txt-total service-change">{{ $tax->nama }} <p class="presentage">{{ $tax->tax_rate
                                }} %</p>:</div>
                        <div class="txt-price-total nominal-tax" style="color: grey;font-size: 13px;">{{ number_format(
                            $nominalTax, 0, '.','.') }}</div>
                    </div>
                    @endforeach

                    <div class="total">
                        @php
                        $grandTotal = 0;
                        $grandTotal = $subtotalRef + $totalTax;
                        @endphp
                        <div class="txt-total total">Total:</div>
                        <div class="txt-price-total total" data-total="{{ $grandTotal }}">{{ number_format( $grandTotal,
                            0, '.','.') }}</div>
                    </div>
                </div>
                @else

                @endif

            </div>

        </div>
    </div>

</section>

<div class="pop-ex-refund">
    <div class="card-ex-ref">
        <div class="header-card-ex">
            <p class="txt-head">Alasan Refund</p>
            <p class="close-popup">X</p>
        </div>
        <div class="isi-excuse-refund">
            <div class="list-excuse">
                <div class="itm-ex-refund">Product Retur</div>
                <div class="itm-ex-refund">Transaction Error</div>
                <div class="itm-ex-refund">Order Cancellation</div>
                <div class="itm-ex-refund other" contenteditable>Other</div>
            </div>
        </div>
    </div>
    <div class="other-excuse" style="width: 280px">
        <div class="header-card-ex">
            <p class="txt-head">Other Reasons</p>

        </div>
        <div class="isi-oth">
            <textarea name="" id="" cols="30" rows="4" class="other-exc"></textarea>
            <div class="act-oth">
                <div class="save">Save</div>
                <div class="exit">Close</div>
            </div>
        </div>
    </div>
</div>

<div class="popup-print" style="display: none">
    <div class="position-card">
        <div class="card-colum-input">
            <div class="header-card">
                <div class="txt-tittle">Print sedang diproses..</div>
                <div class="total-payment"></div>
            </div>
            
            <p>Tunggu Sebentar..</p>
            
        </div>
    </div>
</div>
@stop



@section('script')
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>

<script>
    $(()=>{

        $('.pop-ex-refund').hide();
        $('.act-btn').hide();
        $('.act-edit').hide();
        $('.other-excuse').hide();

        $('#status').on('change', function(e){

            var kode = $('.data').attr('data-id');

            UpadateStatusOrder(kode)

        });

        //refund
        $('.btn-act-refund').on('click', function(){
                $('.pop-ex-refund').fadeIn();
                $('.act-btn').show();
                $('.act-edit').show();
        })
        $('body').on('click','.itm-ex-refund', function(){
            $(this).addClass('active');
            $('.pop-ex-refund').hide();
        })
        $('body').on('click','.itm-ex-refund.active', function(){
            $(this).removeClass('active');
        })
        $('body').on('click','.close-popup', function(){
             $('.pop-ex-refund').hide();
        })

        {{--  $('body').on('click','.other', function(){
            $('.other-excuse').show();
        })
        $('body').on('click','.act-oth', function(){
            $('.other-excuse').hide();
        })  --}}

        $('.print-bill').on('click', function(e){
            var xid = $(this).attr('data-id');
            Bill(xid, 'Bill');
           
        })
            //qty
        $('a.qty-minus').on('click', function(e) {
            e.preventDefault();
            var $this = $(this);
            var $input = $this.closest('div').find('input');
            var value = parseInt($input.val());

            if (value > 1) {
                value = value - 1;
            } else {
                value = 0;
            }

            $input.val(value);

        });

        $('a.qty-plus').on('click', function(e) {
            e.preventDefault();
            var $this = $(this);
            var $input = $this.closest('div').find('input');
            var value = parseInt($input.val());

            if (value < 100) {
            value = value + 1;
            } else {
                value =100;
            }

            $input.val(value);
        });

        // RESTRICT INPUTS TO NUMBERS ONLY WITH A MIN OF 0 AND A MAX 100
        $('input').on('blur', function(){

            var input = $(this);
            var value = parseInt($(this).val());

                if (value < 0 || isNaN(value)) {
                    input.val(0);
                } else if
                    (value > 100) {
                    input.val(100);
                }
        });


        $('.act-edit').on('click', `.check-edit`, function(e){

           refund($(this), 1);

        });

        $('.btn-refund').on('click', function(e){
            refund($(this), 2);
        });

        function Bill(id, type){
            var URL = '{{route("print-bill-thermal", "")}}' +'/' + id;
            const data = {
                _token: "{{csrf_token()}}",
                type: type
            }
            $.post(URL, data).done(function(result){
                setTimeout(function(){
                    $('.popup-print p').text('Print in prosess...');
                    $('popup-print').fadeIn();
                    console.log('print..', result)
                },1000)
                
            }).fail(function(result){
                console.log(result);
            })
        }

        function refund($elm, type){
           var id =$elm.attr('id-item');
          
           var $parent = $('.part-list');
           var $itm = $parent.find('.act-edit input:checked').prop('checked', true);
           var $target = $itm.closest('.part-list');
           var $adds = $target.find('.detail-itm .option.add-op');
           var $dis = $target.find('.option.discount');
           var xidOrder = $('.card-body.data').attr('data-id');
           var $tgt_footer = $('.footer-sub-total');
           var subtotal_order = $tgt_footer.find('.txt-price-total.subtotal').attr('data-subtotal');
           var total_order = $tgt_footer.find('.txt-price-total.total').attr('data-total');
           var $taxes = $tgt_footer.find('.service-change');
           console.log(subtotal_order, total_order);
           var order_tax = [];
           var nominalDis = [];
           var Adds = [];
           var Dis = [];
           var itm = []; 

            $taxes.each(function(){
                var $tgt = $(this);
                var rate = $tgt.find('.service-change').attr('data-tax-rate');
                var nominal = $tgt.find('.nominal-tax').attr('data-taxs');
                var objTax = {
                    rate_tax: rate,
                    nom_tax: nominal
                };

                order_tax.push(objTax);
            })

            $adds.each(function(){
                var $tgt = $(this);
                var qty = $target.find('input.qty').val();
                var harga_adds = $tgt.find('.harga-adds').attr('nominal');
                var xid_adds = $tgt.attr('id_adds');
                var orderId = $tgt.closest('.card-body.data').attr('data-id');
                var id = $tgt.closest('.part-list').attr('idx');
                var Total = harga_adds * qty;

                var objadds = {
                    //id_order: orderId,
                    id_menu: id,
                    id_add: xid_adds,
                    addHarga: harga_adds,
                    qty: qty,
                    Total: Total,
                }
                Adds.push(objadds);

            })

            console.log(Adds);
            
            //var total_adds = 0 ;
            //Adds.each(function(){
            //    var hargaAdds = Adds.addHarga;
            //    total_adds += parseInt(hargaAdds);
            //});

            var itm_detail = [];

            

            $itm.each(function(){
                var $chacked = $(this);
                var id = $chacked.attr('id-item');
                var id_detail = $chacked.attr('id_detail');
                var orderId = $chacked.closest('.card-body.data').attr('data-id');
                var $target = $chacked.closest('.part-list');
                var harga_menu = $target.find('.card-text.harga-menu').attr('data-harga');
                var qty = $target.find('input.qty').val();
                var $adds = $target.find('.detail-itm .option.add-op');
                var varian = $target.find('.detail-itm .option.varian-op').attr('id-varian');
                var $dis_ref = $target.find('.detail-itm .option.discount');
                var note = $target.find('.detail-itm .option.note').text();
                var $tgt_alasan =$('.pop-ex-refund .isi-excuse-refund');
                var alasan = $tgt_alasan.find('.itm-ex-refund.active').text();
               

                console.log(alasan);

                var arAdds=[];
                $adds.each(function(){
                    var $tgtAds = $(this);
                    var hargaAds = $tgtAds.find('.harga-adds').attr('nominal');
                    var xid_adds = $tgtAds.attr('id_adds');
                    var orderId = $tgtAds.closest('.card-body.data').attr('data-id');
                    var id = $tgtAds.closest('.part-list').attr('idx');
                    var Total = hargaAds * qty;

                    var objAds = {
                            nominal:hargaAds,
                            id_menu: id,
                            id_add: xid_adds,
                            qty: qty,
                            Total_adds: Total,
                        };

                    arAdds.push(objAds);
                })

                var totalAds=0;
                for(var i=0; i < arAdds.length; i++ ){
                    var adsNominal = arAdds[i].nominal;
                    totalAds += parseInt(adsNominal);


                }

                console.log('total adds' + totalAds);

                var nominal_itm = (parseInt(harga_menu) + parseInt(totalAds)) * parseInt(qty);
                console.log('nominal item: '+ nominal_itm);
                
                var disRef =[];
                $dis_ref.each(function(){
                    var $tgtDisRef = $(this);
                    var dis = $tgtDisRef.attr('dis');
                    var xid_dis = $tgtDisRef.attr('idx');
                    var orderId = $tgtDisRef.closest('.card-body.data').attr('data-id');
                    var id = $tgtDisRef.closest('.part-list').attr('idx');
                    

                    var disRate = dis / 100;
                    var nominal_dis = nominal_itm * disRate;

                    var objDis = {
                        id_menu:id,
                        idDiscount: xid_dis,
                        nominalDis: nominal_dis,
                        rate : dis,
                       
                    };

                    disRef.push(objDis);

                    nominal_itm -= nominal_dis ;
                });

                
                console.log("aray ads: ", arAdds);
                console.log("total array ads: " ,totalAds);

                var objItem = {
                    id_order: orderId,
                    id_menu: id,
                    harga_menu : harga_menu,
                    qty: qty,
                    varian: varian,
                    alasan: alasan,
                    nominal: nominal_itm,
                    adds : totalAds,
                    catatan: note,
                    discount: disRef,
                    additional: arAdds,


                };
                //console.log($target);
                var obj_detail_order ={
                    id_detail: id_detail,
                    id_order: orderId,
                    id_menu: id,
                    harga_menu : harga_menu,
                    qty: qty,
                    varian: varian,
                    adds : totalAds,
                    catatan: note,
                    discount: disRef
                };

                itm_detail.push(obj_detail_order);
                itm.push(objItem);
            });

           
            console.log(itm);

            var totalItm = 0;
            var total = [];
            for (var i = 0; i < itm.length; i++) {
                var harga = itm[i].harga_menu;
                var qty = itm[i].qty;
                var adds =itm[i].adds;
                harga = harga.replace(/\./g, '');

                totalItm = ( parseInt(harga) + parseInt(adds) ) * qty ;

                var objTotal = {
                    total: totalItm,
                };

                total.push(objTotal);

            }

            console.log(total);
            console.log(itm);

            var totalDiscount = 0;
            
           $.each(itm, function(index, item) {
                $.each(item.discount, function(discountIndex, discount) {
                    totalDiscount += parseInt(discount.nominalDis);
                });
            });
            console.log(totalDiscount);

            var subTotal = 0;
            for (var i = 0; i < total.length; i++) {
                var SubT = total[i].total;
                harga = harga.replace(/\./g, '');
                subTotal += parseInt(SubT)

            }

            var SubDis = subTotal - parseInt(totalDiscount) ;
            console.log(subTotal);
            var pb1 = 0.1;
            var service = 0.05;

            var pajak1 = SubDis *  pb1;
            var pajak2 = SubDis * service;

            var TotalRefund = SubDis + pajak1 + pajak2;

            console.log(pajak1, pajak2, TotalRefund);

            var nominal = TotalRefund.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            $('input.nominal-refund').val(nominal);

            //update subtotal , total , dan tax dari tabel order berdasarkan hasil refund

            var sub_update = subtotal_order - SubDis;
            var pj_order1 = sub_update *pb1 ;
            var pj_order2 = sub_update * service;
            var total_update = sub_update + pj_order1 + pj_order2;

            if(type === 2){
                var id =$elm.attr('id-item');

                var DataRefund = {
                    _token : "{{ csrf_token() }}",
                    menu : itm,
                    additionalRef: Adds,
                    detail_menu: itm_detail,
                    order_id : xidOrder,
                    tx_pb1: pj_order1,
                    tx_service: pj_order2
                     

                }

                console.log("Data Refund :", DataRefund);

                $.post("{{ route('refund') }}", DataRefund).done(function(data){
                        if(data.success === 0){
                            alert(data.message);
                        }else{
                            //$(this).attr('data-notify', data['count']);
                            location.reload();
                        }
                }).fail(function(data){
                        console.log('error', data);
                });
            }
        }
    })


</script>

@stop
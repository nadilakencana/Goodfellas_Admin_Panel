@extends('layout.master')
@section('content')
@include('POS.part_lain.popUp_Additional')
{{-- @include('POS.part_lain.Daftar-Bill') --}}
<div class="popup-daftar-bill" style="display: none">

</div>
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3>Dashboard Point Of Sales</h3>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card-header">
            <div class="tab-navigation">
                <div class="tab" target-panel="panel1" order="1">Favorite</div>
                <div class="tab" target-panel="panel2" order="2">All Item</div>
                <div class="tab" target-panel="panel3" order="3">Custom</div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="bar-search">
                    <input type="text" class="search-allmenu" id="FavMenuSearch" placeholder="Search Menu" autofocus>
                    <div class="img-icon" data-target="FavMenuSearch" id="search1">
                        <img src="{{ asset('asset/assets/image/icon _search_.png') }}" alt="" srcset="" class="icon">
                    </div>
                </div>
                <div class="card height-card">
                    <div class="tab-panel-container">
                        {{-- all menu --}}
                        <div class="panel active" data-panel="panel1" panel-order="1">
                            <div class="content-menu">
                                <div class="kategory-menu">
                                    {{-- <div class="menu tab-navigation-menu">
                                       
                                        <div class="menu-cat active" data-type="Fav" order-menu="1">Minuman</div>
                                        <div class="menu-cat" data-type="Fav" order-menu="2">Makanan</div>
                                       
                                    </div> --}}
                                    <div class="tab-panel-menu" data-type="Fav">
                                        <div class="menuKat">
                                            <div class="item-menu" data-search="FavMenuSearch">
                                                @foreach ($itemMenu as $item )
                                                {{-- @if($item->id_kategori == 1) --}}
                                                <div class="item-card-menu" idx="{{ $item->id }}" target-price="{{ $item->harga }}">
                                                    <div class="menu-sub">
                                                        <div class="icon">
                                                            <p class="txt-icon">{{ $item->nama_menu }}</p>
                                                        </div>
                                                        <p class="txt-subMenu">{{ $item->nama_menu }}</p>
                                                    </div>
                                                    <div class="harga">
                                                        <p class="txt-subMenu">{{ $item->harga }}</p>
                                                    </div>
                                                </div>
                                                {{-- @endif --}}
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                        {{-- menu favorite --}}
                        <div class="panel" data-panel="panel2" panel-order="2">
                              <div class="content-menu">
                                <div class="sub-content ">
                                    <div class="itmn-subcategory allmenu">
                                        <div class="menu-sub">
                                            <div class="icon-menu">
                                                <img src="{{ asset('asset/assets/image/icon/icon _list_ white.png') }}"
                                                    alt="" srcset="">
                                            </div>
                                            <p class="txt-subMenu">All Menu</p>
                                        </div>
                                        <div class="icon-arrow">
                                            <img src="{{ asset('asset/assets/image/icon/icon _chevron arrow.png') }}"
                                                alt="">
                                        </div>
                                    </div>
                                    @foreach ($subCategory as $sub )
                                    <div class="itmn-subcategory menusub" idx={{ $sub->id }}>
                                        <div class="menu-sub">
                                            <div class="icon">
                                                <p class="txt-icon">{{ $sub->sub_kategori }}</p>
                                            </div>
                                            <p class="txt-subMenu">{{ $sub->sub_kategori }}</p>
                                        </div>
                                        <div class="icon-arrow">
                                            <img src="{{ asset('asset/assets/image/icon/icon _chevron arrow.png') }}"
                                                alt="">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- custom menu --}}
                        <div class="panel" data-panel="panel3" panel-order="3">
                            <div class="custom-part">

                                <input type="text" id="kalkulator" class="nilai-custom">

                                <div class="kalkulator-tombol" id="tombol-custom">

                                    <span class="tombol">1</span>
                                    <span class="tombol">2</span>
                                    <span class="tombol">3</span>
                                    <span class="tombol" id="nol">000</span>
                                    <span class="tombol">4</span>
                                    <span class="tombol">5</span>
                                    <span class="tombol">6</span>
                                    <span class="tombol" id="nol">00</span>
                                    <span class="tombol">7</span>
                                    <span class="tombol">8</span>
                                    <span class="tombol">9</span>
                                    <span class="tombol" id="nol">0</span>
                                    <span class="add-custom" idx="{{ $customItem->id }}">Add</span>
                                    <span class="tombol oprator">C</span>
                                    <span class="tombol oprator">
                                        < </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- view list order/bill --}}
            <div class="col-sm-6 detail-order">

                <div class="card p-2 height-card-2">
                    <div class="header-part">
                        <div class="menu-icon-list-bil">
                            <img src="{{ asset('asset/assets/image/icon/icon _clipboard list_ white.png') }}" alt="">
                            <small class="txt-icon">Daftar Bil</small>
                        </div>
                        <div class="menu-delete">
                            <img src="{{ asset('asset/assets/image/icon/Delete.png') }}" alt="">
                            <small class="txt-icon">Delete Order</small>
                        </div>
                        <div class="act-btn-add">
                            Display Order
                        </div>
                    </div>
                    <div class="view-detail-ord">
                        @if(session()->has('cart'))
                        <div class="part-order">
                            <div class="drop-down">
                                <p class="txt-dropdown" style="margin: 0">Nomer Meja</p>
                                <input type="text" class="nomer-meja" name="no_meja">
                            </div>
                            <div class="detil-bil">
                                @php
                                $total_dis = 0;
                                @endphp
                                @foreach ($carts as $k => $cart )

                                <div class="itm-bil" idx="{{ $cart['id'] }}" xid="{{ $k }}">
                                    <div class="itm">
                                        <p class="txt-item">{{ $cart['nama_menu'] }}</p>
                                        <div class="qty-menu">
                                            <div class="jumlah">{{ $cart['qty'] }}</div>
                                        </div>
                                        <div class="part-float-right">
                                            @php
                                                $totalHarga = 0;
                                                $discountTotal = 0;
                                                $total_sub= 0;
                                                $total = 0;


                                                $discountTotal += $cart['total_dis'] ;
                                                $float_dis = $discountTotal / 100;
                                                $SubtotalHarga = $cart['harga'] + $cart['harga_addtotal'] ;
                                                $totalHarga = ($cart['harga'] + $cart['harga_addtotal'] ) * $cart['qty'] ;
                                                $subtotalDisCount = $totalHarga * $float_dis;
                                                $total = $totalHarga - $subtotalDisCount;
                                                $total_sub += $total;
                                            @endphp

                                            @if(!@empty($cart['harga']))
                                            <p class="price" price="{{ $cart['harga'] }}">{{number_format( $totalHarga ,
                                                0, ',','.')}}</p>
                                            @else
                                            @endif
                                            <div class="hapus-menu-order" idx="{{ $k }}"> X </div>
                                        </div>

                                    </div>
                                    <div class="detail-itm">
                                        @if(!@empty($cart['var_name']))
                                        <small class="option varian-op" id_var="{{ $cart['variasi_id'] }}">{{ $cart['var_name'] }}</small>
                                        @else
                                        @endif

                                        @if(!@empty($cart['additional']))
                                        @foreach ( $cart['additional'] as $adds )
                                        <small class="option add-op" id_adds="{{ $adds['id']  }}">{{ $adds['nama'] }} -
                                            {{ $adds['harga'] }}</small>
                                        @endforeach
                                        @else
                                        @endif


                                        @if(!@empty($cart['type_name']))
                                        <small class="option status_order" idx="{{ $cart['type_id'] }}">{{
                                            $cart['type_name'] }}</small>
                                        @else
                                        @endif

                                        @if(!@empty($cart['discount']))
                                        @foreach($cart['discount'] as $discounts)
                                            @if(!@empty($cart['total_dis']))
                                                @php
                                                $nominalDis = 0;
                                                $nominalDis = $discounts['nominal'];
                                                $total_dis += $nominalDis;
                                                // dd($total_dis);
                                                @endphp
                                            @endif
                                            <small class="option status_order discount" idx="{{ $discounts['id'] }}">Discount {{$discounts['percent'] }}% - {{ $discounts['nominal'] }}</small>
                                        @endforeach
                                        @endif

                                        @if(!@empty($cart['catatan']))
                                        <small class="option note">{{ $cart['catatan'] }}</small>
                                        @else
                                        @endif
                                    </div>

                                </div>
                                @endforeach

                            </div>
                        </div>

                        <div class="footer-sub-total">
                            <div class="total">
                                @php
                                $sub_total = 0;
                                $sub_total = $subtotal - $total_dis;
                                @endphp
                                <div class="txt-total subtotal">Subtotal:</div>
                                <div class="txt-price-total subtotal" data-subT="" subtotal="{{ $sub_total }}">{{number_format( $sub_total, 0, '.','.') }}</div>
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
                                <div class="txt-price-total nominal-tax" style="color: grey;font-size: 13px;">{{
                                    number_format( $nominalTax, 0, '.','.') }}</div>
                            </div>
                            @endforeach

                            @php
                            $total = 0;
                            $total = $sub_total + $totalTax;
                            @endphp
                            <div class="total">
                                <div class="txt-total total">Total:</div>
                                <div class="txt-price-total total" data-total="" total="{{ $total }}">{{ number_format( $total, 0, '.','.') }}</div>
                            </div>
                        </div>

                        @elseif(session()->has('current_order'))
                        @else
                        <p class="text-center secondary text-empty">Order Empty</p>
                        @endif

                    </div>
                    <div class="act-btn-bill">
                        <div class="act-btn act1">
                            <div class="save-act-btn">Save Bill</div>
                            <div class="print-act-btn print-act"> 
                                {{-- <a href="">  --}}
                                    Print Bill
                                {{-- </a> --}}
                            </div>
                            <div class="print-act-btn split-bill" data-xid="">Split Bill</div>
                        </div>
                        <div class="act-btn act2" data-xid="">
                            <p class="txt-btn-act-bill">Pay</p>
                            @if(empty($total))

                            @else
                            {{-- <p class="txt-btn-act-bill total">{{ number_format( $total, 0, '.','.') }} </p> --}}
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="pop-payment" style="display: none">
    <div class="card-payment">
        <div class="header-card">
            <div class="txt-tittle">Payment Method</div>
            <div class="total-payment"></div>
            <div class="close">X</div>
        </div>
        <div class="content-payment">
            @foreach ( $payment as $pay )
            <div class="part-payment off" xid="{{ $pay->id }}">
                <p class="text-paymnt" data="{{ $pay->nama }}">{{ $pay->nama }}</p>
            </div>
            @endforeach
        </div>

    </div>
</div>
<div class="payment-nominal" style="display: none">
    <div class="card-payment-nominal">
        <div class="header-card">
            <div class="nominal" data-nominal="">
                <div class="nm-payment"></div>
            </div>
        </div>
        <div class="form-cash">
            <div class="txt">Cash nominal</div>
            <input type="text" placeholder="Rp 0" class="cash-nominal-input" oninput="formatRupiah(this)">
            <input type="hidden" class="convert-cash" value="0">
        </div>
        <div class="form-cash">
            <div class="txt">Changel nominal</div>
            <input type="text" placeholder="Rp 0" class="change-input" value="" oninput="formatRupiah(this)">
            <input type="hidden" class="convert-change">
        </div>
        <div class="footer-card">
            <div class="tooltip payment" style="display: block;margin-top: -22px;">cash nominal tidak boleh kosong</div>
            <button class="btn btn-selesai btn-payment" data-type="">
                Selesai
            </button>
            <div class="btn-close-part">
                <p class="text-btn-act" style="margin: 0px">Close</p>
            </div>
        </div>
    </div>
</div>
<div class="popup-name-bill" style="display: none">
    <div class="position-card">
        <div class="card-colum-input">
            <div class="header-card">
                <div class="txt-tittle"></div>
                <div class="total-payment"></div>
                <div class="close">X</div>
            </div>
            <div class="form-group">
                <label for="" class="form-label">Name Bill</label>
                <input type="text" class="form-control nameBill">
            </div>
            <div class="save-bill">
                <p class="text-btn-act" style="margin: 0px; text-align: center; ">Selesai</p>
            </div>
        </div>
    </div>
</div>

<div class="popup-qty" style="display: none">
    <div class="position-card">
        <div class="card-colum-input" style="width: 500px;height: 450px;">
            <div class="header-card">
                <div class="txt-tittle"></div>
                <div class="total-payment"></div>
                <div class="close">X</div>
            </div>
            <div class="cotent-detail" style="overflow: scroll;height: 348px;">

            </div>
            <div class="btn-selesai">
                <p class="text-btn-act oke" style="margin: 0px; text-align: center; ">Oke</p>
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

<script src="{{ asset("asset/assets/js/function_POS.js") }}"></script>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>

    var currentBillId = 0;
  
    

 $(()=>{

    var throttledButtonClick;
    var throttledButtonClickDelete;
    var canClick;
    var canClickDelete;
 
        canClick = true;
        canClickDelete = true;
       

        $('body').on('click','.item-card-menu', function(){
            var $elm = $(this);
            var idx = $elm.attr('idx');
            var $popup = $('.pop-up.additional');
            var harga = $elm.attr('target-price');

            if (harga.indexOf('.') !== -1) {
                 var harga_ = harga.replace(".", "");
                 $popup.find('.harga-total').attr('price', harga_).text(harga);
            }

            $popup.find('.harga-total').attr('price', harga).text(harga);
            $popup.find('.card-popup').attr('id-x', idx);
            $popup.find('.btn-add').attr('x-id', idx);

            console.log(idx);
            getVariasi(idx, 'add','');
            getAdditional(idx,'add','');
            $('.pop-up.additional').fadeIn();
           
        });
        $('body').on('click', '.add-custom',  function(){
           
            //menampilkan pop up additional untuk menu custom
            var $elm = $(this);
            var idx = $elm.attr('idx');
            var $popup = $('.pop-up.additional');
            var harga = $('.custom-part .nilai-custom').val();

            if (harga.indexOf('.') !== -1) {
                 var harga_ = harga.replace(".", "");
                 $popup.find('.harga-total').attr('price', harga_).text(harga);
            }

            $popup.find('.harga-total').attr('price', harga).text(harga);
            $popup.find('.card-popup').attr('id-x', idx);
            $popup.find('.btn-add').attr('x-id', idx);

            console.log(idx);
            getVariasi(idx, 'add','');
            getAdditional(idx,'add','');
            $('.pop-up.additional').fadeIn();

        }).on('click', '.act-btn-add', function(e){
            currentBillId = 0; // gunanya untuk reset state jadi customer / bill baru supaya item yang di add lewat session lagi
        })

        //klik item untuk edit
        $('body').on('click','.itm-bil',function(){
           
            var $elm = $(this);
            $('.pop-up.additional').fadeIn();
            $('.btn-add').removeAttr('disabled');
            $('.tooltip').fadeOut();


            //urutan array list item
            var arrkey =  $elm.attr('xid');
            //id dari item menu
            var id =  $elm.attr('idx');
            var idDetail =  $elm.attr('id_item_detail');

            var $popup = $('.pop-up.additional');
            var harga =  $elm.find('.price').attr('price');
            var harga_ = harga.replace(".", "");
           
            $popup.find('.harga-total').attr('price', harga).text(harga);
            $popup.find('.card-popup').attr('id-x', id).attr('key-id', arrkey).attr('id_detail', idDetail);
            $popup.find('.btn-add').attr('x-id', id).attr('key',arrkey).attr('id_detail', idDetail).text('update');
           
            
            var Adds = [];
            var dis =[];

            var qty =  $elm.find('.itm .jumlah').text();
            
            var typeSales =$elm.find('.detail-itm .status_order').attr('idx');
            var $discount = $elm.find('.detail-itm .discount');

            $discount.each(function(){
                var id = $(this).attr('idx');
                console.log(id)
                var disObj = {
                    'id': id
                };

                dis.push(disObj);
            });

            var note =$elm.find('.note').text();

            console.log(
                dis,qty, typeSales, note
            )
            
            getVariasi(id, 'edit', arrkey);
            getAdditional(id, 'edit', arrkey);

            var qty = $popup.find('.jumlah-menu input.qty').val(qty);
            var opDis = $popup.find('.option-discount input.opDis');

            dis.forEach(function(obj){
                var id = obj.id;
                opDis.each(function(){
                    var xid = $(this).attr('id');

                    if(xid == id){
                        $(this).prop('checked', true);
                    }
                })
            })

            var opType = $popup.find('.option-type');
            opType.each(function(){
                var xid = $(this).attr('idx');
                if(xid == typeSales){
                    $(this).addClass('active');
                }
            });

            $popup.find('.catatan-menu textarea').val(note);
  
        })


        $('.itmn-subcategory.discount').on('click', function(){
            $('.sub-content').addClass('hidden');
            getDataMenuDiscount();
        });

        $('.itmn-subcategory.allmenu').on('click', function(){
            $('.sub-content').addClass('hidden');
            getmenuAll();
        });

        $('.itmn-subcategory.menusub').on('click', function(){
            var idx = $(this).attr('idx');
            $('.sub-content').addClass('hidden');
            getmenuSub(idx);

        });

        

        //menampilkan detail bill yang di klik
        $('body').on('click','tr.item-bill', function(){
            var xid = $(this).attr('idx');
            console.log(xid);
            var $viewDetail = $('.view-detail-ord');
            $viewDetail.find('.drop-down').remove();
            $viewDetail.find('.detil-bil').remove();
            $viewDetail.find('.footer-sub-total').remove();
            $viewDetail.find('.text-empty').remove();
            $('.act-btn.act2').attr('data-xid', xid);

            var url = "{{ route('print-bill' ,'') }}"+'/'+ xid ;

            // $('.act-btn.act1 .print-act-btn a').attr('href', url);
            $('.act-btn.act1 .split-bill').attr('data-xid', xid);

            currentBillId = xid;
            getBill(xid);

        });

        $('.act-btn.act1 .print-act').on('click', function(e){
            var xid = $('.act-btn.act2').attr('data-xid');
            Bill(xid, 'Bill');
            Tiket(xid, 'Tiket');
            Kitchen(xid, 'Kitchen')
        })

        $('body').on('click', 'tr.server', function(){
            var xid = $(this).attr('idx');
            var url = "{{ route('data-print-server', '') }}" + '/'+ xid;

            $('.act-btn.act1 .print-act-btn a').attr('href', url);
            var $target = $('.act-btn.act1 .print-act-btn.split-bill');
            $target.addClass('server');
            $target.attr('data-xid', xid);
            getDetailBillServer(xid, 'datail_bill');
        });

        $('.payment-nominal .card-payment-nominal .footer-card .btn-selesai').on('click', function (e) {

            var $target = $('.itm');
            var $tgt_input_local = $target.find('.act-edit.local input:checked').prop('checked', true);
            var $tgt_input_server = $target.find('.act-edit.server input:checked').prop('checked', true);
            var type = $(this).attr('data-type');

            console.log($tgt_input_local, $tgt_input_server);
            if ($tgt_input_local.length > 0) {
                console.log('pyment split bill Local');
                splitBill($(this), 3);
            }else{
                console.log('pyment to local')
                payment($(this), 'local');
            }


            // clearSession();
        });

        $('.print-act-btn.split-bill').on('click', function(){
            var idx = $(this).attr('data-xid');
            var $server = $('.print-act-btn.split-bill.server');
            if($server.length > 0){
                getDataDetailSplitServer(idx);
            }else{
                getDataDetailSplit(idx);
            }

        })

        $('body').on('click', `.act-edit .check-edit`, function(e){
           splitBill($(this), 1);
           $('.pop-up.additional').hiden();
        });

        $('.popup-qty').on('click', '.oke', function(){
             $('.popup-qty').fadeOut();
             splitBill($(this), 2);
        }).on('click', '.header-card .close', function(){
             $('.popup-qty').fadeOut();
        })

        

        $('.card-popup .btn-add').on('click', function(){
            var idx = $(this).attr('x-id');
            var key = $(this).attr('key');
            if (key == '') {
                additional(idx, 'add');
                    
            } else {
                additional(idx,'edit');
            }

        });

         $('body').on('click','.Varian-menu .option-varian', function(){
            var $elm =$(this).addClass('active');
            var harga = $elm.find('.harga-varian').text();
            var nilai = harga.replace(/\./g, '');
            var $price = $('.header-card-popup .harga-total');
            $price.text(harga).attr('price', nilai);
            checkVariantSelection()
        });


        //delete item order
        $('body').on('click', '.itm .hapus-menu-order', function(e){
            checkVariantSelection()
           
                var id = $(this).attr('idx');

                var $elmentcart = $(this).parents('.itm-bil');

                var konfirmasi = confirm('sure you want to delete the menu item?');

                if(konfirmasi){
                    //console.log('tes hapus')
                    deleteItem(id, $elmentcart);
                }
        });

        //delete session
        $('.menu-delete').on('click', function(){
            clearSession();
        })
    
        $('.popup-name-bill .card-colum-input .save-bill').on('click', function(e){
            POSorder();
        });

        
            //function payment post
        function payment($elment, type){
            var $targetpayment = $('.pop-payment');
            var paymentId = $targetpayment.find('.content-payment .part-payment.active').attr('xid');
            var xidOrder = $('.act-btn-bill .act-btn.act2').attr('data-xid');
            let $tgrPayment = $('.payment-nominal .card-payment-nominal');
            var cash = $tgrPayment.find('.form-cash input.convert-cash').val();
            var change_ = $tgrPayment.find('.form-cash input.convert-change').val();
            var total = $('.txt-price-total.total').attr('data-total');
            if(total !== undefined && total !== null && total !== ""){
                console.log('data ada');
               
            }else{
                total = $('.txt-price-total.total').text();
                total = total.replace(/\./g, '');
            }
            

            console.log(paymentId)
            if(xidOrder !== undefined && xidOrder !== null && xidOrder !== ""){
                
                console.log(postData);
                if(paymentId === null || paymentId === "" || paymentId === undefined){
                    alert('Payment method is empty');
                    
                }else{
                    var postData = {
                        _token : "{{ csrf_token() }}",
                        id: xidOrder,
                        Idpayment: paymentId,
                        cash: cash,
                        change_ : change_,
                        total: total,
                    }
                    
                    $.post("{{ route('pyment-order') }}", postData).done(function(data){
                        // alert('Done');
                        setTimeout(function(){
                            $('.popup-print .form-group p').text('order is processed and has been update');
                            $('popup-print').fadeIn();
                        },1000)
                            $('.pop-payment').hide();
                            $('.payment-nominal .card-payment-nominal').val('');
                            $('.form-cash input.change-input').val('');
                            $('.payment-nominal').hide();
                           
                            clearSession()
                            console.log($target1, $target2);

                            if(data.error){
                                console.log(data.error)
                                return;
                                LogActivity('error payment', data)
                            }
                            LogActivity('success payment', data)
                        }).fail(function(data){
                                console.log('error', data);
                                alert('Payment tidak sesuai')
                                LogActivity('error payment', data)
                        });

                    if(type == 'server'){
                        var idAdmin = $('.main-sidebar .info.admin').attr('data-admin');
                        console.log('idAdmin');
                        postData['idUser'] = idAdmin;
                        $.post("https://admin.goodfellas.id/api/payment-POS", postData).done(function(data){
                        // alert('Done');
                        setTimeout(function(){
                            $('.popup-print .form-group p').text('order is processed and has been update');
                            $('popup-print').fadeIn();
                        },1000)
                            getDetailBillServer(xidOrder, 'detailprint_bil');
                        }).fail(function(data){
                                console.log('error', data);
                        });
                    }
                }
               

            }else{
                POSorder()
            }

            Pusher.logToConsole = true;

            var pusher = new Pusher('1d53fe58e629925b2d3c', {
            cluster: 'ap1'
            });

            var channel = pusher.subscribe('my-channel');
            channel.bind('my-event', function(resp) {
                notify(resp.data)
            });

        }

        //function pusher notifikasi
        function notify(data){
            Toastify({
                text: data.message,
                duration: 10000,
                close: true,
                gravity: "top", // `top` or `bottom`
                stopOnFocus: true, // Prevents dismissing of toast on hover
                style: {
                background: "linear-gradient(to right, #00b09b, #96c93d)",
                },
            }).showToast();
        }

        //function clear session
        function clearSession(){
            let URL = "{{ route('sessionClear') }}";
            $.ajax({
                url: URL,
                method: 'GET',
                success: function(result){
                    var $detaiOrder = $('.detail-order');
                    var $payment = $('body .pop-payment .content-payment');
                    var $actBtn1 = $('.act-btn.act1');
                    var $popUpSplit = $('.popup-qty');
                    var $paymentNominal = $('.payment-nominal');
                    
                    currentBillId = 0;
                    console.log('clear session')

                     $('.popup-name-bill input.nameBill').val('');
                     $('.popup-name-bill .total-payment').text('');
                     $('.act-2 .txt-btn-act-bill.total').text('');
                     $('.act-btn-bill .act-btn.act2').attr('data-xid','');
                     $detaiOrder.find('.view-detail-ord').empty();
                     $payment.find('.part-payment.active').addClass('off').removeClass('active');
                     $actBtn1.find('.print-act-btn.split-bill').attr('data-xid', '');
                     $popUpSplit.find('.cotent-detail').empty();
                     $popUpSplit.find('.txt-tittle').empty();
                     $popUpSplit.find('.total-payment').empty();
                     $paymentNominal.find('.nominal').attr('data-nominal', '');
                     $paymentNominal.find('.nm-payment').empty();
                     $paymentNominal.find('input.convert-cash').val('');
                     $paymentNominal.find('input.cash-nominal-input').val('');


                }
            }).fail(function(result){
                console.log(result);
            });
        }

        //get menu discount
        function getDataMenuDiscount(){
            let URL = "{{ route('getMenuDiscount')}}";
            $.get(URL, function(result){
                $(result).appendTo('.panel[data-panel="panel2"] .content-menu');
                $('.menu_discount').addClass('active')
            }).fail(function(result){
                console.log(result);
            })
        }

        //get all menu
        function getmenuAll(){
            let URL = "{{ route('allmenu')}}";
            $.get(URL, function(result){
                $(result).appendTo('.panel[data-panel="panel2"] .content-menu');
                $('.all-menu').addClass('active')
            }).fail(function(result){
                console.log(result);
            })
        }
        //get menu sub
        function getmenuSub(id){
            let URL = "{{ route('subMenu', '')}}"+'/'+id;
            $.get(URL, function(result){
            $(result).appendTo('.panel[data-panel="panel2"] .content-menu');
                $('.menuSub').addClass('active')
            }).fail(function(result){
                console.log(result);
            })
        }
        //get menu kat
       
        // get data variasi
        function getVariasi(idx,type,key){
            console.log('get varian menu id:', idx);

            $.ajax({
                url: "{{ route('variasi-menu') }}",
                data: {id_menu: idx},
                method:'GET',
                type: 'json',
                success: function(result){
                    var $target = $('.Varian-menu');
                    $target.html('');
                    if(result.data === null || result.data.length === 0){
                        $target.hide();
                        console.log(result.data, 'data tidak ada')
                    }else{
                        checkVariantSelection()
                        console.log('data ada', result.data)
                        $target.append('<div class="name-additional">Varian| Choose one</div>');
                        $.each(result.data, function(key, value){
                           
                            $target.append(
                               ' <div class="option-varian" idx="'+value.id+'">'+
                                    '<p class="varian">'+ value.nama+'</p>'+
                                    '<p class="harga-varian" harga='+value.harga+'>' +parseInt(value.harga).toLocaleString("id-ID")+ '</p>'+

                                '</div>'
                            );
                            
                        });
                        $target.show();

                        if(type == 'edit'){
                          
                             var $elm = $('body .itm-bil[xid="' + key + '"]');
                             var varian = $elm.find('.detail-itm .varian-op').attr('id_var');
                             var opVar = $target.find('.option-varian');
                                console.log(opVar, 'varian',varian);
                                opVar.each(function(){
                                    var idx = $(this).attr('idx');
                                    console.log(idx)
                                    if(idx == varian){
                                        $(this).addClass('active');
                                        checkVariantSelection()
                                        
                                    }
                                });
                        }
                    }

                },
                done:function(){

                }
            });
        }

        // get data additional
        function getAdditional(id,type,key){
            console.log('get additional menu id:', id);

            $.ajax({
                url: "{{ route('option-add') }}",
                data: {id_menu: id},
                method:'GET',
                type: 'json',
                success: function(result){
                    var $target = $('.additional-menu');
                    $target.html('');
                    if(result.data === null || result.data.length === 0){
                        $target.hide();
                        console.log(result.data, 'data tidak ada additional')
                    }else{
                        console.log('data ada additional', result.data)
                        $target.append('<div class="name-additional"> Additional|Select multiple</div>');
                        $.each(result.data, function(key, value){
                            $target.append(
                               ' <div class="option-menu-additional" idx="'+value.id+'">'+
                                    '<p class="nama">'+ value.name+'</p>'+
                                    '<p class="harga" harga='+value.harga+'>' +parseInt(value.harga).toLocaleString("id-ID")+ '</p>'+
                                '</div>'
                            );
                        });
                        $target.show();
                        if (type == 'edit'){

                            var $elm = $('body .itm-bil[xid="' + key + '"]');
                            var Adds = [];
                            var $adds = $elm.find('.detail-itm .add-op');
                            $adds.each(function(){
                                var $elm = $(this);
                                var id = $elm.attr('id_adds');
                                var obj = {
                                    'id' : id
                                };
                                Adds.push(obj);
                            });

                            var opAdds = $target.find('.option-menu-additional');
                            Adds.forEach(function(obj){
                                var id = obj.id;
                                opAdds.each(function(){
                                    var idx = $(this).attr('idx');
                                    if(idx == id){
                                        $(this).addClass('active');
                                    }
                                })
                            })
                        }
                    }

                },
                done:function(){

                }
            });
        }

        // custom additional item menu order dan session
        function additional(idx,type){
            var $varian = $('.option-varian.active');
            var $additional = $('.option-menu-additional.active');
            var $dis = $('.option-discount input:checked').prop('checked', true);
            var $add_delete = $('.option-menu-additional.delete');
            var $dis_delete = $('.option-discount input.opDis.delete');

            var qty = $('.jml-menu input.qty').val();
            var catatan = $('.catatan-menu textarea').val()
            
            var id_type_sales = $('.option-type.active').attr('idx');

            if(id_type_sales === undefined && id_type_sales === "" && id_type_sales === null){
               id_type_sales = '4';
            }
            var type_sales = $('.option-type.active .nama-option').text();

            var key = $('.card-popup .btn-add').attr('key');
            var idDetail = $('.card-popup .btn-add').attr('id_detail');

            console.log('salseltype', id_type_sales , type_sales);
            console.log('catatan ',catatan);
            console.log('jumlah menu ',qty);

            var Adds = [];
            var dis =[];
            var Add_delete = [];
            var dis_delete =[];

            var var_id = $varian.attr('idx');
            var var_name = $varian.find('.varian').text();
            var var_harga = $varian.find('.harga-varian').attr('harga');

            //mengambil data additional yang di pilih 
            $additional.each(function(){
                var $boxAdd = $(this);
                var id = $boxAdd.attr('idx');
                var name = $boxAdd.find('.nama').text();
                var harga = $boxAdd.find('.harga').attr('harga');
                var objAdds = {
                    'id': id,
                    'nama' : name,
                    'harga' : harga,
                    'id_detail': idDetail
                };
                Adds.push(objAdds);
            });

            
            
            // menghitung additional yang sudah dipilih lalu di hapus
            $add_delete.each(function(){
                var $tgt = $(this);
                var id = $tgt.attr('idx');
                var objAddsDel = {
                    'id' : id,
                    'id_detail' : idDetail
                };

                Add_delete.push(objAddsDel);
            });
            // menghitung discount yang sudah di pilih lalu di hapus
            $dis_delete.each(function(){
                var $tgt = $(this);
                var id = $tgt.attr('id');
                var objDeleteDis = {
                    'id' : id,
                    'id_detail' : idDetail
                };

                dis_delete.push(objDeleteDis);
            });

            console.log('ini additional', Adds);
            console.log('ini discount', dis);

            // menghitung total additional 
            var totalHarga = 0;
            for (var i = 0; i < Adds.length; i++) {
                var harga = Adds[i].harga;
                harga = harga.replace(/\./g, '');
                totalHarga += parseInt(harga)
            }
            console.log('total additional ',totalHarga);

            // mengambil harga menu item
            var harga_menu  = $('.header-card-popup .harga-total').text();
            harga_menu = harga_menu.replace('.', '');

            // menghitung harga total menu item di tambah dengan total additional
            var hargaTotal = parseInt(harga_menu) + parseInt(totalHarga);
            console.log('harga total ', hargaTotal);

            // menjumlah harga total dari harag + additional dengan qty
            var total = parseInt(hargaTotal) * parseInt(qty);

            $dis.each(function(index) {
                var $targetdis = $(this);
                const id = $targetdis.attr('id');
                const rate = $targetdis.attr('rate');
                
                // Menghitung nominal dengan membagi rate 100 terlebih dahulu
                var nominal = total * (rate / 100);
                
                var objDiscount = {
                    'id': id,
                    'percent': rate,
                    'id_detail': idDetail,
                    'nominal': nominal
                };

                dis.push(objDiscount);
                
                // Mengurangi total dengan nominal yang sudah dihitung untuk diskon berikutnya
                total -= nominal;
            });
            // menjumlah total nominal discount
            var total_discount = 0;
            for (var i = 0; i < dis.length; i++) {
                var rate = dis[i].percent;
                total_discount += parseInt(rate)
            }
            console.log('total discount ',total_discount);


            var postData ={
                    _token: "{{ csrf_token() }}",
                    id : idx,
                    key: key,
                    qty : parseInt(qty),
                    harga: parseInt(harga_menu),
                    harga_addtotal: parseInt(totalHarga),
                    variasi: var_id,
                    var_name: var_name,
                    additional: Adds,
                    discount: dis,
                    catatan : catatan,
                    id_type_sales: id_type_sales,
                    sales_name: type_sales,
                    total_dis: parseInt(total_discount),

            }
            console.log(postData);
            if(currentBillId){
                // ini untuk masukin product ke bill yang sudah ada
                postData["target_order"] = currentBillId;
                // jika item yang di edit maka yang di eksekusi adalah edit
                if(type == 'edit'){
                    postData["target_detail"] = idDetail;
                    postData["adds_delete"] = Add_delete;
                    postData["dis_delete"] = dis_delete;
                }
               
            
                    //disini seharusnya post lagi , route kalau mau dibedakan bisa dibuat
                    $.post("{{ route('billModify') }}", postData).done(function(data){
                        if(data.success === 0){
                                alert(data.message);
                            
                            }else{
                                if(data.error){
                                    console.log(data.error);
                                    return;
                                    LogActivity('Error modify bill add item', data)
                                }
                            
                            
                                LogActivity('modify bill add item', data)
                                // POSorder() ;                           //location.reload();
                                var $viewDetail = $('.view-detail-ord');
                                $viewDetail.find('.drop-down').remove();
                                $viewDetail.find('.detil-bil').remove();
                                $viewDetail.find('.footer-sub-total').remove();
                                $('.option-varian').removeClass('active');
                                $('.option-menu-additional').removeClass('active');
                                $('.option-discount input:checked').prop('checked', false);
                                $('.jml-menu input.qty').val('1');
                                $('.catatan-menu textarea').val('')
                                $('.card-popup').attr('id-x','').attr('key-id', '');
                                $('.btn-add').attr('x-id','').attr('key','').attr('id_detail', '').text('add');
                                $('.pop-up.additional').fadeOut();
                                getBill(currentBillId);
                                $('.pop-up.additional').fadeOut();
                                $('.btn-add').removeAttr('disabled');
                                $('.tooltip').fadeOut();
                                var Option = $('.option-type');
                                $('.option-type.active').removeClass('active');
                                $('.popup-name-bill input.nameBill').val('');
                                Option.each(function() {
                                    if ($(this).attr('idx') === '4') {
                                        // Add 'active' class to the element with idx '4'
                                        $(this).addClass('active');
                                    }
                                });
                            
                            }
                    }).fail(function(data){
                            console.log('error', data);
                            // addLogLocalStorage('error_Modify_bill', 'add Item Modify', data);
                            LogActivity('error modify bill add item', data)
                    });
                
                

            }else{
                var url='';

                if(type == 'add'){
                    url = "{{ route('addOrder') }}";
                 }else{
                    url = "{{ route('edit-item') }}";
                 }
                $.post(url, postData).done(function(data){
                        if(data.success === 0){
                            alert(data.message);
                        }else{
                           
                            if(type == 'add'){
                                if(data.error){
                                    console.log(data.error)
                                    return;
                                    LogActivity('error add item', data)
                                }
                                LogActivity('success add item', data)
                            }else{
                                if(data.error){
                                    console.log(data.error)
                                    return;
                                    LogActivity('error edit item', data)
                                }
                                LogActivity('edit item', data)
                            }
                            var $viewDetail = $('.view-detail-ord');
                            $viewDetail.empty();
                            $('.option-varian').removeClass('active');
                            $('.option-menu-additional').removeClass('active');
                            $('.option-discount input:checked').prop('checked', false);
                            $('.jml-menu input.qty').val('1');
                            $('.catatan-menu textarea').val('')
                            $('.card-popup').attr('id-x','').attr('key-id', '');
                            $('.btn-add').attr('x-id','').attr('key','').attr('id_detail', '').text('add');
                            $('.btn-add').removeAttr('disabled');
                            $('.tooltip').fadeOut();
                            var Option = $('.option-type');
                            $('.option-type.active').removeClass('active');

                            Option.each(function() {
                                if ($(this).attr('idx') === '4') {
                                    // Add 'active' class to the element with idx '4'
                                    $(this).addClass('active');
                                }
                            });
                            getSessionOrder()
                           
                        }
                }).fail(function(data){
                        console.log('error', data);
                        if(type == 'add'){
                            //   addLogLocalStorage('ErrorAdd_Item', 'add Item', data);
                            LogActivity('error add item', data)
                        }else{
                            //   addLogLocalStorage('Erroredit_item', 'edit Item ', data);
                            LogActivity('error edit item', data)
                        }
                          
                 });
            }

        }

       
        function checkVariantSelection() {
       
            // Cek apakah ada opsi varian yang aktif
            if ($('.option-varian.active').length === 0) {
                // Nonaktifkan tombol add dan tampilkan tooltip
                $('.btn-add').attr('disabled', 'disabled');
                $('.tooltip').fadeIn();
            } else {
                // Aktifkan tombol add dan sembunyikan tooltip
                $('.btn-add').removeAttr('disabled');
                $('.tooltip').fadeOut();
            }
             
        }
        function checkSelesTypeSelection() {
       
            // Cek apakah ada opsi varian yang aktif
            if ($('.option-type.active').length === 0) {
                // Nonaktifkan tombol add dan tampilkan tooltip
                $('.btn-add').attr('disabled', 'disabled');
                $('.tooltip').fadeIn();
            } else {
                // Aktifkan tombol add dan sembunyikan tooltip
                $('.btn-add').removeAttr('disabled');
                $('.tooltip').fadeOut();
            }
             
        }

        function getSessionOrder(){
            let URL = "{{ route('view_detail_session')}}";
            $.get(URL, function(result){
                if(result.error){
                    console.log(result.error);
                    return;
                   LogActivity('error get session', result)
                }
               
                //    addLogLocalStorage('get_Session', 'session detail', result);
                LogActivity('get session', result)

                $('.detail-order .view-detail-ord').html(result.view);
                $('.pop-up.additional').fadeOut();
            }).fail(function(result){
                console.log('error',result);
                // addLogLocalStorage('Error_get_Session', 'session detail', result);
                LogActivity('error get session', result)
            })
        }

        //function delete item pos
        function deleteItem(id, $elm){
            var postData = {
                _token : "{{ csrf_token() }}",
                id : id,
            }

            if(currentBillId){
                $.post("{{ route('Delete-item') }}", postData).done(function(data){
                    if(data.success === 0 ){
                        alert(data.message);
                        LogActivity('Error delete itm bill', data)
                    }else{


                        LogActivity('success delete item bill', data)

                        $('.pop-up.additional').hide();
                        
                        
                        var dataDelete = {
                              _token : "{{ csrf_token() }}",
                               id_order : data.data.id_order,
                               id: data.data.id
                        }
                        console.log('data delete', data)
                        $.post("{{ route('print_item_delete_thermal') }}", dataDelete).done(function(data){
                            if(data.success === 0 ){
                                alert(data.message);
                            }else{
                               console.log('data print delete',data);
                                
                                $.each(data.detailItem, function(index, value){
                                    var id = value.id;
                                    var id_order = value.id_order;

                                    throttledButtonClickDelete(id, id_order, $elm);

                                    console.log('data each', id, id_order)
                                })
                                
                              
                                //var nameFile = data.data;
                                
                                //GetPrint('item_delete', nameFile, );

                                $('.option-varian').removeClass('active');
                                $('.option-menu-additional').removeClass('active');
                                $('.option-discount input:checked').prop('checked', false);
                                $('.jml-menu input.qty').val('1');
                                $('.catatan-menu textarea').val('')
                                $('.option-type.active').removeClass('active');
                                $('.option-type').removeClass('active');
                                var Option = $('.option-type');
                                $('.card-popup').attr('id-x','').attr('key-id', '');
                                $('.btn-add').attr('x-id','').attr('key','').attr('id_detail', '').text('add');
                                $('.popup-name-bill input.nameBill').val('');
                                Option.each(function() {
                                    if ($(this).attr('idx') === '4') {
                                        // Add 'active' class to the element with idx '4'
                                        $(this).addClass('active');
                                    }
                                });
                                
                            }
                        }).fail(function(data){
                            console.log('error',data);
                            // addLogLocalStorage('ErrorSessionItemDelete', 'delete item', data);
                            LogActivity('error session Item delete', data)
                        });
                      
                    }
                }).fail(function(data){
                    console.log('error',data);
                    // addLogLocalStorage('ErrorSessionItemDelete', 'delete item', data);
                    LogActivity('error  item delete', data)
                });
            }else{

                $.post("{{ route('item.delete') }}", postData).done(function(data){
                    if(data.success === 0 ){
                        alert(data.message);
                    }else{
                        if(data.error){
                            console.log(data.error);
                            return;
                            LogActivity('Error session delete itm', data)
                        }
                        LogActivity('Success session delete item', data)
                        $elm.remove();
                        //$(this).attr('data-notify',data['count']);
                        var $viewDetail = $('.view-detail-ord');
                        $viewDetail.empty();
                            
                        getSessionOrder()
                    }
                }).fail(function(data){
                    console.log('error',data);
                    // addLogLocalStorage('ErrorSessionItemDelete', 'delete item', data);
                    LogActivity('error session delete item', data)
                });
            }

        }

        //print-item delete

        function updateButtonClickStatusDelete() {
            canClickDelete = true;
                
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

        throttledButtonClickDelete = throttle(function(id, id_order, $elm) {
              
                if (!canClickDelete) {
                    $('.popup-print').fadeIn(); 
                    return;
                }
                
                canClickDelete = false;
                
                $('.popup-print').fadeIn(); 
                console.log("Print sedang diproses...");
                
                setTimeout(function() {

                    var dataDelete = {
                         _token : "{{ csrf_token() }}",
                        id_order : id_order,
                        id: id
                    }
                     
                    console.log('data yang di delete',dataDelete);
                    $('.popup-print').fadeOut(); 
                    console.log("Print selesai.");
                    $elm.remove();


                   

                    LogActivity('item delete', dataDelete);
                    
                    $.post("{{ route('item_delete') }}", dataDelete).done(function(data){
                        if(data.success === 0 ){
                            alert(data.message);
                        }else{
                           var $viewDetail = $('.view-detail-ord');
                            $viewDetail.empty();

                            getBill(dataDelete.id_order);
                            
                        }
                    }).fail(function(data){
                        console.log('error',data);
                        LogActivity('error item delete', data)
                    });
                    updateButtonClickStatusDelete();
                }, 1000);
        }, 1000);
          
        // function GetPrint(type, FileName){
        //     var URL = 'http://192.168.88.22:3377/print-file?type='+type+'&filename='+FileName;
        //     $.get(URL, function(result){
        //         console.log(result.stdout);
               
        //     }).fail(function(result){
        //         console.log(result);
        //     })
        // }

        //post Order
        function POSorder(){
            var $targetpayment = $('.pop-payment');
            var nameBill = $('.popup-name-bill .nameBill').val();
            var table = $('.drop-down input.nomer-meja').val();
            var TargetSub = $('.footer-sub-total .txt-price-total.subtotal').text();
            var subtotal = TargetSub.replace(/\./g, '');
            var total = $('.footer-sub-total .txt-price-total.total').attr('data-total');
            if(total !== undefined && total !== null && total !== ""){
                console.log('data ada');
                if(currentBillId){
                    total = $('.txt-price-total.total').text();
                    total = total.replace(/\./g, '');
                }else{
                     console.log('data tidak ada update');
                }
            }else{
                total = $('.txt-price-total.total').text();
                total = total.replace(/\./g, '');
            }
            var paymentId = $targetpayment.find('.content-payment .part-payment.active').attr('xid');
            if(paymentId !== undefined && paymentId !== null && paymentId !== ""){
                let $tgrPayment = $('.payment-nominal .card-payment-nominal');
                var cash = $tgrPayment.find('.form-cash input.convert-cash').val();
                var change_ = $tgrPayment.find('.form-cash input.convert-change').val();
            }
        
            var taxes = []


            $('.taxes').each(function(){
                var $taxBox = $(this);
                var id = $taxBox.attr('idx');
                var nominal = $taxBox.find('.nominal-tax').text();
                var taxObj = {"id": id, "nominal": nominal};
                taxes.push(taxObj)
            });

            //console.log(taxes);
            //console.log(table);

            var postData = {
                _token: "{{ csrf_token() }}",
                nomer : table,
                nama : nameBill,
                subtotal: subtotal,
                total: total,
                taxes: taxes,
            }

            if(currentBillId){

                postData["target_order"] = currentBillId;

                $.post("{{ route('updateorder') }}", postData).done(function(data){
                    console.log(data);
                    if(data.error){
                        console.log(data.error);
                        return;
                        LogActivity('Error edit order post', data)
                    }

                    LogActivity('edit order post', data)
                    setTimeout(function(){
                            $('.popup-print .form-group p').text('order is processed and has been update');
                            $('.popup-print').fadeIn();
                    },1000)
                    // alert('order is processed and has been update');
                     $('.popup-name-bill').hide();
                    currentBillId = 0;
                    const xid = data.data.id;
                    Tiket(xid, 'Tiket');
                    clearSession()

                }).fail(function(err){
                    console.log(err);
                    alert('Something is incomplete. Check again with Barista');
                    
                    // addLogLocalStorage('error', 'edit order', err);
                    LogActivity('edit error', err)
                })
            }else{
                var URL = "{{ route('POS-Order') }}";
                if(paymentId !== undefined && paymentId !== null && paymentId !== ""){
                    postData['Idpayment'] = paymentId;
                    postData['cash'] = cash;
                    postData['change_'] = change_;

                 }
                $.post(URL, postData).done(function(data){
                    console.log(data);
                    if(data.error){
                        console.log(data.error);
                        return;
                        LogActivity('Error order post', data)
                    }
                    LogActivity('order post', data)
                   
                    //setTimeout(function(){
                    //        $('.popup-print .form-group p').text('order is processed');
                    //        $('.popup-print').fadeIn();
                    //    },1000)
                    $('.popup-name-bill').hide();
                    //$('.popup-name-bill input.nameBill').val('');
                    //$('.popup-name-bill .total-payment').text('');
                    //$('.act-2 .total').text('');
                    //var $detaiOrder = $('.detail-order');
                    //$detaiOrder.find('.view-detail-ord').empty();
                  
                    if(paymentId !== undefined && paymentId !== null && paymentId !== ""){
                        // alert('Done');
                        setTimeout(function(){
                            $('.popup-print .form-group p').text('Done');
                            $('.popup-print').fadeIn();
                        },1000)
                        $('.pop-payment').hide();
                        $('.payment-nominal').hide();
                        const id = data.data.order.id;
                        Bill(id,'Bill')
                        Tiket(id, 'Tiket');
                        // getBill(id);
                        clearSession();
                       
                        
                    }else{
                        const id = data.data.order.id;
                        // $('.act-btn.act2').attr('data-xid',id);
                        Tiket(id, 'Tiket');
                        // getBill(id);
                        currentBillId = 0;
                        clearSession();
                        
                    }
                }).fail(function(err){
                    console.log(err);
                    alert('Something is incomplete. Check again with Barista');
                    // addLogLocalStorage('ErrorOrderPost', 'post Order', data);
                    LogActivity('error order post', data)
                })
            }


        }


        // print bill to thermal 
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
                },1000)
                throttledButtonClick();
                    //console.log(type);
                updateLastPrint(id, type)
                console.log('print bill', result)
                // addLogLocalStorage('PrintBill', 'delete item', result);
                LogActivity('print bill ', result)
            }).fail(function(result){
                LogActivity('error print bill', result)
            })
        }
        function Tiket(id, type){
            var URL = '{{route("print-ticket-thermal", "")}}' +'/' + id;
            const data = {
                _token: "{{csrf_token()}}",
                type: type
            }
            $.post(URL, data).done(function(result){
                setTimeout(function(){
                    $('.popup-print  p').text('Print in prosess...');
                    $('popup-print').fadeIn();
                },1000)
                console.log('print tiket', result)
                throttledButtonClick();
                    //console.log(type);
                updateLastPrint(id, type);
                Kitchen(id, 'Kitchen');
               
                LogActivity('print tiket', result)
            }).fail(function(result){
                LogActivity('error print tiket', result)
                console.log('error tiket', result);
            })
        }

        function Kitchen(id, type){
            var URL = '{{route("print-kitchen-thermal", "")}}' +'/' + id;
            const data = {
                _token: "{{csrf_token()}}",
                type: type
            }
            $.post(URL, data).done(function(result){
                setTimeout(function(){
                    $('.popup-print  p').text('Print in prosess...');
                    $('popup-print').fadeIn();
                },1000);
                
                throttledButtonClick();
                    //console.log(type);
                updateLastPrint(id, type)
                // addLogLocalStorage('print kitchen', 'delete item', result);
                LogActivity('print kitchen', result)
            }).fail(function(result){
                LogActivity('error print kitchen', result)
                console.log('error kitchen', result);
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
                    $('.popup-print').fadeIn(); 
                    return;
                }
                
                canClick = false;
                
                $('.popup-print').fadeIn(); // Menampilkan pop-up print sedang diproses
                console.log("Print sedang diproses...");
                
                setTimeout(function() {
                    $('.popup-print').fadeOut(); // Menyembunyikan pop-up setelah selesai
                    console.log("Print selesai.");
                    updateButtonClickStatus();
                }, 1000);
        }, 1000);

        function updateLastPrint(xid, type){
            const URL = "{{ route('update_last_print', '') }}"+'/'+ xid;
            var dataPost = {
                _token: "{{csrf_token()}}",
                print: type
            }

            $.post(URL, dataPost).done(function(data){
                // alert('Done');
                console.log(data)
            }).fail(function(data){
                    console.log('error', data);
            });
        }  



        //print bill
        function printBill(id){
            var url = "{{ route('print-bill' ,'') }}"+'/'+id ;

             $.get(url, function(result){
               console.log('berhasil');
            }).fail(function(result){
                console.log(result);
            })
        }

        //function get Bill
        function getBill(idx){
            let URL = "{{ route('ref-detail-bil') }}";
            $.ajax({
                url: URL,
                data: {refId: idx},
                method: 'GET',
                success: function(result){
                    if (result.error) {
                        console.log(result.error);
                        return;
                        LogActivity('error get bill', result)
                    }

                    // Simpan data ke localStorage
                    //  addLogLocalStorage('success', 'getBill', result)
                    LogActivity('success get bill', result)
                    console.log("get bill",result);
                    $('.popup-name-bill input.nameBill').val(result.data.Bill.name_bill);
                    // Bersihkan dan tampilkan data pada halaman
                    $('.part-order').empty();
                    $('.detail-order .view-detail-ord').html(result.view);
                    $('.pop-daftar-bill').hide();
                    currentBillId = idx;
                    

                }
            }).fail(function(result){
                console.log(result);
                LogActivity('Error Get bill', result)
            });
        }

    
        function getDataDetailSplit(idx){
            let URL = "{{ route('bill-split') }}";
            $.ajax({
                url: URL,
                data: {refId: idx},
                method: 'GET',
                success: function(result){
                    $('.popup-qty').fadeIn();
                     $('.cotent-detail').empty();
                     $(result).appendTo('.cotent-detail').show();

                }
            }).fail(function(result){
                console.log(result);
            });
        }
        function getDataDetailSplitServer(idx){
            let URL = "{{ route('bill-split-server', '') }}" +'/'+ idx;
            $.ajax({
                url: URL,
                method: 'GET',
                success: function(result){
                    $('.popup-qty').fadeIn();
                     $('.cotent-detail').empty();
                     $(result).appendTo('.cotent-detail');

                }
            }).fail(function(result){
                console.log(result);
            });
        }

        function splitBill($elment, type){
            var nomerMeja = $('.nomer-meja').val();
            var id = $elment.attr('id-item');
            var $target = $('.itm');
            var $itm  = $target.find('.act-edit input:checked').prop('checked', true);
            var subTotal = $('.footer-sub-total .txt-price-total.subtotal ').attr('subtotal');
            var $elm_pb = $('.txt-price-total.PB1');
            var $elm_service = $('.txt-price-total.Service');
            var total = $('.footer-sub-total .txt-price-total.total ').attr('total');
            var $tgt = $itm.closest('.itm-bil');
            var $adds = $tgt.find('.detail-itm .option.add-op');
            var $dis = $tgt.find('.option.discount');

            var $targetpayment = $('.pop-payment');
            var paymentId = $targetpayment.find('.content-payment .part-payment.active').attr('xid');
            let $tgrPayment = $('.payment-nominal .card-payment-nominal');
            var cash = $tgrPayment.find('.form-cash input.convert-cash').val();
            var change_ = $tgrPayment.find('.form-cash input.convert-change').val();

            {{--  total = total.replace(/\./g, '');
            subTotal = subTotal.replace(/\./g, '');  --}}

            var itms = [];
            var Adds = [];
            var dis = [];

            $adds.each(function(){
                var $elm = $(this);
                var id = $elm.attr('id_adds');
                var nominal = $elm.attr('nominal');
                var $perent = $elm.closest('.itm-bil');
                var qty = $perent.find('.control-qty input.qty').val();
                var objAdd = {
                    id: id,
                    nominal: nominal,
                    qty: qty
                }

                Adds.push(objAdd);
            })

            console.log(Adds);

            var TotalAdds = 0;

            for(var i = 0; i< Adds.length; i++){
                var harga = Adds[i].nominal;
                var qty = Adds[i].qty;

                var jumlahhargaAdds = parseInt(harga) * parseInt(qty) ;
                TotalAdds += jumlahhargaAdds;
            }

            console.log(TotalAdds);

            $dis.each(function(){
                var $elm = $(this);
                var xid = $elm.attr('xid-dis');
                var rate = $elm.attr('dis');

                var objDis = {
                    id: xid,
                    rate: rate,
                }

                dis.push(objDis);

            })

            var TotalRateDis = 0;
            for(var i =0; i< dis.length; i++){
                var rate = dis[i].rate;

                TotalRateDis += parseInt(rate);
            }

            console.log(dis);
            console.log(TotalRateDis);

            $itm.each(function(){
                var $elm = $(this);
                var id = $elm.attr('id-item');
                var $perent = $elm.closest('.itm-bil');
                var price  = $perent.find('.price').attr('price');
                var discount = $perent.find('.discount').attr('nominal-dis');
                var qty = $perent.find('.control-qty input.qty').val();
                var $adds = $perent.find('.detail-itm .option.add-op');
                var $dis = $perent.find('.option.discount');
                var itmAds = [];
                var itmsDis=[];

                $adds.each(function(){
                    var $tgtAds = $(this);
                    var hargaAds = $tgtAds.attr('nominal');
                    var id_adds = $tgtAds.attr('id_adds');
                    var objItm = {
                        id: id_adds,
                        hargaAds: hargaAds
                    }

                    itmAds.push(objItm);
                })

                $dis.each(function(){
                    var $elm = $(this);
                    var xid = $elm.attr('xid-dis');
                    var rate = $elm.attr('dis');

                    var objDis = {
                        id: xid,
                        rate: rate,
                    }

                    itmsDis.push(objDis);

                })

                var ItemTotalAdds = 0;
                for(var i = 0; i< itmAds.length; i++){
                    var harga = itmAds[i].hargaAds;
                    //var jumlahhargaAdds = parseInt(harga) * parseInt(qty) ;
                    ItemTotalAdds += parseInt(harga);

                }

                var ItmsTotalRateDis = 0;
                    for(var i =0; i< itmsDis.length; i++){
                        var rate = itmsDis[i].rate;

                        ItmsTotalRateDis += parseInt(rate);
                    }

                var objItm = {
                    id_item : id,
                    harga: price,
                    qty : qty,
                    Totaladds: ItemTotalAdds,
                    Adds: itmAds,
                    jumlah : (parseInt(price) + parseInt(ItemTotalAdds)) * qty,
                    TotalRateDis:ItmsTotalRateDis,
                    Discount: itmsDis

                }

                itms.push(objItm);
            })

            console.log(itms);

            var totalItmSplit = 0;
            var totalDis = 0;


            for (var i=0; i<itms.length; i++){
                var price = itms[i].harga;
                var totalAdds = itms[i].Totaladds;
                var qty = itms[i].qty;
                var jumlah = (parseInt(price) + parseInt(totalAdds)) * parseInt(qty);

                var rate = itms[i].TotalRateDis;
                var nominalDis = parseInt(jumlah) * (parseInt(rate) /100);

                if (!isNaN(nominalDis)) {
                    totalDis += nominalDis;
                }


                totalItmSplit += parseInt(jumlah);
            }

            var totalSplitPrice = parseInt(totalItmSplit) - parseInt(totalDis) ;



            console.log(totalSplitPrice, totalItmSplit, totalDis)


            //update Order

            var SubNow = parseInt(subTotal) - totalSplitPrice ;
            //subtotal Db1 jika bill memiliki data item yang di retur
            var SubTotalDb1 =$('.footer-sub-total .txt-price-total.subtotal ').attr('data-subT');
            if(SubTotalDb1 !== undefined && SubTotalDb1 !== null && SubTotalDb1 !== ""){
                SubTotalDb1 = parseInt(SubTotalDb1) - SubNow ;
            }
            var pb1 = 0.1;
            var service = 0.05;
            var nominalPbNow = SubNow * pb1 ;
            var nominalService = SubNow * service;
            var TotalNow = SubNow + nominalPbNow + nominalService;
           
            console.log(SubNow, nominalPbNow, nominalService, TotalNow);

            //order baru
            var SubTotal = totalSplitPrice ;
            //subtotal Db1 jika bill memiliki data item yang di retur
            var SubTotalDb1 =$('.footer-sub-total .txt-price-total.subtotal ').attr('data-subT');
            if(SubTotalDb1 !== undefined && SubTotalDb1 !== null && SubTotalDb1 !== ""){
                SubTotalDb1 = parseInt(SubTotalDb1) - SubTotal ;
            }
            var nominalPB1 = SubTotal * pb1 ;
            var NomService  = SubTotal * service ;
            var TotalSplit = SubTotal + nominalPB1 + NomService;
             var TotalDb1 =$('.footer-sub-total .txt-price-total.total ').attr('data-total');
            if(TotalDb1 !== undefined && TotalDb1 !== null && TotalDb1 !== ""){
                TotalDb1 = parseInt(TotalDb1) - TotalSplit ;
            }
            console.log(SubTotal, nominalPB1, NomService, TotalSplit);

            var convertPb1 = nominalPbNow.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            var convertService = nominalService.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            var ConSub = SubNow.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            var ConTotal = TotalNow.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            $elm_pb.text(convertPb1);
            $elm_service.text(convertService);
            $('.footer-sub-total .txt-price-total.subtotal').text(ConSub);
            $('.footer-sub-total .txt-price-total.total').text(ConTotal);

            if(type === 2){
                var convert_total = TotalSplit.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                var paymentTotal = $('.total-payment').text(convert_total);
                $('.nm-payment').text(convert_total);
                $('.nominal').attr('data-nominal', TotalSplit);
                $('.pop-payment').fadeIn();
            }



            if (type === 3){
                var DataUpdateSplit = {
                    _token : "{{ csrf_token() }}",
                    target_order: currentBillId,
                    subtotal: SubTotalDb1,
                    total: TotalDb1,
                    subtotalNew: SubTotal,
                    totalNew: TotalSplit,
                    itms: itms,
                    adds: Adds,
                    discount : dis,
                    cash: cash,
                    change: change_,
                    type_pyment: paymentId

                }

                console.log('Data SplitBill: ', DataUpdateSplit);

                $.post("{{ route ('split-bill') }}", DataUpdateSplit).done(function(data){
                    if(data.success === 0){
                        alert(data.message);
                    }else{
                        if(data.error){
                            console.log(data.error);
                            return;
                            LogActivity('Error splitbill', data)
                        }

                        LogActivity('splitBill', data);
                        $('.popup-name-bill').hide();
                        $('.pop-payment').hide();
                        $('.payment-nominal').hide()

                        const id = data.data.new_order.id;
                        currentBillId = 0;

                        Bill(id, 'Bill');
                        Tiket(id, 'Tiket');
                        // Kitchen(id, 'Kitchen');
                        clearSession()
                        
                    }
                }).fail(function(data){
                    console.log('error', data);
                   
                    LogActivity('Error split bill', data)
                    
                });

            }

            if(type === 4){
                var idAdmin = $('.main-sidebar .info.admin').attr('data-admin');

                var DataUpdateSplit = {
                    _token : "{{ csrf_token() }}",
                    target_order: currentBillId,
                    subtotal: SubNow,
                    total: TotalNow,
                    subtotalNew: SubTotal,
                    totalNew: TotalSplit,
                    itms: itms,
                    adds: Adds,
                    discount : dis,
                    cash: cash,
                    change: change_,
                    type_pyment: paymentId

                }
                console.log('idAdmin');
                DataUpdateSplit['idUser'] = idAdmin;
                console.log('Data SplitBill: ', DataUpdateSplit);

                $.post("https://admin.goodfellas.id/api/Spilit-bill-server", DataUpdateSplit).done(function(data){
                    if(data.success === 0){
                        alert(data.message);
                    }else{
                        var printUrl = "{{ route('data-print-server' ,'') }}" + "/";
						printUrl = printUrl + data.splitOrderId;
						window.location = printUrl;
                    }
                }).fail(function(data){
                    console.log('error', data);
                });
            }

        }


        function getDatabill(){
            $.ajax({
                url: "https://admin.goodfellas.id/api/getDataOrder",
                method:'GET',
                success: function(data){
                    console.log(data.data, data.DataRelasi);
                    dataBill(data.data, data.DataRelasi);

                },

            }).fail(function(data){
                    console.log('error', data);
            });

        }

        function isEmpty(value) {
            return value === null || value === undefined || value === '';
        }

        function dataBill(data, DataRelasi){
            var $tgtTable = $('tbody.data-bill');
            var itmBill = $('tr.item-bill');
            $.each(data, function(key, value){
                var html = '<tr class="item-bill server" idx="' + value.id + '">' +
                    '<td>';
                if (!isEmpty(value.name_bill)) {
                    html += value.name_bill;
                } else if (!isEmpty(data.id_user)) {
                    html += value.user.nama;
                } else {
                    html += '-';
                }
                html += '</td>' +
                    '<td>' + value.kode_pemesanan + '</td>' +
                    '<td>';
                if (!isEmpty(value.no_meja)) {
                    html += value.no_meja;
                } else {
                    html += value.booking.type_room;
                }
                html += '</td>' +
                    '<td>' + value.status.status_order + '</td>' +
                    '</tr>';
                 $tgtTable.append(html);
            })
        }

        function getDetailBillServer(idx, type){
            let URL = "https://admin.goodfellas.id/api/getDetailBill";
            $.ajax({
                url: URL,
                data: {refId: idx},
                method: 'GET',
                success: function(result){
                    $('.part-order').empty();

                    detailBillServer(result.data, result.Detail, result.tax)
                    $('.pop-daftar-bill').hide();
                    $('.payment-nominal .card-payment-nominal .footer-card .btn-selesai').attr('data-type', 'server');

                    if(type == 'detailprint_bil'){
                        $('.pop-payment').hide();
                        $('.payment-nominal').hide();
                        var printUrl = "{{ route('data-print-server', '') }}" + '/' + idx;
                            //printUrl = printUrl + xidOrder;
                        window.location = printUrl;
                    }

                }
            }).fail(function(result){
                console.log(result);
            });
        }

        function detailBillServer(data, Detail, tax){
            var $target = $('.view-detail-ord');
            var html = '<div class="part-order" x-id="'+data.id+'">'+
                            '<div class="drop-down">'+
                                '<p class="txt-dropdown" style="margin: 0">Nomer Meja</p>'+
                                '<input type="text" class="nomer-meja" name="no_meja" value="'+data.no_meja+'">'+
                                '<p class="txt-dropdown" style="margin: 0">'+data.kode_pemesanan+'</p>'+
                            '</div>'+
                            '<div class="detil-bil">';
                                var total_dis = 0;
                                var total = 0;
                                var sub_total =0;
                                $.each(Detail, function(key, value){
                                    html += '<div class="itm-bil" idx="'+value.id+'" xid="'+key+'">'+
                                        '<div class="itm">'+
                                            '<p class="txt-item" data-item="'+value.id_menu+'">'+value.menu.nama_menu+'</p>'+
                                            '<div class="qty-menu">'+
                                                '<div class="jumlah">'+value.qty+'</div>'+
                                            '</div>'+
                                            '<div class="part-float-right">';
                                                if(!isEmpty(value.harga)){
                                                    html+= '<p class="price" price="'+value.harga+'">'+ value.total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+'</p>';
                                                }

                                                html+= '<div class="hapus-menu-order" idx="'+value.id+'">X</div>'+
                                                '<div class="act-edit" style="display: none">'+
                                                    '<input type="checkbox" name="" id-item="'+value.id+'" class="check-edit" style="position: relative; right: 0; margin: 0px; left: 20px;">'+
                                                    '<span class="checkmark" style="right: 0px; position: relative;"></span>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                        '<div class="detail-itm">';
                                            if(!isEmpty(value.varian)){
                                                html+= '<small class="option varian-op">'+value.varian.nama+'</small>';
                                            }

                                            if(!isEmpty(value.add_optional_order)){
                                                $.each(value.add_optional_order, function(i, adds){
                                                    html+= '<small class="option add-op" id_adds="" >'+
                                                            adds.optional__add.name +'-'+ adds.optional__add.harga.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+
                                                        '</small>';
                                                });
                                            }

                                            if(!isEmpty(value.sales_type)){
                                                html+= '<small class="option status-order" idx="'+value.id_sales_type+'">'+
                                                            value.sales_type.name +
                                                        '</small>';
                                            }

                                            $.each(value.discount_menu_order, function(k, dis){
                                                var totalDis = value.discount.rate_dis ;

                                                if(!isEmpty(totalDis)){
                                                    var nominalDis = 0;
                                                    var Dis = totalDis / 100;
                                                    nominalDis = value.total * Dis;
                                                    total_dis += nominalDis;
                                                }

                                                html+= '<small class="option status-order discount" dis="'+total_dis+'" nominal-dis="'+nominalDis+'" >Discount -'+ parseInt(nominalDis).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+'</small>';
                                            });

                                            if(!isEmpty(value.catatan)){
                                                html+= '<small class="option status-order">'+value.catatan+'</small>';
                                            }

                                        html+= '</div>'+

                                    '</div>';


                                    sub_total += value.total - total_dis ;
                                });

                            html+= '</div>'+
                        '</div>';

                html+= '<div class="footer-sub-total">'+
                        '<div class="total">'+
                            '<div class="txt-total subtotal">Subtotal:</div>'+
                            '<div class="txt-price-total subtotal" data-subT="'+sub_total+'">'+sub_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+'</div>'+
                        '</div>';

                        var totalTax = 0;
                        $.each(tax, function(k, tax){
                            var nominalTax = 0;
                            var desimalTax = tax.tax_rate / 100;
                            var nominalTax =  sub_total * desimalTax;
                            totalTax += nominalTax;

                            html+= '<div class="total taxes" idx= "'+tax.id+'" >'+
                                        '<div class="txt-total service-change">'+tax.nama+' '+'<p class="presentage">'+tax.tax_rate+'%</p>:</div>'+
                                        '<div class="txt-price-total nominal-tax'+tax.nama+' '+'" rate="'+tax.tax_rate+'" style="color: grey;font-size: 13px;">'+nominalTax.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+'</div>'+
                                    '</div>';
                        });

                        total = sub_total + totalTax;

                        html+= '<div class="total">'+
                            '<div class="txt-total total">Total:</div>'+
                            '<div class="txt-price-total total" data-total="'+total+'">'+total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+'</div>'+
                        '</div>';

                        if(!isEmpty(data.booking)){
                            var sisaBayar = 0;
                            sisaBayar = total - data.booking.nominal_dp;

                            html+='<div class="total">'+
                                    '<div class="txt-total total">Deposit:</div>'+
                                    '<div class="txt-price-total ">'+data.booking.nominal_dp.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+'</div>'+
                                '</div>'+
                                '<div class="total">'+
                                    '<div class="txt-total total">';
                                        if(sisaBayar > 0 ) {
                                            html+='Sisa Bayar:';
                                        }else {
                                            html+= 'Lebih Bayar :';
                                        }
                                        html+='</div>';
                                    html+= '<div class="txt-price-total';
                                    if(sisaBayar > 0 ){
                                        html+= 'sisa-bayar';
                                    }
                                    html+='" data-total="'+sisaBayar+'">'+sisaBayar.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')+'</div>'+
                                '</div>';
                        }
                   html+='</div>';

            $target.append(html);
        }


        function formatRupiah(input) {
            // Menghilangkan semua karakter non-digit
            let nominal = input.value.replace(/\D/g, '');

            // Format nominal menjadi format Rupiah yang sesuai
            const formatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
            });

            // Update nilai input dengan format Rupiah
            input.value = formatter.format(nominal);
            var Nominal = $('input.cash-nominal-input').val();
            console.log(Nominal)
        }
        
        function convertToRupiah(stringValue) {
        // Hapus karakter selain angka
            var numberValue = parseInt(stringValue.replace(/[^0-9]/g, ''), 10);

            // Format angka ke dalam format Rupiah
            var formattedValue = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(numberValue);

            return formattedValue;
        }

        // Fungsi untuk menangani perubahan nilai input
        function handleInputChange() {
            var inputValue = $('#inputString').val();
            var convertedValue = convertToRupiah(inputValue);
            $('#result').text(convertedValue);
        }

        if(!localStorage.getItem('pcguid')) {
            localStorage.setItem('pcguid', makeid(8));
        }

        function meekid(length){
            let result = '';
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            const charactersLength = characters.length;
            let counter = 0;
            while (counter < length) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
                counter += 1;
            }
            return result;
        }

        // function addLogLocalStorage(dataStream, fromAction, result){
        //     let logStream = localStorage.getItem('data_log_action');
        
        //     let uid = localStorage.getItem('pcguid');
        //     let newLog = {
        //         'data-stream': dataStream,
        //         'timestamp':new Date().toISOString(), 
        //         'Date_Time': formatDate(new Date()), 
        //         'from-action': fromAction,
        //         'result': result
        //     };

        
        //     if (!logStream) {
        //         // Jika logStream belum ada, buat objek baru
        //         logStream = {
        //             'date': newLog.timestamp,
        //             'uid': uid,
        //             'logList': [newLog]
        //         };
        //     } else {
        //         // Jika logStream sudah ada, parse string JSON menjadi objek
        //         logStream = JSON.parse(logStream);

        //         if (logStream.uid === uid) {
        //             // Jika UID sama, tambahkan log baru ke logList dan update date
        //             logStream.logList.push(newLog);
        //             logStream.date = newLog.timestamp;
        //         } else {
        //             // Jika UID berbeda, buat objek baru (ini skenario yang jarang terjadi)
        //             logStream = {
        //                 'date': newLog.timestamp,
        //                 'uid': uid,
        //                 'logList': [newLog]
        //             };
        //         }
        //     }
        //     localStorage.setItem('data_log_action', JSON.stringify(logStream));
        // }
    
        function formatDate(date) {
            let day = ("0" + date.getDate()).slice(-2);
            let month = ("0" + (date.getMonth() + 1)).slice(-2);
            let year = date.getFullYear();
            let hours = ("0" + date.getHours()).slice(-2);
            let minutes = ("0" + date.getMinutes()).slice(-2);
            let seconds = ("0" + date.getSeconds()).slice(-2);

            return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
        }

        // function getLocalstorage() {
        //     let logStream = localStorage.getItem('data_log_action');
        //     if(!logStream){
        //         logStream = [];
        //     }else{
        //         logStream = JSON.parse(logStream);
        //     }
        //     return logStream
        // }

        // function RemoveLocalStorageAction() {
        //     let logStream = localStorage.getItem('data_log_action');
        //     if(logStream){
        //         logStream = JSON.parse(logStream);
        //         let newDate =  new Date().getTime();
        //         logStream = logStream.filter(log => {
        //             let logDate = new Date(log.timestamp).getTime();
        //            return (now - logDate) < (2 * 86400000);
        //         });
        //         localStorage.setItem('data_log_action', JSON.stringify(logStream));
        //     }
        // }

        // RemoveLocalStorageAction();

        // let logs_activity = getLocalstorage();
        // console.log(logs_activity)

        function LogActivity(fromAction, result) {
            const Url = "{{route('action-log')}}";
            const Data = {
                _token : "{{ csrf_token() }}",
                action: fromAction,
                detail: result
            }
            $.ajax({
                url: Url,
                method: 'POST',
                data: Data,
                success: function(result){
                    console.log('success',result)

                }
            }).fail(function(result){
                console.log('error', result)
            });
        }

        function updateSalesType() {
            const Url = '{{route('update_sales_type')}}';
            const Data = {
                _token : "{{ csrf_token() }}"
            }
            $.ajax({
                url: Url,
                method: 'POST',
                data: Data,
                success: function(result){
                    console.log('success update',result)

                }
            }).fail(function(result){
                console.log('error update', result)
            });
        }

    
});   


</script>
@stop
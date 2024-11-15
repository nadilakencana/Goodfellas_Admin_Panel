@if(session()->has('cart'))
{{-- ini untuk mengambil session cart --}}
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
                    {{-- <div class="btn-min"> - </div> --}}
                    <div class="jumlah">{{ $cart['qty'] }}</div>
                    {{-- <div class="btn-tambah"> + </div> --}}
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
                <small class="option varian-op" id_var= "{{ $cart['variasi_id'] }}">{{ $cart['var_name'] }}</small>
                @else
                @endif

                @if(!@empty($cart['additional']))
                @foreach ( $cart['additional'] as $adds )
                <small class="option add-op" id_adds="{{ $adds['id']  }}">{{ $adds['nama'] }} - {{ $adds['harga'] }}</small>
                @endforeach
                @else
                @endif


                @if(!@empty($cart['type_name']))
                <small class="option status_order type_order" idx="{{ $cart['type_id'] }}">{{
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
                        
                        @endphp
                    @endif
                    <small class="option status_order discount" idx="{{ $discounts['id'] }}" 
                    dis="{{ $discounts['percent'] }}">Discount {{$discounts['percent'] }}% - {{ $discounts['nominal'] }}</small>
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
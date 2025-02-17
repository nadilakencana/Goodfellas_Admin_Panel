<div class="detil-bil" style="display: none">
    @php
        $total_dis = 0;
    @endphp
    @foreach ($Details as $k => $cart )
    <div class="itm-bil" idx="{{ $cart['id'] }}" xid="{{ $k }}">
        <div class="itm">
            <p class="txt-item" data-item="{{ $cart['id_menu'] }}">{{ $cart->menu->nama_menu }}</p>
            <div class="control-qty">
                <a href="#" class="qty qty-minus" style="color:black">-</a>
                <input type="numeric" class="qty" value="{{ $cart['qty'] }}" max="{{ $cart['qty'] }}" />
                <a href="#" class="qty qty-plus" style="color:black">+</a>
            </div>
            <div class="part-float-right">
                @php
                    $totalHarga = 0;
                    $discountTotal = 0;
                    $total_sub= 0;
                    $total = 0;

                    $discountTotal = $totalDis ;
                    $float_dis = $discountTotal / 100;
                    $SubtotalHarga = $cart['harga'] + $cart['harga_addtotal'] ;
                    $totalHarga = ($cart['harga'] + $totalAdds ) * $cart['qty'] ;
                    $subtotalDisCount = $totalHarga * $float_dis;
                    $total = $totalHarga - $subtotalDisCount;
                    $total_sub += $total;
                @endphp

                @if(!@empty($cart['harga']))
                    <p class="price" price="{{ $cart['harga'] }}">{{number_format( $cart['total'], 0, ',','.')}}</p>
                @else
                @endif

                <div class="act-edit local">
                    <input type="checkbox" name="" id-item="{{ $cart['id'] }}" class="check-edit" style="position: relative; right: 0; margin: 0px; left: 20px;">
                    <span class="checkmark" style="right: 0px; position: relative;"></span>
                </div>
            </div>

        </div>
        <div class="detail-itm">
            @if(!@empty($cart['id_varian']))
                <small class="option varian-op" data-id="{{ $cart['id_varian'] }}">{{ $cart->varian->nama }}</small>
            @else
            @endif

            @if(!@empty($cart->AddOptional_order))
            @foreach($cart->AddOptional_order as $adds)
            @php
            $totalAdds =+ $adds->optional_Add->harga;
            @endphp
            <small class="option add-op" id_adds="{{ $adds->optional_Add->id }}" nominal="{{ $adds->optional_Add->harga }}">
                {{ $adds->optional_Add->name }} - {{ $adds->optional_Add->harga }}
            </small>
            @endforeach
            @else
            @endif

            @if(!@empty($cart['id_sales_type']))
            <small class="option status-order" idx="{{ $cart['id_sales_type'] }}">
                {{ $cart->salesType->name }}
            </small>
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
                    $nominalDis = str_replace(".", "", $cart['total']) * $Dis ;
                    $total_dis += $nominalDis;
                    // dd($total_dis);
                @endphp
                <small class="option status-order discount" xid-dis ="{{ $discounts->discount->id}}" dis="{{ $totalDis }}" nominal-dis="{{ $nominalDis }}">Discount -
                    {{ number_format( $nominalDis, 0, ',','.') }}
                </small>
            @else
            @endif
            @endforeach



            @if(!@empty($cart['catatan']))
            <small class="option status-order">{{ $cart['catatan'] }}</small>
            @else
            @endif
        </div>

    </div>
    @php
        $sub_total = 0;
        $sub_total = $subtotal - $total_dis;
    @endphp

    @endforeach

</div>

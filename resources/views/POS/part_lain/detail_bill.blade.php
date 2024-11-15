<div class="part-order" x-id="{{ $orderBill }}">
    <div class="drop-down">
        <p class="txt-dropdown" style="margin: 0">Nomer Meja</p>
        <input type="text" class="nomer-meja" data-name="{{$Bill->name_bill}}" name="no_meja" value="{{ $Bill->no_meja}}">
        <p class="txt-dropdown" style="margin: 0">{{ $Bill->kode_pemesanan }}</p>
        <div class="save-split" style="display: none">Save Split</div>
    </div>
    <div class="detil-bil">
        @php
            $total_dis = 0;
            $sub_total = 0;
        @endphp
        @foreach ($Details as $k => $cart )
            {{--  idx id dari menu, xid key array, id item detail dari id detail --}}
            <div class="itm-bil" idx="{{ $cart['id_menu'] }}" xid="{{ $k }}" id_item_detail="{{ $cart['id'] }}">
                <div class="itm">
                    <p class="txt-item" data-item="{{ $cart['id_menu'] }}">{{ $cart->menu->nama_menu }}</p>
                    <div class="qty-menu">
                        <div class="jumlah">{{ $cart['qty'] }}</div>
                    </div>

                    <div class="part-float-right">
                        @php
                            $totalHarga = 0;
                            $discountTotal = 0;
                            $total_sub= 0;
                            $total = 0;

                            $discountTotal =  $totalDis ;
                            $float_dis = $discountTotal / 100;
                            $SubtotalHarga = $cart['harga'] + $cart['harga_addtotal'] ;
                            $totalHarga = ($cart['harga'] + $totalAdds ) * $cart['qty'] ;
                            $subtotalDisCount =  $totalHarga * $float_dis;
                            $total =  $totalHarga - $subtotalDisCount;
                            $total_sub += $total;
                        @endphp

                        @if(!@empty($cart['harga']))
                            <p class="price" price="{{ $cart['harga'] }}">{{number_format(  $cart['total'], 0, ',','.')}}</p>
                        @else
                        @endif
                        <div class="hapus-menu-order"  idx="{{ $cart['id'] }}"> X </div>
                        <div class="act-edit" style="display: none">
                            <input type="checkbox" name="" id-item="{{ $cart['id'] }}" class="check-edit" style="position: relative; right: 0; margin: 0px; left: 20px;">
                            <span class="checkmark" style="right: 0px; position: relative;"></span>
                        </div>
                    </div>

                </div>
                <div class="detail-itm">
                    @if(!@empty($cart['id_varian']))
                        <small class="option varian-op" id_var= "{{ $cart['id_varian'] }}">{{ $cart->varian->nama }}</small>
                    @else
                    @endif

                    @if(!@empty($cart->AddOptional_order))
                        @foreach($cart->AddOptional_order as $adds)
                        @php
                            $totalAdds =+ $adds->optional_Add->harga;
                        @endphp
                        {{--  id adds id dari optional , data id item id dari detail order item , data id dari id additional id  --}}
                                <small class="option add-op" id_adds="{{  $adds->optional_Add->id }}" 
                                    data-idItem="{{ $adds->id_detail_order}}" data-id="{{ $adds->id }}">
                                    {{ $adds->optional_Add->name }} - {{ $adds->optional_Add->harga }}
                                </small>
                        @endforeach
                    @else
                    @endif

                    @if(!@empty($cart['id_sales_type']))
                    <small class="option status_order type_order" idx="{{ $cart['id_sales_type'] }}">
                        {{ $cart->salesType->name }}
                    </small>
                    @else
                    @endif

                    @foreach($cart ->Discount_menu_order as $discounts)
                        @php
                            $totalDis =+ $discounts->discount->rate_dis;
                            $total_dis += $discounts->total_discount;
                        @endphp
                    
                        
                        <small class="option discount" idx="{{ $discounts->discount->id }}" dis="{{ $discounts->discount->rate_dis }}" 
                            nominal-dis="{{ $discounts->total_discount }}" data-idItem="{{ $discounts->id_detail_order }}">Discount {{ $discounts->discount->rate_dis }}%  - {{ number_format( $discounts->total_discount, 0, ',','.') }}</small>
                
                    @endforeach



                    @if(!@empty($cart['catatan']))
                    <small class="option note">{{ $cart['catatan'] }}</small>
                    @else
                    @endif
                </div>

            </div>
            @php
            
                $sub_total = $subtotal - $total_dis;
            @endphp
        @endforeach

    </div>
</div>


<div class="footer-sub-total">
    <div class="total">

        <div class="txt-total subtotal">Subtotal:</div>
        <div class="txt-price-total subtotal" data-subT="{{ $Bill->subtotal }}" subtotal="{{ $sub_total }}">{{number_format( $sub_total, 0, '.','.') }}</div>
        {{--  <div class="txt-price-total subtotal">{{number_format(  $subtotal, 0, '.','.') }}</div>  --}}
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
      <div class="total taxes" idx= "{{ $tax-> id }}" >
          <div class="txt-total service-change">{{ $tax->nama }} <p class="presentage">{{ $tax->tax_rate }}%</p>:</div>
          <div class="txt-price-total nominal-tax {{ $tax->nama }}" rate="{{ $tax->tax_rate }}" style="color: grey;font-size: 13px;">{{ number_format( $nominalTax, 0, '.','.') }}</div>
      </div>
    @endforeach

    @php
        $total = 0;
        $total = $sub_total + $totalTax;
    @endphp
    <div class="total">
        <div class="txt-total total">Total:</div>
        <div class="txt-price-total total" data-total="{{ $Bill->total_order }}" total="{{ $total }}">{{ number_format( $total, 0, '.','.') }}</div>
    </div>

    @if(!empty($Bill->id_booking))
    @php
        $sisaBayar = 0;
        $sisaBayar = $total - $Bill->booking->nominal_dp;

    @endphp
    <div class="total">
        <div class="txt-total total">Deposit:</div>
        <div class="txt-price-total ">{{ number_format( $Bill->booking->nominal_dp, 0, '.','.') }}</div>
    </div>
    <div class="total">
        <div class="txt-total total">@if($sisaBayar > 0 ) Sisa Bayar: @else Lebih Bayar : @endif</div>
        <div class="txt-price-total @if($sisaBayar > 0 ) sisa-bayar @endif" data-total="{{ $sisaBayar }}">{{ number_format( $sisaBayar, 0, '.','.') }}</div>
    </div>
    @endif
    @if(!empty ($Bill->id_type_payment) )
    <div class="total">
        <div class="txt-cash" data-payment="{{$Bill->id_type_payment}}">{{ $Bill->payment->nama }}</div>
        <div class="cash-nominal">Rp. {{ number_format( $Bill->cash, 0, ',','.') }}</div>
    </div>
    <div class="total">
        <div class="txt-cash">Change</div>
        <div class="cash-nominal">Rp. {{ number_format( $Bill->change_, 0, ',','.') }}</div>
    </div>
    @endif
</div>

@extends('CustomerOrder.index')
@section('content_order')
    <div class="pt-5">
        <div class="container py-4">
            <div class="header d-flex justify-content-between align-items-center pt-2">
                <p class="fw-bold fs-3">Your cart</p>
                <p class="fs-5">No Meja : {{$meja}}</p>
            </div>
            <div class="content pt-2">
               
                <div class="items-menu justify-content-between align-items-center gap-4 py-3">
                    @if(!empty($carts))
                        @foreach ($carts as $k => $itm)
                            <div class="itm-menu d-flex justify-content-between align-items-center gap-4 px-2 py-3" xkey='{{ $k }}'>
                                @if (!empty($itm['image']))
                                    <div class="img-menu">
                                        <img src="{{ asset('asset/assets/image/menu/' . $itm['image']) }}" alt="" style="object-fit:contain">
                                    </div>
                                @else
                                    <div class="img-menu">
                                        <img src="{{ asset('asset/assets/image/menu/drink.png') }}" alt="">
                                    </div>
                                @endif

                                <div class="detail-menu d-flex flex-column" style="width: 11rem;">
                                    <span class="fw-bold item-name cursor-pointer" xkey="{{$k}}" xid="{{encrypt($itm['id'])}}">{{ $itm['nama_menu'] }}</span>
                                    <span class="py-2">{{$itm['type_name']}}</span>
                                    <span class="pt-2">{{ $itm['var_name'] }}</span>
                                    <div class="additional d-flex flex-column" style="font-size: small">
                                        @if(!empty($itm['additional']))
                                            <span class="pt-3">Additional:</span>
                                            @foreach ($itm['additional'] as $itmAdds )
                                                <span class="text-small" xid="{{$itmAdds['id']}}">{{$itmAdds['name']}} - {{$itmAdds['harga']}}</span>
                                            @endforeach
                                        @endif
                                        @if(!empty($itm['catatan']))
                                            <span class="pt-1">Note: {{$itm['catatan']}}</span>
                                        @endif
                                    </div>
                                   

                                </div>
                                <div class="d-flex justify-content-center align-items-center gap-4">
                                    @php
                                        $total = 0;
                                        $total = $itm['harga'] + $itm['harga_addtotal'];
                                    @endphp
                                    <div class="d-flex flex-column align-items-center" style="font-size: 13px">
                                        <span class="harga fw-bold fs-7">Rp.{{number_format($total, 0, ',', '.')}}</span>
                                        <span class="fw-bold">X</span>
                                        <span class="fw-bold">{{$itm['qty']}} qty</span>

                                    </div>

                                    <div class="btn-delete-itm cursor-pointer" xid="{{encrypt($itm['id'])}}" xkey='{{$k}}'>
                                        <img src="{{ asset('asset/assets/image/icon/Trash Can.png') }}" alt="" width="30"
                                            height="30">
                                    </div>
                                </div>
                                
                            </div>
                        @endforeach
                    @else

                    @endif

                </div>

                <div class="total-order mt-3">
                    <div class="card-total-order p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-order">SubTotal : </span>
                            <span class="nominal-total-order fw-bold" nominal="{{$subtotal}}">Rp. {{number_format( $subtotal, 0, '.','.') }}</span>
                        </div>
                        @php
                            $totalTax = 0;
                        @endphp
                        @foreach ($taxs as $tax )
                            @php
                                $nominalTax = 0;
                                $desimalTax = $tax->tax_rate / 100;
                                $nominalTax = str_replace(".", "", $subtotal) * $desimalTax;
                                $totalTax += $nominalTax;
                            @endphp
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-order">{{$tax->nama}} {{$tax->tax_rate}}% :</span>
                            <span class="nominal-total-order fw-bold">Rp. {{ number_format( $nominalTax, 0, '.','.') }}</span>
                        </div>
                        @endforeach
                        @php
                            $total = 0;
                            $total = $subtotal + $totalTax;
                        @endphp
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-order">Grand Total : </span>
                            <span class="nominal-total-order fw-bold" nominal="{{$total}}">Rp. {{ number_format( $total, 0, '.','.') }}</span>
                        </div>
                    </div>

                    <div class="btn-order mt-3 py-3 d-flex justify-content-center align-itms-center cursor-pointer" nomer-meja="">
                        <span>Order Now</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pop-up-modal-menu" style="display: none">
       
    </div>
@endsection


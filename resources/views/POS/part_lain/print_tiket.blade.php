<div class="detail-tiket">
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
            @if($cart['update'] === 1)
                <p class="option status-order" style="font-size: 15px;font-weight: 700;">
                    Item Update
                </p>
            @else
            @endif
            <div class="itms-order" style="justify-content: normal;gap: 11px;">
                <div class="name-itms">{{ $cart->menu->nama_menu }}</div>
                <div class="qty-itm">{{ $cart->qty }}</div>
            </div>
            <div class="detail-itm">
                @if(!@empty($cart['id_varian']))
                <p class="option varian-op">{{ $cart->varian->nama }}</p>
                @else
                @endif

                @foreach($cart->AddOptional_order as $adds)

                @if(!@empty( $adds ))

                <p class="option add-op" id_adds="">
                    {{ $adds->optional_Add->name }} 
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

            
                @if(!@empty($cart['catatan']))
                <p class="option status-order">{{ $cart['catatan'] }}</p>
                @else
                @endif
            </div>
        </div>
        @endforeach

    </div>
</div>
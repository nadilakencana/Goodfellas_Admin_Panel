<div class="card-body  p-0">
    <div class="head-card">
        <div class="sub-head">Payment Methods</div>
        <div class="sub-head">Transaksi</div>
        <div class="sub-head">Total Collected</div>
    </div>

    <div class="body-summary">
        @php
            $totalOrders =0;
            $totalPembayarans = 0;
        @endphp
        @foreach ( $paymentData as $payment)

            <div class="label">
                <div class="title-label" style="width: 150px;">{{ $payment['paymentMethod']->nama }}</div>
                <div class="title-label" style="width: 150px; text-align: center;"> {{$payment ['totalOrder']}}</div>
                <div class="nominal-lebel" style="width: 150px; text-align: end;" >Rp. {{number_format(  $payment ['totalPembayaran'], 0, ',','.')}}</div>
            </div>

            @php
                $totalOrders += $payment ['totalOrder'];
                $totalPembayarans += $payment ['totalPembayaran'];

            @endphp
        @endforeach

        <div class="line"></div>
        <div class="label">
            <div class="title-label" style="width: 150px;">Total </div>
            <div class="title-label" style="width: 150px; text-align: center;">{{ $totalOrders }}</div>
            <div class="nominal-lebel"style="width: 150px; text-align: end;">Rp. {{number_format( $totalPembayarans, 0, ',','.')}}</div>
        </div>
        <div class="line"></div>
    </div>
</div>

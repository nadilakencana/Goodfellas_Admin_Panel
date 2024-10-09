<div class="card-body  p-0">
    <div class="head-card">
        <div class="sub-head">Sales Type</div>
        <div class="sub-head">Count</div>
        <div class="sub-head">Total Collected</div>
    </div>

    <div class="body-summary">
        @php
            $totalOrders =0;
            $totalPembayarans = 0;
        @endphp
        @foreach ( $SalesData as $sales)

            <div class="label">
                <div class="title-label" style="width: 150px;">{{ $sales['Sales Type']}}</div>
                <div class="title-label" style="width: 150px; text-align: center;"> {{$sales ['totalOrder']}}</div>
                <div class="nominal-lebel" style="width: 150px; text-align: end;" >Rp. {{number_format(  $sales ['Total'], 0, ',','.')}}</div>
            </div>

            @php
                $totalOrders += $sales ['totalOrder'];
                $totalPembayarans += $sales ['Total'];

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

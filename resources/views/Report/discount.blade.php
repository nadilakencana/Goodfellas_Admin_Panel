<div class="card-body  p-0">
    <div class="head-card" style=" font-size: 15px;">
        <div class="sub-head">Name</div>
        <div class="sub-head">Discount Amount</div>
        <div class="sub-head">Count</div>
        <div class="sub-head">Gross Discount</div>
        <div class="sub-head">Discount Refund</div>
        <div class="sub-head">Net Discount</div>
    </div>

    <div class="body-summary">
        @php
            $count = 0;
            $Gross = 0;
            $ref = 0;
            $netSels= 0;
        @endphp
        @foreach ($dataDiscount as $dis )
        <div class="label">
            <div class="title-label" style="width: 150px;">{{ $dis['nama']->nama }}</div>
            <div class="title-label" style="width: 150px; text-align: center;">{{ $dis['nama']->rate_dis }}%</div>
            <div class="nominal-lebel" style="width: 150px; text-align: center;" >{{ $dis['count'] }}</div>
            <div class="nominal-lebel" style="width: 150px; text-align: end;" >(Rp. {{number_format(  $dis['Gross'], 0, ',','.')}} )</div>
            <div class="nominal-lebel" style="width: 150px; text-align: end;" >(Rp. {{number_format(  $dis['refund'], 0, ',','.')}} )</div>
            <div class="nominal-lebel" style="width: 150px; text-align: end;" >(Rp. {{number_format(  $dis['Net'], 0, ',','.')}})</div>
        </div>

        @php
            $count += $dis['count'];
            $Gross += $dis['Gross'];
            $ref += $dis['refund'];
            $netSels += $dis['Net'];
        @endphp
        @endforeach

        <div class="line"></div>
        <div class="label">
            <div class="title-label" style="width: 150px;">Total </div>
            <div class="title-label" style="width: 150px; text-align: center;"></div>
            <div class="nominal-lebel" style="width: 150px; text-align: center;" >{{ $count }}</div>
            <div class="nominal-lebel" style="width: 150px; text-align: end;" >(Rp. {{number_format(  $Gross, 0, ',','.')}} )</div>
            <div class="nominal-lebel" style="width: 150px; text-align: end;" >(Rp. {{number_format(  $ref, 0, ',','.')}} )</div>
            <div class="nominal-lebel" style="width: 150px; text-align: end;" >(Rp. {{number_format( $netSels, 0, ',','.')}})</div>
        </div>
        <div class="line"></div>
    </div>
</div>

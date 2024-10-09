<div class="card-body  p-0">
    <div class="head-card">
        <div class="sub-head">Name</div>
        <div class="sub-head">Quantity Sold</div>
        <div class="sub-head">Gross Sales</div>
        <div class="sub-head">Discount</div>
        <div class="sub-head">Refund</div>
        <div class="sub-head">Net Sales</div>
    </div>

    <div class="body-summary">
        @php
            $qty = 0;
            $Gross = 0;
            $Dis = 0;
            $ref = 0;
            $netSels= 0;
        @endphp
        @foreach ($itemSalesAdss as $itms )
        <div class="label">
            <div class="title-label" style="width: 150px;">{{ $itms['Name']->name }}</div>
            <div class="title-label" style="width: 150px; text-align: center;">{{ $itms['item Sold'] }}</div>
            <div class="nominal-lebel" style="width: 150px; text-align: end;" >Rp.{{number_format(  $itms['Gross Salse'], 0, ',','.')}}</div>
            <div class="nominal-lebel" style="width: 150px; text-align: end;" >(Rp. {{number_format(  $itms['DisNominal'], 0, ',','.')}} )</div>
            <div class="nominal-lebel" style="width: 150px; text-align: end;" >(Rp. {{number_format(  $itms['Refund'], 0, ',','.')}} )</div>
            <div class="nominal-lebel" style="width: 150px; text-align: end;" >(Rp. {{number_format(  $itms['Net Sales'], 0, ',','.')}})</div>
        </div>

        @php
            $qty += $itms['item Sold'];
            $Gross += $itms['Gross Salse'];
            $Dis += $itms['DisNominal'];
            $ref += $itms['Refund'];
            $netSels += $itms['Net Sales'];
        @endphp
        @endforeach



        <div class="line"></div>
        <div class="label">
            <div class="title-label" style="width: 150px;">Total </div>
            <div class="title-label" style="width: 150px; text-align: center;">{{ $qty }}</div>
            <div class="nominal-lebel" style="width: 150px; text-align: end;" >Rp.{{number_format(   $Gross, 0, ',','.')}}</div>
            <div class="nominal-lebel" style="width: 150px; text-align: end;" >(Rp. {{number_format(  $Dis, 0, ',','.')}} )</div>
            <div class="nominal-lebel" style="width: 150px; text-align: end;" >(Rp. {{number_format(  $ref, 0, ',','.')}} )</div>
            <div class="nominal-lebel" style="width: 150px; text-align: end;" >(Rp. {{number_format( $netSels, 0, ',','.')}})</div>
        </div>
        <div class="line"></div>
    </div>
</div>

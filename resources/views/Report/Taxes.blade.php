<div class="card-body  p-0">
    <div class="head-card">
        <div class="sub-head">Name</div>
        <div class="sub-head">Tax Rate</div>
        <div class="sub-head">Taxable Amount</div>
        <div class="sub-head">Taxable Collected</div>
    </div>

    <div class="body-summary">
        @php
            $totalTax=0;
        @endphp
        @foreach ( $dataTax as $tax)

            <div class="label">
                <div class="title-label" style="width: 150px;">{{ $tax['Taxs']->nama }}</div>
                <div class="title-label" style="width: 150px; text-align: center;"> {{$tax['Taxs']->tax_rate}}%</div>
                <div class="nominal-lebel" style="width: 150px; text-align: end;" >Rp. {{number_format(  $tax ['Net'], 0, ',','.')}}</div>
                <div class="nominal-lebel" style="width: 150px; text-align: end;" >Rp. {{number_format(  $tax ['taxTotal'], 0, ',','.')}}</div>
            </div>
            @php
                $totalTax += $tax ['taxTotal'];
            @endphp
        @endforeach

        <div class="line"></div>
        <div class="label">
            <div class="title-label" style="width: 150px;">Total </div>
            <div class="title-label" style="width: 150px; text-align: center;"></div>
            <div class="title-label" style="width: 150px; text-align: center;"></div>
            <div class="nominal-lebel"style="width: 150px; text-align: end;">Rp. {{number_format( $totalTax, 0, ',','.')}}</div>
        </div>
        <div class="line"></div>
    </div>
</div>

<div class="card-body  p-0">
    <div class="header-title">Sales Summary</div>
    <div class="body-summary">
        <div class="label">
            <div class="title-label">Gross Sales</div>
            <div class="nominal-lebel">Rp. {{number_format( $allGrandSales, 0, ',','.')}}</div>
        </div>
        <div class="label">
            <div class="title-label">Discounts</div>
            <div class="nominal-lebel">(Rp. {{number_format( $allGrandDis, 0, ',','.')}})</div>
        </div>
        <div class="label">
            <div class="title-label">Refunds</div>
            <div class="nominal-lebel">(Rp. {{number_format( $allGrandRefund, 0, ',','.')}})</div>
        </div>
        <div class="line"></div>
        <div class="label font-bold">
            <div class="title-label">Net Sales</div>
            <div class="nominal-lebel">Rp. {{number_format( $allGrandNet, 0, ',','.')}}</div>
        </div>
        <div class="label">
            <div class="title-label">Tax</div>
            <div class="nominal-lebel">Rp. {{number_format( $totalTax, 0, ',','.')}}</div>
        </div>
        <div class="line"></div>
        <div class="label font-bold">
            <div class="title-label">Total Collected</div>
            <div class="nominal-lebel">Rp. {{number_format( $TotalGrand, 0, ',','.')}}</div>
        </div>
        <div class="line"></div>
    </div>
</div>

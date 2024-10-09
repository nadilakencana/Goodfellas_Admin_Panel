<div class="card-body  p-0">
    <div class="header-title">Gross Profit</div>
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
            <div class="nominal-lebel">(Rp. {{number_format( $allGrandRefund, 0, ',','.')}})
            </div>
        </div>
        <div class="line"></div>
        <div class="label font-bold">
            <div class="title-label">Net Sales</div>
            <div class="nominal-lebel">Rp. {{number_format( $allGrandNet, 0, ',','.')}}</div>
        </div>

        <div class="line"></div>
        <div class="label font-bold">
            <div class="title-label">Gross Profit</div>
            <div class="nominal-lebel">Rp. {{number_format( $allGrandNet, 0, ',','.')}}</div>
        </div>
        <div class="line"></div>
    </div>
</div>

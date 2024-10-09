<table>
    <thead>
        <tr>
            <th>Menu</th>
            <th>Varian</th>
            <th>Category Item</th>
            <th>Item Sold</th>
            <th>Item Refund</th>
            <th>Gross Sales</th>
            <th>Discount</th>
            <th>Refund</th>
            <th>Net Sales</th>
            <th>Gross Profit</th>
        </tr>
    </thead>
    <tbody>
        @php
            $itemSoldAll = 0;
            $itemRefundAll = 0;
            $itmSoldMenuAll = 0;
            $itmSoldAdssAll = 0;
            $itmRfundMenuAll = 0;
            $itmRfundAdssAll = 0;
            $totalGrandGrosSalesMenu = 0;
            $totalGrandDisMenu=0;
            $totalGrandRefudMenu = 0;
            $totalNetMenu= 0;
            $totalGrandGrosSalesAdds = 0;
            $totalGrandDisAdds=0;
            $totalGrandRefudAdds = 0;
            $totalNetAdds= 0;
            $allGrandSales =0;
            $allGrandDis = 0;
            $allGrandRefund = 0;
            $allGrandNet = 0;

        @endphp
        @foreach($menu as $itm)
            @php
            $items = App\Models\DetailOrder::where('created_at', '>=', $tanggal_mulai)->where('created_at', '<', $tanggal_akhir)
            ->where('id_menu', $itm->id)->value('harga');

            $itmsum = App\Models\DetailOrder::where('created_at', '>=', $tanggal_mulai)->where('created_at', '<', $tanggal_akhir)
            ->where('id_menu', $itm->id)->sum('qty');

            $totalDiscount = App\Models\Discount_detail_order::join('detail_order', 'discount_detail_order.id_detail_order', '=', 'detail_order.id')
            ->where('detail_order.id_menu', $itm->id)->where('detail_order.created_at', '>=', $tanggal_mulai)
            ->where('detail_order.created_at', '<', $tanggal_akhir)
            ->sum('discount_detail_order.total_discount');

            $varian = App\Models\DetailOrder::join('varian_menu', 'detail_order.id_varian', '=', 'varian_menu.id')->where('detail_order.created_at', '>=', $tanggal_mulai)
            ->where('detail_order.created_at', '<', $tanggal_akhir)
            ->where('detail_order.id_menu', $itm->id)->pluck('nama');

            $refundDisCountSum = App\Models\DiscountMenuRefund::where('id_menu', $itm->id)->where('created_at', '>=', $tanggal_mulai)
            ->where('id_menu', $itm->id)->sum('nominal_dis');

            $SumRefund = App\Models\RefundOrderMenu::where('id_menu', $itm->id)->where('id_menu', $itm->id)->
            where('created_at', '>=', $tanggal_mulai)->sum('qty');


            $itemsold = $itmsum;
            $harga = $items * $itemsold;

            $itmRefund = $SumRefund;
            $totalRefund = $itmRefund * $items ;

            $disTotal = $totalDiscount - $refundDisCountSum ;
            $netSales = $harga - $disTotal - $totalRefund;

            $itmSoldMenuAll += $itemsold;
            $itmRfundMenuAll += $itmRefund;

            $totalGrandGrosSalesMenu += $harga;
            $totalGrandDisMenu += $disTotal;
            $totalGrandRefudMenu += $totalRefund;
            $totalNetMenu += $netSales;


            @endphp
            <tr>
                <td>{{ $itm->nama_menu }}</td>
                <td>
                    @if ($varian == Null)
                        -
                    @else
                    {{ $varian }}
                    @endif
                </td>
                <td>{{ $itm->subkategori->sub_kategori }}</td>
                <td>{{ $itemsold }}</td>
                <td>{{ $itmRefund }}</td>
                <td>{{ $harga }}</td>
                <td>- {{ $disTotal }}</td>
                <td>- {{ $totalRefund }}</td>
                <td>{{ $netSales }}</td>
                <td>{{ $netSales }}</td>
            </tr>
        @endforeach
        @foreach($additional as $adds)
            @php
                $itmAdsSold = App\Models\Additional_menu_detail::join('detail_order','additional_menu.id_detail_order', '=', 'detail_order.id')
                ->where('additional_menu.created_at', '>=', $tanggal_mulai)->where('additional_menu.created_at', '<', $tanggal_akhir)
                ->where('additional_menu.id_option_additional', $adds->id)
                ->sum('detail_order.qty');

                $refundSum = App\Models\AdditionalRefund::join('refund_menu_order', 'additional_refund.id_refund_menu', '=', 'refund_menu_order.id')
                ->where('additional_refund.created_at', '>=', $tanggal_mulai)->where('additional_refund.created_at', '<', $tanggal_akhir)
                ->where('id_option_additional', $adds->id)->sum('refund_menu_order.qty');


               $refund = App\Models\AdditionalRefund::where('created_at', '>=', $tanggal_mulai)->where('created_at','<',$tanggal_akhir)
               ->where('id_option_additional', $adds->id)
               ->sum('harga');

                $grosSale = $adds->harga * $itmAdsSold;
                $grosRefund = $refund * $refundSum;

                $NetSales = $grosSale  - $grosRefund;

                $itmSoldAdssAll+= $itmAdsSold;
                $itmRfundAdssAll += $refundSum;

                $totalGrandGrosSalesAdds += $grosSale;
                $totalGrandRefudAdds += $refund;
                $totalNetAdds += $NetSales;

            @endphp
            <tr>
                <td>{{ $adds->name}}</td>
                <td>-</td>
                <td>-</td>
                <td>{{ $itmAdsSold }}</td>
                <td>{{ $refundSum }}</td>
                <td>{{ $grosSale }}</td>
                <td>- 0 </td>
                <td>- {{ $refund }}</td>
                <td>{{ $NetSales }}</td>
                <td>{{ $NetSales }}</td>
            </tr>
        @endforeach
        <tr>
            @php

                $itemSoldAll = $itmSoldMenuAll + $itmSoldAdssAll ;
                $itemRefundAll =  $itmRfundMenuAll + $itmRfundAdssAll;

                $allGrandSales =  $totalGrandGrosSalesMenu + $totalGrandGrosSalesAdds;
                $allGrandDis = $totalGrandDisMenu + $totalGrandDisAdds;
                $allGrandRefund = $totalGrandRefudMenu + $totalGrandRefudAdds;
                $allGrandNet = $totalNetMenu + $totalNetAdds;
            @endphp
                <td style="font-weight: 600;">Total</td>
                <td></td>
                <td></td>
                <td style="font-weight: 600;">{{ $itemSoldAll }}</td>
                <td style="font-weight: 600;">{{ $itemRefundAll }}</td>
                <td style="font-weight: 600;">{{ $allGrandSales }}</td>
                <td style="font-weight: 600;">- {{ $allGrandDis }}</td>
                <td style="font-weight: 600;">- {{ $allGrandRefund }}</td>
                <td style="font-weight: 600;">{{ $allGrandNet }}</td>
                <td style="font-weight: 600;">{{ $allGrandNet }}</td>
            </tr>
    </tbody>
</table>

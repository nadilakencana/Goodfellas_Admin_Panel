<div class="card-body  p-0">
    <table class="tebel-item">
        <thead>
            <tr class="row-itm">
                <th class="head-row-item list-nama">Nama</th>
                <th class="head-row-item">Item Sold</th>
                <th class="head-row-item">Item Refund</th>
                <th class="head-row-item">Gross Sales</th>
                <th class="head-row-item">Discount</th>
                <th class="head-row-item">Refund</th>
                <th class="head-row-item">Net Sales</th>
                <th class="head-row-item">Gross Profit</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalItemSoldMenu = 0;
                $totalItemRefundMenu = 0;
                $totalGrossMenu = 0;
                $totalDiscountMenu = 0;
                $totalRefundMenu = 0;
                $totalNetMenu = 0;
                $totalItemSoldAdds = 0;
                $totalItemRefundAdds = 0;
                $totalGrossAdds = 0;
                $totalDiscountAdds = 0;
                $totalRefundAdds = 0;
                $totalNetAdds = 0;

            @endphp
            @foreach ($itemSalesMenu as $menu)
            @if (count($menu['Variants']) > 0)
                    @foreach ($menu['Variants'] as $variant)
                        <tr class="body-data">
                            <td class="data-item list-nama">
                                @if(!empty($menu['Name'])) 
                                    {{ $menu['Name'] }} 
                                    @if(!empty($variant)) - {{ $variant }} @endif 
                                @else 
                                    Menu Not available 
                                @endif
                            </td>
                            <td class="data-item">{{ $menu['itemSold'] }}</td>
                            <td class="data-item">{{ $menu['itemrefund'] }}</td>
                            <td class="data-item">Rp. {{ number_format($menu['GrossSalse'], 0, ',', '.') }}</td>
                            <td class="data-item">(Rp. {{ number_format($menu['Discount'], 0, ',', '.') }})</td>
                            <td class="data-item">(Rp. {{ number_format($menu['Refund'], 0, ',', '.') }})</td>
                            <td class="data-item">Rp. {{ number_format($menu['NetSales'], 0, ',', '.') }}</td>
                            <td class="data-item">Rp. {{ number_format($menu['NetSales'], 0, ',', '.') }}</td>
                        </tr>
                        @php
                            $totalItemSoldMenu += $menu['itemSold'];
                            $totalItemRefundMenu += $menu['itemrefund'];
                            $totalGrossMenu += $menu['GrossSalse'];
                            $totalDiscountMenu += $menu['Discount'];
                            $totalRefundMenu += $menu['Refund'];
                            $totalNetMenu += $menu['NetSales'];
                        @endphp
                    @endforeach
                @else
                    <tr class="body-data">
                        <td class="data-item list-nama">{{ $menu['Name'] }}</td>
                        <td class="data-item">{{ $menu['itemSold'] }}</td>
                        <td class="data-item">{{ $menu['itemrefund'] }}</td>
                        <td class="data-item">Rp. {{ number_format($menu['GrossSalse'], 0, ',', '.') }}</td>
                        <td class="data-item">(Rp. {{ number_format($menu['Discount'], 0, ',', '.') }})</td>
                        <td class="data-item">(Rp. {{ number_format($menu['Refund'], 0, ',', '.') }})</td>
                        <td class="data-item">Rp. {{ number_format($menu['NetSales'], 0, ',', '.') }}</td>
                        <td class="data-item">Rp. {{ number_format($menu['NetSales'], 0, ',', '.') }}</td>
                    </tr>
                    @php
                            $totalItemSoldMenu += $menu['itemSold'];
                            $totalItemRefundMenu += $menu['itemrefund'];
                            $totalGrossMenu += $menu['GrossSalse'];
                            $totalDiscountMenu += $menu['Discount'];
                            $totalRefundMenu += $menu['Refund'];
                            $totalNetMenu += $menu['NetSales'];
                    @endphp
                @endif
            @endforeach
           
            @foreach ($itemSalesAdss as $itmsAdds)
                <tr class="body-data">
                    <td class="data-item list-nama">{{ $itmsAdds['Name']->name }}</td>
                    <td class="data-item">{{ $itmsAdds['item Sold'] }}</td>
                    <td class="data-item">{{ $itmsAdds['item refund'] }}</td>
                    <td class="data-item">Rp. {{number_format( $itmsAdds['Gross Salse'], 0, ',','.')}}</td>
                    <td class="data-item">(0)</td>
                    <td class="data-item">(Rp. {{number_format( $itmsAdds['Refund'], 0, ',','.')}})</td>
                    <td class="data-item">Rp. {{number_format( $itmsAdds['Net Sales'] , 0, ',','.')}}</td>
                    <td class="data-item">Rp. {{number_format( $itmsAdds['Net Sales'] , 0, ',','.')}}</td>
                </tr>
                @php
                    $totalItemSoldAdds += $itmsAdds['item Sold'];
                    $totalItemRefundAdds +=$itmsAdds['item refund'];
                    $totalGrossAdds += $itmsAdds['Gross Salse'];
                    $totalRefundAdds += $itmsAdds['Refund'];
                    $totalNetAdds += $itmsAdds['Net Sales'];
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="data-item list-nama">Total</td>
                <td>{{ $totalItemSoldMenu + $totalItemSoldAdds}}</td>
                <td>{{ $totalItemRefundMenu + $totalItemRefundAdds}}</td>
                <td>Rp. {{number_format( $totalGrossMenu + $totalGrossAdds , 0, ',','.')}}</td>
                <td>(Rp. {{number_format( $totalDiscountMenu , 0, ',','.')}} )</td>
                <td>(Rp. {{number_format( $totalRefundMenu + $totalRefundAdds , 0, ',','.')}})</td>
                <td>Rp. {{number_format( $totalNetMenu + $totalNetAdds , 0, ',','.')}}</td>
                <td>Rp. {{number_format( $totalNetMenu + $totalNetAdds , 0, ',','.')}}</td>
            </tr>
        </tfoot>
    </table>



</div>

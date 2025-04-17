{{--  <div class="card-body  p-0">
    <table class="tebel-item">
        <thead>
            <tr class="row-itm">
                <th class="head-row-item list-nama">Name</th>
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
            @if (isset($menu['Variants']) && is_array($menu['Variants']) && count($menu['Variants']) > 0)
                    @foreach ($menu['Variants'] as $variant)
                        <tr class="body-data">
                            <td class="data-item list-nama">
                                @if(!empty($menu['Name'])) 
                                    {{ $menu['Name'] }} - {{ $variant['Name'] }}
                                @else 
                                    Menu Not available 
                                @endif
                            </td>
                            </td></td>
                            <td class="data-item">{{ $variant['itemSold'] ?? 0 }}</td>
                            <td class="data-item">{{ $variant['itemrefund'] ?? 0 }}</td>
                            <td class="data-item">Rp. {{ number_format($variant['GrossSalse'] , 0, ',', '.') }}</td>
                            <td class="data-item">(Rp. {{ number_format($variant['Discount'] , 0, ',', '.') }})</td>
                            <td class="data-item">(Rp. {{ number_format($variant['Refund'] , 0, ',', '.') }})</td>
                            <td class="data-item">Rp. {{ number_format($variant['NetSales'] , 0, ',', '.') }}</td>
                            <td class="data-item">Rp. {{ number_format($variant['NetSales'], 0, ',', '.') }}</td>
                        </tr>
                        @php
                            $totalItemSoldMenu += $variant['itemSold'];
                            $totalItemRefundMenu += $variant['itemrefund'];
                            $totalGrossMenu += $variant['GrossSalse'];
                            $totalDiscountMenu += $variant['Discount'];
                            $totalRefundMenu += $variant['Refund'];
                            $totalNetMenu += $variant['NetSales'];
                        @endphp
                    @endforeach
                @else
                    <tr class="body-data">
                        <td class="data-item list-nama">
                                @if(!empty($menu['Name'])) 
                                    {{ $menu['Name'] }} 
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



</div>  --}}
<div class="card-body p-0">
    <table class="tebel-item">
        <thead>
            <tr class="row-itm">
                <th class="head-row-item list-nama">Name</th>
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
                // Variabel total keseluruhan
                $totalItemSoldMenu = $totalItemRefundMenu = $totalGrossMenu = 0;
                $totalDiscountMenu = $totalRefundMenu = $totalNetMenu = 0;
                $totalItemSoldMenuOld = $totalItemRefundMenuOld = $totalGrossMenuOld = 0;
                $totalDiscountMenuOld = $totalRefundMenuOld = $totalNetMenuOld = 0;
            @endphp

            @foreach ($itemSalesMenu as $menu)
                @if (!empty($menu['Variants'])) {{-- Jika ada varian --}}
                    @foreach ($menu['Variants'] as $variant)
                        <tr class="body-data">
                            <td class="data-item list-nama">
                                {{ $menu['Name'] ?? 'Menu Not available' }} - {{ $variant['variant_name'] }}
                            </td>
                            <td class="data-item">{{ $variant['itemSold'] ?? 0 }}</td>
                            <td class="data-item">{{ $variant['itemrefund'] ?? 0 }}</td>
                            <td class="data-item">Rp. {{ number_format($variant['GrossSalse'] ?? 0, 0, ',', '.') }}</td>
                            <td class="data-item">(Rp. {{ number_format($variant['Discount'] ?? 0, 0, ',', '.') }})</td>
                            <td class="data-item">(Rp. {{ number_format($variant['Refund'] ?? 0, 0, ',', '.') }})</td>
                            <td class="data-item">Rp. {{ number_format($variant['NetSales'] ?? 0, 0, ',', '.') }}</td>
                            <td class="data-item">Rp. {{ number_format($variant['NetSales'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        @php
                            // Hitung total varian
                            $totalItemSoldMenu += $variant['itemSold'] ?? 0;
                            $totalItemRefundMenu += $variant['itemrefund'] ?? 0;
                            $totalGrossMenu += $variant['GrossSalse'] ?? 0;
                            $totalDiscountMenu += $variant['Discount'] ?? 0;
                            $totalRefundMenu += $variant['Refund'] ?? 0;
                            $totalNetMenu += $variant['NetSales'] ?? 0;
                        @endphp
                    @endforeach
                @else {{-- Jika tidak ada varian --}}  
                    <tr class="body-data">
                        <td class="data-item list-nama">{{ $menu['Name'] ?? 'Menu Not available' }}</td>
                        <td class="data-item">{{ $menu['itemSold'] ?? 0 }}</td>
                        <td class="data-item">{{ $menu['itemrefund'] ?? 0 }}</td>
                        <td class="data-item">Rp. {{ number_format($menu['GrossSalse'] ?? 0, 0, ',', '.') }}</td>
                        <td class="data-item">(Rp. {{ number_format($menu['Discount'] ?? 0, 0, ',', '.') }})</td>
                        <td class="data-item">(Rp. {{ number_format($menu['Refund'] ?? 0, 0, ',', '.') }})</td>
                        <td class="data-item">Rp. {{ number_format($menu['NetSales'] ?? 0, 0, ',', '.') }}</td>
                        <td class="data-item">Rp. {{ number_format($menu['NetSales'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    @php
                        // Hitung total menu tanpa varian
                        $totalItemSoldMenu += $menu['itemSold'] ?? 0;
                        $totalItemRefundMenu += $menu['itemrefund'] ?? 0;
                        $totalGrossMenu += $menu['GrossSalse'] ?? 0;
                        $totalDiscountMenu += $menu['Discount'] ?? 0;
                        $totalRefundMenu += $menu['Refund'] ?? 0;
                        $totalNetMenu += $menu['NetSales'] ?? 0;
                    @endphp
                @endif
            @endforeach
            @foreach ( $itmOrderNonVar as $menuOld )
                    <tr class="body-data">
                        <td class="data-item list-nama">{{ $menuOld['Name'] }}</td>
                        <td class="data-item">{{ $menuOld['itemSold'] ?? 0 }}</td>
                        <td class="data-item">{{ $menuOld['itemrefund'] ?? 0 }}</td>
                        <td class="data-item">Rp. {{ number_format($menuOld['GrossSalse'] ?? 0, 0, ',', '.') }}</td>
                        <td class="data-item">(Rp. {{ number_format($menuOld['Discount'] ?? 0, 0, ',', '.') }})</td>
                        <td class="data-item">(Rp. {{ number_format($menuOld['Refund'] ?? 0, 0, ',', '.') }})</td>
                        <td class="data-item">Rp. {{ number_format($menuOld['NetSales'] ?? 0, 0, ',', '.') }}</td>
                        <td class="data-item">Rp. {{ number_format($menuOld['NetSales'] ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    @php
                        // Hitung total menu tanpa varian
                        $totalItemSoldMenuOld += $menuOld['itemSold'] ?? 0;
                        $totalItemRefundMenuOld += $menuOld['itemrefund'] ?? 0;
                        $totalGrossMenuOld += $menuOld['GrossSalse'] ?? 0;
                        $totalDiscountMenuOld += $menuOld['Discount'] ?? 0;
                        $totalRefundMenuOld += $menuOld['Refund'] ?? 0;
                        $totalNetMenuOld += $menuOld['NetSales'] ?? 0;
                    @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="data-item list-nama">Total</td>
                <td>{{ $totalItemSoldMenu +  $totalItemSoldMenuOld}}</td>
                <td>{{ $totalItemRefundMenu + $totalItemRefundMenuOld}}</td>
                <td>Rp. {{ number_format($totalGrossMenu + $totalGrossMenuOld, 0, ',', '.') }}</td>
                <td>(Rp. {{ number_format($totalDiscountMenu + $totalDiscountMenuOld, 0, ',', '.') }})</td>
                <td>(Rp. {{ number_format($totalRefundMenu + $totalRefundMenuOld, 0, ',', '.') }})</td>
                <td>Rp. {{ number_format($totalNetMenu + $totalNetMenuOld, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($totalNetMenu + $totalNetMenuOld, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</div>

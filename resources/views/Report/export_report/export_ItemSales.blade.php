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

            {{-- <tr><td>{{$itemsOrderNonVar}}</td></tr> --}}
            
            @if (is_array($itemsOrderNonVar))
                @foreach ( $itemsOrderNonVar as $menuOld )
                    {{-- @php
                        dd($itmOrderNonVar);
                    @endphp --}}
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
            @else 
            @endif
            
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
<div class="card-body  p-0">
    <table class="tebel-item">
        <thead>
            <tr class="row-itm">
                <th class="head-row-item list-nama">Kode Pemesanan</th>
                <th class="head-row-item">Tanggal_transaksi</th>
                <th class="head-row-item">Tanggal_payment</th>
                <th class="head-row-item">Item Name</th>
                <th class="head-row-item">QTY</th>
                <th class="head-row-item">Gross Sales</th>
                <th class="head-row-item">Discount</th>
                <th class="head-row-item">Total</th>
                <th class="head-row-item">Refund Qty</th>
                <th class="head-row-item">Discount Refund</th>
                <th class="head-row-item">Refund</th>
                <th class="head-row-item">NetSales</th>
                <th class="head-row-item">Payment Methode</th>
            </tr>
        </thead>
        <tbody>
            @if(count($itemSalesMenu) == 0)
            <tr>
                <td colspan="11">No data available for export.</td>
            </tr>
            @else
            @foreach ($itemSalesMenu as $menu)
            <tr class="body-data">
                <td class="data-item list-nama">{{ $menu['Kode_Pesanan'] }}</td>
                <td class="data-item">{{ $menu['create'] }}</td>
                <td class="data-item">{{ $menu['Tanggal'] }}</td>
                <td class="data-item">{{ $menu['Name'] }} @if(!empty($menu->varian->nama)) - {{ $menu->varian->nama }}@else @endif</td>
                <td class="data-item">{{ $menu['itemSold'] }}</td>
                <td class="data-item">Rp. {{ number_format($menu['GrossSalse'] ?? 0, 0, ',', '.') }}</td>
                <td class="data-item">(Rp. {{ number_format($menu['Discount'] ?? 0, 0, ',', '.') }})</td>
                <td class="data-item">Rp. {{ number_format($menu['Total'] ?? 0, 0, ',', '.') }}</td>
                <td class="data-item">(@if(empty($menu['itemrefund'])) 0 @else {{ $menu['itemrefund'] }} @endif)</td>
                <td class="data-item">(Rp. {{ number_format($menu['Discount_ref'] ?? 0, 0, ',', '.') }})</td>
                <td class="data-item">(Rp. {{ number_format($menu['Refund'] ?? 0, 0, ',', '.') }})</td>
                <td class="data-item">Rp. {{ number_format($menu['NetSales'] ?? 0, 0, ',', '.') }}</td>
                <td class="data-item">{{ $menu['paymentMetode'] }}</td>
            </tr>
            @endforeach
            @endif
        </tbody>

    </table>



</div>
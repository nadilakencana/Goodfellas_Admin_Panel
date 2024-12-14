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
                $totalNominalKat = 0;

            @endphp
          @foreach ($kategori as $kat)
                @php
                    $netSales = ($kat['GrossSalse'] - $kat['Refund']) - $kat['Discount'];
                    $totalItemSoldMenu += $kat['itemSold'];
                    $totalItemRefundMenu += $kat['itemrefund'];
                    $totalGrossMenu += $kat['GrossSalse'];
                    $totalDiscountMenu += $kat['Discount'];
                    $totalRefundMenu += $kat['Refund'];
                    $totalNetMenu += $netSales;
                @endphp

                <tr class="body-data">
                    <td class="data-item list-nama">{{ $kat['Name']->sub_kategori }}</td>
                    <td class="data-item">{{ $kat['itemSold'] }}</td>
                    <td class="data-item">{{ $kat['itemrefund'] }}</td>
                    <td class="data-item">Rp. {{number_format( $kat['GrossSalse'], 0, ',','.')}}</td>
                    <td class="data-item">(Rp. {{ number_format( $kat['Discount'], 0, ',','.')  }})</td>
                    <td class="data-item">(Rp. {{ number_format( $kat['Refund'], 0, ',','.')  }} )</td>
                    <td class="data-item">Rp. {{number_format( $netSales, 0, ',','.')}}</td>
                    <td class="data-item">Rp. {{number_format( $netSales, 0, ',','.')}}</td>
                </tr>

                
          @endforeach
          @foreach ($modifier as $mod)
            <tr class="body-data">
                <td class="data-item list-nama">{{ $mod['Name']->name }}</td>
                <td class="data-item">{{ $mod['itemSold'] }}</td>
                <td class="data-item">{{ $mod['itemrefund'] }}</td>
                <td class="data-item">Rp. {{number_format( $mod['Gross Salse'], 0, ',','.')}}</td>
                <td class="data-item">(0)</td>
                <td class="data-item">(Rp. {{number_format( $mod['Refund'], 0, ',','.')}})</td>
                <td class="data-item">Rp. {{number_format( $mod['NetSales'] , 0, ',','.')}}</td>
                <td class="data-item">Rp. {{number_format( $mod['NetSales'] , 0, ',','.')}}</td>
            </tr>
             @php
                $totalItemSoldAdds += $mod['itemSold'];
                $totalItemRefundAdds +=$mod['itemrefund'];
                $totalGrossAdds += $mod['Gross Salse'];
                $totalRefundAdds += $mod['Refund'];
                $totalNetAdds += $mod['NetSales'];
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

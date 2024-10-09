<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Quantity Sold</th>
            <th>Gross Sales</th>
            <th>Discount</th>
            <th>Refund</th>
            <th>Net Sales</th>
        </tr>
    </thead>
    <tbody>
        @php
            $qty = 0;
            $Gross = 0;
            $Dis = 0;
            $ref = 0;
            $netSels= 0;
        @endphp
        @foreach ($itemSalesAdss as $itms )
        <tr>
            <td>{{ $itms['Name']->name}}</td>
            <td>{{ $itms['item Sold'] }}</td>
            <td>{{$itms['Gross Salse']}}</td>
            <td>{{$itms['DisNominal']}}</td>
            <td>{{$itms['Refund']}}</td>
            <td>{{$itms['Net Sales']}}</td>
        </tr>
          @php
            $qty += $itms['item Sold'];
            $Gross += $itms['Gross Salse'];
            $Dis += $itms['DisNominal'];
            $ref += $itms['Refund'];
            $netSels += $itms['Net Sales'];
        @endphp
        @endforeach
        <tr>
            <td>Total </td>
            <td>{{ $qty }}</td>
            <td>{{$Gross}}</td>
            <td>{{$Dis}}</td>
            <td>{{$ref}}</td>
            <td>{{$netSels}}</td>
        </tr>
    </tbody>
</table>

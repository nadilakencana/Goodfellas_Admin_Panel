<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Discount Amount</th>
            <th>Count</th>
            <th>Gross Discount</th>
            <th>Discount Refund</th>
            <th>Net Discount</th>
        </tr>
    </thead>
    <tbody>
        @php
            $count = 0;
            $Gross = 0;
            $ref = 0;
            $netSels= 0;
        @endphp
        @foreach ($dataDiscount as $itms )
        <tr>
            <td>{{ $itms['nama']->nama }}</td>
            <td>{{ $itms['nama']->rate_dis }}</td>
            <td>{{$itms['count']}}</td>
            <td>{{ $itms['Gross']}}</td>
            <td>{{ $itms['refund']}}</td>
            <td>{{ $itms['Net']}}</td>
        </tr>
           @php
            $count += $itms['count'];
            $Gross += $itms['Gross'];
            $ref += $itms['refund'];
            $netSels += $itms['Net'];
        @endphp
        @endforeach
        <tr>
            <td>Total </td>
            <td></td>
            <td>{{$count}}</td>
            <td>{{ $Gross}}</td>
            <td>{{ $ref}}</td>
            <td>{{ $netSels}}</td>
        </tr>
    </tbody>
</table>

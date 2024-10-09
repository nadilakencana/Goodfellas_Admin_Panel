<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Tax Rate</th>
            <th>Taxable Amount</th>
            <th>Taxable Collected</th>
        </tr>
    </thead>
    <tbody>
         @php
            $totalTax=0;
        @endphp
        @foreach ($dataTax as $taxs )
        <tr>
            <td>{{ $taxs['Taxs']->nama }}</td>
            <td>{{ $taxs['Taxs']->tax_rate }}%</td>
            <td>{{$taxs ['Net']}}</td>
            <td>{{$taxs ['taxTotal']}}</td>
        </tr>
          @php
                $totalTax += $taxs ['taxTotal'];
            @endphp
        @endforeach
        <tr>
            <td>Total </td>
            <td></td>
            <td></td>
            <td>{{$totalTax}}</td>
        </tr>
    </tbody>
</table>

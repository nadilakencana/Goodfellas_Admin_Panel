<table>
    <thead>
        <tr>
            <th>Type</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Gross Sales</td>
            <td>{{ $allGrandSales }}</td>
        </tr>
        <tr>
            <td>Discounts</td>
            <td>{{ $allGrandDis }}</td>
        </tr>
        <tr>
            <td>Refunds</td>
            <td>{{ $allGrandRefund }}</td>
        </tr>
        <tr>
           <td>Net Sales</td>
            <td>{{$allGrandNet  }}</td>
        </tr>
        <tr>
            <td>Gross Profit</td>
            <td>{{ $allGrandNet }}</td>
        </tr>
    </tbody>
</table>

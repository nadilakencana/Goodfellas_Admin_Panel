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
            <td>Tax</td>
            <td>{{ $totalTax }}</td>
        </tr>
        <tr>
            <td>Total Collected</td>
            <td>{{ $TotalGrand }}</td>
        </tr>
    </tbody>
</table>

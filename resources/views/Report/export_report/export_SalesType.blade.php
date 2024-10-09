<table>
    <thead>
        <tr>
            <th>Sales Type</th>
            <th>Count</th>
            <th>Total Collected</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalOrders =0;
            $totalPembayarans = 0;
        @endphp
        @foreach ($SalesData as $sales )
        <tr>
            <td>{{ $sales['Sales Type'] }}</td>
            <td>{{ $sales['totalOrder'] }}</td>
            <td>{{ $sales['Total'] }}</td>
        </tr>
         @php
                $totalOrders += $sales ['totalOrder'];
                $totalPembayarans += $sales ['Total'];

            @endphp
        @endforeach
        <tr>
            <td>Total </td>
            <td>{{ $totalOrders }}</td>
            <td>Rp. {{number_format( $totalPembayarans, 0, ',','.')}}</td>
        </tr>
    </tbody>
</table>

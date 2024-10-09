<table>
    <thead>
        <tr>
            <th>Payment Methods</th>
            <th>Transaksi</th>
            <th>Total Collected</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalOrders =0;
            $totalPembayarans = 0;
        @endphp
        @foreach ($paymentData as $payment )
        <tr>
            <td>{{ $payment['paymentMethod']['nama'] }}</td>
            <td>{{ $payment['totalOrder'] }}</td>
            <td>{{ $payment['totalPembayaran'] }}</td>
        </tr>
         @php
                $totalOrders += $payment ['totalOrder'];
                $totalPembayarans += $payment ['totalPembayaran'];

            @endphp
        @endforeach
        <tr>
            <td>Total </td>
            <td>{{ $totalOrders }}</td>
            <td>Rp. {{number_format( $totalPembayarans, 0, ',','.')}}</td>
        </tr>
    </tbody>
</table>

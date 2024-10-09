@php $no=1; @endphp
@foreach ($order as $end )

<tr class="tbl-data">
    <td>{{ $no++ }}</td>

    <td>{{ $end ->kode_pemesanan }}</td>
    <td>{{ $end ->no_meja }}</td>
    <td>{{date("d/m/Y", strtotime($end->created_at))  }}</td>
    <td>{{number_format(  $end->total_order, 0, ',','.')}}</td>
    <td>
        <div class="text-center">
        <a href="{{route('detail.order',($end->kode_pemesanan))}}" type="button" class="btn btn-block btn-warning mb-2">
            Detail
        </a>
        <div id-item="{{ $end->id }}" type="button" class="btn btn-block btn-danger deleted-order-finish mb-2">
          Delete
        </div>
        </div>
    </td>
</tr>

@endforeach
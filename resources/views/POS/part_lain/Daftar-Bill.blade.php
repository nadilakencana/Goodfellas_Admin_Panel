<div class="pop-daftar-bill" >
    <div class="card-list-bill">
        <div class="header-card">
            <div class="txt-tittle">Daftar Bill</div>
            <div class="close">X</div>
        </div>
        <div class="content-list-bill">
            <div class="serach">
                <div class="search-list-bill" contenteditable></div>
            </div>
            <div class="list-bill">
                <table class="list-bill-table">
                    <thead class="label-tabel">
                        <th>Date & Time</th>
                        <th>No Meja</th>
                        <th>Kode Pesanan</th>
                        <th>Nama Customer</th>
                        <th>Status</th>
                    </thead>
                    <tbody class="data-bill">
                        @foreach ($billOrder as $bill )
                        
                        <tr class="item-bill local" idx="{{ $bill->id }}">
                            <td>{{$bill->created_at}}</td>
                            <td>
                                @if(!empty($bill->no_meja))
                                {{ $bill->no_meja }}
                                @elseif(!empty($bill->booking->room->type_room))
                                 {{ $bill->booking->room->type_room }}
    
                                @else
                                -                               
                                 @endif
                            </td>
                            
                            <td class="kode-pemesanan">
                                {{ $bill->kode_pemesanan}}
                            </td>
                            <td>
                                @if(!empty($bill->user->nama))
                                 {{ $bill->user->nama }}
                                @elseif(!empty($bill->name_bill))
                                    {{ $bill->name_bill }}
                                @else
                                 -
                                @endif
                            </td>
                            <td>
                                {{ $bill->status->status_order }}
                            </td>
                        </tr>
                        @endforeach

                        {{-- @foreach ($billServer as $dataServer )
                        <tr class="item-bill server" idx="{{ $dataServer['id'] }}">
                            <td>
                                @if(!empty($dataServer['user']))
                                 {{ $dataServer['user']['nama'] }}
                                @elseif(!empty($dataServer['name_bill']))
                                    {{ $dataServer['name_bill'] }}
                                @else
                                 -
                                @endif
                            </td>
                            <td class="kode-pemesanan">
                                {{ $dataServer['kode_pemesanan']}}
                            </td>
                            <td>
                                @if(!empty($dataServer['no_meja']))
                                {{ $dataServer['no_meja'] }}
                                @else
                                 {{ $dataServer['booking']['room']['type_room'] }}
                                @endif
                            </td>
                            <td>
                                {{ $dataServer['status']['status_order'] }}
                            </td>
                        </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

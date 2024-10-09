<div class="part-detail-isi">
    <div class="header-detail">
        {{--  <div class="barcode-claim"></div>  --}}
        <div class="kode-vocher">{{ $vocherClaim->kode_qr }}</div>
    </div>
    <div class="detail-user">
        <div class="user">
            <label for="" class="txt-name">Name </label>
            <div class="nama-user">
                {{ $vocherClaim->user->nama }}
            </div>
        </div>
        <div class="user">
            <label for="" class="txt-name">No Handphone</label>
            <div class="nama-user">
               {{ $vocherClaim->user->no_hp }}
            </div>
        </div>
        <div class="item-vocher">
            <div class="img-item">
                <img src="{{ $vocherClaim->vocher->image }}" alt="">
            </div>
             <div class="status-claim">
                <div class="nama-itm">{{ $vocherClaim->vocher->nama_vocher }}</div>

                <div class="status">{{ $vocherClaim->flag }}</div>


                @if(!empty($vocherClaim->id_admin))
                <div class="admin">
                    <label for="" class="txt-name">Admin</label>
                    <div class="txt-name-admin">: {{ $vocherClaim->admin->nama }}</div>
                </div>
                @else
                  <div class="admin">
                    <label for="" class="txt-name">Admin</label>
                    <div class="txt-name-admin">: -</div>
                </div>
                @endif

                @if(!empty($vocherClaim->tanggal_tukar))
                <div class="admin">
                    <label for="" class="txt-name">Date</label>
                    <div class="txt-name-admin">: {{ $vocherClaim->tanggal_tukar }}</div>
                </div>
                @else
                 <div class="admin">
                    <label for="" class="txt-name">Date</label>
                    <div class="txt-name-admin">: - </div>
                </div>
                @endif

            </div>
        </div>
        @if($vocherClaim->tanggal_tukar == null)
            <div class="btn-claim vcr" xid="{{ $vocherClaim->id }}">Claim Vocher</div>
        @else
        @endif

    </div>
</div>

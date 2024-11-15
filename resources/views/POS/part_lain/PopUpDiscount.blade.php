<div class="pop-daftar-discount" >
    <div class="card-list-discount">
        <div class="header-card">
            <div class="txt-tittle">Daftar Discount</div>
            <div class="close">X</div>
        </div>
        <div class="content-list-discount">
            <div class="discount">
                @foreach ($discount as $dis )
                <div class="option-discount">
                    <p class="nama-dis">{{ $dis->nama }} {{ $dis->rate_dis }}%</p>
                    <input class="opDis" type="checkbox" name="" id="{{ $dis->id }}" rate="{{ $dis->rate_dis }}">
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
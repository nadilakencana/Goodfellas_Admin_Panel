{{-- Bill Name Popup --}}
<div class="popup-name-bill" style="display: none">
    <div class="position-card">
        <div class="card-colum-input">
            <div class="header-card">
                <div class="txt-tittle"></div>
                <div class="total-payment"></div>
                <div class="close">X</div>
            </div>
            <div class="form-group">
                <label for="" class="form-label">Name Bill</label>
                <input type="text" class="form-control nameBill">
            </div>
            <button class="btn save-bill">Selesai</button>
        </div>
    </div>
</div>

{{-- Quantity Popup --}}
<div class="popup-qty" style="display: none">
    <div class="position-card">
        <div class="card-colum-input" style="width: 500px;max-height: 450px; height:auto;">
            <div class="header-card">
                <div class="txt-tittle"></div>
                <div class="total-payment"></div>
                <div class="close">X</div>
            </div>
            <div class="cotent-detail" style="overflow: scroll;max-height: 330px;height:auto;"></div>
            <button class="btn btn-selesai" disabled>Oke</button>
        </div>
    </div>
</div>

{{-- Category Custom Popup --}}
<div class="popup-category-custom" style="display: none">
    <div class="position-card">
        <div class="card-colum-input">
            <div class="header-card">
                <div class="txt-tittle">Choice Category Custom</div>
                <div class="total-payment"></div>
                <div class="close">X</div>
            </div>
            <div class="content-payment">
                @foreach ($customItem as $item)
                    <div class="part-category uncative p-1" xid="{{ $item->id }}">
                        <p class="text-paymnt" data="{{ $item->id }}">{{ $item->nama_menu }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
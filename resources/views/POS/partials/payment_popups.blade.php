{{-- Payment Method Popup --}}
<div class="pop-payment" style="display: none">
    <div class="card-payment">
        <div class="header-card">
            <div class="txt-tittle">Payment Method</div>
            <div class="total-payment"></div>
            <div class="close">X</div>
        </div>
        <div class="content-payment">
            @foreach ($payment as $pay)
                <div class="part-payment unactive" xid="{{ $pay->id }}">
                    <p class="text-paymnt" data="{{ $pay->nama }}">{{ $pay->nama }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Payment Nominal Popup --}}
<div class="payment-nominal" style="display: none">
    <div class="card-payment-nominal">
        <div class="header-card">
            <div class="nominal" data-nominal="">
                <div class="nm-payment"></div>
            </div>
        </div>
        <div class="form-cash">
            <div class="txt">Cash nominal</div>
            <input type="text" placeholder="Rp 0" class="cash-nominal-input" oninput="formatRupiah(this)">
            <input type="hidden" class="convert-cash" value="0">
        </div>
        <div class="form-cash">
            <div class="txt">Change nominal</div>
            <input type="text" placeholder="Rp 0" class="change-input" value="" oninput="formatRupiah(this)">
            <input type="hidden" class="convert-change">
        </div>
        <div class="footer-card">
            <div class="tooltip payment" style="display: block;margin-top: -22px;">
                Cash nominal tidak boleh kosong
            </div>
            <button class="btn btn-selesai btn-payment" data-type="">Selesai</button>
            <div class="btn-close-part">
                <p class="text-btn-act" style="margin: 0px">Close</p>
            </div>
        </div>
    </div>
</div>
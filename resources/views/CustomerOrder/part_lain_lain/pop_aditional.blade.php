<div class="content-modal col-12 px-0 col-sm-8 col-md-4 fixed-bottom mx-auto p-0">
    <div class="pop-modal mt-auto">

        <div class="header d-flex justify-content-between align-items-center p-1">
            <p class="fw-bold">Nama Menu</p>
            <p class="fw-bold">X</p>
        </div>
        <div class="body-content">

            <div class="variasi-menu pt-3 mb-2">
                <p class="mb-0 pb-1">Varian Menu | Choose One</p>
                <div class="items-varias d-flex justify-content-between align-items-center gap-3">
                    <div class="itm-var">
                        <span>Hot</span>
                        <span>Rp. 30.0000</span>
                    </div>
                    <div class="itm-var">
                        <span>Cold</span>
                        <span>Rp. 30.0000</span>
                    </div>

                </div>
            </div>
            <div class="additional pb-3">
                <p class="mb-0 pb-1">Additional Menu :</p>
                <div class="items-Adds d-flex flex-column">
                    <div class="itm-adds mb-2">
                        <span>Hot</span>
                        <div class="d-flex gap-4">
                            <span>Rp. 30.0000</span>
                            <input type="radio" class="additional-itms" name="" id="">
                        </div>
                    </div>
                    <div class="itm-adds mb-2">
                        <span>Hot</span>
                        <div class="d-flex gap-4">
                            <span>Rp. 30.0000</span>
                            <input type="radio" class="additional-itms" name="" id="">
                        </div>
                    </div>
                    <div class="itm-adds mb-2">
                        <span>Hot</span>
                        <div class="d-flex gap-4">
                            <span>Rp. 30.0000</span>
                            <input type="radio" class="additional-itms" name="" id="">
                        </div>

                    </div>

                </div>
            </div>
            <div class="jumlah-menu ">
                <div class="jml-menu d-flex justify-content-between align-items-center gap-3">
                    <div class="btn-minus">-</div>
                    <input class="qty" type="number" name="num-product" min=0 value=1 max=100>
                    <div class="btn-plus">+</div>
                </div>
            </div>
            <div class="tipe-penjualan py-3">
                <div class="name-additional pb-2">Sales Type | Choose one</div>

                <div class="option-type d-flex justify-content-center align-items-center gap-4">
                    <div class="nama-option w-100" idx="">Take Away</div>
                    <div class="nama-option w-100" idx="">Take Away</div>
                </div>

            </div>
            <div class="catatan-menu">
                <div class="name-additional">Catatan</div>
                <textarea name="" id="" cols="54" rows="5"></textarea>
            </div>
            <div class="btn btn-add-items d-flex justify-content-center gap-4">
                <img src="{{ asset('asset/assets/image/icon/icon_plus.png') }}" alt="" width="20"
                    height="20">
                <p class="m-0">Add</p>
            </div>
        </div>
    </div>

</div>

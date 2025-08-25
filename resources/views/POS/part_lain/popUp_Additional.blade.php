<div class="pop-up additional" style="display: none">
    {{--  id-x dari id dari menu  --}}
    <div class="card-popup" id-x="" key-id="" id_detail="">
        <div class="header-card-popup">
            {{--  x-id dari id   --}}
            <div class="tooltip">Item tidak bisa di add sebelum memilih variant item dan Sales Type</div>
            <button class="btn btn-add" x-id="" key="">Add</button>
            <div class="harga-total" price=""></div>
            <p class="close">X</p>
        </div>
        <div class="content-popup">
            <div class="option-menu-add">
                <div class="Varian-menu">
                   

                </div>

                <div class="additional-menu">
                    
                </div>
            </div>


            <div class="jumlah-menu">
                <div class="jml-menu">
                    <div class="btn-minus">-</div>
                    <input class="qty" type="number" name="num-product" min="1" value="1" max="">
                    <div class="btn-plus">+</div>
                </div>
            </div>

            <div class="discount">
                <div class="name-additional">Discount</div>
                @foreach ($discount as $dis )
                <div class="option-discount">
                    <p class="nama-dis">{{ $dis->nama }} {{ $dis->rate_dis }}%</p>
                    <input class="opDis" type="checkbox" name="" id="{{ $dis->id }}" rate="{{ $dis->rate_dis }}">
                </div>
                @endforeach


            </div>

            <div class="tipe-penjualan">
                <div class="name-additional">Sales Type | choose one</div>
                @foreach ($typeOrder as $typeSalses )
                <div class="option-type @if($typeSalses->name === 'Dine In') active @endif" idx="{{  $typeSalses->id }}">
                    <div class="nama-option ">{{ $typeSalses->name }}</div>
                </div>
                @endforeach

            </div>

            <div class="catatan-menu">
                <div class="name-additional">Catatan</div>
                <textarea name="" id="" cols="73" rows="5"></textarea>
            </div>
        </div>
    </div>
</div>

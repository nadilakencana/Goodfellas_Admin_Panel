<div class="menuSub">
    <div class="header-action">
        <div class="arrow">
            <img src="{{ asset('asset/assets/image/icon/arrow.png') }}" alt="" class="icon">
        </div>
        <p>{{ $subCategory->sub_kategori}}</p>
    </div>
       
    
    <div class="item-menu" data-search="FavMenuSearch">
        @foreach ($itemMenu as $item )
        <div class="item-card-menu" idx="{{ $item->id }}" target-price="{{ $item->harga }}">
            <div class="menu-sub">
                <div class="icon">
                    <p class="txt-icon">{{ $item->nama_menu }}</p>
                 </div>
                 <p class="txt-subMenu">{{ $item->nama_menu }}</p>
            </div>
            <div class="harga">
                <p class="txt-subMenu">{{ $item->harga }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

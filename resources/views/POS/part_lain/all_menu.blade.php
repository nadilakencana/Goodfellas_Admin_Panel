<div class="all-menu">
    <div class="header-action">
        <div class="icon-arrow">
            <img src="{{ asset('asset/assets/image/icon/arrow.png') }}" alt="" class="icon">
        </div>
        <p>All Menu</p>
    </div>
    <div class="tab-navigation-menu">
        <div class="menu-cat active" data-type="all-menu" order-menu="1">Minuman</div>
        <div class="menu-cat" data-type="all-menu" order-menu="2">Makanan</div>
    </div>
    <div class="tab-panel-menu" data-type="all-menu">
        <div class="menuKat">
            <div class="item-menu" data-search="FavMenuSearch">
                @foreach ($itemMenu as $item )
                    @if($item->id_kategori == 1)
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
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
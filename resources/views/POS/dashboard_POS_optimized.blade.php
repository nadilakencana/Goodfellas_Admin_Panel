@extends('layout.master')
@section('content')
    @include('POS.part_lain.popUp_Additional')
    
    <div class="popup-daftar-bill" style="display: none"></div>
    <div class="popup-daftar-discount" style="display: none"></div>
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Dashboard Point Of Sales</h3>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card-header">
                <div class="tab-navigation">
                    <div class="tab" target-panel="panel1" order="1">Favorite</div>
                    <div class="tab" target-panel="panel2" order="2">All Item</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="bar-search">
                        <input type="text" class="search-allmenu" id="FavMenuSearch" placeholder="Search Menu" autofocus>
                        <div class="img-icon" data-target="FavMenuSearch" id="search1">
                            <img src="{{ asset('asset/assets/image/icon _search_.png') }}" alt="" class="icon">
                        </div>
                    </div>
                    <div class="card height-card">
                        <div class="tab-panel-container">
                            <div class="panel active" data-panel="panel1" panel-order="1">
                                <div class="content-menu">
                                    <div class="kategory-menu">
                                        <div class="tab-panel-menu" data-type="Fav">
                                            <div class="menuKat">
                                                <div class="item-menu" data-search="FavMenuSearch">
                                                    @foreach ($itemMenu as $item)
                                                        <div class="item-card-menu" idx="{{ $item->id }}"
                                                            target-price="{{ $item->harga }}" stok="{{$item->stok}}" status="{{$item->active}}">
                                                            <div class="menu-sub">
                                                                <div class="icon">
                                                                    <p class="txt-icon">{{ $item->nama_menu }}</p>
                                                                </div>
                                                                <p class="txt-subMenu">{{ $item->nama_menu }}</p>
                                                            </div>
                                                            <div class="harga">
                                                                @if($item->kategori->kategori_nama === 'Foods')
                                                                    @if($item->stok > 0 && $item->active)
                                                                        <p class="txt-subMenu">{{ $item->harga }}</p>
                                                                    @else
                                                                        <div class="status" style="color: rgb(251, 42, 42)">unavailable</div>
                                                                    @endif
                                                                @elseif ($item->kategori->kategori_nama === 'Drinks')
                                                                    @if($item->active !== 0)
                                                                        <p class="txt-subMenu">{{ $item->harga }}</p>
                                                                    @else
                                                                        <div class="status" style="color: rgb(251, 42, 42)">unavailable</div>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="panel" data-panel="panel2" panel-order="2">
                                <div class="content-menu">
                                    <div class="sub-content">
                                        <div class="itmn-subcategory allmenu">
                                            <div class="menu-sub">
                                                <div class="icon-menu">
                                                    <img src="{{ asset('asset/assets/image/icon/icon _list_ white.png') }}" alt="">
                                                </div>
                                                <p class="txt-subMenu">All Menu</p>
                                            </div>
                                            <div class="icon-arrow">
                                                <img src="{{ asset('asset/assets/image/icon/icon _chevron arrow.png') }}" alt="">
                                            </div>
                                        </div>
                                        @foreach ($subCategory as $sub)
                                            <div class="itmn-subcategory menusub" idx="{{ $sub->id }}">
                                                <div class="menu-sub">
                                                    <div class="icon">
                                                        <p class="txt-icon">{{ $sub->sub_kategori }}</p>
                                                    </div>
                                                    <p class="txt-subMenu">{{ $sub->sub_kategori }}</p>
                                                </div>
                                                <div class="icon-arrow">
                                                    <img src="{{ asset('asset/assets/image/icon/icon _chevron arrow.png') }}" alt="">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Order Details Section --}}
                <div class="col-sm-6 detail-order">
                    <div class="card p-2 height-card-2">
                        <div class="header-part">
                            <div class="menu-icon-list-bil">
                                <img src="{{ asset('asset/assets/image/icon/icon _clipboard list_ white.png') }}" alt="">
                                <small class="txt-icon">Daftar Bil</small>
                            </div>
                            <div class="menu-delete">
                                <img src="{{ asset('asset/assets/image/icon/Delete.png') }}" alt="">
                                <small class="txt-icon">Delete Order</small>
                            </div>
                            <div class="menu-discount">
                                <img src="{{ asset('asset/assets/image/icon/Discount.png') }}" alt="">
                                <small class="txt-icon">Daftar Discount</small>
                            </div>
                            <div class="act-btn-add">Display Order</div>
                        </div>
                        
                        <div class="view-detail-ord">
                            @if (session()->has('cart'))
                                <div class="part-order">
                                    <div class="drop-down">
                                        <p class="txt-dropdown" style="margin: 0">Nomer Meja</p>
                                        <input type="text" class="nomer-meja" name="no_meja">
                                    </div>
                                    <div class="detil-bil">
                                        @php $total_dis = 0; @endphp
                                        @foreach ($carts as $k => $cart)
                                            <div class="itm-bil" idx="{{ $cart['id'] }}" xid="{{ $k }}" 
                                                stok="{{$cart['stok']}}" status="{{$cart['active']}}">
                                                <div class="itm">
                                                    <p class="txt-item">{{ $cart['nama_menu'] }}</p>
                                                    <div class="qty-menu">
                                                        <div class="jumlah">{{ $cart['qty'] }}</div>
                                                    </div>
                                                    <div class="part-float-right">
                                                        @php
                                                            $totalHarga = 0;
                                                            $discountTotal = 0;
                                                            $SubtotalHarga = $cart['harga'] + $cart['harga_addtotal'];
                                                            $totalHarga = ($cart['harga'] + $cart['harga_addtotal']) * $cart['qty'];
                                                            $discountTotal += $cart['total_dis'] ?? 0;
                                                            $subtotalDisCount = $totalHarga * ($discountTotal / 100);
                                                            $total = $totalHarga - $subtotalDisCount;
                                                        @endphp

                                                        @if (!empty($cart['harga']))
                                                            <p class="price" price="{{ $cart['harga'] }}">
                                                                {{ number_format($totalHarga, 0, ',', '.') }}
                                                            </p>
                                                        @endif
                                                        <div class="hapus-menu-order" idx="{{ $k }}">X</div>
                                                    </div>
                                                </div>
                                                
                                                <div class="detail-itm">
                                                    @if (!empty($cart['var_name']))
                                                        <small class="option varian-op" id_var="{{ $cart['variasi_id'] }}">
                                                            {{ $cart['var_name'] }}
                                                        </small>
                                                    @endif

                                                    @if (!empty($cart['additional']))
                                                        @foreach ($cart['additional'] as $adds)
                                                            <small class="option add-op" id_adds="{{ $adds['id'] }}">
                                                                {{ $adds['nama'] ?? "Not found" }} - {{ $adds['harga'] }}
                                                            </small>
                                                        @endforeach
                                                    @endif

                                                    @if (!empty($cart['type_name']))
                                                        <small class="option status_order type_order" idx="{{ $cart['type_id'] }}">
                                                            {{ $cart['type_name'] }}
                                                        </small>
                                                    @endif

                                                    @if (!empty($cart['discount']))
                                                        @foreach ($cart['discount'] as $discounts)
                                                            @if (!empty($cart['total_dis']))
                                                                @php
                                                                    $nominalDis = $discounts['nominal'];
                                                                    $total_dis += $nominalDis;
                                                                @endphp
                                                            @endif
                                                            <small class="option status_order discount" 
                                                                idx="{{ $discounts['id'] }}" dis="{{ $discounts['percent'] }}">
                                                                Discount {{ $discounts['percent'] }}% - {{ $discounts['nominal'] }}
                                                            </small>
                                                        @endforeach
                                                    @endif

                                                    @if (!empty($cart['catatan']))
                                                        <small class="option note">{{ $cart['catatan'] }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="footer-sub-total">
                                    @php $sub_total = $subtotal - $total_dis; @endphp
                                    <div class="total">
                                        <div class="txt-total subtotal">Subtotal:</div>
                                        <div class="txt-price-total subtotal" data-subT="" subtotal="{{ $sub_total }}">
                                            {{ number_format($sub_total, 0, '.', '.') }}
                                        </div>
                                    </div>
                                    
                                    @php $totalTax = 0; @endphp
                                    @foreach ($taxs as $tax)
                                        @php
                                            $desimalTax = $tax->tax_rate / 100;
                                            $nominalTax = str_replace('.', '', $sub_total) * $desimalTax;
                                            $totalTax += $nominalTax;
                                        @endphp
                                        <div class="total taxes" idx="{{ $tax->id }}">
                                            <div class="txt-total service-change">
                                                {{ $tax->nama }} <p class="presentage">{{ $tax->tax_rate }}%</p>:
                                            </div>
                                            <div class="txt-price-total nominal-tax" style="color: grey;font-size: 13px;">
                                                {{ number_format($nominalTax, 0, '.', '.') }}
                                            </div>
                                        </div>
                                    @endforeach

                                    @php $total = $sub_total + $totalTax; @endphp
                                    <div class="total">
                                        <div class="txt-total total">Total:</div>
                                        <div class="txt-price-total total" data-total="" total="{{ $total }}">
                                            {{ number_format($total, 0, '.', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p class="text-center secondary text-empty">Order Empty</p>
                            @endif
                        </div>
                        
                        <div class="act-btn-bill">
                            <div class="act-btn act1">
                                <div class="save-act-btn">Save Bill</div>
                                <div class="print-act-btn print-act">Print Bill</div>
                                <div class="print-act-btn split-bill" data-xid="">Split Bill</div>
                            </div>
                            <div class="act-btn act2" data-xid="">
                                <p class="txt-btn-act-bill">Pay</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Popup Modals --}}
    @include('POS.partials.payment_popups')
    @include('POS.partials.bill_popups')
    @include('POS.partials.print_popups')

@stop

@section('script')
    <script src="{{ asset('asset/assets/js/function_POS.js') }}"></script>
    <script src="{{ asset('asset/assets/js/idle timer check.js') }}"></script>
    <script src="{{ asset('asset/assets/js/pusher.min.js') }}"></script>
    <script src="{{ asset('asset/assets/js/pos_optimized.js') }}"></script>
@stop
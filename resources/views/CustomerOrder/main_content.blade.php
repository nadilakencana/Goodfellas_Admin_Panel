@extends('CustomerOrder.index')
@section('content_order')
    <div class="hero-section position-relative">
        <div class="banner">
            <img src="{{ asset('asset/assets/image/banner-1.png') }}" alt="">
        </div>
    </div>
    <div class="container py-4">
        <div class="header">
            <p class="fw-bold fs-3">Bast Menu</p>
        </div>
        <div class="content pt-3">
            <div class="items-menu d-flex flex-column">
                @foreach ($topSellingItems as $itm)
                    <div class="itm-menu d-flex gap-5 py-3">
                        @if (!empty($itm->menu->image))
                            <div class="img-menu">
                                <img src="{{ asset('asset/assets/image/menu/' . $itm->menu->image) }}" alt="">
                            </div>
                        @else
                            <div class="img-menu">
                                <img src="{{ asset('asset/assets/image/menu/drink.png') }}" alt="">
                            </div>
                        @endif

                        <div class="detail-menu">
                            <span class="fw-bold">{{ $itm->menu->nama_menu }}</span>
                            <p class="mb-0">Varian Menu: </p>
                            <div class="d-flex gap-3">

                                @foreach ($itm->menu->varian as $var)
                                    <span>{{ $var->nama }}</span>
                                    @if (!$loop->last)
                                        /
                                    @endif
                                @endforeach
                            </div>
                            <span class="pt-2">Rp. {{$itm->menu->harga}}</span>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection

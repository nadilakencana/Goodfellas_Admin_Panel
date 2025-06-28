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
            <div class="items-menu justify-content-between align-items-center gap-5 py-3">
                @foreach ($topSellingItems as $itm)
                    <div class="itm-menu d-flex justify-content-between align-items-center gap-5 px-2 py-3">
                        @if (!empty($itm->menu->image))
                            <div class="img-menu">
                                <img src="{{ asset('asset/assets/image/menu/' . $itm->menu->image) }}" alt="">
                            </div>
                        @else
                            <div class="img-menu">
                                <img src="{{ asset('asset/assets/image/menu/drink.png') }}" alt="">
                            </div>
                        @endif

                        <div class="detail-menu d-flex flex-column" style="width: 11rem;">
                            <span class="fw-bold">{{ $itm->menu->nama_menu }}</span>
                            <span class="pt-2">Rp. {{$itm->menu->harga}}</span>
                            
                        </div>
                        <div class="btn-add-menu cursor-pointer" xid="{{encrypt($itm->menu->id)}}">
                            <img src="{{asset('asset/assets/image/icon/btn_Add.png')}}" alt="" width="30" height="30">
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
    <div class="col-12 px-0 col-sm-8 col-md-4 mx-auto p-0">
        <div class="pop-up-modal-menu" style="display: none">
            
        </div>
    </div>
    
@endsection
@section('script-order')


@endsection

@extends('CustomerOrder.index')
@section('content_order')
    <div class="pt-5">
        <div class="container py-4">
            
            <div class="content pt-3">
                <div class="items-menu justify-content-between align-items-center gap-5 py-3">
                    @foreach ($ItemCats as $itm)
                        <div class="itm-menu d-flex justify-content-between align-items-center gap-5 px-2 py-3">
                            @if (!empty($itm->image))
                                <div class="img-menu">
                                    <img src="{{ asset('asset/assets/image/menu/' . $itm->image) }}" alt="">
                                </div>
                            @else
                                <div class="img-menu">
                                    <img src="{{ asset('asset/assets/image/menu/drink.png') }}" alt="">
                                </div>
                            @endif

                            <div class="detail-menu d-flex flex-column" style="width: 11rem;">
                                <span class="fw-bold">{{ $itm->nama_menu }}</span>
                                {{-- <p class="mb-0">Varian Menu: </p>
                                <div class="d-flex gap-3">
                                    @if (!empty($itm->varian))
                                        @foreach ($itm->varian as $var)
                                            <span>{{ $var->nama }}</span>
                                            @if (!$loop->last)
                                                /
                                            @endif
                                        @endforeach
                                    @else 
                                        <span>-</span>
                                    @endif
                                </div> --}}
                                <span class="pt-2">Rp. {{ $itm->harga }}</span>
                            </div>
                            <div class="btn-add-menu cursor-pointer" xid="{{encrypt($itm->id)}}">
                                <img src="{{ asset('asset/assets/image/icon/btn_Add.png') }}" alt="" width="30"
                                    height="30">
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
    <div class="pop-up-modal-menu" style="display: none">
       
    </div>
@endsection


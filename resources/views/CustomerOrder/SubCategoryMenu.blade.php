@extends('CustomerOrder.index')
@section('content_order')
    <div class="pt-5">
        <div class="container py-4">
            <div class="header d-flex justify-content-between align-items-center">
                <p class="fw-bold fs-3" style="width: 50%;">{{$subcat_tgt->kategori->kategori_nama}} - {{$subcat_tgt->sub_kategori}} </p>
                <div class="filter d-flex align-items-center gap-2 position-relative" id="dropdown-cat">
                    <img src="{{asset('asset/assets/image/icon/Tune.png')}}" alt="" width="24" height="24" class="cursor-pointer" >
                    <p class="mb-0">Other Categories</p>
                    <div class="category-dropdown off px-3">
                        <ul class="p-2 w-100">
                            @foreach ($subcat as $sub )
                                <li class="p-2 list-sub-cat"><a href="{{route('OrderCustomer.Subcategory', $sub->slug)}}">{{$sub->sub_kategori}}</a></li>
                            @endforeach
                            
                        </ul>
                    </div>
                </div>
            </div>
            <div class="content pt-3">
                <div class="items-menu d-flex flex-column px-4">
                    @foreach ($itemSub as $itm)
                        <div class="itm-menu d-flex justify-content-between align-items-center gap-5 py-3">
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
    <div class="col-12 px-0 col-sm-8 col-md-4 mx-auto p-0">
        <div class="pop-up-modal-menu" style="display: none">
            
        </div>
    </div>
@endsection
{{-- @section('script-order')
<script>
    $(()=>{
        $('#dropdown-cat').on('click', function(e){
            console.log('test')
            e.stopPropagation();

            $('.category-dropdown').not($(this).find('.category-dropdown')).slideUp();


            $(this).find('.category-dropdown').slideToggle('fast');

        })

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#dropdown-cat').length) {
                $('.category-dropdown').slideUp();
            }
        })
    })
    
</script>
@endsection --}}


<div class="content-modal col-12 px-0 col-sm-8 col-md-4 fixed-bottom mx-auto p-0">
    <div class="pop-modal mt-auto">

        <div class="header d-flex justify-content-between align-items-center p-3">
            @php
                $stokTersedia = $itemMenu->tipe_stok === 'Stok Bahan Baku' 
                    ? ($itemMenu->bahanBaku ? $itemMenu->bahanBaku->stok_porsi : 0)
                    : $itemMenu->stok;
            @endphp
            <p class="fw-bold">{{ $itemMenu->nama_menu }} @if($stokTersedia > 0) - {{$stokTersedia}}portions available @endif</p>
            @php
               
                if($itemEdit !== 0){
                    $hargaAwal = $itemEdit['harga'];
                    $totalHarga = ($itemEdit['harga'] + $itemEdit['harga_addtotal']) * $itemEdit['qty'];
                }else{
                    $hargaAwal = $varian !== null ? 0 : $itemMenu->harga ;
                }
                
            @endphp

            <p class="fw-bold total-menu" nominal="{{ $hargaAwal }}">
                @if($itemEdit !== 0)
                  Rp.{{number_format($totalHarga, 0, ',', '.')}} 
                @else
                {{ $hargaAwal }}
                @endif
                
            </p>            
            <p class="fw-bold close cursor-pointer">X</p>
        </div>
        <div class="body-content">
            @if ($varian !== null)
                <div class="variasi-menu pt-3 mb-2">
                    <p class="mb-0 pb-1">Varian Menu | Choose One</p>
                    <div class="items-varias d-flex flex-wrap justify-content-between align-items-center gap-3">
                        @foreach ($varian as $var)
                            <div class="itm-var @if($itemEdit !== 0) @if($itemEdit['variasi_id']=== $var->id) active @endif @endif" idx={{ $var->id }}>
                                <span>{{ $var->nama }}</span>
                                <span class="var-harga" nominal="{{$var->harga}}">Rp. {{ number_format($var->harga, 0, ',', '.') }}</span>
                            </div>
                        @endforeach

                    </div>
                </div>
            @endif
            <div class="additional pb-3" @if (empty($additional)) style="display: none;" @endif>
                <p class="mb-0 pb-1">Additional Menu :</p>
                <div class="items-Adds d-flex flex-column">
                    @foreach ($additional as $adds)
                        @php
                         $isChecked = false;
                            if ($itemEdit !== 0) {
                               if(!empty($itemEdit['additional'])){
                                    foreach ($itemEdit['additional'] as $editAdd) {
                                    
                                        if ($editAdd['id'] == $adds->id) {
                                            $isChecked = true; 
                                        
                                        }
                                        
                                    }
                                }

                            }
                            
                        @endphp
                        <div class="itm-adds mb-2">
                            <span class="name-adds">{{ $adds->name }}</span>
                            <div class="d-flex gap-4">
                                <span class="adds-harga">Rp. {{ number_format($adds->harga, 0, ',', '.') }}</span>
                                <input type="checkbox"  class="additional-itms" name="" nominal="{{$adds->harga}}" idx="{{ $adds->id }}" {{ $isChecked ? 'checked' : '' }}>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="jumlah-menu ">
                {{-- <div class="jml-menu d-flex justify-content-between align-items-center gap-3">
                    <div class="btn-minus">-</div>
                    <input class="qty" type="number" name="num-product" min=0 value=@if($itemEdit !== 0) {{$itemEdit['qty']}}  @else 1 @endif max=100>
                    <div class="btn-plus">+</div>
                </div> --}}
                <div class="jml-menu d-flex justify-content-between align-items-center gap-3">
                    <div class="btn-minus">-</div>
                    <input class="qty" type="number" name="num-product" 
                        min="1" 
                        value= @if($itemEdit != 0) "{{$itemEdit['qty']}}" @else "1" @endif 
                        max= @if($itemMenu->stok !== 0) "{{$itemMenu->stok}}" @else "100" @endif >
                    <div class="btn-plus">+</div>
                </div>

            </div>
            <div class="tipe-penjualan py-3">
                <div class="name-additional pb-2">Sales Type | Choose one</div>

                <div class="option-type d-flex justify-content-center align-items-center gap-4">
                    @foreach ($type_sales as $type)
                         <div class="nama-option w-100
                            @php
                                $isActive = false; 

                                if ($itemEdit) { 
                                    if (isset($itemEdit['type_name']) && $itemEdit['type_name'] == $type->name) {
                                        $isActive = true;
                                    }
                                } else {
                                  
                                    if ($type->name == 'Dine In') {
                                        $isActive = true;
                                    }
                                }
                            @endphp
                            {{ $isActive ? 'active' : '' }}" idx="{{ $type->id }}">

                           {{ $type->name }}
                           </div>
                    @endforeach
                </div>

            </div>
            <div class="catatan-menu">
                <div class="name-additional">Catatan</div>
                <textarea name="" id="" cols="50" rows="5">
                    @if($itemEdit !== 0) {{$itemEdit['catatan'] }} @endif
                </textarea>
            </div>
            <div class="btn btn-add-items d-flex justify-content-center gap-4" idx_menu="{{ $itemMenu->id }}" xkey=""> 
                <img src="{{ asset('asset/assets/image/icon/icon_plus.png') }}" alt="" width="20"
                    height="20">
                <p class="m-0">Add</p>
            </div>
        </div>
    </div>

</div>

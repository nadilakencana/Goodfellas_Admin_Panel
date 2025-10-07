// cek kategori menu 
function hendelCheckKategori(id){
    const URL = "{{route('kategori-cek')}}";

    $.ajax({
        url: URL,
        data: { id: id },
        method: 'GET',
        dataType: 'json',
        
        success: function(result) {
            console.log(result);
            const $target = $('body .pop-up.additional');
            const $btnAdd = $target.find('.btn-add');

            let category = result.data.kategori.kategori_nama;
            console.log(category)
            
            if (category == 'Foods') {
                let stok = 0;
                
                // Fix: Check tipe_stok properly
                if(result.data.tipe_stok == 'bahan_baku'){
                    // Use bahan baku stock if available
                    stok = result.data.bahan_baku ? result.data.bahan_baku.stok_porsi : 0;
                } else {
                    // Use manual stock
                    stok = result.data.stok || 0;
                }
               
                if(stok <= 0 || result.data.active == 0){
                    $btnAdd.prop('disabled', true).text('tidak tersedia');
                    console.log('tidak tersedia')
                } else {
                    $btnAdd.prop('disabled', false);
                    // Fix: Check if this is edit mode
                    if($btnAdd.attr('id_detail')) {
                        $btnAdd.text('update');
                    } else {
                        $btnAdd.text('add');
                    }
                }

                $target.find('.jumlah-menu input').attr('max', stok);
                console.log('Max stock:', stok);

            } else if(category == 'Drinks') {
                if(result.data.active == 0){
                    $btnAdd.prop('disabled', true).text('tidak tersedia');
                    console.log('drink tidak tersedia')
                } else {
                    $btnAdd.prop('disabled', false);
                    // Fix: Check if this is edit mode
                    if($btnAdd.attr('id_detail')) {
                        $btnAdd.text('update');
                    } else {
                        $btnAdd.text('add');
                    }
                }

                $target.find('.jumlah-menu input').attr('max', 100);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading category check:', error);
            const $btnAdd = $('body .pop-up.additional .btn-add');
            $btnAdd.prop('disabled', true).text('error');
        }
    });
}
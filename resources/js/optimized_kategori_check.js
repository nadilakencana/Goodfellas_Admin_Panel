function hendelCheckKategori(id) {
    $.ajax({
        url: "{{route('kategori-cek')}}",
        data: { id: id },
        method: 'GET',
        dataType: 'json',
        success: function(result) {
            const $target = $('.pop-up.additional');
            const $btnAdd = $target.find('.btn-add');
            const $qtyInput = $target.find('.jumlah-menu input');
            
            const isEditMode = $btnAdd.attr('id_detail') && $btnAdd.attr('id_detail') !== 'null' && $btnAdd.attr('id_detail') !== '0';
            const category = result.data.kategori.kategori_nama;
            
            if (category === 'Foods') {
                const stok = result.data.tipe_stok === 'bahan_baku' 
                    ? (result.data.bahan_baku?.stok_porsi || 0)
                    : (result.data.stok || 0);
                
                const isAvailable = stok > 0 && result.data.active === 1;
                
                $btnAdd.prop('disabled', !isEditMode && !isAvailable)
                       .text(isEditMode ? 'Update' : (isAvailable ? 'Add' : 'Tidak Tersedia'));
                
                $qtyInput.attr('max', stok);
                
            } else if (category === 'Drinks') {
                const isAvailable = result.data.active === 1;
                
                $btnAdd.prop('disabled', !isEditMode && !isAvailable)
                       .text(isEditMode ? 'Update' : (isAvailable ? 'Add' : 'Tidak Tersedia'));
                
                $qtyInput.attr('max', 100);
            }
        },
        error: function() {
            $('.pop-up.additional .btn-add').prop('disabled', true).text('Error');
        }
    });
}
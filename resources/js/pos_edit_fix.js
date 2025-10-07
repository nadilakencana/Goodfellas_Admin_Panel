//klik item untuk edit
$('body').on('click', '.itm-bil', function() {
    var $elm = $(this);
    $('.pop-up.additional').fadeIn();
    $('.btn-add').removeAttr('disabled');
    $('.tooltip').fadeOut();

    //urutan array list item
    var arrkey = $elm.attr('xid');
    //id dari item menu
    var id = $elm.attr('idx');
    var idDetail = $elm.attr('id_item_detail');
    var orderId = $elm.attr('order_id'); // ADD THIS - order ID context
    let stok = parseInt($elm.attr('stok'));
    let status = parseInt($elm.attr('status'));

    var $popup = $('.pop-up.additional');
    var harga = $elm.find('.price').attr('price');
    var harga_ = harga.replace(".", "");

    $popup.find('.harga-total').attr('price', harga).text(harga);
    $popup.find('.card-popup')
        .attr('id-x', id)
        .attr('key-id', arrkey)
        .attr('id_detail', idDetail)
        .attr('order_id', orderId); // ADD THIS

    $popup.find('.btn-add')
        .attr('x-id', id)
        .attr('key', arrkey)
        .attr('id_detail', idDetail)
        .attr('order_id', orderId) // ADD THIS
        .attr('menu_id', id) // ADD THIS - menu ID for stock checking
        .text('update');

    var Adds = [];
    var dis = [];
    var qty = $elm.find('.itm .jumlah').text();
    var typeSales = $elm.find('.detail-itm .status_order').attr('idx');
    var $discount = $elm.find('.detail-itm .discount');

    $discount.each(function() {
        var id = $(this).attr('idx');
        var disObj = { 'id': id };
        dis.push(disObj);
    });

    var note = $elm.find('.note').text();

    hendelCheckKategori(id)
    getVariasi(id, 'edit', arrkey);
    getAdditional(id, 'edit', arrkey);

    // Set form values
    $popup.find('.jumlah-menu input.qty').val(qty);
    var opDis = $popup.find('.option-discount input.opDis');

    dis.forEach(function(obj) {
        var id = obj.id;
        opDis.each(function() {
            var xid = $(this).attr('id');
            if (xid == id) {
                $(this).prop('checked', true);
            }
        })
    })

    var opType = $popup.find('.option-type');
    var typeActive = $popup.find('.option-type[idx="4"]').attr('idx');
    opType.each(function() {
        var xid = $(this).attr('idx');
        if (xid == typeSales && typeSales !== typeActive) {
            $(this).addClass('active');
            $popup.find('.option-type[idx="4"]').removeClass('active');
        }
        if (xid == typeSales == typeActive) {
            $popup.find('.option-type[idx="4"]').addClass('active');
        }
    });

    $popup.find('.catatan-menu textarea').val(note);
});

// UPDATE BUTTON HANDLER - ADD THIS
$('body').on('click', '.btn-add[id_detail]', function() {
    var $btn = $(this);
    var menuId = $btn.attr('menu_id');
    var orderId = $btn.attr('order_id');
    var detailId = $btn.attr('id_detail');
    
    // Get form data
    var qty = $('.jumlah-menu input.qty').val();
    var variasi = $('.variasi-menu .option-variasi.active').attr('idx') || null;
    var catatan = $('.catatan-menu textarea').val();
    var harga = $('.harga-total').attr('price').replace('.', '');
    
    // Collect additionals
    var additional = [];
    $('.option-additional input:checked').each(function() {
        additional.push({
            id: $(this).attr('id'),
            qty: 1,
            harga: $(this).attr('price')
        });
    });
    
    // Collect discounts
    var discount = [];
    $('.option-discount input:checked').each(function() {
        discount.push({
            id: $(this).attr('id'),
            nominal: $(this).attr('nominal')
        });
    });
    
    // Send update request
    $.ajax({
        url: '/pos/modify-bill', // Your modify route
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            target_order: orderId,
            target_detail: detailId,
            id: menuId,
            qty: qty,
            harga: harga,
            variasi: variasi,
            catatan: catatan,
            additional: additional,
            discount: discount
        },
        success: function(response) {
            if (response.success) {
                $('.pop-up.additional').fadeOut();
                // Refresh bill display
                loadBillDetails(orderId);
            } else {
                alert(response.message);
            }
        },
        error: function(xhr) {
            alert('Error updating order: ' + xhr.responseJSON.message);
        }
    });
});
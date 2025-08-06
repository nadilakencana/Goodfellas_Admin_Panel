let loadPhase = false;
// let currentBillId = 0;

$(() => {
    $('.pop-up.additional').hide();
    $('.pop-daftar-bill').hide();
    $('.pop-daftar-discount').hide();
    $('.pop-payment').hide();
    $('.payment-nominal').hide();
    $('.popup-name-bill').hide();
    $('.popup-qty').hide();
    //menampilkan pop up data bill yang di save dan close pop up bill
    $('.menu-icon-list-bil').on('click', function () {
        $('.popup-daftar-bill').fadeIn();
        getDataBills();
    });

    $('.menu-discount').on('click', function () {
        $('.pop-daftar-discount').fadeIn();
        getDataDiscount();
        console.log('data discount')
    });

    $('body').on('click', ".pop-daftar-bill .header-card .close",function () {
        var $tgtPopUp = $('.popup-daftar-bill');
         $tgtPopUp.empty();
         $tgtPopUp.fadeOut();
     }).on('click', '.popup-daftar-bill', function(){
        $(this).fadeOut();
     });
    $('body').on('click', ".pop-daftar-discount .header-card .close",function () {
        var $tgtPopUp = $('.popup-daftar-discount');
        $tgtPopUp.empty();
        $tgtPopUp.fadeOut();
        $('.option-discount input:checked').prop('checked', false);
     });

    $(".popup-daftar-discount").click(function(event){
        if(!$(event.target).closest('.card-list-discount').length) {
            $(this).fadeOut();
            $('.option-discount input:checked').prop('checked', false);
        }
    });
    $('.pop-up .header-card-popup .close').on('click', function () {

        var $custom = $('.panel[data-panel="panel3"] .custom-part');
        var $customCategory = $('.content-payment');
        $custom.find('input.nilai-custom').val('');
        $customCategory.find('.part-category.active').addClass('unactive').removeClass('active');
        $('.pop-up.additional').fadeOut();
        $('.option-varian').removeClass('active');
        $('.option-menu-additional').removeClass('active');
        $('.option-discount input:checked').prop('checked', false);
        $('.jml-menu input.qty').val('1');
        $('.catatan-menu textarea').val('')
        var Option = $('.option-type');
        $('.option-type.active').removeClass('active');

        Option.each(function() {
            if ($(this).attr('idx') === '4') {
                // Add 'active' class to the element with idx '4'
                $(this).addClass('active');
            }
        });
       
        $('.btn-add').text('add');
        $('.card-popup').attr('id-x','').attr('key-id', '');
        $('.btn-add').attr('x-id','').attr('key','');
        $('.btn-add').removeAttr('disabled');
        $('.tooltip').fadeOut();
    })

    $(".pop-up.additional").click(function(event){
        if(!$(event.target).closest('.card-popup').length) {
            $(this).fadeOut();
            var $custom = $('.panel[data-panel="panel3"] .custom-part');
            var $customCategory = $('.content-payment');
            $custom.find('input.nilai-custom').val('');
            $customCategory.find('.part-category.active').addClass('unactive').removeClass('active');
            $('.option-varian').removeClass('active');
            $('.option-menu-additional').removeClass('active');
            $('.option-discount input:checked').prop('checked', false);
            $('.jml-menu input.qty').val('1');
            $('.catatan-menu textarea').val('')
            // $('.option-type.active').removeClass('active');
            // $('.option-type').removeClass('active');
            $('.btn-add').text('add');
            $('.card-popup').attr('id-x','').attr('key-id', '');
            $('.btn-add').attr('x-id','').attr('key','');
            $('.btn-add').removeAttr('disabled');
            $('.tooltip').fadeOut();
            var Option = $('.option-type');
            $('.option-type.active').removeClass('active');

            Option.each(function() {
                if ($(this).attr('idx') === '4') {
                    // Add 'active' class to the element with idx '4'
                    $(this).addClass('active');
                }
            });
        }
    });

    $('body').on('click', '.option-varian', function () {
        //var $elm = $(this).addClass('active');
        var elm = $(this);
        $(elm).toggleClass('active');
        $('.option-varian').not(elm).removeClass('active');
    });

    $('body').on('click', '.option-menu-additional', function () {
        //var $elm = $(this).addClass('active');
        const $elm = $(this);

        // Periksa apakah elemen memiliki kelas 'active'
        if (!$elm.hasClass('active')) {
            // Jika tidak memiliki kelas 'active', tambahkan 'active' dan hapus 'delete'
            $elm.addClass('active').removeClass('delete');
        } else {
            // Jika sudah memiliki kelas 'active', hapus 'active' dan tambahkan 'delete'
            $elm.removeClass('active').addClass('delete');
        }
    });

    $('body').on('click', '.option-type', function () {
        //var $elm = $(this).addClass('active');
        var elm = $(this);
        $(elm).toggleClass('active');
        $('.option-type').not(elm).removeClass('active');
    });

    var $popUpAdd = $('body .pop-up.additional');
    $popUpAdd.on('click', '.option-discount input[type="checkbox"]', function() {
        const $elm = $(this);
        
        if ($elm.prop('checked')) {
            $elm.removeClass('delete').addClass('active');
        } 
        else {
            $elm.addClass('delete').removeClass('active');
        }
    });

    //end pop up additional part

    //payment
    $('.pop-payment .header-card .close').on('click', function () {
        $('.pop-payment').fadeOut();
    })
    $(".pop-payment").click(function(event){
        if(!$(event.target).closest('.card-payment').length) {
            $(this).fadeOut();
        }
    });
    $(".popup-category-custom").click(function(event){
        if(!$(event.target).closest('.position-card').length) {
            $(this).fadeOut();
        }
    });
    
    $('.payment-nominal .footer-card .btn-close-part').on('click', function () {
        $('.payment-nominal').fadeOut();
        var $payment = $('body .pop-payment .content-payment');
        $payment.find('.part-payment.active').addClass('unactive').removeClass('active');
        // console.log('testing get id',xid);
        const $button =$('.payment-nominal .card-payment-nominal .footer-card .btn-selesai')
        loadPhase = false;
        $button.prop('disabled', false).text('Selesai');
    })

    $('.popup-category-custom .close').on('click', function () {
        $('.popup-category-custom').fadeOut();
        var $category = $('body .popup-category-custom .content-payment');
        $payment.find('.part-category.active').addClass('unactive').removeClass('active');
        
    })
    // $('body').on('click', '.payment-nominal', function(){
    //     $('.payment-nominal').hide();
    // });

    $('body').on('click', '.part-payment', function () {
        var elm = $(this);
        $(elm).toggleClass('active');
        $(elm).removeClass('unactive');
        $('.part-payment').not(elm).removeClass('active').addClass('unactive');
        $('.payment-nominal').fadeIn();
        $('.pop-payment').fadeOut();
    });

    
    $('.act-btn.act2').on('click', function (e) {
        //var $total = $('.txt-price-total.total').text();
        var $total = $('.txt-price-total.sisa-bayar');
        

        if ($total.length > 0) {
            var nominal_total = $('.txt-price-total.sisa-bayar').attr('data-total');
        } else {
            var $total = $('.txt-price-total.total').text();

            var nominal_total = $('.txt-price-total.total').attr('data-total');
            if(nominal_total !== undefined && nominal_total !== null && nominal_total !== ""){
                console.log('nominal data tersedia');
                var convert = $total.replace(/[^\d]/g, '');
                // console.log(convert);
                // var data_total = $('.txt-price-total.total').attr('data-total', convert);
                // var nominal_total = $('.txt-price-total.total').attr('data-total');
                
            }else{
                var convert = $total.replace(/[^\d]/g, '');
                console.log(convert);
                // var data_total = $('.txt-price-total.total').attr('data-total', convert);
                var nominal_total = $('.txt-price-total.total').attr('data-total', convert);
            }

        }

        var paymentTotal = $('.total-payment').text($total);
       console.log(nominal_total);
        $('.nm-payment').text($total);
        $('.nominal').attr('data-nominal', convert);
        $('.pop-payment').fadeIn();
    });

    $('body').on('click', '.payment-nominal .nominal', function (e) {
      
        var $target = $(this);
        var nominal = $target.attr('data-nominal');
        let $tgrPayment = $('.payment-nominal .card-payment-nominal');
        var cash = $tgrPayment.find('.form-cash input.convert-cash').val(nominal);
        var convert = nominal.replace(/[^\d]/g, '');
        var formattedValue = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(convert);
        $('.form-cash input.cash-nominal-input').val(formattedValue);
        var $elm = $('.form-cash input.convert-cash');
        var cash = $elm.val();
        var nominal_total = $('.txt-price-total.sisa-bayar').length > 0 ? 
                            $('.txt-price-total.sisa-bayar').attr('data-total') : 
                            $('.nominal').attr('data-nominal');

        var change_nominal = parseInt(cash) - parseInt(nominal_total);
        console.log(nominal_total, change_nominal);
    
        var formattedValue = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(change_nominal);
        $('.form-cash input.change-input').val(formattedValue);
        $('.form-cash input.convert-change').val(change_nominal);
          CekCashNominal();
    }).on('keyup', function (e) {
        
        var $elm = $('.form-cash input.convert-cash');
        var cash = $elm.val();
        var nominal_total = $('.txt-price-total.sisa-bayar').length > 0 ? 
                        $('.txt-price-total.sisa-bayar').attr('data-total') : 
                        $('.nominal').attr('data-nominal');

        var change_nominal = parseInt(cash) - parseInt(nominal_total);
        console.log(nominal_total, change_nominal);
        let $tgrPayment = $('.payment-nominal .card-payment-nominal');
        var formattedValue = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(change_nominal);
        $('.form-cash input.change-input').val(formattedValue);
        $('.form-cash input.convert-change').val(change_nominal);
        CekCashNominal();
    });

    //qty
    $('body').on('click', 'a.qty-minus', function (e) {
        e.preventDefault();
        var $this = $(this);
        var $input = $this.closest('div').find('input');
        var value = parseInt($input.val());

        if (value > 1) {
            value = value - 1;
        } else {
            value = 0;
        }

        $input.val(value);

    });

    $('body').on('click', 'a.qty-plus', function (e) {
        e.preventDefault();
        var $this = $(this);
        var $input = $this.closest('div').find('input');
        var value = parseInt($input.val());

        if (value < 100) {
            value = value + 1;
        } else {
            value = 100;
        }

        $input.val(value);
    });

    // RESTRICT INPUTS TO NUMBERS ONLY WITH A MIN OF 0 AND A MAX 100
    $('body .control-qty input').on('blur', function () {

        var input = $(this);
        var value = parseInt($(this).val());

        if (value < 0 || isNaN(value)) {
            input.val(0);
        } else if
            (value > 100) {
            input.val(100);
        }
    });

    $('body').on('click', '.header-action img.icon', function () {
        var $target = $('.content-menu');

        if ($('.menu_discount.active')) {
            $target.find('.menu_discount').remove().removeClass('active');
        }
        if ($('.all-menu.active')) {
            $target.find('.all-menu').remove().removeClass('active');
        }
        if ($('.menuSub.active')) {
            $target.find('.menuSub').remove().removeClass('active');
        }

        $('.sub-content').removeClass('hidden');
    });

    //end payment
    // proses simpan data order
    $('.act-btn.act1 .save-act-btn').on('click', function (e) {
        $('.popup-name-bill').show();
        var nameBill = $('.popup-name-bill input.nameBill');
        console.log('name bill',nameBill.val());
    })


    $('.card-colum-input .close').on('click', function () {
        $('.popup-name-bill').hide();
        var nameBill = $('.popup-name-bill input.nameBill');
        var name = $('input.nomer-meja').attr('data-name');

        if(nameBill == "" || nameBill == null || nameBill == undefined){
            nameBill.val('');
        }else{
            nameBill.val(name)
        }

        loadPhase = false;
        const $button = $('.popup-name-bill .card-colum-input .save-bill');
        $button.prop('disabled', false).text('Selesai'); 
    })
    $('body').on('click', '.popup-name-bill', function(event){
        if(!$(event.target).closest('.card-colum-input').length) {
            $(this).fadeOut();
            var nameBill = $('.popup-name-bill input.nameBill');
            var name = $('input.nomer-meja').attr('data-name');
             
            if(nameBill == "" || nameBill == null || nameBill == undefined){
                nameBill.val('');
            }else{
                nameBill.val(name)
            }
            loadPhase = false;
            const $button = $('.popup-name-bill .card-colum-input .save-bill');
            $button.prop('disabled', false).text('Selesai');  
        }
    })

    $('.cash-nominal-input').on('input',  CekCashNominal(), function () {
        var value = $(this).val();
        console.log(value)
        // Contoh penggunaan:
        const nilaiAngka = konversiStringKeAngka(value);
        var input = $('input.convert-cash').val(nilaiAngka);
        console.log(input);
    })
    
    $('.change-input').on('input', function () {
        var value = $(this).val();
        console.log(value)
        // Contoh penggunaan:
        const nilaiAngka = konversiStringKeAngka(value);
        var input = $('input.convert-change').val(nilaiAngka);
        console.log(input);
    })
})
// $(() => {

//     let throttledButtonClick;
//     let throttledButtonClickDelete;
//     let canClick;
//     let canClickDelete;
//     canClick = true;
//     canClickDelete = true;

//     $('body').on('click', '.item-card-menu', function() {
//         var $elm = $(this);
//         var idx = $elm.attr('idx');
//         var $popup = $('.pop-up.additional');
//         var harga = $elm.attr('target-price');
//         if (harga.indexOf('.') !== -1) {
//             var harga_ = harga.replace(".", "");
//             $popup.find('.harga-total').attr('price', harga_).text(harga);
//         }
//         $popup.find('.harga-total').attr('price', harga).text(harga);
//         $popup.find('.card-popup').attr('id-x', idx);
//         $popup.find('.btn-add').attr('x-id', idx);
//         console.log(idx);
//         getVariasi(idx, 'add', '');
//         getAdditional(idx, 'add', '');
//         $('.pop-up.additional').fadeIn();
//     });
//     $('body').on('click', '.add-custom', function() {
//         $('.popup-category-custom').fadeIn();
//     }).on('click', '.act-btn-add', function(e) {
//         currentBillId =
//         0; // gunanya untuk reset state jadi customer / bill baru supaya item yang di add lewat session lagi
//     })
//     $('body').on('click', '.part-category', function() {
//         var elm = $(this);
//         $(elm).toggleClass('active');
//         $(elm).removeClass('off');
//         $('.popup-category-custom').not(elm).removeClass('active').addClass('unactive');
//         var $partActive = $('.part-category.active');
//         var idx = $partActive.attr('xid');
//         var $popup = $('.pop-up.additional');
//         var harga = $('.custom-part .nilai-custom').val();
//         if (harga.indexOf('.') !== -1) {
//             var harga_ = harga.replace(".", "");
//             $popup.find('.harga-total').attr('price', harga_).text(harga);
//         }
//         $popup.find('.harga-total').attr('price', harga).text(harga);
//         $popup.find('.card-popup').attr('id-x', idx);
//         $popup.find('.btn-add').attr('x-id', idx);
//         console.log(idx);
//         getVariasi(idx, 'add', '');
//         getAdditional(idx, 'add', '');
//         $('.pop-up.additional').fadeIn();
//         $('.popup-category-custom').fadeOut();
//     });
//     //klik item untuk edit
//     $('body').on('click', '.itm-bil', function() {
//         var $elm = $(this);
//         $('.pop-up.additional').fadeIn();
//         $('.btn-add').removeAttr('disabled');
//         $('.tooltip').fadeOut();
//         //urutan array list item
//         var arrkey = $elm.attr('xid');
//         //id dari item menu
//         var id = $elm.attr('idx');
//         var idDetail = $elm.attr('id_item_detail');
//         var $popup = $('.pop-up.additional');
//         var harga = $elm.find('.price').attr('price');
//         var harga_ = harga.replace(".", "");
//         $popup.find('.harga-total').attr('price', harga).text(harga);
//         $popup.find('.card-popup').attr('id-x', id).attr('key-id', arrkey).attr('id_detail',
//             idDetail);
//         $popup.find('.btn-add').attr('x-id', id).attr('key', arrkey).attr('id_detail', idDetail)
//             .text('update');
//         var Adds = [];
//         var dis = [];
//         var qty = $elm.find('.itm .jumlah').text();
//         var typeSales = $elm.find('.detail-itm .status_order').attr('idx');
//         var $discount = $elm.find('.detail-itm .discount');
//         $discount.each(function() {
//             var id = $(this).attr('idx');
//             console.log(id)
//             var disObj = {
//                 'id': id
//             };
//             dis.push(disObj);
//         });
//         var note = $elm.find('.note').text();
//         console.log(
//             dis, qty, typeSales, note
//         )
//         getVariasi(id, 'edit', arrkey);
//         getAdditional(id, 'edit', arrkey);
//         var qty = $popup.find('.jumlah-menu input.qty').val(qty);
//         var opDis = $popup.find('.option-discount input.opDis');
//         dis.forEach(function(obj) {
//             var id = obj.id;
//             opDis.each(function() {
//                 var xid = $(this).attr('id');
//                 if (xid == id) {
//                     $(this).prop('checked', true);
//                 }
//             })
//         })
//         var opType = $popup.find('.option-type');
//         var typeActive = $popup.find('.option-type[idx="4"]').attr('idx');
//         opType.each(function() {
//             var xid = $(this).attr('idx');
//             if (xid == typeSales && typeSales !== typeActive) {
//                 $(this).addClass('active');
//                 $popup.find('.option-type[idx="4"]').removeClass('active');
//             }
//             if (xid == typeSales == typeActive) {
//                 $popup.find('.option-type[idx="4"]').addClass('active');
//             }
//         });
//         $popup.find('.catatan-menu textarea').val(note);
//     })
//     $('.itmn-subcategory.discount').on('click', function() {
//         $('.sub-content').addClass('hidden');
//         getDataMenuDiscount();
//     });
//     $('.itmn-subcategory.allmenu').on('click', function() {
//         $('.sub-content').addClass('hidden');
//         getmenuAll();
//     });
//     $('.itmn-subcategory.menusub').on('click', function() {
//         var idx = $(this).attr('idx');
//         $('.sub-content').addClass('hidden');
//         getmenuSub(idx);
//     });
//     //menampilkan detail bill yang di klik
//     $('body').on('click', 'tr.item-bill', function() {
//         var xid = $(this).attr('idx');
//         console.log(xid);
//         var $viewDetail = $('.view-detail-ord');
//         $viewDetail.find('.drop-down').remove();
//         $viewDetail.find('.detil-bil').remove();
//         $viewDetail.find('.footer-sub-total').remove();
//         $viewDetail.find('.text-empty').remove();
//         $('.act-btn.act2').attr('data-xid', xid);
//         var url = "{{ route('print-bill', '') }}" + '/' + xid;
//         // $('.act-btn.act1 .print-act-btn a').attr('href', url);
//         $('.act-btn.act1 .split-bill').attr('data-xid', xid);
//         currentBillId = xid;
//         getBill(xid);
//     });
//     $('.act-btn.act1 .print-act').on('click', function(e) {
//         var xid = $('.act-btn.act2').attr('data-xid');
//         Bill(xid, 'Bill');
//         //Tiket(xid, 'Tiket');
//         //Kitchen(xid, 'Kitchen')
//     })
//     $('body').on('click', 'tr.server', function() {
//         var xid = $(this).attr('idx');
//         var url = "{{ route('data-print-server', '') }}" + '/' + xid;
//         $('.act-btn.act1 .print-act-btn a').attr('href', url);
//         var $target = $('.act-btn.act1 .print-act-btn.split-bill');
//         $target.addClass('server');
//         $target.attr('data-xid', xid);
//         getDetailBillServer(xid, 'datail_bill');
//     });
//     $('.payment-nominal .card-payment-nominal .footer-card .btn-selesai').on('click', function(e) {
//         var $target = $('.itm');
//         var $tgt_input_local = $target.find('.act-edit.local input:checked').prop('checked', true);
//         var $tgt_input_server = $target.find('.act-edit.server input:checked').prop('checked',
//         true);
//         var type = $(this).attr('data-type');
//         console.log($tgt_input_local, $tgt_input_server);
//         if ($tgt_input_local.length > 0) {
//             console.log('pyment split bill Local');
//             splitBill($(this), 3);
//         } else {
//             console.log('pyment to local');
//             if (loadPhase) {
//                 console.log('Process is already running. Please wait.');
//                 return;
//             }
//             loadPhase = true;
//             const $button = $(this);
//             $button.prop('disabled', true).text('Processing...');
//             payment($(this), 'local', $button);
//         }
//         // clearSession();
//     });
//     $('.print-act-btn.split-bill').on('click', function() {
//         var idx = $(this).attr('data-xid');
//         var $server = $('.print-act-btn.split-bill.server');
//         if ($server.length > 0) {
//             getDataDetailSplitServer(idx);
//         } else {
//             getDataDetailSplit(idx);
//         }
//     })
//     $('body').on('click', `.act-edit .check-edit`, function(e) {
//         splitBill($(this), 1);
//         checkCheckboxes()
//         var $popAdds = $('.pop-up.additional');
//         if ($popAdds.css('display') === 'block' || $popAdds.css('') === '') {
//             $popAdds.css('display', 'none');
//         }
//         $('.pop-up.additional').fadeOut();
//     });
//     $('body').on('click', `.detil-bil .itm .qty`, function(e) {
//         var $popAdds = $('.pop-up.additional');
//         if ($popAdds.css('display') === 'block' || $popAdds.css('') === '') {
//             $popAdds.css('display', 'none');
//         }
//     });
//     $('.popup-qty').on('click', '.btn-selesai', function() {
//         $('.popup-qty').fadeOut();
//         splitBill($(this), 2);
//         checkCheckboxes()
//     }).on('click', '.header-card .close', function() {
//         let $popupQty = $('.popup-qty');
//         let $detail = $popupQty.find('.detil-bil');
//         if ($detail.length > 0) {
//             var $item = $detail.find('.itm-bil');
//             var $itm = $item.find('.act-edit input:checked').prop('checked', true);
//             if ($itm.length > 0) {
//                 // Mengubah statusnya menjadi tidak dicentang
//                 $itm.each(function() {
//                     let $elm = $(this);
//                     $elm.prop('checked', false); // Mengubah menjadi tidak dicentang
//                 });
//             }
//         }
//         var subTotal = $('.footer-sub-total .txt-price-total.subtotal ').attr('subtotal');
//         var $elm_pb = $('.txt-price-total.PB1');
//         var $elm_service = $('.txt-price-total.Service');
//         var total = $('.footer-sub-total .txt-price-total.total ').attr('total');
//         var pb1 = 0.1;
//         var service = 0.05;
//         var nominalPb = subTotal * pb1;
//         var nominalService = subTotal * service;
//         var Totalreset = subTotal + nominalPb + nominalService;
//         var convertPb1 = nominalPb.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
//         var convertService = nominalService.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
//         var ConSub = subTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
//         var ConTotal = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
//         $elm_pb.text(convertPb1);
//         $elm_service.text(convertService);
//         $('.footer-sub-total .txt-price-total.subtotal').text(ConSub);
//         $('.footer-sub-total .txt-price-total.total').text(ConTotal);
//         $('.popup-qty').fadeOut();
//     })
//     $('.card-popup .btn-add').on('click', function() {
//         var idx = $(this).attr('x-id');
//         var key = $(this).attr('key');
//         console.log('id menu ' + idx);
//         if (key == '') {
//             additional(idx, 'add');
//         } else {
//             additional(idx, 'edit');
//         }
//     });
//     $('body').on('click', '.Varian-menu .option-varian', function() {
//         var $elm = $(this).addClass('active');
//         var harga = $elm.find('.harga-varian').text();
//         var nilai = harga.replace(/\./g, '');
//         var $price = $('.header-card-popup .harga-total');
//         $price.text(harga).attr('price', nilai);
//         checkVariantSelection()
//     });
//     //delete item order
//     $('body').on('click', '.itm .hapus-menu-order', function(e) {
//         checkVariantSelection()
//         var id = $(this).attr('idx');
//         var $elmentcart = $(this).parents('.itm-bil');
//         var konfirmasi = confirm('sure you want to delete the menu item?');
//         if (konfirmasi) {
//             //console.log('tes hapus')
//             deleteItem(id, $elmentcart);
//         }
//     });
//     //delete session
//     $('.menu-delete').on('click', function() {
//         clearSession();
//     })
//     $('.popup-name-bill .card-colum-input .save-bill').on('click', function(e) {
//         if (loadPhase) {
//             console.log('Process is already running. Please wait.');
//             return;
//         }
//         loadPhase = true;
//         const $button = $(this);
//         $button.prop('disabled', true).text('Processing...');
//         POSorder($button);
//     });
//     var $disBill = $('body .popup-daftar-discount');
//     var $tgtDisBill = $disBill.find('.option-discount input[type="checkbox"]');
//     console.log($tgtDisBill);
//     $disBill.on('click', '.option-discount input[type="checkbox"]', function() {
//         discountBill()
//         console.log('get dis')
//     });
//     function checkCheckboxes() {
//         // Periksa apakah ada checkbox yang dicentang
//         const isAnyChecked = $('.popup-qty .check-edit:checked').length > 0;
//         // Aktifkan atau nonaktifkan tombol "Oke"
//         $('.popup-qty .btn-selesai').prop('disabled', !isAnyChecked);
//     }
//     //function payment post
//     function payment($elment, type, $button) {
//         var $targetpayment = $('.pop-payment');
//         var paymentId = $targetpayment.find('.content-payment .part-payment.active').attr('xid');
//         var xidOrder = $('.act-btn-bill .act-btn.act2').attr('data-xid');
//         let $tgrPayment = $('.payment-nominal .card-payment-nominal');
//         var cash = $tgrPayment.find('.form-cash input.convert-cash').val();
//         var change_ = $tgrPayment.find('.form-cash input.convert-change').val();
//         var total = $('.txt-price-total.total').attr('data-total');
//         if (total !== undefined && total !== null && total !== "") {
//             console.log('data ada');
//         } else {
//             total = $('.txt-price-total.total').text();
//             total = total.replace(/\./g, '');
//         }
//         console.log(paymentId)
//         if (xidOrder !== undefined && xidOrder !== null && xidOrder !== "") {
//             console.log(postData);
//             if (paymentId === null || paymentId === "" || paymentId === undefined) {
//                 alert('Payment method is empty');
//             } else {
//                 var postData = {
//                     _token: "{{ csrf_token() }}",
//                     id: xidOrder,
//                     Idpayment: paymentId,
//                     cash: cash,
//                     change_: change_,
//                     total: total,
//                 }
//                 $.post("{{ route('pyment-order') }}", postData).done(function(data) {
//                     // alert('Done');
//                     setTimeout(function() {
//                         $('.popup-print .form-group p').text(
//                             'order is processed and has been update');
//                         $('popup-print').fadeIn();
//                     }, 1000)
//                     $('.pop-payment').hide();
//                     $('.payment-nominal .card-payment-nominal').val('');
//                     $('.form-cash input.change-input').val('');
//                     $('.payment-nominal').hide();
//                     //Bill(id,'Bill')
//                     Bill(xidOrder, 'Bill')
//                     clearSession()
//                     //console.log($target1, $target2);
//                     if (data.error) {
//                         console.log(data.error)
//                         return;
//                         LogActivity('error payment', data)
//                     }
//                     LogActivity('success payment', data)
//                 }).fail(function(data) {
//                     console.log('error', data);
//                     alert('Payment tidak sesuai')
//                     LogActivity('error payment', data)
//                 }).always(function() {
//                     // Reset loadPhase and button state
//                     loadPhase = false;
//                     $button.prop('disabled', false).text('Pay Now');
//                 });
//                 if (type == 'server') {
//                     var idAdmin = $('.main-sidebar .info.admin').attr('data-admin');
//                     console.log('idAdmin');
//                     postData['idUser'] = idAdmin;
//                     $.post("https://admin.goodfellas.id/api/payment-POS", postData).done(function(data) {
//                         // alert('Done');
//                         setTimeout(function() {
//                             $('.popup-print .form-group p').text(
//                                 'order is processed and has been update');
//                             $('popup-print').fadeIn();
//                         }, 1000)
//                         getDetailBillServer(xidOrder, 'detailprint_bil');
//                     }).fail(function(data) {
//                         console.log('error', data);
//                     });
//                 }
//             }
//         } else {
//             POSorder($button)
//         }
//         Pusher.logToConsole = true;
//         var pusher = new Pusher('1d53fe58e629925b2d3c', {
//             cluster: 'ap1'
//         });
//         var channel = pusher.subscribe('my-channel');
//         channel.bind('my-event', function(resp) {
//             notify(resp.data)
//         });
//     }
//     //function pusher notifikasi
//     function notify(data) {
//         Toastify({
//             text: data.message,
//             duration: 10000,
//             close: true,
//             gravity: "top", // `top` or `bottom`
//             stopOnFocus: true, // Prevents dismissing of toast on hover
//             style: {
//                 background: "linear-gradient(to right, #00b09b, #96c93d)",
//             },
//         }).showToast();
//     }
//     //function clear session
//     function clearSession() {
//         let URL = "{{ route('sessionClear') }}";
//         $.ajax({
//             url: URL,
//             method: 'GET',
//             success: function(result) {
//                 var $detaiOrder = $('.detail-order');
//                 var $payment = $('body .pop-payment .content-payment');
//                 var $actBtn1 = $('.act-btn.act1');
//                 var $popUpSplit = $('.popup-qty');
//                 var $paymentNominal = $('.payment-nominal');
//                 var $customCategory = $('.payment-nominal');
//                 var $customCategory = $('.content-payment');
//                 var $custom = $('.panel[data-panel="panel3"] .custom-part');
//                 currentBillId = 0;
//                 console.log('clear session')
//                 $('.popup-name-bill input.nameBill').val('');
//                 $('.popup-name-bill .total-payment').text('');
//                 $('.act-2 .txt-btn-act-bill.total').text('');
//                 $('.act-btn-bill .act-btn.act2').attr('data-xid', '');
//                 $detaiOrder.find('.view-detail-ord').empty();
//                 $payment.find('.part-payment.active').addClass('unactive').removeClass(
//                 'active');
//                 $customCategory.find('.part-category.active').addClass('unactive').removeClass(
//                     'active');
//                 $actBtn1.find('.print-act-btn.split-bill').attr('data-xid', '');
//                 $popUpSplit.find('.cotent-detail').empty();
//                 $popUpSplit.find('.txt-tittle').empty();
//                 $popUpSplit.find('.total-payment').empty();
//                 $paymentNominal.find('.nominal').attr('data-nominal', '');
//                 $paymentNominal.find('.nm-payment').empty();
//                 $paymentNominal.find('input.convert-cash').val('');
//                 $paymentNominal.find('input.cash-nominal-input').val('');
//                 $custom.find('input.nilai-custom').val('');
//             }
//         }).fail(function(result) {
//             console.log(result);
//         });
//     }
//     //get menu discount
//     function getDataMenuDiscount() {
//         let URL = "{{ route('getMenuDiscount') }}";
//         $.get(URL, function(result) {
//             $(result).appendTo('.panel[data-panel="panel2"] .content-menu');
//             $('.menu_discount').addClass('active')
//         }).fail(function(result) {
//             console.log(result);
//         })
//     }
//     //get all menu
//     function getmenuAll() {
//         let URL = "{{ route('allmenu') }}";
//         $.get(URL, function(result) {
//             $(result).appendTo('.panel[data-panel="panel2"] .content-menu');
//             $('.all-menu').addClass('active')
//         }).fail(function(result) {
//             console.log(result);
//         })
//     }
//     //get menu sub
//     function getmenuSub(id) {
//         let URL = "{{ route('subMenu', '') }}" + '/' + id;
//         $.get(URL, function(result) {
//             $(result).appendTo('.panel[data-panel="panel2"] .content-menu');
//             $('.menuSub').addClass('active')
//         }).fail(function(result) {
//             console.log(result);
//         })
//     }
//     //get menu kat
//     // get data variasi
//     function getVariasi(idx, type, key) {
//         console.log('get varian menu id:', idx);
//         $.ajax({
//             url: "{{ route('variasi-menu') }}",
//             data: {
//                 id_menu: idx
//             },
//             method: 'GET',
//             type: 'json',
//             success: function(result) {
//                 var $target = $('.Varian-menu');
//                 $target.html('');
//                 if (result.data === null || result.data.length === 0) {
//                     $target.hide();
//                     console.log(result.data, 'data tidak ada')
//                 } else {
//                     checkVariantSelection()
//                     console.log('data ada', result.data)
//                     $target.append('<div class="name-additional">Varian| Choose one</div>');
//                     $.each(result.data, function(key, value) {
//                         $target.append(
//                             ' <div class="option-varian" idx="' + value.id + '">' +
//                             '<p class="varian">' + value.nama + '</p>' +
//                             '<p class="harga-varian" harga=' + value.harga + '>' +
//                             parseInt(value.harga).toLocaleString("id-ID") + '</p>' +
//                             '</div>'
//                         );
//                     });
//                     $target.show();
//                     if (type == 'edit') {
//                         var $elm = $('body .itm-bil[xid="' + key + '"]');
//                         var varian = $elm.find('.detail-itm .varian-op').attr('id_var');
//                         var opVar = $target.find('.option-varian');
//                         console.log(opVar, 'varian', varian);
//                         opVar.each(function() {
//                             var idx = $(this).attr('idx');
//                             console.log(idx)
//                             if (idx == varian) {
//                                 $(this).addClass('active');
//                                 checkVariantSelection()
//                             }
//                         });
//                     }
//                 }
//             },
//             done: function() {
//             }
//         });
//     }
//     // get data additional
//     function getAdditional(id, type, key) {
//         console.log('get additional menu id:', id);
//         $.ajax({
//             url: "{{ route('option-add') }}",
//             data: {
//                 id_menu: id
//             },
//             method: 'GET',
//             type: 'json',
//             success: function(result) {
//                 var $target = $('.additional-menu');
//                 $target.html('');
//                 if (result.data === null || result.data.length === 0) {
//                     $target.hide();
//                     console.log(result.data, 'data tidak ada additional')
//                 } else {
//                     console.log('data ada additional', result.data)
//                     $target.append(
//                         '<div class="name-additional"> Additional|Select multiple</div>');
//                     $.each(result.data, function(key, value) {
//                         $target.append(
//                             ' <div class="option-menu-additional" idx="' + value
//                             .id + '">' +
//                             '<p class="nama">' + value.name + '</p>' +
//                             '<p class="harga" harga=' + value.harga + '>' +
//                             parseInt(value.harga).toLocaleString("id-ID") + '</p>' +
//                             '</div>'
//                         );
//                     });
//                     $target.show();
//                     if (type == 'edit') {
//                         var $elm = $('body .itm-bil[xid="' + key + '"]');
//                         var Adds = [];
//                         var $adds = $elm.find('.detail-itm .add-op');
//                         $adds.each(function() {
//                             var $elm = $(this);
//                             var id = $elm.attr('id_adds');
//                             var obj = {
//                                 'id': id
//                             };
//                             Adds.push(obj);
//                         });
//                         var opAdds = $target.find('.option-menu-additional');
//                         Adds.forEach(function(obj) {
//                             var id = obj.id;
//                             opAdds.each(function() {
//                                 var idx = $(this).attr('idx');
//                                 if (idx == id) {
//                                     $(this).addClass('active');
//                                 }
//                             })
//                         })
//                     }
//                 }
//             },
//             done: function() {
//             }
//         });
//     }
//     // custom additional item menu order dan session
//     function additional(idx, type) {
//         var $varian = $('.option-varian.active');
//         var $additional = $('.option-menu-additional.active');
//         var $dis = $('.option-discount input:checked').prop('checked', true);
//         var $add_delete = $('.option-menu-additional.delete');
//         var $dis_delete = $('.option-discount input.opDis.delete');
//         var qty = $('.jml-menu input.qty').val();
//         var catatan = $('.catatan-menu textarea').val()
//         var id_type_sales = $('.option-type.active').attr('idx');
//         if (id_type_sales === undefined && id_type_sales === "" && id_type_sales === null) {
//             id_type_sales = '4';
//         }
//         var type_sales = $('.option-type.active .nama-option').text();
//         var key = $('.card-popup .btn-add').attr('key');
//         var idDetail = $('.card-popup .btn-add').attr('id_detail');
//         console.log('salseltype', id_type_sales, type_sales);
//         console.log('catatan ', catatan);
//         console.log('jumlah menu ', qty);
//         var Adds = [];
//         var dis = [];
//         var Add_delete = [];
//         var dis_delete = [];
//         var var_id = $varian.attr('idx');
//         var var_name = $varian.find('.varian').text();
//         var var_harga = $varian.find('.harga-varian').attr('harga');
//         //mengambil data additional yang di pilih 
//         $additional.each(function() {
//             var $boxAdd = $(this);
//             var id = $boxAdd.attr('idx');
//             var name = $boxAdd.find('.nama').text();
//             var harga = $boxAdd.find('.harga').attr('harga');
//             var objAdds = {
//                 'id': id,
//                 'nama': name,
//                 'harga': harga,
//                 'id_detail': idDetail,
//                 'qty': qty
//             };
//             Adds.push(objAdds);
//         });
//         // menghitung additional yang sudah dipilih lalu di hapus
//         $add_delete.each(function() {
//             var $tgt = $(this);
//             var id = $tgt.attr('idx');
//             var harga = $tgt.find('.harga').attr('harga');
//             var objAddsDel = {
//                 'id': id,
//                 'id_detail': idDetail,
//                 'harga': harga
//             };
//             Add_delete.push(objAddsDel);
//         });
//         // menghitung discount yang sudah di pilih lalu di hapus
//         $dis_delete.each(function() {
//             var $tgt = $(this);
//             var id = $tgt.attr('id');
//             var objDeleteDis = {
//                 'id': id,
//                 'id_detail': idDetail
//             };
//             dis_delete.push(objDeleteDis);
//         });
//         console.log('ini additional', Adds);
//         console.log('ini discount delete', dis_delete);
//         console.log('ini discount', dis);
//         // menghitung total additional 
//         var totalHarga = 0;
//         for (var i = 0; i < Adds.length; i++) {
//             var harga = Adds[i].harga;
//             harga = harga.replace(/\./g, '');
//             totalHarga += parseInt(harga)
//         }
//         console.log('total additional ', totalHarga);
//         // mengambil harga menu item
//         var harga_menu = $('.header-card-popup .harga-total').text();
//         harga_menu = harga_menu.replace('.', '');
//         // menghitung harga total menu item di tambah dengan total additional
//         var hargaTotal = parseInt(harga_menu) + parseInt(totalHarga);
//         console.log('harga total ', hargaTotal);
//         // menjumlah harga total dari harag + additional dengan qty
//         var total = parseInt(hargaTotal) * parseInt(qty);
//         $dis.each(function(index) {
//             var $targetdis = $(this);
//             const id = $targetdis.attr('id');
//             const rate = $targetdis.attr('rate');
//             // Menghitung nominal dengan membagi rate 100 terlebih dahulu
//             var nominal = total * (rate / 100);
//             var objDiscount = {
//                 'id': id,
//                 'percent': rate,
//                 'id_detail': idDetail,
//                 'nominal': nominal
//             };
//             dis.push(objDiscount);
//             // Mengurangi total dengan nominal yang sudah dihitung untuk diskon berikutnya
//             total -= nominal;
//         });
//         // menjumlah total nominal discount
//         var total_discount = 0;
//         for (var i = 0; i < dis.length; i++) {
//             var rate = dis[i].percent;
//             total_discount += parseInt(rate)
//         }
//         console.log('total discount ', total_discount);
//         console.log('data dis', dis);
//         var postData = {
//             _token: "{{ csrf_token() }}",
//             id: idx,
//             key: key,
//             qty: parseInt(qty),
//             harga: parseInt(harga_menu),
//             harga_addtotal: parseInt(totalHarga),
//             variasi: var_id,
//             var_name: var_name,
//             additional: Adds,
//             discount: dis,
//             catatan: catatan,
//             id_type_sales: id_type_sales,
//             sales_name: type_sales,
//             total_dis: parseInt(total_discount),
//         }
//         console.log("Data Add item: " + postData);
//         if (currentBillId) {
//             // ini untuk masukin product ke bill yang sudah ada
//             postData["target_order"] = currentBillId;
//             // jika item yang di edit maka yang di eksekusi adalah edit
//             if (type == 'edit') {
//                 postData["target_detail"] = idDetail;
//                 postData["adds_delete"] = Add_delete;
//                 postData["dis_delete"] = dis_delete;
//             }
//             //disini seharusnya post lagi , route kalau mau dibedakan bisa dibuat
//             $.post("{{ route('billModify') }}", postData).done(function(data) {
//                 if (data.success === 0) {
//                     alert(data.message);
//                 } else {
//                     if (data.error) {
//                         console.log(data.error);
//                         return;
//                         LogActivity('Error modify bill add item', data)
//                     }
//                     LogActivity('modify bill add item', data);
//                     var $custom = $('.panel[data-panel="panel3"] .custom-part');
//                     var $customCategory = $('.content-payment');
//                     var $viewDetail = $('.view-detail-ord');
//                     $custom.find('input.nilai-custom').val('');
//                     $customCategory.find('.part-category.active').addClass('unactive').removeClass(
//                         'active');
//                     $viewDetail.find('.drop-down').remove();
//                     $viewDetail.find('.detil-bil').remove();
//                     $viewDetail.find('.footer-sub-total').remove();
//                     $('.option-varian').removeClass('active');
//                     $('.option-menu-additional').removeClass('active');
//                     $('.option-discount input:checked').prop('checked', false);
//                     $('.jml-menu input.qty').val('1');
//                     $('.catatan-menu textarea').val('')
//                     $('.card-popup').attr('id-x', '').attr('key-id', '');
//                     $('.btn-add').attr('x-id', '').attr('key', '').attr('id_detail', '').text(
//                     'add');
//                     $('.pop-up.additional').fadeOut();
//                     getBill(currentBillId);
//                     $('.pop-up.additional').fadeOut();
//                     $('.btn-add').removeAttr('disabled');
//                     $('.tooltip').fadeOut();
//                     var Option = $('.option-type');
//                     $('.option-type.active').removeClass('active');
//                     $('.popup-name-bill input.nameBill').val('');
//                     Option.each(function() {
//                         if ($(this).attr('idx') === '4') {
//                             // Add 'active' class to the element with idx '4'
//                             $(this).addClass('active');
//                         }
//                     });
//                 }
//             }).fail(function(data) {
//                 console.log('error', data);
//                 // addLogLocalStorage('error_Modify_bill', 'add Item Modify', data);
//                 LogActivity('error modify bill add item', data)
//             });
//         } else {
//             var url = '';
//             if (type == 'add') {
//                 url = "{{ route('addOrder') }}";
//             } else {
//                 url = "{{ route('edit-item') }}";
//             }
//             $.post(url, postData).done(function(data) {
//                 if (data.success === 0) {
//                     alert(data.message);
//                 } else {
//                     if (type == 'add') {
//                         if (data.error) {
//                             console.log(data.error)
//                             return;
//                             LogActivity('error add item', data)
//                         }
//                         LogActivity('success add item', data)
//                     } else {
//                         if (data.error) {
//                             console.log(data.error)
//                             return;
//                             LogActivity('error edit item', data)
//                         }
//                         LogActivity('edit item', data)
//                     }
//                     console.log('data item add ' + data);
//                     var $custom = $('.panel[data-panel="panel3"] .custom-part');
//                     var $customCategory = $('.content-payment');
//                     var $viewDetail = $('.view-detail-ord');
//                     $custom.find('input.nilai-custom').val('');
//                     $customCategory.find('.part-category.active').addClass('unactive').removeClass(
//                         'active');
//                     $viewDetail.empty();
//                     $('.option-varian').removeClass('active');
//                     $('.option-menu-additional').removeClass('active');
//                     $('.option-discount input:checked').prop('checked', false);
//                     $('.jml-menu input.qty').val('1');
//                     $('.catatan-menu textarea').val('')
//                     $('.card-popup').attr('id-x', '').attr('key-id', '');
//                     $('.btn-add').attr('x-id', '').attr('key', '').attr('id_detail', '').text(
//                     'add');
//                     $('.btn-add').removeAttr('disabled');
//                     $('.tooltip').fadeOut();
//                     var Option = $('.option-type');
//                     $('.option-type.active').removeClass('active');
//                     Option.each(function() {
//                         if ($(this).attr('idx') === '4') {
//                             // Add 'active' class to the element with idx '4'
//                             $(this).addClass('active');
//                         }
//                     });
//                     getSessionOrder()
//                 }
//             }).fail(function(data) {
//                 console.log('error', data);
//                 if (type == 'add') {
//                     //   addLogLocalStorage('ErrorAdd_Item', 'add Item', data);
//                     LogActivity('error add item', data)
//                 } else {
//                     //   addLogLocalStorage('Erroredit_item', 'edit Item ', data);
//                     LogActivity('error edit item', data)
//                 }
//             });
//         }
//     }
//     function checkVariantSelection() {
//         // Cek apakah ada opsi varian yang aktif
//         if ($('.option-varian.active').length === 0) {
//             // Nonaktifkan tombol add dan tampilkan tooltip
//             $('.btn-add').attr('disabled', 'disabled');
//             $('.tooltip').fadeIn();
//         } else {
//             // Aktifkan tombol add dan sembunyikan tooltip
//             $('.btn-add').removeAttr('disabled');
//             $('.tooltip').fadeOut();
//         }
//     }
//     function checkSelesTypeSelection() {
//         // Cek apakah ada opsi varian yang aktif
//         if ($('.option-type.active').length === 0) {
//             // Nonaktifkan tombol add dan tampilkan tooltip
//             $('.btn-add').attr('disabled', 'disabled');
//             $('.tooltip').fadeIn();
//         } else {
//             // Aktifkan tombol add dan sembunyikan tooltip
//             $('.btn-add').removeAttr('disabled');
//             $('.tooltip').fadeOut();
//         }
//     }
//     function getSessionOrder() {
//         let URL = "{{ route('view_detail_session') }}";
//         $.get(URL, function(result) {
//             if (result.error) {
//                 console.log(result.error);
//                 return;
//                 LogActivity('error get session', result)
//             }
//             //    addLogLocalStorage('get_Session', 'session detail', result);
//             LogActivity('get session', result)
//             $('.detail-order .view-detail-ord').html(result.view);
//             $('.pop-up.additional').fadeOut();
//         }).fail(function(result) {
//             console.log('error', result);
//             // addLogLocalStorage('Error_get_Session', 'session detail', result);
//             LogActivity('error get session', result)
//         })
//     }
//     //function delete item pos
//     function deleteItem(id, $elm) {
//         var postData = {
//             _token: "{{ csrf_token() }}",
//             id: id,
//         }
//         if (currentBillId) {
//             $.post("{{ route('Delete-item') }}", postData).done(function(data) {
//                 if (data.success === 0) {
//                     alert(data.message);
//                     LogActivity('Error delete itm bill', data)
//                 } else {
//                     LogActivity('success delete item bill', data)
//                     $('.pop-up.additional').hide();
//                     var dataDelete = {
//                         _token: "{{ csrf_token() }}",
//                         id_order: data.data.id_order,
//                         id: data.data.id
//                     }
//                     console.log('data delete', data)
//                     $.post("{{ route('print_item_delete_thermal') }}", dataDelete).done(function(
//                         data) {
//                         if (data.success === 0) {
//                             alert(data.message);
//                         } else {
//                             console.log('data print delete', data);
//                             $.each(data.detailItem, function(index, value) {
//                                 var id = value.id;
//                                 var id_order = value.id_order;
//                                 throttledButtonClickDelete(id, id_order, $elm);
//                                 console.log('data each', id, id_order)
//                             })
//                             var $custom = $('.panel[data-panel="panel3"] .custom-part');
//                             var $customCategory = $('.content-payment');
//                             var $viewDetail = $('.view-detail-ord');
//                             $custom.find('input.nilai-custom').val('');
//                             $customCategory.find('.part-category.active').addClass(
//                                 'unactive').removeClass('active');
//                             $viewDetail.find('.drop-down').remove();
//                             $viewDetail.find('.detil-bil').remove();
//                             $viewDetail.find('.footer-sub-total').remove();
//                             $('.option-varian').removeClass('active');
//                             $('.option-menu-additional').removeClass('active');
//                             $('.option-discount input:checked').prop('checked', false);
//                             $('.jml-menu input.qty').val('1');
//                             $('.catatan-menu textarea').val('')
//                             $('.card-popup').attr('id-x', '').attr('key-id', '');
//                             $('.btn-add').attr('x-id', '').attr('key', '').attr('id_detail',
//                                 '').text('add');
//                             $('.pop-up.additional').fadeOut();
//                             getBill(currentBillId);
//                             $('.pop-up.additional').fadeOut();
//                             $('.btn-add').removeAttr('disabled');
//                             $('.tooltip').fadeOut();
//                             var Option = $('.option-type');
//                             $('.option-type.active').removeClass('active');
//                             $('.popup-name-bill input.nameBill').val('');
//                             Option.each(function() {
//                                 if ($(this).attr('idx') === '4') {
//                                     // Add 'active' class to the element with idx '4'
//                                     $(this).addClass('active');
//                                 }
//                             });
//                         }
//                     }).fail(function(data) {
//                         console.log('error', data);
//                         // addLogLocalStorage('ErrorSessionItemDelete', 'delete item', data);
//                         LogActivity('error session Item delete', data)
//                     });
//                 }
//             }).fail(function(data) {
//                 console.log('error', data);
//                 // addLogLocalStorage('ErrorSessionItemDelete', 'delete item', data);
//                 LogActivity('error  item delete', data)
//             });
//         } else {
//             $.post("{{ route('item.delete') }}", postData).done(function(data) {
//                 if (data.success === 0) {
//                     alert(data.message);
//                 } else {
//                     if (data.error) {
//                         console.log(data.error);
//                         return;
//                         LogActivity('Error session delete itm', data)
//                     }
//                     LogActivity('Success session delete item', data)
//                     $elm.remove();
//                     //$(this).attr('data-notify',data['count']);
//                     var $custom = $('.panel[data-panel="panel3"] .custom-part');
//                     var $customCategory = $('.content-payment');
//                     var $viewDetail = $('.view-detail-ord');
//                     $custom.find('input.nilai-custom').val('');
//                     $customCategory.find('.part-category.active').addClass('unactive').removeClass(
//                         'active');
//                     $viewDetail.empty();
//                     $('.option-varian').removeClass('active');
//                     $('.option-menu-additional').removeClass('active');
//                     $('.option-discount input:checked').prop('checked', false);
//                     $('.jml-menu input.qty').val('1');
//                     $('.catatan-menu textarea').val('')
//                     $('.card-popup').attr('id-x', '').attr('key-id', '');
//                     $('.btn-add').attr('x-id', '').attr('key', '').attr('id_detail', '').text(
//                     'add');
//                     $('.btn-add').removeAttr('disabled');
//                     $('.tooltip').fadeOut();
//                     var Option = $('.option-type');
//                     $('.option-type.active').removeClass('active');
//                     Option.each(function() {
//                         if ($(this).attr('idx') === '4') {
//                             // Add 'active' class to the element with idx '4'
//                             $(this).addClass('active');
//                         }
//                     });
//                     getSessionOrder()
//                 }
//             }).fail(function(data) {
//                 console.log('error', data);
//                 // addLogLocalStorage('ErrorSessionItemDelete', 'delete item', data);
//                 LogActivity('error session delete item', data)
//             });
//         }
//     }
//     //print-item delete
//     function updateButtonClickStatusDelete() {
//         canClickDelete = true;
//     }
//     function throttle(func, delay) {
//         var timeout;
//         return function() {
//             if (!timeout) {
//                 func.apply(this, arguments);
//                 timeout = setTimeout(function() {
//                     timeout = null;
//                 }, delay);
//             }
//         };
//     }
//     throttledButtonClickDelete = throttle(function(id, id_order, $elm) {
//         if (!canClickDelete) {
//             $('.popup-print').fadeIn();
//             return;
//         }
//         canClickDelete = false;
//         $('.popup-print').fadeIn();
//         console.log("Print sedang diproses...");
//         setTimeout(function() {
//             var dataDelete = {
//                 _token: "{{ csrf_token() }}",
//                 id_order: id_order,
//                 id: id
//             }
//             console.log('data yang di delete', dataDelete);
//             $('.popup-print').fadeOut();
//             console.log("Print selesai.");
//             $elm.remove();
//             LogActivity('item delete', dataDelete);
//             $.post("{{ route('item_delete') }}", dataDelete).done(function(data) {
//                 if (data.success === 0) {
//                     alert(data.message);
//                 } else {
//                     var $viewDetail = $('.view-detail-ord');
//                     $viewDetail.empty();
//                     getBill(dataDelete.id_order);
//                 }
//             }).fail(function(data) {
//                 console.log('error', data);
//                 LogActivity('error item delete', data)
//             });
//             updateButtonClickStatusDelete();
//         }, 1000);
//     }, 1000);
//     //post Order
//     function POSorder($button) {
//         var $targetpayment = $('.pop-payment');
//         var nameBill = $('.popup-name-bill .nameBill').val();
//         var table = $('.drop-down input.nomer-meja').val();
//         var TargetSub = $('.footer-sub-total .txt-price-total.subtotal').text();
//         var subtotal = TargetSub.replace(/\./g, '');
//         var total = $('.footer-sub-total .txt-price-total.total').attr('data-total');
//         if (total !== undefined && total !== null && total !== "") {
//             console.log('data ada');
//             if (currentBillId) {
//                 total = $('.txt-price-total.total').text();
//                 total = total.replace(/\./g, '');
//             } else {
//                 console.log('data tidak ada update');
//             }
//         } else {
//             total = $('.txt-price-total.total').text();
//             total = total.replace(/\./g, '');
//         }
//         var paymentId = $targetpayment.find('.content-payment .part-payment.active').attr('xid');
//         if (paymentId !== undefined && paymentId !== null && paymentId !== "") {
//             let $tgrPayment = $('.payment-nominal .card-payment-nominal');
//             var cash = $tgrPayment.find('.form-cash input.convert-cash').val();
//             var change_ = $tgrPayment.find('.form-cash input.convert-change').val();
//         }
//         var taxes = []
//         $('.taxes').each(function() {
//             var $taxBox = $(this);
//             var id = $taxBox.attr('idx');
//             var nominal = $taxBox.find('.nominal-tax').text();
//             var taxObj = {
//                 "id": id,
//                 "nominal": nominal
//             };
//             taxes.push(taxObj)
//         });
//         //console.log(taxes);
//         //console.log(table);
//         var postData = {
//             _token: "{{ csrf_token() }}",
//             nomer: table,
//             nama: nameBill,
//             subtotal: subtotal,
//             total: total,
//             taxes: taxes,
//         }
//         if (currentBillId) {
//             postData["target_order"] = currentBillId;
//             $.post("{{ route('updateorder') }}", postData).done(function(data) {
//                 console.log(data);
//                 if (data.error) {
//                     console.log(data.error);
//                     return;
//                     LogActivity('Error edit order post', data)
//                 }
//                 LogActivity('edit order post', data)
//                 setTimeout(function() {
//                     $('.popup-print .form-group p').text(
//                         'order is processed and has been update');
//                     $('.popup-print').fadeIn();
//                 }, 1000)
//                 alert('order is processed and has been update');
//                 $('.popup-name-bill').hide();
//                 currentBillId = 0;
//                 const xid = data.data.id;
//                 //Tiket(xid, 'Tiket');
//                 Tiket(xid, 'Tiket');
//                 clearSession()
//             }).fail(function(err) {
//                 console.log(err);
//                 alert('Sepertinya kamu melakukan kesalah coba cek kembali');
//                 // addLogLocalStorage('error', 'edit order', err);
//                 LogActivity('edit error', err)
//             }).always(function() {
//                 // Reset loadPhase and button state
//                 loadPhase = false;
//                 $button.prop('disabled', false).text('Selesai');
//             });
//         } else {
//             var URL = "{{ route('POS-Order') }}";
//             if (paymentId !== undefined && paymentId !== null && paymentId !== "") {
//                 postData['Idpayment'] = paymentId;
//                 postData['cash'] = cash;
//                 postData['change_'] = change_;
//             }
//             $.post(URL, postData).done(function(data) {
//                 console.log(data);
//                 if (data.error) {
//                     console.log(data.error);
//                     return;
//                     LogActivity('Error order post', data)
//                 }
//                 LogActivity('order post', data)
//                 //setTimeout(function(){
//                 //        $('.popup-print .form-group p').text('order is processed');
//                 //        $('.popup-print').fadeIn();
//                 //    },1000)
//                 $('.popup-name-bill').hide();
//                 if (paymentId !== undefined && paymentId !== null && paymentId !== "") {
//                     // alert('Done');
//                     setTimeout(function() {
//                         $('.popup-print .form-group p').text('Done');
//                         $('.popup-print').fadeIn();
//                     }, 1000)
//                     $('.pop-payment').hide();
//                     $('.payment-nominal').hide();
//                     const id = data.data.order.id;
//                     Bill(id, 'Bill')
//                     // getBill(id);
//                     Tiket(id, 'Tiket');
//                     clearSession();
//                 } else {
//                     const id = data.data.order.id;
//                     // $('.act-btn.act2').attr('data-xid',id);
//                     //Tiket(id, 'Tiket');
//                     // getBill(id);
//                     Tiket(id, 'Tiket');
//                     currentBillId = 0;
//                     clearSession();
//                 }
//             }).fail(function(err) {
//                 console.log(err);
//                 alert('Sepertinya kamu melakukan kesalah coba cek kembali');
//                 LogActivity('error order post', data)
//             }).always(function() {
//                 // Reset loadPhase and button state
//                 loadPhase = false;
//                 $button.prop('disabled', false).text('Selesai');
//             });
//         }
//     }
//     // print bill to thermal 
//     function Bill(id, type, retryCount = 0, maxRetries = 3) {
//         var URL = '{{ route('print-bill-thermal', '') }}' + '/' + id;
//         const data = {
//             _token: "{{ csrf_token() }}",
//             type: type
//         }
//         $.post(URL, data).done(function(result) {
//             setTimeout(function() {
//                 $('.popup-print p').text('Print in prosess...');
//                 $('popup-print').fadeIn();
//             }, 1000)
//             throttledButtonClick();
//             //console.log(type);
//             updateLastPrint(id, type)
//             console.log('print bill', result)
//             LogActivity('print bill ', result);
//         }).fail(function(xhr, status, error) {
//             LogActivity('error print bill', error)
//             console.error('Error response:', xhr.responseText || 'No response');
//             console.error('Error details:', error);
//             console.log(error);
//             console.log('Retry Count:', retryCount);
//             console.log('Error Response:', xhr.responseJSON);
//             // Ambil properti `success` dari respons JSON
//             const response = xhr.responseJSON;
//             const success = response?.success || 0;
//             const errorMessage = response?.data || 'Unknown error occurred';
//             if (success === 0 && retryCount < maxRetries) {
//                 console.log(`Retrying print... Attempt ${retryCount + 1} of ${maxRetries}`);
//                 $('.popup-print  p').text('Retrying print...');
//                 $('.popup-print').fadeIn();
//                 Bill(id, type, retryCount + 1, maxRetries);
//                 setTimeout(function() {
//                     $('.popup-print').fadeOut();
//                 }, 3000)
//                 // Retry callback
//             } else {
//                 $('.popup-print p').text('Print failed. Please check the Lan Cabel.');
//                 $('.popup-print').fadeIn();
//                 setTimeout(function() {
//                     $('.popup-print').fadeOut();
//                 }, 3000)
//                 console.log('Max retry attempts reached. Aborting.');
//             }
//         })
//     }
//     function Tiket(id, type, retryCount = 0, maxRetries = 3) {
//         var URL = '{{ route('print-ticket-thermal', '') }}' + '/' + id;
//         const data = {
//             _token: "{{ csrf_token() }}",
//             type: type
//         }
//         $.post(URL, data).done(function(result) {
//             $('.popup-print  p').text('Print in prosess...');
//             $('popup-print').fadeIn();
//             setTimeout(function() {
//                 $('popup-print').fadeOut();
//             }, 2000)
//             console.log('print tiket', result)
//             throttledButtonClick();
//             updateLastPrint(id, type);
//             //Kitchen(id, 'Kitchen');
//             Kitchen(id, 'Kitchen');
//             LogActivity('print tiket', result)
//         }).fail(function(xhr, status, error) {
//             LogActivity('error print tiket', error);
//             console.error('Error response:', xhr.responseText || 'No response');
//             console.error('Error details:', error);
//             console.log(error);
//             console.log('Retry Count:', retryCount);
//             console.log('Error Response:', xhr.responseJSON);
//             const response = xhr.responseJSON;
//             const success = response?.success || 0;
//             const errorMessage = response?.data || 'Unknown error occurred';
//             if (success === 0 && retryCount < maxRetries) {
//                 console.log(`Retrying print... Attempt ${retryCount + 1} of ${maxRetries}`);
//                 $('.popup-print  p').text('Retrying print...');
//                 $('.popup-print').fadeIn();
//                 Tiket(id, type, retryCount + 1, maxRetries);
//                 setTimeout(function() {
//                     $('.popup-print').fadeOut();
//                 }, 3000)
//                 // Retry callback
//             } else {
//                 $('.popup-print p').text('Print failed. Please check the Lan Cabel.');
//                 $('.popup-print').fadeIn();
//                 setTimeout(function() {
//                     $('.popup-print').fadeOut();
//                 }, 3000)
//                 console.log('Max retry attempts reached. Aborting.');
//             }
//         })
//     }
//     function Kitchen(id, type, retryCount = 0, maxRetries = 3) {
//         var URL = '{{ route('print-kitchen-thermal', '') }}' + '/' + id;
//         const data = {
//             _token: "{{ csrf_token() }}",
//             type: type
//         }
//         $.post(URL, data).done(function(result) {
//             $('.popup-print  p').text('Print in prosess...');
//             $('popup-print').fadeIn();
//             setTimeout(function() {
//                 $('popup-print').fadeOut();
//             }, 1000);
//             throttledButtonClick();
//             //console.log(type);
//             updateLastPrint(id, type)
//             LogActivity('print kitchen', result)
//         }).fail(function(xhr, status, error) {
//             LogActivity('error print kitchen', error)
//             console.error('Error response:', xhr.responseText || 'No response');
//             console.error('Error details:', error);
//             console.log(error);
//             console.log('Retry Count:', retryCount);
//             console.log('Error Response:', xhr.responseJSON);
//             // Ambil properti `success` dari respons JSON
//             const response = xhr.responseJSON;
//             const success = response?.success || 0;
//             const errorMessage = response?.data || 'Unknown error occurred';
//             if (success === 0 && retryCount < maxRetries) {
//                 console.log(`Retrying print... Attempt ${retryCount + 1} of ${maxRetries}`);
//                 $('.popup-print  p').text('Retrying print...');
//                 $('.popup-print').fadeIn();
//                 Kitchen(id, type, retryCount + 1, maxRetries);
//                 setTimeout(function() {
//                     $('.popup-print').fadeOut();
//                 }, 3000)
//                 // Retry callback
//             } else {
//                 $('.popup-print p').text('Print failed. Please check the Lan Cabel.');
//                 $('.popup-print').fadeIn();
//                 setTimeout(function() {
//                     $('.popup-print').fadeOut();
//                 }, 3000)
//                 console.log('Max retry attempts reached. Aborting.');
//             }
//         })
//     }
//     function updateButtonClickStatus() {
//         canClick = true;
//     }
//     function throttle(func, delay) {
//         var timeout;
//         return function() {
//             if (!timeout) {
//                 func.apply(this, arguments);
//                 timeout = setTimeout(function() {
//                     timeout = null;
//                 }, delay);
//             }
//         };
//     }
//     throttledButtonClick = throttle(function() {
//         if (!canClick) {
//             $('.popup-print').fadeIn();
//             return;
//         }
//         canClick = false;
//         $('.popup-print').fadeIn(); // Menampilkan pop-up print sedang diproses
//         console.log("Print sedang diproses...");
//         setTimeout(function() {
//             $('.popup-print').fadeOut(); // Menyembunyikan pop-up setelah selesai
//             console.log("Print selesai.");
//             updateButtonClickStatus();
//         }, 1000);
//     }, 1000);
//     function updateLastPrint(xid, type) {
//         const URL = "{{ route('update_last_print', '') }}" + '/' + xid;
//         var dataPost = {
//             _token: "{{ csrf_token() }}",
//             print: type
//         }
//         $.post(URL, dataPost).done(function(data) {
//             // alert('Done');
//             console.log(data)
//         }).fail(function(data) {
//             console.log('error', data);
//         });
//     }
//     //print bill
//     function printBill(id) {
//         var url = "{{ route('print-bill', '') }}" + '/' + id;
//         $.get(url, function(result) {
//             console.log('berhasil');
//         }).fail(function(result) {
//             console.log(result);
//         })
//     }
//     //function get Bill
//     function getBill(idx) {
//         let URL = "{{ route('ref-detail-bil') }}";
//         $.ajax({
//             url: URL,
//             data: {
//                 refId: idx
//             },
//             method: 'GET',
//             success: function(result) {
//                 if (result.error) {
//                     console.log(result.error);
//                     return;
//                     LogActivity('error get bill', result)
//                 }
//                 // Simpan data ke localStorage
//                 //  addLogLocalStorage('success', 'getBill', result)
//                 LogActivity('success get bill', result)
//                 console.log("get bill", result);
//                 $('.popup-name-bill input.nameBill').val(result.data.Bill.name_bill);
//                 // Bersihkan dan tampilkan data pada halaman
//                 $('.part-order').empty();
//                 $('.detail-order .view-detail-ord').html(result.view);
//                 $('.pop-daftar-bill').hide();
//                 currentBillId = idx;
//             }
//         }).fail(function(result) {
//             console.log(result);
//             LogActivity('Error Get bill', result)
//         });
//     }
//     function getDataDetailSplit(idx) {
//         let URL = "{{ route('bill-split') }}";
//         $.ajax({
//             url: URL,
//             data: {
//                 refId: idx
//             },
//             method: 'GET',
//             success: function(result) {
//                 $('.popup-qty').fadeIn();
//                 $('.cotent-detail').empty();
//                 $(result).appendTo('.cotent-detail').show();
//             }
//         }).fail(function(result) {
//             console.log(result);
//         });
//     }
//     function getDataDetailSplitServer(idx) {
//         let URL = "{{ route('bill-split-server', '') }}" + '/' + idx;
//         $.ajax({
//             url: URL,
//             method: 'GET',
//             success: function(result) {
//                 $('.popup-qty').fadeIn();
//                 $('.cotent-detail').empty();
//                 $(result).appendTo('.cotent-detail');
//             }
//         }).fail(function(result) {
//             console.log(result);
//         });
//     }
//     function splitBill($elment, type) {
//         var nomerMeja = $('.nomer-meja').val();
//         var id = $elment.attr('id-item');
//         var $target = $('.itm');
//         var $itm = $target.find('.act-edit input:checked').prop('checked', true);
//         var subTotal = $('.footer-sub-total .txt-price-total.subtotal ').attr('subtotal');
//         var $elm_pb = $('.txt-price-total.PB1');
//         var $elm_service = $('.txt-price-total.Service');
//         var total = $('.footer-sub-total .txt-price-total.total ').attr('total');
//         var $tgt = $itm.closest('.itm-bil');
//         var $adds = $tgt.find('.detail-itm .option.add-op');
//         var $dis = $tgt.find('.option.discount');
//         var $targetpayment = $('.pop-payment');
//         var paymentId = $targetpayment.find('.content-payment .part-payment.active').attr('xid');
//         let $tgrPayment = $('.payment-nominal .card-payment-nominal');
//         var cash = $tgrPayment.find('.form-cash input.convert-cash').val();
//         var change_ = $tgrPayment.find('.form-cash input.convert-change').val();
//         {{--  total = total.replace(/\./g, '');
//     subTotal = subTotal.replace(/\./g, '');  --}}
//         var itms = [];
//         var Adds = [];
//         var dis = [];
//         $adds.each(function() {
//             var $elm = $(this);
//             var id = $elm.attr('id_adds');
//             var nominal = $elm.attr('nominal');
//             var $perent = $elm.closest('.itm-bil');
//             var qty = $perent.find('.control-qty input.qty').val();
//             var objAdd = {
//                 id: id,
//                 nominal: nominal,
//                 qty: qty
//             }
//             Adds.push(objAdd);
//         })
//         console.log(Adds);
//         var TotalAdds = 0;
//         for (var i = 0; i < Adds.length; i++) {
//             var harga = Adds[i].nominal;
//             var qty = Adds[i].qty;
//             var jumlahhargaAdds = parseInt(harga) * parseInt(qty);
//             TotalAdds += jumlahhargaAdds;
//         }
//         console.log(TotalAdds);
//         $dis.each(function() {
//             var $elm = $(this);
//             var xid = $elm.attr('xid-dis');
//             var rate = $elm.attr('dis');
//             var objDis = {
//                 id: xid,
//                 rate: rate,
//             }
//             dis.push(objDis);
//         })
//         var TotalRateDis = 0;
//         for (var i = 0; i < dis.length; i++) {
//             var rate = dis[i].rate;
//             TotalRateDis += parseInt(rate);
//         }
//         console.log(dis);
//         console.log(TotalRateDis);
//         $itm.each(function() {
//             var $elm = $(this);
//             var id = $elm.attr('id-item');
//             var $perent = $elm.closest('.itm-bil');
//             var price = $perent.find('.price').attr('price');
//             var discount = $perent.find('.discount').attr('nominal-dis');
//             var qty = $perent.find('.control-qty input.qty').val();
//             var $adds = $perent.find('.detail-itm .option.add-op');
//             var $dis = $perent.find('.option.discount');
//             var varian = $perent.find('.option.varian-op').attr('data-id');
//             var itmAds = [];
//             var itmsDis = [];
//             $adds.each(function() {
//                 var $tgtAds = $(this);
//                 var hargaAds = $tgtAds.attr('nominal');
//                 var id_adds = $tgtAds.attr('id_adds');
//                 var objItm = {
//                     id: id_adds,
//                     hargaAds: hargaAds
//                 }
//                 itmAds.push(objItm);
//             })
//             $dis.each(function() {
//                 var $elm = $(this);
//                 var xid = $elm.attr('xid-dis');
//                 var rate = $elm.attr('dis');
//                 var objDis = {
//                     id: xid,
//                     rate: rate,
//                 }
//                 itmsDis.push(objDis);
//             })
//             var ItemTotalAdds = 0;
//             for (var i = 0; i < itmAds.length; i++) {
//                 var harga = itmAds[i].hargaAds;
//                 //var jumlahhargaAdds = parseInt(harga) * parseInt(qty) ;
//                 ItemTotalAdds += parseInt(harga);
//             }
//             var ItmsTotalRateDis = 0;
//             for (var i = 0; i < itmsDis.length; i++) {
//                 var rate = itmsDis[i].rate;
//                 ItmsTotalRateDis += parseInt(rate);
//             }
//             var objItm = {
//                 id_item: id,
//                 harga: price,
//                 qty: qty,
//                 Totaladds: ItemTotalAdds,
//                 Adds: itmAds,
//                 jumlah: (parseInt(price) + parseInt(ItemTotalAdds)) * qty,
//                 TotalRateDis: ItmsTotalRateDis,
//                 Discount: itmsDis,
//                 varian: varian
//             }
//             itms.push(objItm);
//         })
//         console.log(itms);
//         var totalItmSplit = 0;
//         var totalDis = 0;
//         for (var i = 0; i < itms.length; i++) {
//             var price = itms[i].harga;
//             var totalAdds = itms[i].Totaladds;
//             var qty = itms[i].qty;
//             var jumlah = (parseInt(price) + parseInt(totalAdds)) * parseInt(qty);
//             var rate = itms[i].TotalRateDis;
//             var nominalDis = parseInt(jumlah) * (parseInt(rate) / 100);
//             if (!isNaN(nominalDis)) {
//                 totalDis += nominalDis;
//             }
//             totalItmSplit += parseInt(jumlah);
//         }
//         var totalSplitPrice = parseInt(totalItmSplit) - parseInt(totalDis);
//         console.log(totalSplitPrice, totalItmSplit, totalDis)
//         //update Order
//         var SubNow = parseInt(subTotal) - totalSplitPrice;
//         //subtotal Db1 jika bill memiliki data item yang di retur
//         var SubTotalDb1 = $('.footer-sub-total .txt-price-total.subtotal ').attr('data-subT');
//         if (SubTotalDb1 !== undefined && SubTotalDb1 !== null && SubTotalDb1 !== "") {
//             SubTotalDb1 = parseInt(SubTotalDb1) - SubNow;
//         }
//         var pb1 = 0.1;
//         var service = 0.05;
//         var nominalPbNow = SubNow * pb1;
//         var nominalService = SubNow * service;
//         var TotalNow = SubNow + nominalPbNow + nominalService;
//         console.log(SubNow, nominalPbNow, nominalService, TotalNow);
//         //order baru
//         var SubTotal = totalSplitPrice;
//         //subtotal Db1 jika bill memiliki data item yang di retur
//         var SubTotalDb1 = $('.footer-sub-total .txt-price-total.subtotal ').attr('data-subT');
//         if (SubTotalDb1 !== undefined && SubTotalDb1 !== null && SubTotalDb1 !== "") {
//             SubTotalDb1 = parseInt(SubTotalDb1) - SubTotal;
//         }
//         var nominalPB1 = SubTotal * pb1;
//         var NomService = SubTotal * service;
//         var TotalSplit = SubTotal + nominalPB1 + NomService;
//         var TotalDb1 = $('.footer-sub-total .txt-price-total.total ').attr('data-total');
//         if (TotalDb1 !== undefined && TotalDb1 !== null && TotalDb1 !== "") {
//             TotalDb1 = parseInt(TotalDb1) - TotalSplit;
//         }
//         console.log(SubTotal, nominalPB1, NomService, TotalSplit);
//         var convertPb1 = nominalPbNow.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
//         var convertService = nominalService.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
//         var ConSub = SubNow.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
//         var ConTotal = TotalNow.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
//         $elm_pb.text(convertPb1);
//         $elm_service.text(convertService);
//         $('.footer-sub-total .txt-price-total.subtotal').text(ConSub);
//         $('.footer-sub-total .txt-price-total.total').text(ConTotal);
//         if (type === 2) {
//             var convert_total = TotalSplit.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
//             var paymentTotal = $('.total-payment').text(convert_total);
//             $('.nm-payment').text(convert_total);
//             $('.nominal').attr('data-nominal', TotalSplit);
//             $('.pop-payment').fadeIn();
//         }
//         if (type === 3) {
//             var DataUpdateSplit = {
//                 _token: "{{ csrf_token() }}",
//                 target_order: currentBillId,
//                 subtotal: SubTotalDb1,
//                 total: TotalDb1,
//                 subtotalNew: SubTotal,
//                 totalNew: TotalSplit,
//                 itms: itms,
//                 adds: Adds,
//                 discount: dis,
//                 cash: cash,
//                 change: change_,
//                 type_pyment: paymentId
//             }
//             console.log('Data SplitBill: ', DataUpdateSplit);
//             $.post("{{ route('split-bill') }}", DataUpdateSplit).done(function(data) {
//                 if (data.success === 0) {
//                     alert(data.message);
//                 } else {
//                     if (data.error) {
//                         console.log(data.error);
//                         return;
//                         LogActivity('Error splitbill', data)
//                     }
//                     LogActivity('splitBill', data);
//                     $('.popup-name-bill').hide();
//                     $('.pop-payment').hide();
//                     $('.payment-nominal').hide()
//                     const id = data.data.new_order.id;
//                     currentBillId = 0;
//                     Bill(id, 'Bill');
//                     Tiket(id, 'Tiket');
//                     // Kitchen(id, 'Kitchen');
//                     clearSession()
//                 }
//             }).fail(function(data) {
//                 console.log('error', data);
//                 LogActivity('Error split bill', data)
//             });
//         }
//         if (type === 4) {
//             var idAdmin = $('.main-sidebar .info.admin').attr('data-admin');
//             var DataUpdateSplit = {
//                 _token: "{{ csrf_token() }}",
//                 target_order: currentBillId,
//                 subtotal: SubNow,
//                 total: TotalNow,
//                 subtotalNew: SubTotal,
//                 totalNew: TotalSplit,
//                 itms: itms,
//                 adds: Adds,
//                 discount: dis,
//                 cash: cash,
//                 change: change_,
//                 type_pyment: paymentId
//             }
//             console.log('idAdmin');
//             DataUpdateSplit['idUser'] = idAdmin;
//             console.log('Data SplitBill: ', DataUpdateSplit);
//             $.post("https://admin.goodfellas.id/api/Spilit-bill-server", DataUpdateSplit).done(function(
//                 data) {
//                 if (data.success === 0) {
//                     alert(data.message);
//                 } else {
//                     var printUrl = "{{ route('data-print-server', '') }}" + "/";
//                     printUrl = printUrl + data.splitOrderId;
//                     window.location = printUrl;
//                 }
//             }).fail(function(data) {
//                 console.log('error', data);
//             });
//         }
//     }
//     function getDatabill() {
//         $.ajax({
//             url: "https://admin.goodfellas.id/api/getDataOrder",
//             method: 'GET',
//             success: function(data) {
//                 console.log(data.data, data.DataRelasi);
//                 dataBill(data.data, data.DataRelasi);
//             },
//         }).fail(function(data) {
//             console.log('error', data);
//         });
//     }
//     function isEmpty(value) {
//         return value === null || value === undefined || value === '';
//     }
//     function dataBill(data, DataRelasi) {
//         var $tgtTable = $('tbody.data-bill');
//         var itmBill = $('tr.item-bill');
//         $.each(data, function(key, value) {
//             var html = '<tr class="item-bill server" idx="' + value.id + '">' +
//                 '<td>';
//             if (!isEmpty(value.name_bill)) {
//                 html += value.name_bill;
//             } else if (!isEmpty(data.id_user)) {
//                 html += value.user.nama;
//             } else {
//                 html += '-';
//             }
//             html += '</td>' +
//                 '<td>' + value.kode_pemesanan + '</td>' +
//                 '<td>';
//             if (!isEmpty(value.no_meja)) {
//                 html += value.no_meja;
//             } else {
//                 html += value.booking.type_room;
//             }
//             html += '</td>' +
//                 '<td>' + value.status.status_order + '</td>' +
//                 '</tr>';
//             $tgtTable.append(html);
//         })
//     }
//     function getDetailBillServer(idx, type) {
//         let URL = "https://admin.goodfellas.id/api/getDetailBill";
//         $.ajax({
//             url: URL,
//             data: {
//                 refId: idx
//             },
//             method: 'GET',
//             success: function(result) {
//                 $('.part-order').empty();
//                 detailBillServer(result.data, result.Detail, result.tax)
//                 $('.pop-daftar-bill').hide();
//                 $('.payment-nominal .card-payment-nominal .footer-card .btn-selesai').attr(
//                     'data-type', 'server');
//                 if (type == 'detailprint_bil') {
//                     $('.pop-payment').hide();
//                     $('.payment-nominal').hide();
//                     var printUrl = "{{ route('data-print-server', '') }}" + '/' + idx;
//                     //printUrl = printUrl + xidOrder;
//                     window.location = printUrl;
//                 }
//             }
//         }).fail(function(result) {
//             console.log(result);
//         });
//     }
//     function detailBillServer(data, Detail, tax) {
//         var $target = $('.view-detail-ord');
//         var html = '<div class="part-order" x-id="' + data.id + '">' +
//             '<div class="drop-down">' +
//             '<p class="txt-dropdown" style="margin: 0">Nomer Meja</p>' +
//             '<input type="text" class="nomer-meja" name="no_meja" value="' + data.no_meja + '">' +
//             '<p class="txt-dropdown" style="margin: 0">' + data.kode_pemesanan + '</p>' +
//             '</div>' +
//             '<div class="detil-bil">';
//         var total_dis = 0;
//         var total = 0;
//         var sub_total = 0;
//         $.each(Detail, function(key, value) {
//             html += '<div class="itm-bil" idx="' + value.id + '" xid="' + key + '">' +
//                 '<div class="itm">' +
//                 '<p class="txt-item" data-item="' + value.id_menu + '">' + value.menu.nama_menu +
//                 '</p>' +
//                 '<div class="qty-menu">' +
//                 '<div class="jumlah">' + value.qty + '</div>' +
//                 '</div>' +
//                 '<div class="part-float-right">';
//             if (!isEmpty(value.harga)) {
//                 html += '<p class="price" price="' + value.harga + '">' + value.total.toString()
//                     .replace(/\B(?=(\d{3})+(?!\d))/g, '.') + '</p>';
//             }
//             html += '<div class="hapus-menu-order" idx="' + value.id + '">X</div>' +
//                 '<div class="act-edit" style="display: none">' +
//                 '<input type="checkbox" name="" id-item="' + value.id +
//                 '" class="check-edit" style="position: relative; right: 0; margin: 0px; left: 20px;">' +
//                 '<span class="checkmark" style="right: 0px; position: relative;"></span>' +
//                 '</div>' +
//                 '</div>' +
//                 '</div>' +
//                 '<div class="detail-itm">';
//             if (!isEmpty(value.varian)) {
//                 html += '<small class="option varian-op">' + value.varian.nama + '</small>';
//             }
//             if (!isEmpty(value.add_optional_order)) {
//                 $.each(value.add_optional_order, function(i, adds) {
//                     html += '<small class="option add-op" id_adds="" >' +
//                         adds.optional__add.name + '-' + adds.optional__add.harga.toString()
//                         .replace(/\B(?=(\d{3})+(?!\d))/g, '.') +
//                         '</small>';
//                 });
//             }
//             if (!isEmpty(value.sales_type)) {
//                 html += '<small class="option status-order" idx="' + value.id_sales_type + '">' +
//                     value.sales_type.name +
//                     '</small>';
//             }
//             $.each(value.discount_menu_order, function(k, dis) {
//                 var totalDis = value.discount.rate_dis;
//                 if (!isEmpty(totalDis)) {
//                     var nominalDis = 0;
//                     var Dis = totalDis / 100;
//                     nominalDis = value.total * Dis;
//                     total_dis += nominalDis;
//                 }
//                 html += '<small class="option status-order discount" dis="' + total_dis +
//                     '" nominal-dis="' + nominalDis + '" >Discount -' + parseInt(nominalDis)
//                     .toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + '</small>';
//             });
//             if (!isEmpty(value.catatan)) {
//                 html += '<small class="option status-order">' + value.catatan + '</small>';
//             }
//             html += '</div>' +
//                 '</div>';
//             sub_total += value.total - total_dis;
//         });
//         html += '</div>' +
//             '</div>';
//         html += '<div class="footer-sub-total">' +
//             '<div class="total">' +
//             '<div class="txt-total subtotal">Subtotal:</div>' +
//             '<div class="txt-price-total subtotal" data-subT="' + sub_total + '">' + sub_total.toString()
//             .replace(/\B(?=(\d{3})+(?!\d))/g, '.') + '</div>' +
//             '</div>';
//         var totalTax = 0;
//         $.each(tax, function(k, tax) {
//             var nominalTax = 0;
//             var desimalTax = tax.tax_rate / 100;
//             var nominalTax = sub_total * desimalTax;
//             totalTax += nominalTax;
//             html += '<div class="total taxes" idx= "' + tax.id + '" >' +
//                 '<div class="txt-total service-change">' + tax.nama + ' ' +
//                 '<p class="presentage">' + tax.tax_rate + '%</p>:</div>' +
//                 '<div class="txt-price-total nominal-tax' + tax.nama + ' ' + '" rate="' + tax
//                 .tax_rate + '" style="color: grey;font-size: 13px;">' + nominalTax.toString()
//                 .replace(/\B(?=(\d{3})+(?!\d))/g, '.') + '</div>' +
//                 '</div>';
//         });
//         total = sub_total + totalTax;
//         html += '<div class="total">' +
//             '<div class="txt-total total">Total:</div>' +
//             '<div class="txt-price-total total" data-total="' + total + '">' + total.toString().replace(
//                 /\B(?=(\d{3})+(?!\d))/g, '.') + '</div>' +
//             '</div>';
//         if (!isEmpty(data.booking)) {
//             var sisaBayar = 0;
//             sisaBayar = total - data.booking.nominal_dp;
//             html += '<div class="total">' +
//                 '<div class="txt-total total">Deposit:</div>' +
//                 '<div class="txt-price-total ">' + data.booking.nominal_dp.toString().replace(
//                     /\B(?=(\d{3})+(?!\d))/g, '.') + '</div>' +
//                 '</div>' +
//                 '<div class="total">' +
//                 '<div class="txt-total total">';
//             if (sisaBayar > 0) {
//                 html += 'Sisa Bayar:';
//             } else {
//                 html += 'Lebih Bayar :';
//             }
//             html += '</div>';
//             html += '<div class="txt-price-total';
//             if (sisaBayar > 0) {
//                 html += 'sisa-bayar';
//             }
//             html += '" data-total="' + sisaBayar + '">' + sisaBayar.toString().replace(
//                     /\B(?=(\d{3})+(?!\d))/g, '.') + '</div>' +
//                 '</div>';
//         }
//         html += '</div>';
//         $target.append(html);
//     }
//     function formatRupiah(input) {
//         // Menghilangkan semua karakter non-digit
//         let nominal = input.value.replace(/\D/g, '');
//         // Format nominal menjadi format Rupiah yang sesuai
//         const formatter = new Intl.NumberFormat('id-ID', {
//             style: 'currency',
//             currency: 'IDR',
//             minimumFractionDigits: 0,
//         });
//         // Update nilai input dengan format Rupiah
//         const formattedValue = nominal === '' ? '' : formatter.format(nominal); // Handle nilai kosong
//         input.value = formattedValue;
//         //input.value = formatter.format(nominal);
//         //var Nominal = $('input.cash-nominal-input').val();
//         if (input.value === "Rp0" || input.value.trim() === "" || input.value === "0") {
//             // Nonaktifkan tombol
//             $('.btn-selesai').prop('disabled', true);
//         } else {
//             // Aktifkan tombol
//             $('.btn-selesai').prop('disabled', false);
//         }
//         console.log(Nominal)
//     }
//     function convertToRupiah(stringValue) {
//         // Hapus karakter selain angka
//         var numberValue = parseInt(stringValue.replace(/[^0-9]/g, ''), 10);
//         // Format angka ke dalam format Rupiah
//         var formattedValue = new Intl.NumberFormat('id-ID', {
//             style: 'currency',
//             currency: 'IDR'
//         }).format(numberValue);
//         return formattedValue;
//     }
//     // Fungsi untuk menangani perubahan nilai input
//     function handleInputChange() {
//         var inputValue = $('#inputString').val();
//         var convertedValue = convertToRupiah(inputValue);
//         $('#result').text(convertedValue);
//     }
//     if (!localStorage.getItem('pcguid')) {
//         localStorage.setItem('pcguid', makeid(8));
//     }
//     function meekid(length) {
//         let result = '';
//         const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
//         const charactersLength = characters.length;
//         let counter = 0;
//         while (counter < length) {
//             result += characters.charAt(Math.floor(Math.random() * charactersLength));
//             counter += 1;
//         }
//         return result;
//     }
//     function formatDate(date) {
//         let day = ("0" + date.getDate()).slice(-2);
//         let month = ("0" + (date.getMonth() + 1)).slice(-2);
//         let year = date.getFullYear();
//         let hours = ("0" + date.getHours()).slice(-2);
//         let minutes = ("0" + date.getMinutes()).slice(-2);
//         let seconds = ("0" + date.getSeconds()).slice(-2);
//         return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
//     }
//     function LogActivity(fromAction, result) {
//         const Url = "{{ route('action-log') }}";
//         const Data = {
//             _token: "{{ csrf_token() }}",
//             action: fromAction,
//             detail: result
//         }
//         $.ajax({
//             url: Url,
//             method: 'POST',
//             data: Data,
//             success: function(result) {
//                 console.log('success', result)
//             }
//         }).fail(function(result) {
//             console.log('error', result)
//         });
//     }
//     function updateSalesType() {
//         const Url = '{{ route('update_sales_type') }}';
//         const Data = {
//             _token: "{{ csrf_token() }}"
//         }
//         $.ajax({
//             url: Url,
//             method: 'POST',
//             data: Data,
//             success: function(result) {
//                 console.log('success update', result)
//             }
//         }).fail(function(result) {
//             console.log('error update', result)
//         });
//     }
//     function discountBill() {
//         var $popDisTgt = $('.popup-daftar-discount');
//         var $tgtDis = $popDisTgt.find('.option-discount input:checked');
//         var Discount = [];
//         $tgtDis.each(function() {
//             const id_dis = $(this).attr('id');
//             const rate = parseFloat($(this).attr('rate'));
//             Discount.push({
//                 id_dis,
//                 rate
//             });
//         });
//         var $tgtView = $('.view-detail-ord');
//         var $items = $tgtView.find('.itm-bil');
//         $items.each(function() {
//             var $tgt = $(this);
//             var id_detail = $tgt.attr('id_item_detail');
//             var id_menu = $tgt.attr('idx');
//             var key = $tgt.attr('xid');
//             var hargaAwal = parseFloat($tgt.find('.itm .price').text().replace(/\./g, ''));
//             var hargaSaatIni = hargaAwal;
//             var $detail_dis = $tgt.find('.detail-itm .option.discount');
//             var JmlDis_old = [];
//             $detail_dis.each(function() {
//                 var rate_old = $(this).attr('dis');
//                 var nominal_old = hargaSaatIni * (rate_old / 100);
//                 hargaSaatIni -= nominal_old
//                 JmlDis_old.push({
//                     'nom': nominal_old
//                 });
//                 console.log('nominalDis', nominal_old)
//             });
//             console.log('nominalDis objek', JmlDis_old)
//             Discount.forEach(function(discount) {
//                 var id_dis = discount.id_dis;
//                 var rate = discount.rate;
//                 var diskonAda = false;
//                 $detail_dis.each(function() {
//                     if ($(this).attr('idx') === id_dis) {
//                         diskonAda = true;
//                     }
//                 })
//                 if (!diskonAda) {
//                     var nominal = hargaSaatIni * (rate / 100);
//                     hargaSaatIni -= nominal;
//                     HendelAddDiscount($tgt, id_dis, rate, nominal);
//                     console.log('Diskon diterapkan:', $tgt, id_dis, rate, nominal);
//                 } else {
//                     console.log('Diskon dengan id yang sama sudah ada:', id_dis);
//                 }
//             });
//             HendelOrderUpdateDiscount($tgt, key, id_menu, hargaSaatIni, id_detail);
//             console.log('Harga akhir setelah semua diskon:', hargaSaatIni);
//         });
//     }
//     function HendelAddDiscount($tgt, id, rate, total) {
//         var $tgtItm = $tgt;
//         var $detail = $tgtItm.find('.detail-itm');
//         var Dis = '<small class="option status_order discount"' +
//             'idx=' + id + ' dis=' + rate + '>' +
//             'Discount ' + rate + '%' + ' - ' + total +
//             '</small>';
//         $detail.append(Dis);
//         console.log('add discount hendle');
//     }
//     function HendelOrderUpdateDiscount($tgt, key, id_menu, nominal, id_detail) {
//         var $tgtItm = $tgt;
//         var key = $tgtItm.attr('xid');
//         var hargaMenu = $tgt.find('.itm .price').attr('price');
//         var $tgtharga = $tgt.find('.itm .price').text();
//         var harga = $tgtharga.replace(/\./g, '');
//         var qty = $tgt.find('.itm .jumlah').text();
//         var $detail_dis = $tgt.find('.detail-itm .option.discount');
//         var $add = $tgt.find('.detail-itm .option.add-op');
//         const $type_order = $tgt.find('.detail-itm .option.type_order');
//         const $variasi = $tgt.find('.detail-itm .option.varian-op');
//         const id_type = $type_order.attr('idx');
//         const name_type = $type_order.text();
//         const var_id = $variasi.attr('id_var');
//         const var_name = $variasi.text();
//         const catatan = $tgt.find('.detail-itm .option.note').text();
//         var ObjDis = [];
//         var AddObj = [];
//         $detail_dis.each(function() {
//             var $targetdis = $(this);
//             const id_dis = $targetdis.attr('idx');
//             const rate = $targetdis.attr('dis');
//             var nominal = harga * (rate / 100);
//             harga -= nominal;
//             var objDiscount = {
//                 id: id_dis,
//                 percent: rate,
//                 id_detail: id_detail,
//                 nominal: nominal
//             };
//             ObjDis.push(objDiscount);
//         });
//         var total_discount = 0;
//         for (var i = 0; i < ObjDis.length; i++) {
//             var rate = ObjDis[i].percent;
//             total_discount += parseInt(rate)
//         }
//         $add.each(function() {
//             const $tgt_add = $(this);
//             const Id_add = $tgt_add.attr('id_adds');
//             var txt_add = $tgt_add.text().trim();
//             var nameMatch = txt_add.match(/^(.+?)\s*-\s*(\d+)/);
//             if (nameMatch) {
//                 var name = nameMatch[1].trim();
//                 var nominal_add = nameMatch[2].trim();
//             }
//             AddObj.push({
//                 'id': Id_add,
//                 'nama': name,
//                 'harga': nominal_add,
//                 'id_detail': id_detail,
//                 'qty': qty
//             });
//         });
//         var totalHarga = 0;
//         for (var i = 0; i < AddObj.length; i++) {
//             var harga = AddObj[i].harga;
//             totalHarga += parseInt(harga)
//         }
//         console.log("additional:" + AddObj)
//         var postData = {
//             _token: "{{ csrf_token() }}",
//             id: id_menu,
//             key: key,
//             discount: ObjDis,
//             qty: parseInt(qty),
//             harga: parseInt(hargaMenu),
//             harga_addtotal: parseInt(totalHarga),
//             variasi: var_id,
//             var_name: var_name,
//             additional: AddObj,
//             catatan: catatan,
//             id_type_sales: id_type,
//             sales_name: name_type,
//             total_dis: parseInt(total_discount),
//         };
//         console.log("objekDis", ObjDis);
//         url = "{{ route('edit-item') }}";
//         console.log(currentBillId);
//         console.log('data edit: ' + postData);
//         if (currentBillId) {
//             postData["target_order"] = currentBillId;
//             postData["target_detail"] = id_detail;
//             console.log('data edit: ' + postData);
//             $.post("{{ route('billModify') }}", postData).done(function(data) {
//                 if (data.success === 0) {
//                     alert(data.message);
//                 } else {
//                     if (data.error) {
//                         console.log(data.error);
//                         return;
//                         LogActivity('Error modify bill add item', data)
//                     }
//                     LogActivity('modify bill add item', data)
//                     var $viewDetail = $('.view-detail-ord');
//                     $viewDetail.empty();
//                     $('.option-varian').removeClass('active');
//                     $('.option-menu-additional').removeClass('active');
//                     $('.option-discount input:checked').prop('checked', false);
//                     $('.jml-menu input.qty').val('1');
//                     $('.catatan-menu textarea').val('')
//                     $('.card-popup').attr('id-x', '').attr('key-id', '');
//                     $('.btn-add').attr('x-id', '').attr('key', '').attr('id_detail', '').text(
//                     'add');
//                     var Option = $('.option-type');
//                     $('.option-type.active').removeClass('active');
//                     $('.popup-name-bill input.nameBill').val('');
//                     Option.each(function() {
//                         if ($(this).attr('idx') === '4') {
//                             // Add 'active' class to the element with idx '4'
//                             $(this).addClass('active');
//                         }
//                     });
//                     getBill(currentBillId);
//                 }
//             }).fail(function(data) {
//                 console.log('error', data);
//                 console.log('data edit: ' + postData)
//                 LogActivity('error modify bill add item', data)
//             });
//         } else {
//             $.post(url, postData).done(function(data) {
//                 if (data.success === 0) {
//                     alert(data.message);
//                 } else {
//                     if (data.error) {
//                         console.log(data.error)
//                         return;
//                         LogActivity('error edit item', data)
//                     }
//                     LogActivity('edit item', data)
//                     var $viewDetail = $('.view-detail-ord');
//                     $viewDetail.empty();
//                     getSessionOrder()
//                 }
//             }).fail(function(data) {
//                 console.log('error', data);
//                 LogActivity('error edit item', data)
//             });
//         }
//     }

// });



// javascript
let tombol = document.querySelector('.kalkulator-tombol');
//let tombol = document.querySelector('.tombol');
let kalkulator = document.querySelector('#kalkulator');

tombol.addEventListener('click', function (e) {
    let tombolClick = e.target;
    let nilaiTombol = tombolClick.innerText;

    if (nilaiTombol === 'C') {
        kalkulator.value = '';

    } else if (nilaiTombol == '<') {
        kalkulator.value = kalkulator.value.slice(0, -1);

    } else if (nilaiTombol == "Add") {


    } else if (nilaiTombol == "=") {
        kalkulator.value = eval(kalkulator.value);

    } else {
        kalkulator.value = kalkulator.value + nilaiTombol;
    }
});

//jquery

$('.btn-minus').on('click', function () {
    var numProduct = Number($(this).next().val());
    if (numProduct > 0) $(this).next().val(numProduct - 1);
});

$('.btn-plus').on('click', function () {
    var numProduct = Number($(this).prev().val());
    $(this).prev().val(numProduct + 1);
});

$('.tab-navigation .tab').on('click', function (e) {
    var target = $(this).attr('target-panel');
    $('.tab').removeClass('active');
    $(this).addClass('active');
    $('.panel').hide();
    $(`.panel[data-panel="${target}"]`).show();
})

$('body').on('click', '.tab-navigation-menu .menu-cat', function () {
    console.log('btn click')
    var idx = $(this).attr('order-menu');
    var type = $(this).attr('data-type');
    $('.menu-cat').removeClass('active');
    $(this).addClass('active');

    console.log('idx');
    getmenuKat(idx, type);

});

// search POS Dashboard
// document.addEventListener("DOMContentLoaded", function() {
document.querySelectorAll("body .search-allmenu").forEach(function (searchBar) {
    searchBar.addEventListener("keyup", function (event) {
        var id = this.id;
        var key = this.value;
        console.log(id);
        console.log(key);
        if (event.key === 'Enter') {
            searchItem(id);
        }
    });
});
// });    

// cek nominal cash 
function CekCashNominal(){
    if ($('input.cash-nominal-input').val() === "0" || $('input.cash-nominal-input').val() === "") {
        // Nonaktifkan tombol add dan tampilkan tooltip
        $('.btn-payment').attr('disabled', 'disabled');
        $('.tooltip.payment').fadeIn();
    } else {
        // Aktifkan tombol add dan sembunyikan tooltip
        $('.btn-payment').removeAttr('disabled');
        $('.tooltip.payment').fadeOut();
    }
}

//search item menu
function searchItem(idSearch) {
    // Cek apakah elemen dengan idSearch ada
    var element = document.querySelector('.search-allmenu[id="' + idSearch + '"]');
    console.log(element)

    if (element) {
        // Ambil nilai input pencarian
        if (element.tagName === "DIV") {
            var searchValue = element.textContent.toLowerCase();
        } else {
            var searchValue = element.value.toLowerCase();
        }

        // Ambil semua card
        // var targetData = document.querySelector('[data-search="' + idSearch + '"]');
        var cards = document.querySelectorAll(".item-card-menu");

        // Iterasi melalui setiap card
        cards.forEach(function (card) {
            // Ambil teks di dalam card
            var cardText = card.textContent.toLowerCase();

            // Cek apakah teks pencarian ada di dalam teks card
            if (cardText.indexOf(searchValue) !== -1) {
                // Jika ditemukan, tampilkan card
                card.style.display = "flex";
            } else {
                // Jika tidak ditemukan, sembunyikan card
                card.style.display = "none";
            }
        });
    } else {
        console.log("Elemen dengan ID '" + idSearch + "' tidak ditemukan.");
    }
}



function removeDataBill() {
    var kode_bill_server = $('body tr.item-bill.server tb.kode-pemesanan').text();
    var kode_bill_local = $('body tr.item-bill.local tb.kode-pemesanan').text();
    console.log(kode_bill_local, kode_bill_server);

    if (kode_bill_server === kode_bill_local) {
        var dataSama = "Kode Bill yang sama: " + kode_bill_server;
        console.log(dataSama);
        $('body .pop-daftar-bill .data-bill tr.item-bill.server').remove();
    } else {
        console.log('data tidak ada ')
    }
}
function konversiStringKeAngka(stringRupiah) {
    // Hapus "Rp" dan tanda titik, lalu ubah ke angka
    const angka = parseFloat(stringRupiah.replace(/[^\d]/g, ''));

    return angka;
}
function getmenuKat(id, type) {
    let URL = "http://127.0.0.1:8000/data-menu-kategori" + '/' + id;
    $.get(URL, function (result) {
        if (type === 'all-menu'){
             var $target = $('.panel[data-panel="panel2"]');
            if ($('.all-menu.active')) {
                $target.find('.all-menu.active .tab-panel-menu').empty();
                $(result).appendTo('.panel[data-panel="panel2"] .content-menu .all-menu .tab-panel-menu');
            }
            
        }

        if(type === 'Fav'){
            var $target = $('.panel[data-panel="panel1"]');
            $target.find('.kategory-menu .tab-panel-menu').empty();
            $(result).appendTo('.panel[data-panel="panel1"] .content-menu .kategory-menu .tab-panel-menu');
        }
        
    }).fail(function (result) {
        console.log(result);
    })
}

function getDataBills(){
    let URL = "http://127.0.0.1:8000/data-bill";
    $.get(URL, function(result){
         var $tgtBill = $('.popup-daftar-bill');
         $tgtBill.fadeIn();
       
        $tgtBill.empty();
        $(result).appendTo($tgtBill);
    }).fail(function(result){
        console.log(result);
    });
}
function getDataDiscount(){
    let URL = "http://127.0.0.1:8000/pos/data-discount";
    $.get(URL, function(result){
         var $tgtBill = $('.popup-daftar-discount');
         $tgtBill.fadeIn();
       
        $tgtBill.empty();
        $(result).appendTo($tgtBill);
    }).fail(function(result){
        console.log(result);
    });
}

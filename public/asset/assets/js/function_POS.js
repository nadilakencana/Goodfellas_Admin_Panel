$(() => {
    $('.pop-up.additional').hide();
    $('.pop-daftar-bill').hide();
    $('.pop-payment').hide();
    $('.payment-nominal').hide();
    $('.popup-name-bill').hide();
    $('.popup-qty').hide();
    //menampilkan pop up data bill yang di save dan close pop up bill
    $('.menu-icon-list-bil').on('click', function () {
        $('.popup-daftar-bill').fadeIn();
        getDataBills();
    })
    $('body').on('click', ".pop-daftar-bill .header-card .close",function () {
        var $tgtPopUp = $('.popup-daftar-bill');
         $tgtPopUp.empty();
         $tgtPopUp.fadeOut();
     }).on('click', '.popup-daftar-bill', function(){
        $(this).fadeOut();
     });

    $('.pop-up .header-card-popup .close').on('click', function () {
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
        var elm = $(this);
        $(elm).toggleClass('active');
        //$('.option-menu-additional').not(elm).removeClass('active');
    });
    $('body').on('click', '.option-type', function () {
        //var $elm = $(this).addClass('active');
        var elm = $(this);
        $(elm).toggleClass('active');
        $('.option-type').not(elm).removeClass('active');
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
    
    $('.payment-nominal .footer-card .btn-close-part').on('click', function () {
        $('.payment-nominal').fadeOut();
    })
    // $('body').on('click', '.payment-nominal', function(){
    //     $('.payment-nominal').hide();
    // });

    $('body').on('click', '.part-payment', function () {
        var elm = $(this);
        $(elm).toggleClass('active');
        $('.part-payment').not(elm).removeClass('active');
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
    let URL = "http://192.168.1.22:8000/data-menu-kategori" + '/' + id;
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
    let URL = "http://192.168.1.22:8000/data-bill";
    $.get(URL, function(result){
         var $tgtBill = $('.popup-daftar-bill');
         $tgtBill.fadeIn();
       
        $tgtBill.empty();
        $(result).appendTo($tgtBill);
    }).fail(function(result){
        console.log(result);
    });
}

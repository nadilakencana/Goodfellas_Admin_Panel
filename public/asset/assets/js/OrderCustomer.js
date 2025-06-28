 const localhost = 'http://192.168.1.22:8000';
 const Token = $('meta[name="csrf-token"]').attr('content');
 
 
 $(() => {
    // Dropdown Sub Categori
    $('#dropdown-cat').on('click', function(e) {
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
    // end Dropdown Sub Categori

    // popUp Additional
    $('.btn-add-menu').on('click', function(){
        let tgt = $(this).attr('xid');
        additional(tgt);
        console.log(tgt)
        // console.log('click')
    })

    $('body').on('click', ".pop-up-modal-menu .header .close",function () {
        const $content = $('.content-modal');
        $content.removeClass('show').addClass('hide');

        setTimeout(() => {
            $('.pop-up-modal-menu').fadeOut().empty();
        }, 400);
     });

    $(".pop-up-modal-menu").click(function(event){
        if (!$(event.target).closest('.content-modal').length) {
            const $content = $('.content-modal');
            $content.removeClass('show').addClass('hide');

            setTimeout(() => {
                $('.pop-up-modal-menu').fadeOut().empty();
            }, 400);
        }
    });

    $('body').on('click', '.jumlah-menu .btn-minus', function (e) {
        e.preventDefault();
        const $input = $(this).siblings('input.qty');
        let value = parseInt($input.val()) || 1;

        if (value > 1) {
            value--;
        }

        $input.val(value);
        recountNominal();
    });

    $('body').on('click', '.jumlah-menu .btn-plus', function (e) {
        e.preventDefault();
        const $input = $(this).siblings('input.qty');
        let value = parseInt($input.val()) || 1;

        if (value < 100) {
            value++;
        }

        $input.val(value);
        recountNominal();
    });

    $('body').on('click','.itm-var',function(e){
        var elm = $(this);
        $(elm).toggleClass('active');
        $('.itm-var').not(elm).removeClass('active');
        recountNominal();
        
    })

    $('body').on('click','.nama-option',function(e){
        var elm = $(this);
        $(elm).toggleClass('active');
        $('.nama-option').not(elm).removeClass('active');
    })

    let lastChecked = null;

    $('body').on('change', '.additional-itms', function () {
        recountNominal();
       
    });

    $('body').on('click','.btn-add-items', function(e){
        let idx = $(this).attr('idx_menu');
        addToCart(idx);
        console.log(idx);
    })
    // end popUp Additional


})

function additional(xid){
    let URL = localhost + '/additional-pop';
    console.log(URL)
    $.ajax({
        type: 'json',
        url: URL,
        data:{
            ex: xid,
        },
        method: 'GET',
        success: function(response){
            let $popup = $('.pop-up-modal-menu');
            $popup.empty().append(response).fadeIn(10, function () {
                $('.content-modal').addClass('show');
            });            
        },
    }).fail(function(result){
        console.log(result);
    });
}

function recountNominal(){
    let nominal = 0;

    let varian_harga = $('.itm-var.active .var-harga');

    if (varian_harga.length > 0) {
        nominal = parseInt(varian_harga.attr('nominal'));
    } else {
        let nominalText = $('.total-menu').attr('nominal');
        nominalText = nominalText.replace(/\./g, ''); 
        nominal = parseInt(nominalText);
        // nominal = parseInt($('.total-menu').attr('nominal'));
    }

    let $Adds = $('.itm-adds input:checked').prop('checked', true);
    let qty = parseInt($('.jumlah-menu input.qty').val());

    let totalAdds = 0;

    $Adds.each(function(){
        let hargaAdd = $(this).attr('nominal');
        hargaAdd = hargaAdd.replace(/\./g, '');
        totalAdds += parseInt(hargaAdd);
    });

    let nominalRecount = (nominal + totalAdds) * qty;
    let formatted = 'Rp. ' + nominalRecount.toLocaleString('id-ID');

    $('.total-menu').text(formatted);
}

function addToCart(idx){
    const URL = localhost +'/add-to-cart';
    let nominal = 0;
    let idx_Var = 0 ;
    let $varian = $('.itm-var.active');
    if ($varian.length > 0) {
        nominal = parseInt($varian.find('.var-harga').attr('nominal'));
        idx_Var = $varian.attr('idx');
        console.log(nominal);
    } else {
        let nominalText = $('.total-menu').attr('nominal');
        nominalText = nominalText.replace(/\./g, ''); 
        nominal = parseInt(nominalText);
       
    }
    let $additional = $('.itm-adds input:checked').prop('checked', true);
    let qty = $('.jumlah-menu input.qty').val();
    let catatan = $('.catatan-menu textarea').val();
    let type_sales = $('.nama-option.active').attr('idx');

    let totalAdds = 0;
    let Adds = [];

    $additional.each(function(){
        let hargaAdd = $(this).attr('nominal');
        let id = $(this).attr('idx');
        let name =  $(this).closest('.itm-adds').find('.name-adds').text();
        hargaAdd = hargaAdd.replace(/\./g, '');
        totalAdds += parseInt(hargaAdd);
        var objAdds = {
            'id': id ,
            'harga' : hargaAdd,
            'name' : name
            
        };

        Adds.push(objAdds);
    });

    let nominalRecount = (nominal + totalAdds) * qty;

    var postData ={
        _token: Token,
        id : idx,
        // key: key,
        qty : parseInt(qty),
        harga: parseInt(nominal),
        harga_addtotal: parseInt(totalAdds),
        variasi: idx_Var,
        additional: Adds,
        catatan : catatan,
        id_type_sales: type_sales,
       
    }

    console.log(postData);


    $.post(URL, postData).done(function(data){
        if(data.success === 0){
            alert(data.message);
            // console.log(postData, data);
            window.location.reload()

        }else{
            alert(data.message);
            // console.log(postData, data);
        }
    }).fail(function(err){
        console.log('error', err)
         console.log(postData, err);
        alert('faild add this item to cart, please cek your order item again');
    })

}
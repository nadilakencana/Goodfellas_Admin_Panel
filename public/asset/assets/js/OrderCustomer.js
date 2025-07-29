const localhost = 'http://192.168.1.22:8000';
const Token = $('meta[name="csrf-token"]').attr('content');
const mejaSession = $('.session-meja').attr('content');

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search_menu');
    const resultsContainer = document.getElementById('search-results-dropdown');
    let searchTimeout;

    function renderResults(data) {
        resultsContainer.innerHTML = '';

        if ((!data.data || data.data.length === 0)) {
            resultsContainer.style.display = 'none';
            return;
        }

        // Tampilkan hasil produk
        if (data.data && data.data.length > 0) {
            resultsContainer.style.display = 'block';
            data.data.forEach(item => {
                const imageUrl = item.image ? `/asset/assets/image/menu/${item.image}` : `/asset/assets/image/menu/drink.png`;
                resultsContainer.innerHTML += `
                    <div class="result-item">
                        <img src="${imageUrl}" alt="${item.nama_menu}">
                        <div class="info gap-3">
                            <div class="name">${item.nama_menu}</div>
                             <span class="pt-2">Rp. ${item.harga}</span>
                        </div>
                       
                        <div class="btn-add-menu cursor-pointer" xid="${item.encrypted_id}">
                            <img src="/asset/assets/image/icon/btn_Add.png" alt="" width="30" height="30">
                        </div>
                    </div>
                `;
            });
        }

        
    }

    // Event listener untuk input
    searchInput.addEventListener('keyup', () => {
        clearTimeout(searchTimeout);
        const keyword = searchInput.value;

        if (keyword.length < 2) {
            resultsContainer.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`${localhost}/menu/search?search=${keyword}`) 
                .then(response => response.json())
                .then(data => {
                    renderResults(data);
                    // console.log(data)
                })
                .catch(error => console.error('Error:', error));
        }, 100); // Debounce 300ms
    });

    // Sembunyikan dropdown jika klik di luar area
    document.addEventListener('click', function(event) {
        if (!resultsContainer.contains(event.target)) {
            resultsContainer.style.display = 'none';
        }
    });
});
 
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
    $('body').on('click','.btn-add-menu' ,function(){
        let tgt = $(this).attr('xid');
        additional(tgt, 'add', null);
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
        let xkey = $(this).attr('xkey');

        console.log('Menu ID:', idx);
        console.log('Xkey:', xkey);

        // Cek apakah xkey kosong atau tidak terdefinisi
        if (xkey === '' || xkey === undefined || xkey === null) {
            // Jika xkey kosong/tidak ada, ini adalah aksi "ADD"
            console.log('Action: Add new item to cart');
            addToCart(idx, 'add', xkey); // Panggil fungsi untuk menambah item baru
        } else {
            // Jika xkey memiliki nilai, ini adalah aksi "EDIT"
            // Pastikan xkey adalah integer jika itu merepresentasikan indeks array
            let itemIndexToEdit = parseInt(xkey);
            if (isNaN(itemIndexToEdit)) {
                console.error('Invalid xkey for edit action:', xkey);
                return; // Hentikan eksekusi jika xkey tidak valid
            }
            console.log('Action: Edit existing item in cart (Index:', itemIndexToEdit, ')');
            addToCart(idx, 'edit', xkey); // Panggil fungsi untuk mengedit item yang ada
        }
       
    })

    
    // end popUp Additional

    // cart
    $('.btn-delete-itm').on('click', function(e){
        let xkey= $(this).attr('xkey');
        ItemDelete(xkey);
    })

    $('.item-name').on('click', function(e){
        let tgt = $(this).attr('xid');
        let key = $(this).attr('xkey')
        additional(tgt, 'edit', key);
    })

    $('.btn-order').on('click', function(){
        postOrder()
    });

    // end cart


})

function additional(xid, action, key){
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

            if(action === 'edit'){
                $popup.find('.btn-add-items').attr('xkey', key)
                console.log(key)
            }           
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

function addToCart(idx, action, key){
    let valid = false;
    let URL = '';
    if(action == 'add'){
        URL = localhost +'/add-to-cart';
    }else{
        URL = localhost +'/edit-item-cart';
    }
     
    let nominal = 0;
    let idx_Var = 0 ;
    let $variansItem = $('.itm-var');
    let $varian =  $('.itm-var.active');;
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

    if(action == 'edit'){
         postData["key"] = key;
    }

    if ($variansItem.length > 0){
        if ($varian.length > 0) {

            valid = true;
        }

    }else{
        valid = true;
    }


    if(valid == true){
        $.post(URL, postData).done(function(data){
            if(data.success === 0){
                // alert(data.message);
               Swal.fire({
                    title: 'Faild!',
                    text: data.message,
                    icon: 'error',
                })

            }else{
                Swal.fire({
					title: 'Success!',
					text: data.message,
					icon: 'success',
				})
                // alert(data.message);
                window.location.reload();
                // console.log(postData, data);
            }
        }).fail(function(err){
            console.log('error', err)
            console.log(postData, err);
            Swal.fire({
				title: 'Faild!',
				text: 'faild add this item to cart, please cek your order item again',
				icon: 'error',
			})
            // alert('faild add this item to cart, please cek your order item again');
        })
    }else{
        new swal({
			// title: 'Faild!',
			text: 'please select one varian item',
			icon: 'question',
		})
        // alert('please select one varian item');
    }

    

}

function ItemDelete(xid){
    
    const URL = localhost +'/delete-item-cart';
    let postData = {
        _token : Token,
        id : xid,
    }

    $.post(URL, postData).done(function(data){
        if(data.success === 0 ){
             Swal.fire({
					title: 'Faild!',
					text: data.message,
					icon: 'error',
				})
            // alert(data.message);
        }else{
             Swal.fire({
					title: 'Success!',
					text: data.message,
					icon: 'success',
				})
            // alert(data.message);
            window.location.reload();
            
        }
    }).fail(function(data){
        console.log('error',data);
        
    });
}

function postOrder(){
    let valid = true;
    const URL = localhost + '/Order-customer/post';
    
    let customerName = $('input[name="customer_name"]').val();
    let subtotal = $('span.subtotal').attr('nominal');
    let $tax = $('.tax-total');
    let grand_total = $('span.grand-total').attr('nominal');

    let taxes = [];

    $tax.each(function(){
        let $tgt = $(this);
        let xid = $tgt.attr('xid_tax');
        let nominal = $tgt.attr('nominal');

        let taxObj = {"xid": xid, "nominal": nominal};

        taxes.push(taxObj);
    })

    console.log(taxes)

    let postData = {
        _token: Token,
        customer_name:  customerName,
        subtotal: subtotal,
        total: grand_total,
        tax: taxes
    }

    if(customerName == ''){
        valid = false;
        Swal.fire({
			// title: 'Success!',
			text: 'plase input your name in column name, If you have previously opened a bill, please enter the same name as the previous bill',
			icon: 'question',
		});

    }

    if (!mejaSession) { 
        valid = false;
        Swal.fire({
            // title: 'Faild!',
            text: 'Table number not found. Please rescan the QR Code at your table.',
            icon: 'question',
        });
    }

   

    if(valid == true){
        $.post(URL, postData).done(function(data){
        if(data.success === 0 ){
                Swal.fire({
					title: 'Faild!',
					text: data.message,
					icon: 'Error',
				})
                alert(data.message);
            }else{
               Swal.fire({
					title: 'Success!',
					text: data.message,
					icon: 'success',
				})
                // alert(data.message);
                window.location.reload();
                
            }
        }).fail(function(data){
            console.log('error',data);
            
        });
    }

}
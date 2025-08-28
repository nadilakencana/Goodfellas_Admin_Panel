// Optimized POS JavaScript - Performance Improvements
const localhost = 'http://127.0.0.1:8000';

$(document).ready(function() {
    // Cache frequently used DOM elements
    const $body = $('body');
    const $popup = $('.pop-up.additional');
    const $viewDetail = $('.view-detail-ord');
    const $paymentPopup = $('.pop-payment');
    const $paymentNominal = $('.payment-nominal');
    
    let currentBillId = 0;
    let loadPhase = false;
    let canClick = true;
    let canClickDelete = true;

    // Pusher configuration
    Pusher.logToConsole = true;
    const pusher = new Pusher('2370e8d9488988129926', { cluster: 'ap1' });
    const channel = pusher.subscribe('orders');
    
    channel.bind('orders-event', function(data) {
        console.log('✅ Event "order.created" RECEIVED:', data);
        handleOrderNotification(data);
    });

    // Event Handlers - Using event delegation for better performance
    $body.on('click', '.item-card-menu', handleMenuItemClick);
    $body.on('click', '.itm-bil', handleBillItemEdit);
    $body.on('click', '.hapus-menu-order', handleDeleteItem);
    $body.on('click', '.btn-add', handleAddItem);
    $body.on('click', '.option-varian', handleVariantSelection);
    $body.on('click', '.act-btn-add', () => currentBillId = 0);

    // Menu item click handler
    function handleMenuItemClick() {
        const $elm = $(this);
        const idx = $elm.attr('idx');
        const harga = $elm.attr('target-price');
        const stok = parseInt($elm.attr('stok'));
        const status = parseInt($elm.attr('status'));
        checkVariantSelection();
        checkSalesType();
        updatePopupPrice(harga);
        $popup.find('.card-popup').attr('id-x', idx);
        $popup.find('.btn-add').attr('x-id', idx);

        hendelCheckKategori(idx)

        getVariasi(idx, 'add', '');
        getAdditional(idx, 'add', '');
        $popup.fadeIn();
    }

    // cek kategori menu 
    function hendelCheckKategori(id){
        const URL = localhost +  "/kategori-cek";
        $.ajax({
                url: URL,
                data: { id: id },
                method: 'GET',
                dataType: 'json',
                
                success: function(result) {
                    console.log(result);
                    const $target = $('.pop-up.additional');
                    const $btnAdd = $target.find('.btn-add');
                    let category = result.data.kategori.kategori_nama;
                    console.log(category)
                    
                    if (category == 'Foods') {
                        if(result.data.stok <= 0){
                            $btnAdd.prop('disabled', true).text('tidak tersedia');
                            console.log('food tidak tersedia')
                        } else {
                            $btnAdd.prop('disabled', false).text('add');
                        }
                    } else if(category == 'Drinks') {
                        if(result.data.active == 0){
                            $btnAdd.prop('disabled', true).text('tidak tersedia');
                            console.log('drink tidak tersedia')
                        } else {
                            $btnAdd.prop('disabled', false).text('add');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading additional options:', error);
                    $('.additional-menu').html('<div class="error">Error loading options</div>');
                }
        });
    }
    // Bill item edit handler
    function handleBillItemEdit() {
        const $elm = $(this);
        const arrkey = $elm.attr('xid');
        const id = $elm.attr('idx');
        const idDetail = $elm.attr('id_item_detail');
        const stok = parseInt($elm.attr('stok'));
        const status = parseInt($elm.attr('status'));

        $popup.fadeIn();
        $('.btn-add').removeAttr('disabled');
        $('.tooltip').fadeOut();

        const harga = $elm.find('.price').attr('price');
        updatePopupPrice(harga);
        
        $popup.find('.card-popup')
            .attr('id-x', id)
            .attr('key-id', arrkey)
            .attr('id_detail', idDetail);
            
        $popup.find('.btn-add')
            .attr('x-id', id)
            .attr('key', arrkey)
            .attr('id_detail', idDetail)
            .text('update');

        populateEditForm($elm, id, arrkey);
    }

    // Delete item handler
    function handleDeleteItem(e) {
        e.stopPropagation();
        const id = $(this).attr('idx');
        const $elementCart = $(this).parents('.itm-bil');

        if (confirm('Sure you want to delete the menu item?')) {
            deleteItem(id, $elementCart);
        }
    }

    // Add item handler
    function handleAddItem() {
        const idx = $(this).attr('x-id');
        const key = $(this).attr('key');
        
        if (!key) {
            additional(idx, 'add');
        } else {
            additional(idx, 'edit');
        }
    }

    // Variant selection handler
    function handleVariantSelection() {
        const $elm = $(this).addClass('active');
        const harga = $elm.find('.harga-varian').text();
        const nilai = harga.replace(/\./g, '');
        
        $('.header-card-popup .harga-total').text(harga).attr('price', nilai);
        checkVariantSelection();
    }

    // Utility Functions
    function updatePopupPrice(harga) {
        const $price = $popup.find('.harga-total');
        if (harga.includes('.')) {
            const harga_ = harga.replace(".", "");
            $price.attr('price', harga_).text(harga);
        } else {
            $price.attr('price', harga).text(harga);
        }
    }

    function populateEditForm($elm, id, arrkey) {
        const qty = $elm.find('.itm .jumlah').text();
        const typeSales = $elm.find('.detail-itm .status_order').attr('idx');
        const note = $elm.find('.note').text();

        // Populate discount checkboxes
        const dis = [];
        $elm.find('.detail-itm .discount').each(function() {
            dis.push({ id: $(this).attr('idx') });
        });

        getVariasi(id, 'edit', arrkey);
        getAdditional(id, 'edit', arrkey);

        $popup.find('.jumlah-menu input.qty').val(qty);
        
        // Set discount checkboxes
        const opDis = $popup.find('.option-discount input.opDis');
        dis.forEach(obj => {
            opDis.filter(`[id="${obj.id}"]`).prop('checked', true);
        });

        // Set type selection
        const opType = $popup.find('.option-type');
        const typeActive = $popup.find('.option-type[idx="4"]').attr('idx');
        
        opType.each(function() {
            const xid = $(this).attr('idx');
            if (xid == typeSales && typeSales !== typeActive) {
                $(this).addClass('active');
                $popup.find('.option-type[idx="4"]').removeClass('active');
            }
            if (xid == typeSales && typeSales == typeActive) {
                $popup.find('.option-type[idx="4"]').addClass('active');
            }
        });

        $popup.find('.catatan-menu textarea').val(note);
    }

    function checkVariantSelection() {
        const $variantContainer = $('.Varian-menu');
        const hasVariants = $variantContainer.is(':visible') && $variantContainer.find('.option-varian').length > 0;
        
        if (!hasVariants) {
            $('.btn-add').prop('disabled', false);
            $('.tooltip').fadeOut();
            return;
        }
        
        const hasActive = $('.option-varian.active').length > 0;
        $('.btn-add').prop('disabled', !hasActive);
        $('.tooltip')[hasActive ? 'fadeOut' : 'fadeIn']();
    }

    const checkSalesType = () => {
        const hasActive = $('.option-type.active').length > 0;
        $('.btn-add').prop('disabled', !hasActive);
        $('.tooltip')[hasActive ? 'fadeOut' : 'fadeIn']();
    };

    // AJAX Functions with error handling and loading states
    function getVariasi(idx, type, key) {
        $.ajax({
            url: localhost + "/variasi-menu",
            data: { id_menu: idx },
            method: 'GET',
            type: 'json',
            beforeSend: function() {
                // Show loading state
                $('.Varian-menu').html('<div class="loading">Loading variants...</div>');
            },
            success: function(result) {
                const $target = $('.Varian-menu');
                $target.html('');
                
                if (!result.data || result.data.length === 0) {
                    $target.hide();
                } else {
                    $target.append('<div class="name-additional">Varian | Choose one</div>');
                    
                    result.data.forEach(value => {
                        $target.append(`
                            <div class="option-varian" idx="${value.id}">
                                <p class="varian">${value.nama}</p>
                                <p class="harga-varian" harga="${value.harga}">
                                    ${parseInt(value.harga).toLocaleString("id-ID")}
                                </p>
                            </div>
                        `);
                    });
                    
                    $target.show();
                    
                    if (type === 'edit') {
                        setEditVariant(key, $target);
                    }
                }
                checkVariantSelection();
            },
            error: function(xhr, status, error) {
                console.error('Error loading variants:', error);
                $('.Varian-menu').html('<div class="error">Error loading variants</div>');
            }
        });
    }

    function setEditVariant(key, $target) {
        const $elm = $(`.itm-bil[xid="${key}"]`);
        const varian = $elm.find('.detail-itm .varian-op').attr('id_var');
        
        $target.find('.option-varian').each(function() {
            if ($(this).attr('idx') == varian) {
                $(this).addClass('active');
                checkVariantSelection();
            }
        });
    }

    function getAdditional(id, type, key) {
        $.ajax({
            url: localhost + "/option-additional",
            data: { id_menu: id },
            method: 'GET',
            type: 'json',
            beforeSend: function() {
                $('.additional-menu').html('<div class="loading">Loading options...</div>');
            },
            success: function(result) {
                const $target = $('.additional-menu');
                $target.html('');
                
                if (!result.data || result.data.length === 0) {
                    $target.hide();
                } else {
                    $target.append('<div class="name-additional">Additional | Select multiple</div>');
                    
                    result.data.forEach(value => {
                        $target.append(`
                            <div class="option-menu-additional" idx="${value.id}">
                                <p class="nama">${value.name}</p>
                                <p class="harga" harga="${value.harga}">
                                    ${parseInt(value.harga).toLocaleString("id-ID")}
                                </p>
                            </div>
                        `);
                    });
                    
                    $target.show();
                    
                    if (type === 'edit') {
                        setEditAdditional(key, $target);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading additional options:', error);
                $('.additional-menu').html('<div class="error">Error loading options</div>');
            }
        });
    }

    function setEditAdditional(key, $target) {
        const $elm = $(`.itm-bil[xid="${key}"]`);
        const adds = [];
        
        $elm.find('.detail-itm .add-op').each(function() {
            adds.push({ id: $(this).attr('id_adds') });
        });

        adds.forEach(obj => {
            $target.find(`.option-menu-additional[idx="${obj.id}"]`).addClass('active');
        });
    }

    // Optimized additional function with better error handling
    function additional(idx, type) {
        if (loadPhase) {
            console.log('Process already running. Please wait.');
            return;
        }

        const formData = collectFormData(idx, type);
        
        if (!validateFormData(formData)) {
            return;
        }

        loadPhase = true;
        const $button = $('.btn-add');
        $button.prop('disabled', true).text('Processing...');
        

        const url = currentBillId ? 
            (type === 'add' ? localhost+"/modify-bill" : localhost+"/modify-bill") :
            (type === 'add' ? localhost+"/data-session" : localhost+"/update-item-order");

        $.post(url, formData)
            .done(handleAddSuccess)
            .fail(handleAddError)
            .always(() => {
                loadPhase = false;
                $button.prop('disabled', false).text(type === 'add' ? 'Add' : 'Update');
            });
    }

    function collectFormData(idx, type) {
        const $varian = $('.option-varian.active');
        const $additional = $('.option-menu-additional.active');
        const $dis = $('.option-discount input:checked');
        
        const qty = $('.jml-menu input.qty').val();
        const catatan = $('.catatan-menu textarea').val();
        const id_type_sales = $('.option-type.active').attr('idx') || '4';
        const type_sales = $('.option-type.active .nama-option').text();
        
        const key = $('.card-popup .btn-add').attr('key');
        const idDetail = $('.card-popup .btn-add').attr('id_detail');

        // Collect additional items
        const adds = [];
        $additional.each(function() {
            const $box = $(this);
            adds.push({
                id: $box.attr('idx'),
                nama: $box.find('.nama').text(),
                harga: $box.find('.harga').attr('harga'),
                id_detail: idDetail,
                qty: qty
            });
        });

        // Calculate totals
        const harga_menu = parseInt($('.header-card-popup .harga-total').text().replace(/\./g, ''));
        const totalAdditional = adds.reduce((sum, item) => sum + parseInt(item.harga.replace(/\./g, '')), 0);
        const hargaTotal = harga_menu + totalAdditional;
        let total = hargaTotal * parseInt(qty);

        // Collect discounts
        const dis = [];
        $dis.each(function() {
            const rate = parseFloat($(this).attr('rate'));
            const nominal = total * (rate / 100);
            
            dis.push({
                id: $(this).attr('id'),
                percent: rate,
                id_detail: idDetail,
                nominal: nominal
            });
            
            total -= nominal;
        });

        const total_discount = dis.reduce((sum, item) => sum + item.percent, 0);

        return {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id: idx,
            key: key,
            qty: parseInt(qty),
            harga: harga_menu,
            harga_addtotal: totalAdditional,
            variasi: $varian.attr('idx'),
            var_name: $varian.find('.varian').text(),
            additional: adds,
            discount: dis,
            catatan: catatan,
            id_type_sales: id_type_sales,
            sales_name: type_sales,
            total_dis: total_discount,
            target_order: currentBillId || undefined,
            target_detail: type === 'edit' ? idDetail : undefined
        };
    }

    function validateFormData(data) {
        if (!data.qty || data.qty <= 0) {
            alert('Please enter a valid quantity');
            return false;
        }
        return true;
    }

    function handleAddSuccess(data) {
        if (data.success === 0) {
            alert(data.message);
            return;
        }

        resetForm();
        
        if (currentBillId) {
            getBill(currentBillId);
        } else {
            getSessionOrder();
        }
        
        $popup.fadeOut();
        LogActivity(currentBillId ? 'modify bill add item' : 'success add item', data);
    }

    function handleAddError(xhr) {
        console.error('Error:', xhr);
        alert(xhr.responseJSON?.message || 'An error occurred');
        LogActivity('error add item', xhr);
    }

    function resetForm() {
        $('.option-varian').removeClass('active');
        $('.option-menu-additional').removeClass('active');
        $('.option-discount input:checked').prop('checked', false);
        $('.jml-menu input.qty').val('1');
        $('.catatan-menu textarea').val('');
        $('.card-popup').attr('id-x', '').attr('key-id', '');
        $('.btn-add').attr('x-id', '').attr('key', '').attr('id_detail', '').text('add');
        $('.btn-add').removeAttr('disabled');
        $('.tooltip').fadeOut();
        
        const $options = $('.option-type');
        $options.removeClass('active');
        $options.filter('[idx="4"]').addClass('active');
    }

    // Notification handler
    function handleOrderNotification(data) {
        const $iframe = $('.frameHolder');
        
        if ($iframe.length && $iframe[0].contentWindow) {
            const iframeContentWindow = $iframe[0].contentWindow;
            
            $(iframeContentWindow.document).ready(function() {
                const $notifAudio = $(iframeContentWindow.document).find('.audioPlace');
                
                if ($notifAudio.length) {
                    const notifAudio = $notifAudio[0];
                    
                    if (typeof notifAudio.play === 'function') {
                        notifAudio.muted = false;
                        notifAudio.play()
                            .then(() => {
                                setTimeout(() => {
                                    alert(`Check Order Now\nKode: ${data.order.kode_pemesanan}\nNomor Meja: ${data.order.no_meja}`);
                                }, 500);
                            })
                            .catch(err => {
                                console.error('Failed to play audio:', err);
                                alert(`Check Order Now\nKode: ${data.order.kode_pemesanan}\nNomor Meja: ${data.order.no_meja}`);
                            });
                    }
                }
            });
        }
    }

    // Utility functions
    function getSessionOrder() {
        let URL = localhost + "/data-session-order";
        $.get(URL)
            .done(function(result) {
                if (result.error) {
                    console.log(result.error);
                    return;
                }
                $viewDetail.html(result.view);
                $popup.fadeOut();
                LogActivity('get session', result);
            })
            .fail(function(result) {
                console.log('Error getting session:', result);
                LogActivity('error get session', result);
            });
    }

    function getBill(idx) {
        $.ajax({
            url: localhost+"/data-detail-order-ref",
            data: { refId: idx },
            method: 'GET',
            success: function(result) {
                if (result.error) {
                    console.log(result.error);
                    return;
                }
                
                $('.popup-name-bill input.nameBill').val(result.data.Bill.name_bill);
                $('.part-order').empty();
                $viewDetail.html(result.view);
                $('.pop-daftar-bill').hide();
                currentBillId = idx;
                
                LogActivity('success get bill', result);
            },
            error: function(result) {
                console.log('Error getting bill:', result);
                LogActivity('Error Get bill', result);
            }
        });
    }

    function deleteItem(id, $elm) {
        const postData = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id: id
        };

        const url = currentBillId ? localhost+"/delete-modify" :  localhost+ "/delete/item";

        $.post(url, postData)
            .done(function(data) {
                if (data.success === 0) {
                    alert(data.message);
                    return;
                }

                if (currentBillId) {
                    handleBillItemDelete(data, $elm);
                } else {
                    $elm.remove();
                    resetForm();
                    getSessionOrder();
                }
                
                LogActivity('Success delete item', data);
            })
            .fail(function(data) {
                console.log('Error deleting item:', data);
                LogActivity('error delete item', data);
            });
    }

    function handleBillItemDelete(data, $elm) {
        $('.pop-up.additional').hide();

        let URL = localhost + '/print-item-delete-thermal';
        
        const deleteData = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            id_order: data.data.id_order,
            id: data.data.id
        };

        $.post(URL, deleteData)
            .done(function(printData) {
                if (printData.success === 0) {
                    alert(printData.message);
                    return;
                }

                printData.detailItem.forEach(item => {
                    throttledButtonClickDelete(item.id, item.id_order, $elm);
                });

                resetForm();
                getBill(currentBillId);
            })
            .fail(function(data) {
                console.log('Error printing delete:', data);
                LogActivity('error print delete', data);
            });
    }

    // Throttled functions for print operations
    function throttle(func, delay) {
        let timeout;
        return function() {
            if (!timeout) {
                func.apply(this, arguments);
                timeout = setTimeout(() => timeout = null, delay);
            }
        };
    }

    const throttledButtonClick = throttle(function() {
        if (!canClick) return;
        
        canClick = false;
        $('.popup-print').fadeIn();
        
        setTimeout(() => {
            $('.popup-print').fadeOut();
            canClick = true;
        }, 1000);
    }, 1000);

    const throttledButtonClickDelete = throttle(function(id, id_order, $elm) {
        if (!canClickDelete) return;
        
        canClickDelete = false;
        $('.popup-print').fadeIn();
        
        setTimeout(() => {
            const deleteData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id_order: id_order,
                id: id
            };

            const URL = localhost + '/item-delete' ;

            $.post(URL, deleteData)
                .done(function(data) {
                    if (data.success !== 0) {
                        getBill(deleteData.id_order);
                    }
                })
                .fail(function(data) {
                    console.log('Error:', data);
                });

            $('.popup-print').fadeOut();
            $elm.remove();
            canClickDelete = true;
        }, 1000);
    }, 1000);

    // Activity logging
    function LogActivity(action, result) {
        $.ajax({
            url: localhost +  "/activity-log",
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                action: action,
                detail: result
            },
            success: function(result) {
                console.log('Activity logged:', result);
            },
            error: function(result) {
                console.log('Error logging activity:', result);
            }
        });
    }

    // Format currency input
    window.formatRupiah = function(input) {
        let nominal = input.value.replace(/\D/g, '');
        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        });

        input.value = nominal === '' ? '' : formatter.format(nominal);
        
        const isEmpty = input.value === "Rp0" || input.value.trim() === "" || input.value === "0";
        $('.btn-selesai').prop('disabled', isEmpty);
    };

    // Clear session
    function clearSession() {
        const URL = localhost + '/clear-session';
        $.get(URL)
            .done(function() {
                currentBillId = 0;
                resetAllForms();
                $viewDetail.empty();
                console.log('Session cleared');
            })
            .fail(function(result) {
                console.log('Error clearing session:', result);
            });
    }

    function resetAllForms() {
        $('.popup-name-bill input.nameBill').val('');
        $('.popup-name-bill .total-payment').text('');
        $('.act-btn-bill .act-btn.act2').attr('data-xid', '');
        $('.pop-payment .part-payment.active').removeClass('active').addClass('unactive');
        $('.content-payment .part-category.active').removeClass('active').addClass('unactive');
        $('.print-act-btn.split-bill').attr('data-xid', '');
        $('.popup-qty .cotent-detail').empty();
        $('.payment-nominal .nominal').attr('data-nominal', '');
        $('.payment-nominal .nm-payment').empty();
        $('.payment-nominal input.convert-cash').val('');
        $('.payment-nominal input.cash-nominal-input').val('');
        $('.custom-part input.nilai-custom').val('');
    }

    // Expose functions to global scope if needed
    window.POS = {
        clearSession,
        getBill,
        getSessionOrder,
        LogActivity
    };
});
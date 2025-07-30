const baseURL = 'http://192.168.89.122:8000';
// const Token = $('meta[name="csrf-token"]').attr('content');

$(()=>{
    $('.create-new').on('click', function(e){
        getFormCreate()
    })
     $('body').on('click', ".pop-up-01 .header-card .close-card",function () {
    
        setTimeout(() => {
            $('.pop-up-01').fadeOut()
            $('.body-card-01').empty();
        }, 400);
     });
      $(".pop-up-01").click(function(event){
        if (!$(event.target).closest('.card-01').length) {

            setTimeout(() => {
                $('.pop-up-01').fadeOut()
                $('.body-card-01').empty();
            }, 400);
        }
    });

    $('.open-qr').on('click', function(){
        let xid = $(this).attr('xid');
        detailQR(xid);
    })
})

function getFormCreate(){
   

    let URL = baseURL + '/create-Qr';
    $.ajax({
        type: 'json',
        url: URL,
        method: 'GET',
        success: function(response){
            let $popup = $('.pop-up-01');
            $popup.fadeIn();
            $popup.find('.body-card-01').empty().append(response);
            $popup.find('.card-01').css({'height': '200px', 'overflow': 'hidden'})

                  
        },
    }).fail(function(result){
        console.log(result);
    });
}

function detailQR(xid){

    let URL = baseURL + '/QR-download';
    $.ajax({
        type: 'json',
        url: URL,
        data:{
            xid: xid
        },
        method: 'GET',
        success: function(response){
            let $popup = $('.pop-up-01');
            $popup.fadeIn();
            $popup.find('.body-card-01').empty().append(response);
            $popup.find('.card-01 .txt-tittle').text('QR Code Download')

                  
        },
    }).fail(function(result){
        console.log(result);
    });
}

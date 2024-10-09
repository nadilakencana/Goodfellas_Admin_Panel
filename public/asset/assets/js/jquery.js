//const { result } = require("lodash");

 $('.tab-sdr[target-panel="panel3"]').on('click', function(){
           var $tgt = $(`.panel[data-panel='panel3']`);
           $tgt.find('.card-body').remove();
            getPayment();
        })


        // function getPayment(){
        //     let URL = "/payment";
        //     $.get(URL, function(result){
        //         $(result).appendTo(`.panel[data-panel='panel3']`);

        //     }).fail(function(result){
        //         console.log(result);
        //     })
        // }
        function getPayment(){
            let URL = "/payment";
            $.ajax({
                url: URL,
                method:'GET',
                success: function(result){
                     $(result).appendTo(`.panel[data-panel='panel3']`);

                }

            }).fail(function(result){
                console.log(result);
            })
        }

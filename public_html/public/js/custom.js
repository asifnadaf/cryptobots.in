$("document").ready(function () {

    $('.settings-btn').click(function () {
        $.ajax({
            url: 'buylimit',
            type: "post",
            data: {
                'id': $('input[name=id]').val(),
                'marketName': $('input[name=buyMarketName]').val(),
                'quantity': $('input[name=buyQuantity]').val(),
                'rate': $('input[name=buyRate]').val(),
                'totalCost': $('input[name=buyTotalCost]').val(),
                '_token': $('input[name=_token]').val()
            },
            success: function (data) {
                console.log(data),
                    location.href = ""
            }, error: function (e) {
                console.log('Error!', e);
            }
        })
        ;
    });


    $('.sell-btn').click(function () {
        $.ajax({
            url: 'selllimit',
            type: "post",
            data: {
                'id': $('input[name=id]').val(),
                'marketName': $('input[name=sellMarketName]').val(),
                'quantity': $('input[name=sellQuantity]').val(),
                'rate': $('input[name=sellRate]').val(),
                'totalCost': $('input[name=sellTotalCost]').val(),
                '_token': $('input[name=_token]').val()
            },
            success: function (data) {
                console.log(data),
                    location.href = ""
            }, error: function (e) {
                console.log('Error!', e);
            }
        });
    });

});
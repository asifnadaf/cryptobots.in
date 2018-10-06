$("document").ready(function(){


    $(".deleteTracker").click(function(){
        var id = $(this).attr('id');

        if(confirm('Do you really want to delete this marketpumps?')) {
            $.ajax({
                url: "marketpumps/delete/"+id, //Relative or absolute path to response.php file
                dataType: "json",
                success: function(data) {
                    if(data["status"]){
                        location.href = "trackers"
                    }
                }
            });    
        }
        

    });

    $(".deleteCard").click(function(){
        var id = $(this).attr('id');
        if(confirm('Do you really want to delete this card?')) {
            $.ajax({
                url: "card/delete/"+id, //Relative or absolute path to response.php file
                dataType: "json",
                success: function(data) {
                    if(data["status"]){
                        location.href = "cards"
                    }
                }
            });    
        }
        

    });


    $(".deleteBlacklistedVolume").click(function(){
        var id = $(this).attr('id');
        if(confirm('Do you really want to delete this data?')) {
            $.ajax({
                url: "volume/delete/"+id, //Relative or absolute path to response.php file
                dataType: "json",
                success: function(data) {
                    if(data["status"]){
                        location.href = "volume"
                    }
                }
            });
        }
    });


    $(".deleteBlacklistedLowVolume").click(function(){
        var id = $(this).attr('id');
        if(confirm('Do you really want to delete this data?')) {
            $.ajax({
                url: "lowvolume/delete/"+id, //Relative or absolute path to response.php file
                dataType: "json",
                success: function(data) {
                    if(data["status"]){
                        location.href = "lowvolume"
                    }
                }
            });
        }


    });


    $(".deleteBlacklistedPrice").click(function(){
        var id = $(this).attr('id');
        if(confirm('Do you really want to delete this data?')) {
            $.ajax({
                url: "price/delete/"+id, //Relative or absolute path to response.php file
                dataType: "json",
                success: function(data) {
                    if(data["status"]){
                        location.href = "price"
                    }
                }
            });
        }
    });

    $(".deleteWhitelist").click(function(){
        var id = $(this).attr('id');
        if(confirm('Do you really want to delete this data?')) {
            $.ajax({
                url: "whitelist/delete/"+id, //Relative or absolute path to response.php file
                dataType: "json",
                success: function(data) {
                    if(data["status"]){
                        location.href = "whitelist"
                    }
                }
            });
        }
    });


    $('.settings-btn').click(function(){
        $.ajax({
            url: 'buylimit',
            type: "post",
            data: {'id':$('input[name=id]').val(),'marketName':$('input[name=buyMarketName]').val(),'quantity':$('input[name=buyQuantity]').val(),'rate':$('input[name=buyRate]').val(),'totalCost':$('input[name=buyTotalCost]').val(),'_token': $('input[name=_token]').val()},
            success: function(data){
                location.href = ""
            }
        });
    });



    $('.sell-btn').click(function(){
        $.ajax({
            url: 'selllimit',
            type: "post",
            data: {'id':$('input[name=id]').val(),'marketName':$('input[name=sellMarketName]').val(),'quantity':$('input[name=sellQuantity]').val(),'rate':$('input[name=sellRate]').val(),'totalCost':$('input[name=sellTotalCost]').val(),'_token': $('input[name=_token]').val()},
            success: function(data){
                location.href = ""
            }
        });
    });



    $(".cancelOpenOrder").click(function(){
        var id = $(this).attr('id');
        var orderUuid = $(this).attr('orderUuid');
        if(confirm('Do you really want to cancel this order?')) {
            $.ajax({
                url: "cancelopenorder/"+id+"/"+orderUuid, //Relative or absolute path to response.php file
                dataType: "json",
                success: function(data) {
                    location.href = ""
                }
            });
        }
    });



    $(".deleteClient").click(function(){
        var id = $(this).attr('id');
        if(confirm('Do you really want to delete this data?')) {
             $.ajax({
                url: "list/delete/"+id, //Relative or absolute path to response.php file
                dataType: "json",
                success: function(data) {
                    if(data["status"]){
                        location.href = "list"
                    }
                }
            });
        }
    });



});
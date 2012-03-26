    //cross domain call in houses
    jQuery.getJSON("http://localhost/roommates/webservice/houses?callback=?", 
            function(data) {
                    console.log(data);
            });

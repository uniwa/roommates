$(document).ready( function(){

    var lat = $('#houseLatitude').val();
    var lng = $('#houseLongitude').val();

    $('#viewMap').gmap3(
        {   action: 'init',
            options: {
                zoom: 15
            }
        },
        {   action: 'addCircle',
            circle: {
                center: [lat, lng],
                radius: 300
            },
            map: {
                center: true
            }
        }
    );
});

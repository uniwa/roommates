$(document).ready( function(){
    var teiLocation = new google.maps.LatLng(38.004135, 23.676619);

    if( !houseLat || !houseLng )    return;

    houseAction = getHouseAction( displayCircle );
    teiMarker = {
        action: 'addMarker',
        latLng: teiLocation,
        options: {
            draggable: false
        }
    };

    $('#viewMap').gmap3(
        {   action: 'init',
            options: {
                zoom: 15
            }
        },
        houseAction,
        {
            action: 'addMarker',
            latLng: teiLocation,
            options: {
                draggable: false
            },
        }
    );

    function getHouseAction( displayCircle ) {

        if( displayCircle ) {

            return {
                action: 'addCircle',
                    circle: {
                        center: [houseLat, houseLng],
                        radius: 300,
                        strokeWeight: 1
                    },
                    map: {
                        center: true
                    },
                    options: {
                        draggable: false
                    }
                };
        } else {
            return {
                action: 'addMarker',
                latLng: [houseLat, houseLng],
                map: {
                    center: true
                },
                options: {
                    draggable: false
                }
            };
        }
    }
});

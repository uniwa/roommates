$(document).ready(function() {
    var geocoder = new google.maps.Geocoder();

    // initialize map zoom factor
    $('#editMap').gmap3(
        {   action: 'init',
            options: {
                zoom: 15
            }
        }
    );

    // check for latitude and longitude values returned from server
    mapLat = $('#HouseLatitude').val();
    mapLng = $('#HouseLongitude').val();

    // focus over TEI, if coordinates were not supplied
    if( mapLat == '' || mapLng == '' ) {

        mapLat = 38.004135;
        mapLng = 23.676619;
    }

    //
    repositionMarker( '#editMap', mapLat, mapLng );

    // refresh map when thus directed by the user
    //(?! what if they don't, though they have changed the address!!!)
    $('#updateMap').click(function(){

        query = getQueryAddress();

        geocoder.geocode({'address': query}, function(results, status){

            if( status == google.maps.GeocoderStatus.OK ) {

                latLng = results[0].geometry.location;
                lat = latLng.lat();
                lng = latLng.lng();
                $('#HouseLatitude').val( lat );
                $('#HouseLongitude').val( lng );
                repositionMarker( '#editMap', lat, lng );
            }
        });
    });

    // Returns the full address of the house so that it may be used with the
    // Google Maps API.
    function getQueryAddress() {

        country = "Ελλάς";
        address = $('#HouseAddress').val();
        postalCode = $('#HousePostalCode').val();

        municipality = "";
        if( $('#HouseMunicipalityId option:selected').val() > 0 ) {
            municipality = $('#HouseMunicipalityId option:selected').text();
        }

        query = country + ", "
            + municipality + ", "
            + address + ", "
            + postalCode;     
        return query;
    }

    // Clears current marker, and positions a new one under the supplied
    // coordinates.
    function repositionMarker(mapId, latitude, longitude) {

        $(mapId).gmap3(
            {   action: 'clear',
                name: 'marker'
            },
            {   action: 'addMarker',
                latLng: [latitude, longitude],
                map: {
                    center: true
                },
                marker: {
                    options: {
                        draggable: true
                    },
                    events: {
                        // when the marker is manually repositioned, the
                        // coordinates are copied into the corresponding hidden
                        // fields, and automatic address resolution is attempted
                        dragend: function(marker) {

                            $('#HouseLatitude').val(
                                marker.getPosition().lat());
                            $('#HouseLongitude').val(
                                marker.getPosition().lng());
                            geocoder.geocode(
                                {'latLng': marker.getPosition()},
                                resolveAddress
                            );
                        }
                    }
                }
            }
        );
    };

     // Updates [street_number], [municipality], [address] and [postal_code]
     // fields of the form, based on what was returned by the request.
    function resolveAddress(results, status) {
        var components;
        var streetNumber = "";

        if( status == google.maps.GeocoderStatus.OK ) {

            // get most detailed description
            components = results[0].address_components;

            $.map( components, function(n, i) {
                type = n.types[0];

                // street number is of highest priority - if it exists, it
                // precedes the address
                if( type == 'street_number'  ) {

                    // retain only single-number (not compound) indications
                    streetNumber = ' ' + n.long_name;
                    if( streetNumber.search( '-' ) > -1 )    streetNumber = '';
                } else if( type == 'route' ) {
                    
                    $('#HouseAddress').val( n.long_name + streetNumber );
                } else if( type == 'locality' ) {

                    // attempt identification and selection of municipality
                    selectMunicipality( n.long_name );
                } else if( type == 'postal_code' ) {

                    $('#HousePostalCode').val( n.long_name );
                }
            });
        }
    }

    function selectMunicipality(municipality) {

        isFound = false;
        $('#HouseMunicipalityId option').each( function(){

            municipality = purgeIntonation( municipality );

            if( matchesMaimed( municipality, $(this).text() ) > -1 ) {

                $('#HouseMunicipalityId').val( $(this).val() );
                isFound = true;
            }
        });

        if( !isFound ) {

            $('#HouseMunicipalityId').val( 0 );
        }
    }
});

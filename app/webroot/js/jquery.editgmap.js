$(document).ready(function() {
    if( !shouldProceed )    return;

    var geocoder = new google.maps.Geocoder();
    var teiLocation = new google.maps.LatLng(38.004135, 23.676619);

    // initialize map zoom factor and the event to move the marker on click
    $('#editMap').gmap3(
        {   action: 'init',
            options: {
                zoom: 15
            },
            events: {
                click: function(map, event) {
                    latLng = event.latLng;
                    repositionMarkers('#editMap', false, latLng);
                    updateFormFields(latLng);
                }
            }
        }
    );
    if( $('#eraseLatLng').length > 0 ){

        $('#eraseLatLng').click(function(e){

            e.preventDefault();
            repositionMarkers( '#editMap', true, teiLocation );
            $('#HouseLatitude').val(null);
            $('#HouseLongitude').val(null);

        });
    }

    // check for latitude and longitude values returned from server
    mapLat = $('#HouseLatitude').val();
    mapLng = $('#HouseLongitude').val();

    // focus over TEI, if coordinates were not supplied
    if( !mapLat || !mapLng ) {

        repositionMarkers('#editMap', true, teiLocation);
    } else {

        houseLoc = new google.maps.LatLng(mapLat, mapLng );
        repositionMarkers('#editMap', true, houseLoc);
    }

    // updates map when directed by the user
    $('#updateMap').click(function(e){

        e.preventDefault();
        query = getQueryAddress();

        // null means that no address field has been filled in
        if( !query )    return;

        geocoder.geocode({'address': query}, function(results, status){

            if( status == google.maps.GeocoderStatus.OK ) {

                latLng = results[0].geometry.location;
                $('#HouseLatitude').val( latLng.lat() );
                $('#HouseLongitude').val( latLng.lng() );

                repositionMarkers( '#editMap', true, latLng );
            }
        });
    });

    // Returns the full address of the house so that it may be used with the
    // Google Maps API.
    function getQueryAddress() {
        query = null;
        country = "Ελλάς";
        address = $('#HouseAddress').val();
        postalCode = $('#HousePostalCode').val();

        municipality = '';
        if( $('#HouseMunicipalityId option:selected').val() > 0 ) {
            municipality = $('#HouseMunicipalityId option:selected').text();
        }

        if( municipality != '' || postalCode != '' || address != ''  )  {
            query = country + ", " 
                + municipality + ", "
                + address + ", "
                + postalCode;
        }
        return query;
    }

    // Clears current marker and creates a new one under the specified
    // coordinates [loc]. The marker will be draggable as well as movable 
    // by means of point-and-click over the map. [map] represents the element
    // that contains the map. [doCenter] determines whether or not the map
    // should be centered around [loc]
    function repositionMarkers(map, doCenter, loc) {

        $(map).gmap3(
            {   action: 'clear',
                name: 'marker'
            },
            {   action: 'addMarker',
                latLng: loc,
                tag: 'house',
                marker: {
                    options: {
                        draggable: true
                    },
                    events: {
                        dragend: function(marker) {
                            updateFormFields(marker.getPosition());
                        }
                    }
                },
                map: {
                    center: doCenter
                }
            }
        );
    };

    // Updates the html form fields according to the coordinates specified by
    // [latLng]
    function updateFormFields(latLng) {
        $('#HouseLatitude').val(latLng.lat());
        $('#HouseLongitude').val(latLng.lng());
    }

    // Returns true if all required elements are defined.
    function shouldProceed(){

        should = $('#editMap').length && $('#updateMap').length
            && $('#HouseLatitude').length && $('#HouseLongitude').length
            && $('#HouseAddress').length && $('#HousePostalCode').length
            && $('#HouseMunicipalityId').length;

        return should;
    }
});

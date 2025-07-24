/**
 * @author Kwamelal
 * @copyright 2020 - present Spesuna Limited
 * @license http://www.gnu.org/copyleft/lesser.html
 * @appname Cedijob
*/

function initialize() {
    document.querySelector("#placesAutocomplete").parentElement.removeChild(document.querySelector("#placesAutocomplete"));
    var input = document.getElementById('create-new-user-account-item-location');
    autocomplete = new google.maps.places.Autocomplete(input);
    google.maps.event.addListener(autocomplete, 'place_changed', function(data) {
        Modules.togglePageLoader(true, true);
        let address = input.value;
        try{
            Modules.sendFetchData(__GLOBALS__.DOMAIN_API_URL, Modules.REQUEST_METHOD.POST, {
                task: "get_google_map_API_Key",
                data: {}
            }, (response) => {
                Modules.togglePageLoader(false);
                if (Modules.isValidJSON(response)) {
                    if (response.status == Modules.status.OKAY) {
                        let key = response.data;
                        Modules.sendFetchData("https://maps.googleapis.com/maps/api/geocode/json?address=" + address + "&key=" + key, Modules.REQUEST_METHOD.REQUEST, {
                            task: null,
                            data: null,
                        }, (response) => {
                            let len = response.results.length;
                            for (let i = 0; i < len; i++) {
                                try{
                                    let place = autocomplete.getPlace();
                                    let components = response.results[i].address_components;
                                    let filtered_array = place.address_components.filter(function (address_component) { return address_component.types.includes("country"); });
                                    
                                    $("#user-map-view-location-lat").val(place.geometry.location.lat());
                                    $("#user-map-view-location-lng").val(place.geometry.location.lng());
                                    $("#user-map-view-location-name").val(place.formatted_address);
                                    $("#user-map-view-location-country").val((filtered_array.length ? filtered_array[0].long_name : ""));
                                    $("#user-map-view-location-country-code").val((filtered_array.length ? filtered_array[0].short_name : ""));
                                    components.map(function (component, index) {
                                        if(Modules.inArray(component.types, "sublocality_level_1")){
                                            $("#user-map-view-location-sublocality-level-1-long-name").val(component.long_name);
                                            $("#user-map-view-location-sublocality-level-1-short-name").val(component.short_name);
                                        }
        
                                        if(component.types[0] == "neighborhood" && component.types[1] == "political"){
                                            $("#user-map-view-location-neighborhood-long-name").val(component.long_name);
                                            $("#user-map-view-location-neighborhood-short-name").val(component.short_name);
                                        }
        
                                        if (component.types[0] == "administrative_area_level_1") {
                                            $("#user-map-view-location-region").val(component.long_name);
                                        }
                                    });
                                }
                                catch(err){
                                    console.log(err);
                                    Modules.toggleToastContainer({
                                        message: "An unknown error occurred. Please refresh your page and try again.",
                                        status: Modules.status.FAILED
                                    });
                                }
                            }
                        });
                        return;
                    }
                    Modules.toggleToastContainer({
                        message: "An unknown error occurred. Please refresh your page and try again.",
                        status: Modules.status.FAILED
                    });
                }
                else {
                    Modules.toggleToastContainer({
                        message: "An unknown error occurred. Please refresh your page and try again.",
                        status: Modules.status.FAILED
                    });
                }
            });
        }
        catch(err){
            Modules.toggleToastContainer({
                message: "An unknown error occurred. Please refresh your page and try again.",
                status: Modules.status.FAILED
            });
        }
    });
}

google.maps.event.addDomListener(window, 'load', initialize);
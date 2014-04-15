// global variable for map and overlays
var overlays = [];
var infoWindows = [];
var map;
var geocoder; 

/**
 * document ready function, will be called when document is ready
 */ 
$(document).ready(function() {
	var myLatlng = new google.maps.LatLng(51.079529, -114.132446);
    var myOptions = {
          zoom: 9,
          center: myLatlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          mapTypeControl: true
  };
  
  map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);
  
  geocoder = new google.maps.Geocoder();

});// end ready

// Auto-select text in form when clicked
$(function(){
   $(document).on('click','input[type=text]',function(){ this.select(); });
});

// Function runs after "Go!" button is clicked
function goButtonClicked(myForm){
    clearInfoWindows();
    clearOverlays();
    codeAddress(myForm);
}

// GEOCODE the address in the search field
function codeAddress(form) {
    geocoder.geocode({ 'address': form.searchAddress.value }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            if (form.radius.value == '1') {
                map.setZoom(14);
            } else if (form.radius.value == '5'){
                map.setZoom(12);
            } else {
                map.setZoom(11);
            }
            var pinColor = "0AF234";
            var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
                new google.maps.Size(21, 34),
                new google.maps.Point(0, 0),
                new google.maps.Point(10, 34));
            var marker = new google.maps.Marker({
                position: results[0].geometry.location,
                icon: pinImage,
                infoWindowIndex: 0
            });
            google.maps.event.addListener(marker, 'click',
            function (event) {
                closeAllInfoWindows();
                infoWindows[this.infoWindowIndex].open(map, this);
            }
            );
            var infoWindow = new google.maps.InfoWindow({
                content: "You are here"
            });
            infoWindows.push(infoWindow);
            addOverlay(marker);
            doGetRequest(results[0].geometry.location.lat(), results[0].geometry.location.lng(), form);
            //alert(results[0].geometry.location.lat() + "," + results[0].geometry.location.lng())
        } else {
            alert("Geocode was not successful for the following reason: " + status);
        }
    });
}

// Make request to "server"
function doGetRequest(latitude, longitude, myForm){
    $.ajax({
        type: "get",
        url: "makeDbQuery.php",
        data: { "lat": latitude, "long": longitude, "radius": myForm.radius.value, "rating": myForm.rating.value, "price": myForm.price.value },
        success: function (data) {
            dataJSON = JSON.parse(data);
            console.log(dataJSON); // do something with data
            //console.log(data);
            drawRestaurants(dataJSON);
        }
    });
}

// Draw restaurant markers
function drawRestaurants(data){
    data.forEach(function (entry, index, array) {
        var location = new google.maps.LatLng(entry[0], entry[1]);
        var marker = new google.maps.Marker({
            position: location,
            infoWindowIndex: index + 1
        });
        google.maps.event.addListener(marker, 'click',
            function (event) {
                closeAllInfoWindows();
                infoWindows[this.infoWindowIndex].open(map, this);
            }
        );
        addOverlay(marker);
        addInfo(entry);
    });
}

// Add info for info window
function addInfo(restaurant){
    var infoText = '<h4>' + restaurant[2] + '</h4><br><b>Rating: </b>' + restaurant[3] + '%<br><b>Price:</b> ' + restaurant[4] + 
                    '<br><b>Address:</b> ' + restaurant[6] + ' ' + restaurant[7] + ' ' + restaurant[8] + ' ' + restaurant[9];
    var infoWindow = new google.maps.InfoWindow({
            content : infoText
        });
    infoWindows.push( infoWindow );
}

// Clear info windows
function clearInfoWindows(){
    infoWindows = [];
}

function closeAllInfoWindows(){
    infoWindows.forEach(function (entry, index, array) {
        entry.close();
    });
}

// Add overlay to map
function addOverlay(overlay){
  if( overlay ){
    overlay.setMap(map);
    overlays.push( overlay );
  }
}

// Clear all overlays in map
function clearOverlays(){
    while(overlays[0]){
      overlays.pop().setMap(null);
    }
}

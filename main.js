      // global variable for map and overlays
      var overlays = [];
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
    
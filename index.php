<html>
</head>
<title> Latest Earthquake data </title>
 <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
	
  <script src="http://maps.google.com/maps/api/js?v=3&sensor=false" type="text/javascript"></script>
  <script type="text/javascript" src="js/sweet-alert.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/sweet-alert.css">

  <script>
	var map;
	var currentPopup;
	var markers= [];
	function initialize() {
  var mapOptions = {
    zoom: 8,
    center: new google.maps.LatLng(27.6431649,85.3277502)
  };
  	map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);
 google.maps.event.addListenerOnce(map, 'tilesloaded', initializeDataPoints);

	}

function addMarker( lat, lng, route, name, time) {

  var pt = new google.maps.LatLng(lat, lng); 
  
  var marker = new google.maps.Marker({
    position: pt,
    map: map
  });

  var content = "<b>"+name + "</b>" + "</br>" + time;
  addInfoWindow(marker,content);
  markers.push(marker);


}


 function addInfoWindow(marker, message) {

  var infoWindow = new google.maps.InfoWindow({
    content: "<b> "+ message["mag"] + " Richter </b></br>" 
    		 + "<i>" + message["place"]	+ "</i></br>" 
    		 + " Depth : " + message["depth"] + "km </br>" 	
    		 + "Time : " + message["time"] 
  });

  google.maps.event.addListener(marker, 'click', function () {
   if (currentPopup != null) {
    currentPopup.close();
    currentPopup = null;
  }   

  infoWindow.open(map, marker);
  currentPopup = infoWindow;
});
  google.maps.event.addListener(infoWindow, "closeclick", function() {
    currentPopup = null;
  });

  

}

function initializeDataPoints (limit) {

	setInterval( function() {
    
var data =  "limit="+20; // first get Routes ploted
  var request=null; 
  if (request) {
   request.abort();
 }
 request = $.ajax({
  url: "getData.php",
  type: "post",
  data: data
});
        // Callback handler that will be called on success
        request.done(function (response, textStatus, jqXHR){
          var res = eval('(' + response + ')') ;
          clearMarkers();
          for(var i=0;i<res.length;i++)
          {
            var lat = res[i]["lat"];
            var lng = res[i]["lng"];
            
            var pt = new google.maps.LatLng(lat, lng); 
            

            var marker = new google.maps.Marker({
              position: pt,
              map: map,
              animation: google.maps.Animation.DROP
            });
            
            addInfoWindow(marker,res[i]);
            
            if(res[i]["info"]!="end"){
            	swal("Earthquake Notification", res[i]["info"], "success");
            }
            
          }
        });
}, 1*60*1000); //5 minutes
}


  // Removes the markers from the map, but keeps them in the array.
  function clearMarkers() {
   setAllMap(null);
 }
 

      // Sets the map on all markers in the array.
      function setAllMap(map) {
       for (var i = 0; i < markers.length; i++) {
         markers[i].setMap(map);
       }
     }
     
         // Shows any markers currently in the array.
         function showMarkers() {
           setAllMap(map);
         }
         
 // Deletes all markers in the array by removing references to them.
 function deleteMarkers() {
   clearMarkers();
   markers = [];
 }

google.maps.event.addDomListener(window, 'load', initialize);
</script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> 
</head>



 <body>
    <div id="map-canvas"></div>
 </body>








</html>
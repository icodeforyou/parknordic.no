<!-- 5 -->
<div class="row">
  <div class="span8">
    <h1>Hvor er vi?</h1>
    <?php if(isset($_GET['recieved'])) { ?>
      <div class="alert alert-success">
        Takk for din søknad, vil vi behandle den så snart som mulig
      </div>
    <?php } ?>
    <div id="map_canvas" style="width:600px;height:400px"></div>
  <!--  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script> -->
    <!-- AIzaSyCnxx8GSwo9wTevNlsWOM2mvlTkXMc3I38 -->
      <script>
        function initialize() {
          var mapOptions = {
            zoom: <?php echo isset($geopos) ? 10 : 7; ?>,
            center: new google.maps.LatLng(<?php echo isset($geopos) ? "{$geopos->Lat}, {$geopos->Lng}" : "59.939858, 10.823685"; ?>),
            mapTypeId: google.maps.MapTypeId.ROADMAP
          }
          var map = new google.maps.Map(document.getElementById('map_canvas'),mapOptions);
         // https://www.google.com/maps?q=norway&hl=sv&ll=59.189999,8.31665&spn=2.923742,7.218018&sll=37.0625,-95.677068&sspn=70.352627,115.488281&t=h&hnear=Norge&z=8
          setMarkers(map, lots);

					<?php if(isset($placemarker) && $placemarker === true && isset($geopos)) { ?>
					//setMarkers(map, [["Adressen du søker etter, er her", <?php echo $geopos->Lat; ?>, <?php echo $geopos->Lng; ?>, 0]]);
						var mySearchLatLng = new google.maps.LatLng(<?php echo $geopos->Lat; ?>, <?php echo $geopos->Lng; ?>);
						var marker = new google.maps.Marker({
							position: mySearchLatLng,
							map: map,
					//		icon: image,
							title : "Adressen du søker etter, er her"
						});

						var searchinfowindow = new google.maps.InfoWindow({
							maxWidth : 300
						});
						searchinfowindow.setContent("Adressen du søker etter, er her");
						searchinfowindow.open(map, marker);



					<?php } ?>
        }

       /**
         * Data for the markers consisting of a name, a LatLng and a zIndex for
         * the order in which these markers should display on top of each
         * other.
         */
        var lots = [<?php 
            if(isset($lots) && $lots !== false) {
              foreach ($lots as $index => $lot) {

                $markup = "<h4>".$lot->Address."</h4>";
                $markup .= "<br>".$lot->Description;
                $markup .= (bool)$lot->SupportLongtime === true ? "<br><a href=\"#\" onclick=\"apply_for_rent(".$lot->ID."); return false;\">Søk om langtidsparkering</a>" : "";
                
                $markup = preg_replace('~\R~u', "", $markup);

                $lots_parsed[] = "['".addslashes($markup)."', ".$lot->Lat.",".$lot->Lng.", ".($index+1)."]\n";
              }
              echo implode(",", $lots_parsed);
         
          /*
          ['Brobekkveien 31', 59.917432,10.751949, 1],
          ['Marieboes gt. 16', 59.939858, 10.823685, 2],
          ['Kirkeveien 1', 59.720407,10.838013, 3]
          */
          } ?>];

        function setMarkers(map, locations) {
          // Add markers to the map

          // Marker sizes are expressed as a Size of X,Y
          // where the origin of the image (0,0) is located
          // in the top left of the image.

          // Origins, anchor positions and coordinates of the marker
          // increase in the X direction to the right and in
          // the Y direction down.
          var image = new google.maps.MarkerImage('/img/icon_20x25_parknordic.png',
              // This marker is 20 pixels wide by 32 pixels tall.
              new google.maps.Size(20, 32),
              // The origin for this image is 0,0.
              new google.maps.Point(0,0),
              // The anchor for this image is the base of the flagpole at 0,32.
              new google.maps.Point(0, 32));
        /*
          var shadow = new google.maps.MarkerImage('images/beachflag_shadow.png',
              // The shadow image is larger in the horizontal dimension
              // while the position and offset are the same as for the main image.
              new google.maps.Size(37, 32),
              new google.maps.Point(0,0),
              new google.maps.Point(0, 32));
          */
              // Shapes define the clickable region of the icon.
              // The type defines an HTML &lt;area&gt; element 'poly' which
              // traces out a polygon as a series of X,Y points. The final
              // coordinate closes the poly by connecting to the first
              // coordinate.
          var shape = {
              coord: [1, 1, 1, 20, 18, 20, 18 , 1],
              type: 'poly'
          };
          for (var i = 0; i < locations.length; i++) {
            var lot = locations[i];
            var myLatLng = new google.maps.LatLng(lot[1], lot[2]);
            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
           //     shadow: shadow,
                icon: image,
                shape: shape,
              //  title: lot[0],
                zIndex: lot[3]
            });

            /*
            // Add listener for a click on the pin
            google.maps.event.addListener(marker, 'click', function() {
              infowindow.setContent(lots[i][0]);
              infowindow.open(map, marker);
            });

            // Add information window
            var infowindow = new google.maps.InfoWindow({
              content:  createInfo('Evoluted New Media', 'Ground Floor,35 Lambert Street,Sheffield,South Yorkshire,S3 7BH <a title="Click to view our website" href="http://www.evoluted.net">Our Website</a>')
            });
            */
           
          var infowindow = new google.maps.InfoWindow({
            maxWidth : 300
          });
          var marker, i;

          google.maps.event.addListener(marker, 'click', (function(marker, i) {
       //     console.log(lots[i]);
              return function() {
                infowindow.setContent(lots[i][0]);
                infowindow.open(map, marker);
              }
            })(marker, i));
          }
        }


      </script>
  </div>
  <div id="lots" class="span4">
    <?php $PN->getCities(); ?>

  </div>
</div>

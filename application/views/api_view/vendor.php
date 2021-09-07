 <!-- <!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Page Title</title>
</head>
<body>
<video id="video" width="640" height="480" autoplay></video>
<button id="snap">Snap Photo</button>
<canvas id="canvas" width="300" height="300"></canvas>
<p id="base64_img"></p>
<img id="imgElem"></img>
</body>
</html> -->
<html>
  <head>
    <title>Geolocation</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div */
      
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      .pagewraper { margin: 20px 0; }
      /* element that contains the map. */
      #map { height: 100%; min-height: 500px;}
      .vendorinfo-wrap ul {margin:0; padding:0;}
      .vendorinfo-wrap ul li {list-style: none; padding: 15px; background: #fbfbfb; margin: 0 0 15px;}
      .vendorinfo-wrap ul li h5 { margin-top: 0; }
    </style>
    <script type="text/javascript">
      var base_url = '<?php echo base_url();?>';
    </script>
    <link rel="stylesheet" type="text/css" href="<?php base_url('assets/css/bootstrap.min.css')?>">
    <link rel="stylesheet" type="text/css" href="<?php base_url('assets/css/dashboard_styles.css')?>">
  </head>
  <body>
    <video id="video" width="640" height="480" autoplay>
      
    </video>
    <button id="snap">Snap Photo</button>
    <canvas id="canvas" width="300" height="300"></canvas>
    <p id="base64_img"></p>
    <img id="imgElem"></img>





    <div class="pagewraper">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
             <h5>
                Dear Vendor, Your Case number is : RSA<?php echo $qun_ans_data['id'] ; ?>.
              </h5>
          </div>
        </div>
        <div class="row">
          <div class="col-md-5 mapwrap p-o">
            <div id="map"></div>
          </div>
          <input type="hidden" name="cust_lat_long" id="cust_lat_long" value="<?php echo $qun_ans_data['vendor_lat'];?>">
          <div class="col-md-7 vendorinfo-wrap">
            <ul>  
              <?php 

              if(!empty($policy_data)){ ?>
                <li>                
                  <div class="row">
                    <div class="col-md-1">1.</div>
                    <div class="col-md-6">
                      <h5><?php echo strtoupper($policy_data['fname'].' '.$policy_data['lname']); ?></h5>
                      <span><strong>Phone: </strong> <?php echo $policy_data['mobile_no']; ?></span><br>
                    </div>
                    <div class="col-md-2 text-right">
                      <strong><span id="distance_<?php echo $j;?>"></span></strong><br>
                      <strong><span id="duration_<?php echo $j;?>"></span></strong>
                    </div>
                    <div class="col-md-3 text-right" style="display: none;">
                      <button _ngcontent-c4="" class="btn btn-primary btn-xs">
                        <span>Assign</span>
                      </button>
                    </div>
                  </div>
                </li>
             <?php 
           }
              ?>

            </ul>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <!-- <p>Dummy Text</p> -->
          </div>
        </div>
      </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

  <script type="text/javascript">
      var video = document.getElementById('video');

      // Get access to the camera!
      if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
          // Not adding `{ audio: true }` since we only want video now
          navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
              //video.src = window.URL.createObjectURL(stream);
              video.srcObject = stream;
              video.play();
          });
      }

      var canvas = document.getElementById('canvas');
      var context = canvas.getContext('2d');
      var video = document.getElementById('video');

      // Trigger photo take
      document.getElementById("snap").addEventListener("click", function() {
        context.drawImage(video, 0, 0, 200, 200);
        var image = document.getElementById("canvas").toDataURL("image/png");
          image = image.replace('data:image/png;base64,', '');
        document.getElementById("base64_img").innerHTML = image; 
        imgElem.setAttribute('src', "data:image/jpg;base64," + image);
        $.ajax({
          'url': base_url +'Admin/updateDealerImage',
          data: {image:image,task_id:}
        });
      });
</script>







    <script>

      var map, infoWindow;
      var venode_lat = '<?php echo $qun_ans_data['vendor_lat'];?>';
      var venode_long = '<?php echo $qun_ans_data['vendor_long'];?>';

      var cust_lat = '<?php echo $qun_ans_data['lat'];?>';
      var cust_long = '<?php echo $qun_ans_data['longi'];?>';
     
      function initMap() {
        var bounds = new google.maps.LatLngBounds;
        infoWindow = new google.maps.InfoWindow;
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            latsource = position.coords.latitude;
            langsource = position.coords.longitude;
            var task_id = '<?php echo $qun_ans_data['id'] ?>';
            var table_lat_long = $('#cust_lat_long').val();
            if(table_lat_long ==''){
              $.ajax({
                  url: base_url+'Admin/submitVendorLatLong',
                  data : { latsource : latsource,langsource: langsource ,task_id : task_id },
                  dataType: 'Json',
                  type: 'POST',
                  success: function(response){
                        location.reload();
                  }
              })
            }
            
            lat = parseFloat(venode_lat);
            long = parseFloat(venode_long);

            var markersArray = [];
            var destinations_array = [];
            // map = new google.maps.Map(document.getElementById('map'), {
            //   center: {lat: latsource, lng: langsource},
            //   zoom: 6
            // });
            
              var origin1 = {lat: lat, lng: long};
              var destinationA = {lat: parseFloat(cust_lat), lng: parseFloat(cust_long)};
              destinations_array = [destinationA];
            var destinationIcon = 'https://chart.googleapis.com/chart?' +
            'chst=d_map_pin_letter&chld=W|FF0000|000000';
        var originIcon = 'https://chart.googleapis.com/chart?' +
            'chst=d_map_pin_letter&chld=C|FFFF00|000000';
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: latsource, lng: langsource},
          zoom: 10
        });
        var geocoder = new google.maps.Geocoder;

        var table_lat_long = $('#cust_lat_long').val();

        if(table_lat_long !=''){

           
        var service = new google.maps.DistanceMatrixService;
        service.getDistanceMatrix({
          origins: [origin1],
          destinations: destinations_array,
          travelMode: 'DRIVING',
          unitSystem: google.maps.UnitSystem.METRIC,
          avoidHighways: false,
          avoidTolls: false
        }, function(response, status) {
          if (status !== 'OK') {
            alert('Error was: ' + status);
          } else {
            var originList = response.originAddresses;
            var destinationList = response.destinationAddresses;
            //var outputDiv = document.getElementById('output');
            //outputDiv.innerHTML = '';

            deleteMarkers(markersArray);

            var showGeocodedAddressOnMap = function(asDestination) {
              var icon = asDestination ? destinationIcon : originIcon;
              return function(results, status) {
                if (status === 'OK') {
                  map.fitBounds(bounds.extend(results[0].geometry.location));
                  markersArray.push(new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    icon: icon
                  }));
                } else {
                  alert('Geocode was not successful due to: ' + status);
                }
              };
            };

            for (var i = 0; i < originList.length; i++) {
              var results = response.rows[i].elements;
              geocoder.geocode({'address': originList[i]},
                  showGeocodedAddressOnMap(false));
              for (var j = 0; j < results.length; j++) {
                geocoder.geocode({'address': destinationList[j]},
                 showGeocodedAddressOnMap(true));
                 document.getElementById("distance_"+j).innerHTML = 'Distance: '+results[j].distance.text;
                 document.getElementById("duration_"+j).innerHTML = 'Duration: '+results[j].duration.text;
                // outputDiv.innerHTML += originList[i] + ' to ' + destinationList[j] +
                //     ': ' + results[j].distance.text + ' in ' +
                //     results[j].duration.text + '<br>';
              }
            }
          }
        });
      }



              // var myMarker = new google.maps.Marker({
              //     position: (lat: latsource, lng: langsource),
              //     draggable: true
              // });
          }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
          });

            

        } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
        }
      }
       function deleteMarkers(markersArray) {
        for (var i = 0; i < markersArray.length; i++) {
          markersArray[i].setMap(null);
        }
        markersArray = [];
      }

      function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');
        infoWindow.open(map);
      }
    </script>
    
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBCYcXRkCyzUnrNj9oxGA8-Yh__ApdzErY&callback=initMap">
    </script>
  </body>
</html>
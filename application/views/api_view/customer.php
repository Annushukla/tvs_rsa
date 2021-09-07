<?php 
   $ansers = array();
   $answers = '';
   //echo '<pre>'; print_r($qun_ans_data);die('here');
   // if(!empty($qun_ans_data)){
   // $ansers = json_decode($qun_ans_data['answer_id']);
   // foreach ($ansers as $key => $value) {
   //   $answers = $answers. ' '.$value->answer;
   
   // }
   // }else{
   //  $answers = '';
   // }
   ?>
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
    <div class="pagewraper">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
             <h5>
                Dear Customer, Your Case number : RSA<?php echo $qun_ans_data['id'] ; ?> is sucessfully Registered.
              </h5>
          </div>
        </div>
        <div class="row">
          <div class="col-md-5 mapwrap p-o">
            <div id="map"></div>
          </div>
          <input type="hidden" name="workshop_assignation_status" id="workshop_assignation_status" value="<?php echo $all_party_details['workshop_asigned_status']?>">
          <input type="hidden" name="cust_lat_long" id="cust_lat_long" value="<?php echo $all_party_details['result']['customer_location']['lat'];?>">
          <div class="col-md-7 vendorinfo-wrap">
            <ul>  
              <?php 

              if(!empty($all_party_details)){

                $i = 1;
                $j = 0;
                $workshop_location = $all_party_details['result']['workshop_location'][0];
              // foreach ($all_party_details['result']['workshop_location'] as $key => $workshop_location) { ?>

              <!-- <input type="hidden" name="workshop_<?php echo $i;?>" id="workshop_<?php echo $i;?>" data-lat = "<?php echo $i;?>"> -->

                <li>                
                  <div class="row">
                    <div class="col-md-1"><?php echo $i; ?>.</div>
                    <div class="col-md-6">
                      <h5><?php echo strtoupper($workshop_location['dealer_name']); ?></h5>
                      <span><strong>Phone: </strong> <?php echo $workshop_location['mobile']; ?></span><br>
                      <span><strong>Email: </strong><?php echo $workshop_location['email']; ?></span><br>
                      <span><strong>Add.: </strong> <?php echo $workshop_location['add1'].' '.$workshop_location['add2']; ?></span><br>
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
             <?php $i++; $j++;
             //}
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
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
    <script>

      var map, infoWindow;
      var lat = '<?php echo $all_party_details['result']['customer_location']['lat'];?>';
      var long = '<?php echo $all_party_details['result']['customer_location']['longi'];?>';
      var workshop_assignation_status = $("#workshop_assignation_status").val();
      if(workshop_assignation_status == true){
            var workshop_one_lat = '<?php echo $all_party_details['result']['workshop_location'][0]['lat'];?>'
            var workshop_one_long = '<?php echo $all_party_details['result']['workshop_location'][0]['longi'];?>'
      }else{
             var workshop_one_lat = '<?php echo $all_party_details['result']['workshop_location'][1]['lat'];?>'
            var workshop_one_long = '<?php echo $all_party_details['result']['workshop_location'][1]['longi'];?>'

             var workshop_two_lat = '<?php echo $all_party_details['result']['workshop_location'][2]['lat'];?>'
            var workshop_two_long = '<?php echo $all_party_details['result']['workshop_location'][2]['longi'];?>'

             var workshop_three_lat = '<?php echo $all_party_details['result']['workshop_location'][3]['lat'];?>'
            var workshop_three_long = '<?php echo $all_party_details['result']['workshop_location'][3]['longi'];?>'

             var workshop_four_lat = '<?php echo $all_party_details['result']['workshop_location'][4]['lat'];?>'
            var workshop_four_long = '<?php echo $all_party_details['result']['workshop_location'][4]['longi'];?>'

             var workshop_five_lat = '<?php echo $all_party_details['result']['workshop_location'][5]['lat'];?>'
            var workshop_five_long = '<?php echo $all_party_details['result']['workshop_location'][5]['longi'];?>'
      }




      function initMap() {
        var bounds = new google.maps.LatLngBounds;
        // console.log(lat+'--'+long);
        infoWindow = new google.maps.InfoWindow;
        if (navigator.geolocation) {
          //console.log(navigator.geolocation);
          navigator.geolocation.getCurrentPosition(function(position) {
           // alert('latsource'+'langsource');
           // alert('latsource'+'langsource');
            latsource = position.coords.latitude;
            langsource = position.coords.longitude;
            var task_id = '<?php echo $qun_ans_data['id'] ?>';
            var table_lat_long = $('#cust_lat_long').val();
            if(table_lat_long ==''){
              $.ajax({
                  url: base_url+'Admin/submitCustomerLatLong',
                  data : { latsource : latsource,langsource: langsource ,task_id : task_id },
                  dataType: 'Json',
                  type: 'POST',
                  success: function(response){
                        location.reload();
                  }
              })
            }
            
            lat = parseFloat(lat);
            long = parseFloat(long);

            var markersArray = [];
            var destinations_array = [];
            // map = new google.maps.Map(document.getElementById('map'), {
            //   center: {lat: latsource, lng: langsource},
            //   zoom: 6
            // });
            
              var origin1 = {lat: lat, lng: long};
              var destinationA = {lat: parseFloat(workshop_one_lat), lng: parseFloat(workshop_one_long)};
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
              console.log(results);
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
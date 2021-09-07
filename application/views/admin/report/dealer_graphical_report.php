<?php
// die('hello moto');
        $kotak_array=array();
         // echo '<pre>';print_r($kotak_data);//die('hello');
        foreach ($kotak_data as $key => $value) {
          $strtotime = strtotime($key).'000';
        array_push($kotak_array,array("x" => (float)$strtotime, "y" => (int)$value));
          }
        $dataPoints1 = $kotak_array;
        $il_array=array();
        foreach ($il_data as $key => $value) {
        array_push($il_array,array("x" => (float)(strtotime($key).'000'), "y" => (int)$value));
          }
        $dataPoints2 = $il_array;
        $tata_array=array();
        foreach ($tata_data as $key => $value) {
        array_push($tata_array,array("x" => (float)(strtotime($key).'000'), "y" => (int)$value));
          }
        $dataPoints3 = $tata_array;
        $bagi_array=array();
        foreach ($bagi_data as $key => $value) {
        array_push($bagi_array,array("x" => (float)(strtotime($key).'000'), "y" => (int)$value));
          }
        $dataPoints4 = $bagi_array;
?>
<script>
window.onload = function () {
var chart = new CanvasJS.Chart("chartContainer", {
  animationEnabled: true,
  title:{
    text: ""
  },
  subtitles: [{
    text: " ",
    fontSize: 18
  }],
  axisX:{
     //   interval: 1,
       // intervalType: "month"
 },
  axisY: {
    includeZero: false,
    prefix: ""
  },
  legend:{
    cursor: "pointer",
    itemclick: toggleDataSeries
  },
  toolTip: {
    shared: true
  },
  data: [
  {
    type: "area",
    name: "KOTAK",
    showInLegend: "true",
    xValueType: "dateTime",
   // xValueFormatString: "MMM YYYY",
   // yValueFormatString: "##0.##",
    dataPoints: <?php echo json_encode($dataPoints1); ?>
  },
  {
    type: "area",
    name: "ICICI L",
    showInLegend: "true",
    xValueType: "dateTime",
   // xValueFormatString: "MMM YYYY",
   // yValueFormatString: "₹#,##0.##",
    dataPoints: <?php echo json_encode($dataPoints2); ?>
  },
  {
    type: "area",
    name: "TATA",
    showInLegend: "true",
    xValueType: "dateTime",
   // xValueFormatString: "MMM YYYY",
    //yValueFormatString: "₹#,##0.##",
    dataPoints: <?php echo json_encode($dataPoints3); ?>
  },
  {
    type: "area",
    name: "BAGI",
    showInLegend: "true",
    xValueType: "dateTime",
    //xValueFormatString: "MMM YYYY",
    //yValueFormatString: "₹#,##0.##",
    dataPoints: <?php echo json_encode($dataPoints4); ?>
  }
  ]
});
 
chart.render();
 
function toggleDataSeries(e){
  if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
    e.dataSeries.visible = false;
  }
  else{
    e.dataSeries.visible = true;
  }
  chart.render();
}
 
}
</script>
<div class="wrapper">
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">     
<div class="row">
      <div class="col-lg-12">
      <div class="example-col">
        <h3>Dealer Policy Details</h3>
        <p>Dealer Code:<?php echo '<b>'.$dealer_data['dealer_code'].'</b>';?></p>
        <p>Dealer Name:<?php echo '<b>'.$dealer_data['dealer_name'].'</b>';?></p>
        <br/>
        <form name="policy_filter" id="policy_filter" method="post" action="">

         <div class="row form-group">
                <div class="col-md-2">
                    <label>Start Date :</label>
                    <input type="date" class="form-control"  name="start_date" id="start_date" placeholder="Start Date" value="<?php echo $_POST['start_date'];?>" min="" max="" required="required">
                </div>
                <div class="col-md-2">
                    <label>End Date :</label>
                    <input type="date" class="form-control" name="end_date" id="end_date" placeholder="End Date" value="<?php echo $_POST['end_date'];?>" min="" max="" required="required">
                </div>
                <div class="col-md-2">
                    <label> &nbsp;</label>
                    <input type="submit" class="form-control btn btn-primary" name="submit" id="filter_by_date" value="Submit"/>
                </div>
                <div class="col-md-2">
                    <label> &nbsp;</label>
                   <input type="button" class="form-control btn btn-primary" name="reset_button" onclick="myFunction()" id="filter_by_date" value="Reset"/>
                </div>
            </div>
          </form>
            <br/>
          <div id="chartContainer" style="height: 370px; width: 100%;"></div>
          <script type="text/javascript" src="<?php echo base_url();?>assets/js/canvasjs.min.js"></script>
          <!--<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>-->
      </div>
</div>
      <div class="col-md-6 pull-right" id="image_loader">
        <img src="<?php echo base_url();?>assets/images/loading5.gif" height="100" width="">
      </div>
    <br><br>
</div>    
</section>
  </div>
 <div class="control-sidebar-bg"></div>
</div>
<script type="text/javascript">
  $("#image_loader").css("display","none");
 $(document).ajaxStart(function() {
        // show loader on start
        $("#image_loader").css("display","block");
    }).ajaxSuccess(function() {
        // hide image_loader on success
        $("#image_loader").css("display","none");
    }); 
function myFunction() {
      document.getElementById("policy_filter").reset();
      window.location.href = window.location.href;
}
</script>
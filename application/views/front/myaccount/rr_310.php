<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width initial-scale=1.0 maximum-scale=1.0 user-scalable=yes" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" href="<?php echo base_url('assets/img/favicon.ico');?>">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
  <link rel="stylesheet" href="<?php echo base_url('assets/css/rr_310_style.css');?>">
  <title>Welcome</title>
</head>
<body>
  <header>
    <nav id="navbar">
      <div class="container-fluid header-container">
        <div class="logo"><img src="<?php echo base_url('assets/img/rr-301-logo.png');?>" alt=""></div>
         <div ><p id="message" style="color: red;text-align: center;"><?php echo $this->session->flashdata('message')?></p></div>
        <div class="right-logo"><img src="<?php echo base_url('assets/img/tvs-racing-logo.png');?>" alt=""></div>
      </div>
    </nav>
  </header>
    <div id="showcase">
      <div class="container-fluid">
        <img class="showcase-desktop" src="<?php echo base_url('assets/img/showcase.jpg');?>" alt="">
        <img class="showcase-mob" src="<?php echo base_url('assets/img/showcase-mob.jpg');?>" alt="">
        <!-- <div class="showcase-content">
          <h1><span class="text-primary">Limitless</span> Assistance</h1>
          <p class="lead"><span>Crafted to be invisible.</span> <span>Crafted to stay invincible.</span></p>
        </div> -->
      </div>
    </div>
  </header>
  <section id="features">
    <div class="box">
      <div class="icon"><img src="<?php echo base_url('assets/img/towing.png');?>" alt=""></div>
      <h3>Towing <br>Service</h3>      
    </div>
    <div class="box">
        <div class="icon"><img src="<?php echo base_url('assets/img/flat_tyre.png');?>" alt=""></div>
        <h3>Puncture <br>Assistance</h3>        
    </div>
    <div class="box">
        <div class="icon"><img src="<?php echo base_url('assets/img/battery.png');?>" alt=""></div>
        <h3>Alternate <br>battery</h3>        
    </div>
    <div class="box">
        <div class="icon"><img src="<?php echo base_url('assets/img/breakdown-support.png');?>" alt=""></div>
        <h3>Breakdown <br>Support</h3>        
    </div>
    <div class="box">
      <div class="icon"><img src="<?php echo base_url('assets/img/extraction.png');?>" alt=""></div>
      <h3>Extraction <br>Service</h3>      
    </div>
    <div class="box">
        <div class="icon"><img src="<?php echo base_url('assets/img/taxi.png');?>" alt=""></div>
        <h3>Taxi <br>Service</h3>        
    </div>
    <div class="box">
        <div class="icon"><img src="<?php echo base_url('assets/img/spare-key.png');?>" alt=""></div>
        <h3>Spare <br>Keys</h3>        
    </div>
    <div class="box">
        <div class="icon"><img src="<?php echo base_url('assets/img/fuel.png');?>" alt=""></div>
        <h3>Fuel <br>Assistance</h3>        
    </div>
    <div class="box">
      <div class="icon"><img src="<?php echo base_url('assets/img/message-relay.png');?>" alt=""></div>
      <h3>Message <br>Relay</h3>      
    </div>
    <div class="box">
        <div class="icon"><img src="<?php echo base_url('assets/img/hotel-accomodation.png');?>" alt=""></div>
        <h3>Hotel <br>Accommodation</h3>  
        <span class="sp">*</span>      
    </div>
    <div class="box">
        <div class="icon"><img src="<?php echo base_url('assets/img/legal-and-medical.png');?>" alt=""></div>
        <h3>Medical and Legal <br>Coordination</h3>    
        <span class="sp">*</span>    
    </div>
    <div class="box">
        <div class="icon"><img src="<?php echo base_url('assets/img/alternate-travel.png');?>" alt=""></div>
        <h3>Alternate <br>Travel</h3>    
        <span class="sp">*</span>    
    </div>
  </section>
  <section class="features-terms">
    <div class="container">
      <h2 style="text-transform: uppercase; margin-top: 20px;">Unlimited kilometre Assistance</h2>
      <p style="text-align: left;">This service will be provided to all TVS Apache RR310 owners either free of cost or at a reasonable cost to customers, depending on date of sale of vehicle.</p>
      <ul style=" margin: 0 auto; text-align: left; margin: 0 0 50px 20px; padding: 0;">
        <li><b>Free of Cost (FOC):</b><br>All Apache RR310 sold after the date of launch of the program i.e. 01st Oct ’2019 in the town shall be covered for first year from the date of sale. Service can be renewed post first year by paying a reasonable charge of Rs. 999/-.</li>
        <li><b>Chargeable Basis:</b><br>Existing Apache RR310 customers can avail the program by paying a reasonable charge of Rs. 999/- for first year and can be renewed further on. No separate membership fee required.</li>
      </ul>
      <p>All Consumables are chargeable including cost of accommodation, travel tickets, medical and legal assistance to be paid by the customer.</p>
    </div>
  </section>
  <section id="video" class="bg-light">
    <div class="vd-content">
      <div class="videoWrapper">
          <!-- Copy & Pasted from YouTube -->
          <iframe width="720" height="405" src="https://www.youtube.com/embed/_XdbA1rp1Vg" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      </div>        
    </div>
  </section>
  <section id="price-info" class="bg-primary">
    <div class="info-content">
      <h2>Rs. 999/- Only</h2>  
      <form action="<?php echo base_url('rr_310_policy');?>" method="POST">
        <input type="hidden" name="rsa_type" value="rr_310" id="rr_310">  
        <button type="submit" class="btn buynow">Buy Now</button>
      </form>    
      <!-- <a href="<?php echo base_url('rr_310_login');?>" class="btn  buynow">Buy Now</a> -->
    </div>
    <footer id="main-footer">
    <p>Copyright &copy; TVS Motor Company. | All Rights Reserved. | <a href="https://www.myassistancenow.com/uat/tvs_rsa_new/disclaimer" target="_blank"> Disclaimer</a> | Powered by <a href="https://indicosmic.com" target="_blank"> Indicosmic</a></p>
  </footer>
  </section>
</body>
</html>
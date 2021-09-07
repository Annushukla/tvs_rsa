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
        <div class="right-logo"><img src="<?php echo base_url('assets/img/tvs-racing-logo.png');?>" alt=""></div>
      </div>
    </nav>
  </header>
  <section class="features-terms">
    <div class="container">
      <h2 style="text-transform: uppercase; margin-top: 20px;">LIMITATION TO LIMITLESS ASSIST</h2>
      <ol style=" margin: 0 auto; text-align: left; margin: 0 0 50px 20px; padding: 0;">
        <li>Any event when the rider of the vehicle is found to be in any of the situations that are indicated below:
          <ul style=" margin: 0 auto; text-align: left; margin: 0 0 20px 20px; padding: 0;">
            <li>The state of intoxication or under the influence of alcohol, drugs, toxins or narcotics.</li>
            <li>Lack of permission or corresponding license for the category of the Covered Vehicle or violation of the sanction of cancellation.</li>
          </ul>
        </li>
        <li>Services shall only be applicable till the nearest authorized RR310 dealership and shall not be applicable for an immobilized vehicle if it is already at an authorized workshop or a garage.</li>
        <li>Any event, where at the company’s satisfaction it is found out that the breakdown is caused by deliberately inflicted damage, vandalism or participation in any criminal act or offence.</li>
        <li>Any Customer history where Customer has on prior occasions misused or abused the services.</li>
        <li>Any vehicle involved in or liable to be involved in legal case including but not limited to cases filed in any forum or pre-litigation notices issued against the TVS Motor Company Limited prior to or post immobilization.</li>
        <li>Events happening while the vehicle lacks documentation or requisites (including the Technical Inspection of the vehicles and Obligatory Insurance) legally necessary to ply on public roads.</li>
        <li>Events not covered under the program:
          <ul style=" margin: 0 auto; text-align: left; margin: 0 0 20px 20px; padding: 0;">
            <li>Non-functional horn</li>
            <li>Faulty gauges and meters</li>
            <li>Vehicle headlights not functional during day time. (Night Time, the feature is covered)</li>
          </ul>
        </li>
        <li>Vehicle is involved in motor racing, rallies, speed or duration tests, practice runs or operated outside official roads.</li>
        <li>Assistance is required as a result of wars, riots, uprising, mass political demonstrations, pillage, strike, use for military purposes or acts of terrorism, earthquake damage, freak weather conditions, atmospheric phenomena, nuclear transformation phenomena or radiation caused by artificial acceleration of atomic particles.</li>
        <li>The immobilization is resulting from damage caused by the intervention of the police or other governmental authorities.</li>
        <li>The vehicle shall, at all times, be bound by warranty terms and conditions as stated in the vehicle’s owner’s manual.</li>
      </ol>
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
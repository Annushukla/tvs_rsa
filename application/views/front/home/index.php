
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>User | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
   <link href="<?php echo base_url(); ?>assets/css/styles.css" rel="stylesheet">
    

    
  </head>
  <body class="hold-transition login-bg">
<div  id="pacover-login">
<div class="login-page">
   <div class="login-logo text-white">
        <a href="javascript:void(0)" style="color: white;"><b></b> Login</a>
      </div>
  <div class="form">
    <form class="register-form">
      <input type="text" placeholder="name"/>
      <input type="password" placeholder="password"/>
      <input type="text" placeholder="email address"/>
      <button>create</button>
      <p class="message">Already registered? <a href="#">Sign In</a></p>
    </form>
      <form id="loginform" action="<?php echo base_url();?>Login/submitLogin" class="login-form" role="form" method="POST">
               <input id="email" type="text" class="" name="email" value="" placeholder="Username" style="text-transform: uppercase;">                                        
               <input id="password" name="password" type="password" class=""  placeholder="Password" style="text-transform: uppercase;">
            <div style="margin-top:10px" class="form-group">
               <!-- Button -->
               <div class="col-sm-12 controls">
        <button type="submit" class="btn btn-success">Login</button>
                  <!-- <a id="btn-fblogin" href="#" class="btn btn-primary">Login with Facebook</a> -->
               </div>
            </div>
            <br>
            <br>
            <br>
      <p class="message">Not registered? <a href="#">Create an account</a></p>
    </form>
  </div>
</div>
</div>

<!-- 
    <div class="login-box">
      <div class="login-logo">
        <a href="javascript:void(0)"><b></b> Login</a>
      </div>
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
       <form id="loginform" action="<?php echo base_url();?>Login/submitLogin" class="form-horizontal" role="form" method="POST">
            <div style="margin-bottom: 25px" class="input-group">
               <span class="input-group-addon"><i class="fas fa-user"></i></span>
               <input id="email" type="text" class="form-control" name="email" value="" placeholder="Username">                                        
            </div>
            <div style="margin-bottom: 25px" class="input-group">
               <span class="input-group-addon"><i class="fas fa-lock"></i></span>
               <input id="password" name="password" type="password" class="form-control"  placeholder="Password">
            </div>
            <div style="margin-top:10px" class="form-group">
               <div class="col-sm-12 controls">
        <button type="submit" class="btn btn-success">Login</button>
               </div>
            </div> -->
      <!--       <div class="input-group" style="  width: 100%;">
               <div class="checkbox">
                  <label>
                  <input id="login-remember" type="checkbox" name="remember" value="1"> Remember me
                  </label>
                  <label class="pull-right"><em>Forgot Passwod ?</em>
                  </label>
               </div>
               <center> <a href="get-quote.html" target="_blank" class="btn btn-banner-submit">sign up <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                  </a>
               </center>
            </div> -->
         </form>
    <!-- <a href="#">I forgot my password</a><br>-->
        
      </div>
    </div>

    <!-- jQuery 2.1.4 -->
        <script type="text/javascript">
      $('.message a').click(function(){
   $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
});
    </script>
  </body>
</html>
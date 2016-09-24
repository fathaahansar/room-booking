<?php
session_start();
require_once("includes/class.user.php");

$login = new USER();

if($login->is_loggedin()!="") {
	$login->redirect('home.php');
}

if(isset($_POST['btn-login'])) {
	$umail = strip_tags($_POST['txt_uname_email']);
	$upass = strip_tags($_POST['txt_password']);
		
	$user_type = $login->doLogin($umail,$upass);

  if($user_type!="no" && $user_type!="no user") {
    $login->redirect('home.php');  
	} 
  else if($user_type == "no user") {
		$error = "Seems like you haven't registered yet. Click the link below to register";
	}
  else{
    $error = "The username and password don't match. Please try again or contact ID";
  }	
}

?>

<html>
  <?php include("includes/forevery.php"); ?>
  
  <body>

  <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
      <div class="row" style="background-color: #f4f4f4">
        <div class="col-md-4 col-sm-4 col-xs-4" style="border-bottom:10px solid #74c2e7 ">
          <div class="col-md-8 col-md-offset-4 blue">
            <img src="images/logo.png" class="hidden-xs img-responsive">
          </div>
        </div>
        
        <div class="col-md-4 col-sm-4 col-xs-4 logo" style="border-bottom:10px solid #ec1b23;position:relative;top:34px">
          <h2 class="text-center idtext">INSTRUCTION DIVISION</h2>
        </div>
        
        <div class="col-md-4 col-sm-4 col-xs-4 yellow" style="border-bottom:10px solid #f8c82a;position:relative;top:97px"></div>
      </div>
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="index.php">Home</a></li>
        </ul>
      </div><!-- /.navbar-collapse -->

    </div>
  </nav>

  <div class="signin-form">
    <div class="container">
      <div class="col-md-offset-3 col-md-6">
        <form class="form-horizontal" style="padding-top: 150px;" method="post" id="login-form">
          <fieldset>
            <!-- Form Name -->
            <legend>Login</legend>
            
            <div id="error">
            
              <?php
        			
              if(isset($error)) { ?>
                <div class="alert alert-danger">
                   <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?> !
                </div>
              <?php } 
              else if(isset($_GET['joined'])) { ?>
                <div class="alert alert-info">
                  <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully registered. You can login below.
                </div>
              <?php } ?>

            </div>
            
            <!-- Text input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="user">User ID</label>  
              <div class="col-md-8">
                <input type="text" class="form-control input-md" name="txt_uname_email" placeholder="" required="" value="<?php if(isset($_POST['txt_uname_email'])){ echo $_POST['txt_uname_email'];}?>"/>
                <span id="check-e"></span>
              </div>
            </div>
            
            <!-- Password input-->
            <div class="form-group">
              <label class="col-md-4 control-label" for="password">Password</label>
              <div class="col-md-8">
                <input type="password" class="form-control input-md" name="txt_password" placeholder="" required=""/>
              </div>
            </div>
          
            <hr />
            
            <div class="form-group">
              <label class="col-md-4 control-label" for="button1id"></label>
              <div class="col-md-8">    
                <button type="submit" name="btn-login" class="btn btn-primary">
                  <i class="glyphicon glyphicon-log-in"></i> &nbsp; Log in
                </button>
              </div> 
            </div>

          	<br />

            <label>First time here? <a href="register.php">Register</a></label>
          </fieldset>
        </form>
      </div>
    </div>
  </div>

  </body>

  <script src="./scripts/jquery-2.1.4.min.js"></script>
  <script src="./scripts/bootstrap.min.js"></script>

</html>                   
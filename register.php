<?php
session_start();
require_once('includes/class.user.php');

$user = new USER();

if($user->is_loggedin()!="") {
	$user->redirect('home.php');
}

if(isset($_POST['btn-signup'])) {
	$uname  = strip_tags($_POST['txt_uname']);
	$umail  = strip_tags($_POST['txt_umail']);
	$upass  = strip_tags($_POST['txt_upass']);
	$cupass = strip_tags($_POST['txt_cupass']);	
	
	if($uname=="") {
		$error[] = "Please Enter your Name";	
	}
	else if($umail=="")	{
		$error[] = "Please Enter your User ID";	
	}
	
	else if($upass=="")	{
		$error[] = "Please Enter your Password";
	}
	else if($cupass=="")	{
		$error[] = "Please Enter your Confirm Password";
	}
	else if($upass!=$cupass)	{
		$error[] = "Both the Passwords don't match, Please try again";
	}
	else if(strlen($upass) < 6){
		$error[] = "Password must be atleast 6 characters";	
	}
	else
	{
		try {
			$stmt = $user->runQuery("SELECT user_name, user_email FROM users WHERE user_name=:uname OR user_email=:umail");
			$stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
				
			if($row['user_email']==$umail) {
				$error[] = "Sorry, A User already exist by that User ID";
			}
			else {
				if($user->register($uname,$umail,$upass)) {	
					$user->redirect('login.php?joined');
				}
			}
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}	
}

?>


<html>
	<?php include("includes/forevery.php") ;?>
	
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
      		<li><a href="login.php">Login</a></li>
    		</ul>
  		</div><!-- /.navbar-collapse -->

		</div>
	</nav>
	
	<div class="signin-form">
		<div class="container">
	    <div class=" col-md-offset-2 col-md-8">	
	      <form method="post" class="form-horizontal" style="padding-top: 150px;">
	        <fieldset>

						<!-- Form Name -->
						<legend>Register</legend>
			            
			      <?php
						if(isset($error)) {
						 	foreach($error as $error) { ?>
                <div class="alert alert-danger">
                  <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                </div>
              <?php }
						} ?>

	          <!-- Name input-->
	          <div class="form-group">
	            <label class="col-md-4 control-label" for="nameinput">Name</label>
							<div class="col-md-8">
	            	<input type="text" class="form-control" name="txt_uname" placeholder="" value="<?php if(isset($_POST['txt_uname'])){ echo $_POST['txt_uname'];}?>" />
	            </div>
	          </div>

	          <!-- Appended Input-->
	          <div class="form-group">
		          <label class="col-md-4 control-label" for="useridinput">User ID</label>
							<div class="col-md-8">
								<div class="input-group">
		            	<input type="text" class="form-control" name="txt_umail" placeholder="" value="<?php if(isset($_POST['txt_umail'])){ echo $_POST['txt_umail'];}?>" />
		            	<span class="input-group-addon">@hyderabad.bits-pilani.ac.in</span>
		            </div>
		          	<p class="help-block">Use your BITS e-mail ID</p>
		          </div>
	        	</div>
	            
						<!-- Password input -->
	          <div class="form-group">
	           	<label class="col-md-4 control-label" for="passwordinput">Password</label>
							<div class="col-md-8">	
		            <input type="password" class="form-control" name="txt_upass" placeholder="" value="<?php if(isset($_POST['txt_upass'])){ echo $_POST['txt_upass'];}?>"/>
		          </div>
		        </div>
		        
		        <!-- Confirm Password input -->
	          <div class="form-group">
	      			<label class="col-md-4 control-label" for="confirmpasswordinput">Confirm Password</label>
							<div class="col-md-8">	
		            <input type="password" class="form-control" name="txt_cupass" placeholder="" />
		          </div>
		        </div>
		        
						<div class="clearfix"></div><hr />

           	<div class="form-group">
            	<label class="col-md-4 control-label" for="submit"></label>
							<div class="col-md-8">
	            	<button type="submit" class="btn btn-primary" name="btn-signup">
	                <i class="glyphicon glyphicon-open-file"></i>&nbsp;Register
	              </button>
            	</div>
            </div>

            <br />

            <label>Have an account? <a href="login.php">Log In</a></label>
       		</fieldset>
        </form>
    	</div>
		</div>
	</div>

	</body>
</html>
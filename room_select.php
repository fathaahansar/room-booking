<?php
session_start();
require_once('includes/class.user.php');

$user = new USER();

if($user->is_loggedin()=="") {
	$user->redirect('login.php');
}

$user_id   = $_SESSION['user_session'];
$user_type = $user->UserType();

if(isset($_POST['btn-request-availability'])) {
	$req_date  = strip_tags($_POST['req_date']);
	$time_in   = strip_tags($_POST['timepicker-one']);
	$time_in   = str_replace(" ", "", $time_in);
	//$time_in  .= ":00";
	$time_out  = strip_tags($_POST['timepicker-two']);	
	$time_out  = str_replace(" ", "", $time_out);
	//$time_out .= ":00";
	
	if($req_date=="") {
		$error[] = "Please Select the Date you need your Room";
	}
	else if($time_in=="" || $time_out=="" || $time_in>=$time_out) {
		$error[] = "Please Select the Time for which you need the Room";
	}
	else {
		$_SESSION['book_date'] = $req_date;
		$_SESSION['book_time_in'] = $time_in;
		$_SESSION['book_time_out'] = $time_out;
		$user->redirect('room_book.php');
	}	
}

?>

<html>
	<?php include('includes/forevery.php');?>
	
	<body>

	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<?php include('includes/header.php');?>
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
					<li><a href="home.php" >Home</a></li>
					<li><a href="#">TA Feedback Form</a></li>
					<li><a href="#">Other Forms</a></li>
					<li><a href="#contact">Contact Us</a></li>
				</ul>
			</div><!-- /.navbar-collapse -->

		</div>
	</nav>

	<div class="container">

		<form method="post" class="form-horizontal" style="padding-top: 130px;">
			<br />
			<fieldset>

				<!-- Form Name -->
				<legend>Room Booking: Select the Time and Date</legend>
				<br /><br /><br />
				<div class="col-md-offset-3 col-md-6">
				<?php
				
				if(isset($error)) {
					foreach($error as $error) { ?>
                  <div class="alert alert-danger">
                     <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                  </div>
               	<?php 
            		}
				}
				else if(isset($_GET['booked'])) { ?>
	                <div class="alert alert-info">
	                    <i class="glyphicon glyphicon-log-in"></i> &nbsp; The room has been booked successfully.<br /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Please check Dashboard to confirm.
	                </div>
	            <?php
	        	} ?>
				</div>
				<div class="form-group ">
	      			<label class="col-md-4 control-label" for="req_date">Date</label>
	     			<div class="col-md-4">
	     				<div class="input-group">
		       				<div class="input-group-addon">
		        					<i class="fa fa-calendar-check-o"></i>
		       				</div>
		       				<input class="form-control input-md" id="req_date" name="req_date" type="text" placeholder="" required="" value="<?php if(isset($_SESSION['book_date'])){ echo $_SESSION['book_date'];}?>"/>
	      				</div>
	      			</div>
	     		</div>
	     		
				<!-- Text input-->
				<div class="form-group">
					<label class="col-md-4 control-label" for="time">Time</label>  
					<div class="col-md-2">
						<input type="text" id="timepicker-one" name="timepicker-one" placeholder="From" class="timepicker form-control input-md" required="" value="<?php if(isset($_SESSION['book_time_in'])){ echo $_SESSION['book_time_in'];}?>"/>
					</div>
					<div class="col-md-2">
						<input type="text" id="timepicker-two" name="timepicker-two" placeholder="To" class="timepicker form-control input-md" required="" value="<?php if(isset($_SESSION['book_time_out'])){ echo $_SESSION['book_time_out'];}?>"/>
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-offset-4 col-md-4">
						<button id="btn-request" name="btn-request-availability" class="btn btn-primary">Check Availability</button>
					</div>
				</div>
			</fieldset>
		</form>
	<br /><br /><br />
	<div class="page-header">
			<h3 class="text-center">Important Instructions</h3>
		</div>
		<ul style="padding-bottom: 1em;">
			<li>The Student booking the room is responsible for the room.</li>
			<li>The existing furniture inside the classroom should not be moved or removed. Permission from Associate Dean, ID is needed for the same.</li>
			<li>Food and beverages are not allowed inside the classroom.</li>
		</ul>
	</div>



	<!-- Include Date and Time Picker -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
	<script type="text/javascript" src="./scripts/wickedpicker.js"></script>
	
	<script>
	   $(document).ready(function(){
	      var date_input=$('input[name="req_date"]'); //our date input has the name "req_date"
	      var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
	      
	      date_input.datepicker({
		    format: 'yyyy-mm-dd',
		    // startDate: '0d',
   		 // 	endDate: '+2d',
	        container: container,
	        todayHighlight: true,
	        autoclose: true,
	      })
	    });
	</script>

	<script>
		var options = {
      		now: "18:00", //hh:mm 24 hour format only, defaults to current time
        	twentyFour: true,  //Display 24 hour format, defaults to false
        	upArrow: 'wickedpicker__controls__control-up',  //The up arrow class selector to use, for custom CSS
        	downArrow: 'wickedpicker__controls__control-down', //The down arrow class selector to use, for custom CSS
        	close: 'wickedpicker__close', //The close class selector to use, for custom CSS
        	hoverState: 'hover-state', //The hover state class to use, for custom CSS
        	title: 'Pick Your Time' //The Wickedpicker's title
	   };

	   $('.timepicker').wickedpicker(options);
	</script>

	<?php include("includes/footer.php"); ?>

	</body>
</html>
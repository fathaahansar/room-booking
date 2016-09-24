<?php
session_start();
require_once('includes/class.user.php');

$user = new USER();

if($user->is_loggedin()=="") {
	$user->redirect('login.php');
}

if(!isset($_SESSION['book_date']) && !isset($_SESSION['book_time_in']) && !isset($_SESSION['book_time_out']) ) {
	$user->redirect('room_select.php');
}

$user_id   = $_SESSION['user_session'];
$user_type = $user->UserType();

$stmt = $user->runQuery("SELECT user_id, user_name, user_email FROM users WHERE user_type=:user_type");
$stmt->execute(array(':user_type'=>"1"));
$row_fac = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(isset($_POST['btn-request'])) {
	$req_date = strip_tags($_SESSION['book_date']);
	$req_time_in = strip_tags($_SESSION['book_time_in']);
	$req_time_out = strip_tags($_SESSION['book_time_out']);
	$reason = strip_tags($_POST['txt_reason']);
	$num_stud = strip_tags($_POST['num_stud']);
	$room_num = $_POST['room_num'];

	if(isset($_POST['swd_auth'])) {
		$swd_auth = strip_tags($_POST['swd_auth']);
	}
	
	if(count($room_num) > 1) { $multiple_req_id = mt_rand(); } else { $multiple_req_id = NULL; }

	if($user_type == "2") {
		$fac_inc_id = strip_tags($_POST['fac_inc_id']);
	} else {
		$fac_inc_id = $user_id;
	}
	
	if(isset($_POST['esd_req'])) { $esd_req = strip_tags($_POST['esd_req']); } else { $esd_req = ""; }
	
	foreach($room_data as $room_num_key => $room_data_row) {
		foreach($room_num as $room_num_row) {
			if($room_data_row['room_id'] == $room_num_row) {
				if($room_data_row['room_cap_class'] > $num_stud) {
					$error[] = "The Room you have selected can't accomodate your required Number of Students";
				}
			}
		}
	}

	if($reason=="") {
		$error[] = "Please Enter a valid Reason";	
	}
	else if($num_stud=="") {
		$error[] = "Please Enter the Number of Students";	
	}
	else if(!is_numeric($num_stud)) {
		$error[] = "Please Enter a Valid Number of Students";	
	}
	else if($room_num=="")	{
		$error[] = "Please Select the Room you prefer.";
	}
	else if($esd_req=="")	{
		$error[] = "Please Select Yes/No for Audio-Visual requirement.";
	}
	else if($fac_inc_id == "0" && !isset($_POST['swd_auth'])) {
		$error[] = "Please select the faculty in-charge or SWD to validate your request.";
	}
	else if($fac_inc_id == "0" && $swd_auth == "0") {
		$error[] = "Please select the faculty in-charge or SWD to validate your request.";
	}
	else {
		try {
			if($room_array['room_status'] == "1") {
				$error[] = "Sorry, All requests for the room you have asked has been blocked. Please contact ID.";
			}
			else
			{
				if($user->booktheroom($reason, $num_stud, $req_date, $req_time_in, $req_time_out, $room_num, $esd_req, $user_id, $fac_inc_id, $user_type, $multiple_req_id)) {	
					foreach($row_fac as $single_row_fac) {
						if($single_row_fac['user_id'] == $fac_inc_id) {
							$fac_inc_name = $single_row_fac['user_name'];
							$fac_inc_email = $single_row_fac['user_email'];
						}
					}

					$user->redirect('room_select.php?booked');
					unset($_SESSION['book_date']);
					unset($_SESSION['book_time_in']);
					unset($_SESSION['book_time_out']);

					// if($user_type == "2"){
					// 	$to=$fac_inc_email;
					// 	$from=''; // Have to add the email id. Have to create one in gmail
					// 	$from_name='Instruction Division';
					// 	$subject='Room request';
					// 	$body='Hi '. $fac_inc_name;
					// 	$body.=' ,There has been a room requested that requires your approval to continue. Go to your <a href="home.php">homepage</a>.';

					// 	$user->smtpmailer($to, $from, $from_name, $subject, $body);
					// }

					// $user->send_mail($to_name, $to, $subject, $message, $from_name, $from);
				}
			}
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}	
}

if(isset($_POST['btn-reset'])) {
	$user->redirect('room_select.php');
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

	<div class="container" style="padding-top: 105px;">
		
		<form method="post" class="form-horizontal" style="padding-top: 10px;">
			<br />
			<div class="col-md-7">
				<h3>Information</h3>
				<h4>Selected Date: <?php echo $_SESSION['book_date'];?> </h4>
				<h4>Selected Time: <?php echo $_SESSION['book_time_in']." to ".$_SESSION['book_time_out']; ?></h4>
			</div>

			<fieldset>

				<!-- Form Name -->
				<legend>Room Booking: Select the Room</legend>

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
				

				<!-- Textarea -->
				<div class="form-group">
					<label class="col-md-6 control-label" for="txt_reason">Reason</label>
					<div class="col-md-6">                     
						<textarea class="form-control" id="txt_reason" name="txt_reason"><?php if(isset($reason)){ echo htmlspecialchars($reason); }?></textarea>
					</div>
				</div>
				
				<!-- Text input-->
				<div class="form-group">
					<label class="col-md-6 control-label" for="num_stud">Approximate No.of Students</label>  
					<div class="col-md-6">
						<input id="num_stud" name="num_stud" type="text" placeholder="" class="form-control input-md" required="" value="<?php if(isset($num_stud)){ echo $num_stud;}?>">
					</div>
				</div>

				<div class="form-group">
		      		<label class="col-md-6 control-label" for="room_num">Room Number</label>  
		      		<div class="col-md-6">
		      			<select id="room_num" name="room_num[]" data-placeholder="Select the room required." class="selectpicker select form-control room_num" required="" multiple="multiple" data-live-search="true">

			       			<?php

								$stmt = $user->runQuery("SELECT room_id, req_time_in, req_time_out FROM room_requests WHERE req_date=:req_date");
								$stmt->execute(array(':req_date'=>$_SESSION['book_date']));
								$request_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
								$room_that_are_booked = array();
								$i=0;

								$book_time_in = $_SESSION['book_time_in'];
								$book_time_out = $_SESSION['book_time_out'];

								var_dump($request_data);

								foreach($request_data as $request_data_row) {
									$request_data_time_in = $request_data_row['req_time_in'];
									$request_data_time_out = $request_data_row['req_time_out'];

									if($book_time_in > $request_data_time_in && $book_time_out >= $request_data_time_out) {
										$room_that_are_booked[$i++] = $request_data_row['room_id'];
										echo "case 1";
									} else if($book_time_in <= $request_data_time_in && $book_time_out >= $request_data_time_out) {
										$room_that_are_booked[$i++] = $request_data_row['room_id'];
										echo "case 2";
									} else if($book_time_in <= $request_data_time_in && $book_time_out < $request_data_time_out) {
										$room_that_are_booked[$i++] = $request_data_row['room_id'];
										echo "case 3";	
									}  else if($book_time_in > $request_data_time_in && $book_time_out < $request_data_time_out) {
										$room_that_are_booked[$i++] = $request_data_row['room_id'];
										echo "case 3";	
									} 
								}
								sort($room_that_are_booked);
								var_dump($room_that_are_booked);
								$room_id_counter = 1;
								$i = 0;


								if($room_that_are_booked != array()) {
									$room_that_are_not_booked = array();
									foreach($room_that_are_booked as $room_that_are_booked_row) {
										while($room_id_counter < $room_that_are_booked_row) {
											$room_that_are_not_booked[$i++] = $room_id_counter++;
										}
										$room_id_counter++;
									}
									if($room_id_counter != 33) {
										while($room_id_counter < 33) {
											$room_that_are_not_booked[$i++] = $room_id_counter++;
										}
									}
								} else {
									while($room_id_counter < 33) {
										$room_that_are_not_booked[$i++] = $room_id_counter++;
									}
								} 

								$i=0;

								var_dump($room_that_are_not_booked);


								foreach($room_that_are_not_booked as $room_that_are_not_booked_row) {
									$stmt = $user->runQuery("SELECT room_id, room_num, room_cap_class, room_cap_test, room_audio, room_status FROM rooms WHERE room_id=:room_id");
									$stmt->execute(array(':room_id'=>$room_that_are_not_booked_row));
									$room_data[$i++] = $stmt->fetch(PDO::FETCH_ASSOC);
								}
								var_dump($room_data);

								foreach($room_data as $room_data_row) {
									echo "<option value=".$room_data_row['room_id'].">".$room_data_row['room_num']." | <em>Room Capacity:</em> ".$room_data_row['room_cap_class']."</option>";
								}

				       		?>

			      		</select>
			     	</div>
		     	</div>

				<?php
				if($user_type=="2") { ?>
					<div class="form-group">
			      		<label class="col-md-6 control-label" for="fac_inc_id">Faculty In-charge</label>  
			      		<div class="col-md-6">
			      			<select class="selectpicker select form-control" id="fac_inc_id" data-placeholder="Select the Faculty-incharge" name="fac_inc_id" data-live-search="true" required="" value="<?php if(isset($_POST['fac_inc_id'])){ echo $_POST['fac_inc_id'];}?>">
			      				<option value="0">None</option>
				       			<?php
				       			foreach ($row_fac as $fac_array) {
			 						echo "<option value=\"" . $fac_array['user_id'] . "\">";
					       			echo $fac_array['user_name'];
					       			echo "</option>";
				       			} ?>
				       		</select>
				       	</div>
				    </div>

				    <div class="form-group">
			      		<label class="col-md-6 control-label" for="swd_auth">Let SWD authorize the request?</label>  
			      		<div class="col-md-6"">
				      		<div class="col-md-3 radio">
								<label for="swd_auth_1">
									<input type="radio" name="swd_auth" id="swd_auth_1" value="1">
									Yes
								</label>
							</div>
							<div class="col-md-3 radio">
								<label for="swd_auth_0">
									<input type="radio" name="swd_auth" id="swd_auth_0" value="0">
									No
								</label>
				      		</div>
				      	</div>
				      	<div class="col-md-6 col-md-offset-6">
				      		<p class="help-block">If your request doesn't need a Faculty In-charge approval, please select Yes.</p>
				      	</div>
				<?php } ?>

			
				<!-- Multiple Radios -->
				<div class="form-group">
					<label class="col-md-6 control-label" for="esd_req">Do you require Audio-Visual Equipment? </label>
					<div class="col-md-6">
						<div class="col-md-3 radio">
							<label for="esd_req_1">
								<input type="radio" name="esd_req" id="esd_req_1" value="1">
								Yes
							</label>
						</div>
						<div class="col-md-3 radio">
							<label for="esd_req_0">
								<input type="radio" name="esd_req" id="esd_req_0" value="0">
								No
							</label>
						</div>
					</div>
				</div>
				
				<!-- Button -->
				<div class="form-group">
					<div class="col-md-offset-6 col-md-4">
						<button id="btn-request" name="btn-request" class="btn btn-primary">Book the Room</button>
					</div>
				</div>

			</fieldset>
		</form>

		<form method="post" class="form-horizontal">
			<br />
			<div class="col-md-7">
				

			</div>
			<fieldset>
				<div class="form-group">
					<div class="col-md-offset-6 col-md-6">
						<button id="btn-reset" name="btn-reset" class="btn btn-primary">Change Date and Time</button>
					</div>
				</div>
			</fieldset>
		</form>

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
	
	<script type="text/javascript">
	    $(document).ready(function() {
	        $('#room_num').multiselect();
	    });
	</script>

	<?php include("includes/footer.php"); ?>

	</body>
</html>
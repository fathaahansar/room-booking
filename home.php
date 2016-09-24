<?php
require_once("includes/session.php");
require_once("includes/class.user.php");

$auth_user = new USER();
$user_id = $_SESSION['user_session'];

//Fetching data about the present user who is logged in instead of storing the data in SESSION variables. Makes it more secure. 
$stmt = $auth_user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
$stmt->execute(array(":user_id"=>$user_id));
$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
?>


<?php

if(isset($_POST['approverequest'])) {
  foreach ($_POST['auth'] as $req_approval_id => $value){
    //IF condition for the ID admin
    if($userRow['user_type'] == "0") {
      $user_data = $auth_user->runQuery("SELECT * FROM room_requests WHERE req_id = :request_id");
      $user_data->execute(array(":request_id"=>$req_approval_id));
      //Fetching data about the person who has booked the room to send the mail
      $req_user_id=$data_row['user_id'];
      $req_stmt = $auth_user->runQuery("SELECT * FROM users WHERE user_id=:req_user_id");
      $req_stmt->execute(array(":req_user_id"=>$req_user_id));
      $req_userRow=$req_stmt->fetch(PDO::FETCH_ASSOC);

      //The DB UPDATE part
      $approval = $auth_user->runQuery("UPDATE room_requests SET id_authorized = '1' WHERE req_id = :request_id");
      $approval->execute(array(":request_id"=>$req_approval_id));

      //The mailing part
      // $to=$req_userRow['user_email'];
      // $from='iddonotreply@gmail.com'; 
      // $from_name='Instruction Division';
      // $subject='Room request has been approved';
      // $body='The room you had requested has been accepted by the ID.';

      //Mailing function
      // $auth_user->smtpmailer($to, $from, $from_name, $subject, $body);
    }

    //IF conditions for SWD and Faculty-incharge 

    else if($userRow['user_type'] == "1") {
      $approval = $auth_user->runQuery("UPDATE room_requests SET fac_authorized = '1' WHERE req_id = :request_id");
    }
    else if($userRow['user_type'] == "1") {
      $approval = $auth_user->runQuery("UPDATE room_requests SET swd_authorized = '1' WHERE req_id = :request_id");
    }
    
    $approval->execute(array(":request_id"=>$id));
    $auth_user->redirect('home.php');
  }
}

if(isset($_POST['cancelrequest'])) {
  foreach ($_POST['auth'] as $id => $entry) {
    
    $approval = $auth_user->runQuery("UPDATE room_requests SET id_authorized = '-1' WHERE req_id = :request_id");
    $approval->execute(array(":request_id"=>$id));
    $auth_user->redirect('home.php');
  }
}

if(isset($_POST['deleterequest'])) {
  foreach ($_POST['auth'] as $id => $entry) {
    $approval = $auth_user->runQuery("DELETE FROM room_requests WHERE req_id = :request_id");
    $approval->execute(array(":request_id"=>$id));
    $auth_user->redirect('home.php');
  }
}

?>

<html>

  <?php include("includes/forevery.php"); ?>

  <body>
  <form method="POST" action="home.php">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <?php include('includes/header.php'); ?>
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
            <li><a href="">Home</a></li>
            <li><a href="room_report.php">Room Report</a></li>
            <li><a href="room_select.php">Book a Room</a></li>
            <li><a href="view_schedule.php">Room Schedule Calendar</a></li>
            <li><a href="logout.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Log Out</a></li>
          </ul>
          <div class="pull-left ">
            <div id="btn-group-hidden" class="btn-group-hidden">
              <?php
              if($userRow['user_type'] == "0" || $userRow['user_type'] == "1" || $userRow['user_type'] == "4") { ?>
                <button type="submit" name="approverequest" class="btn btn-success approve" id="request1">Approve</button>
                <button type="submit" name="cancelrequest" class="btn btn-default cancel" id="request2">Cancel</button>
              <?php } 
              if($userRow['user_type'] == "2" || $userRow['user_type'] == "1" || $userRow['user_type'] == "0" || $userRow['user_type'] == "4") { ?>
                <button type="submit" name="deleterequest" class="btn btn-danger delete" id="request3">Delete</button>
              <?php } ?>
            </div>
          </div>
        </div><!-- /.navbar-collapse -->
      </div>
    </nav>

    <div class="container-fluid" style="padding-top: 105px;">
      <div class="row" style="background-color: #f4f4f4">
        <div class="col-md-4 col-sm-4 col-xs-4" style="border-top:2px solid #a9a9a9 "></div>
      
        <div class="col-md-4 col-sm-4 col-xs-4" style="border-top:2px solid #a9a9a9">
          <h4 class="text-center">Welcome <?php echo $userRow['user_name']; ?></h4>
        </div>
        
        <div class="col-md-4 col-sm-4 col-xs-4" style="border-top:2px solid #a9a9a9"></div>
      </div>
    </div>

    
    <?php 

    $data = new USER();

    if(!isset($user_id)) {
      redirect("login.php");
    }
    $query = "SELECT * FROM room_requests";
    
    if($userRow['user_type'] == "2"){       // for all faculty and students to fetch requests that are from them
      $query .= " WHERE user_id=:user_id";
      $stmt = $data->runQuery($query);
      $stmt->execute(array(":user_id"=>$user_id));
      $data_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    if($userRow['user_type'] == "1"){       // for all faculty and students to fetch requests that are from them
      $query .= " WHERE user_id=:user_id OR fac_inc_id=:fac_inc_id OR swd_authorized=:swd_authorized";
      $stmt = $data->runQuery($query);
      $stmt->execute(array(":user_id"=>$user_id, ":fac_inc_id"=>$user_id, ":swd_authorized"=>$swd_authorized));
      $data_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    if($userRow['user_type'] == "3"){      // for esd
      $query .= " WHERE req_esd=:req_esd AND id_authorized=:id_authorized";
      $stmt = $data->runQuery($query);
      $stmt->execute(array(":req_esd"=> "1", ":id_authorized"=> "1"));
      $data_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    if($userRow['user_type'] == "4"){      // for swd
      $query .= " WHERE fac_inc_id=:fac_inc_id";
      $stmt = $data->runQuery($query);
      $stmt->execute(array(":fac_inc_id"=>"0"));
      $data_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    if($userRow['user_type']=="0"){   //for id
      $query .= " WHERE fac_authorized=:fac_authorized";
      $stmt = $data->runQuery($query);
      $stmt->execute(array(":fac_authorized"=> "1"));
      $data_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    if($data_array == array()) { ?>
    
      <div class="row" style="background-color: #f4f4f4">
        <div class="container">
          <table class="table table-filter">
            <tbody>
              <tr data-status="">
                <td>
                  <div class="media">
                    <div class="media-body" style="padding-top: 250px;padding-bottom:230px">
                      <center><h4 class="title">No Requests Yet. Go to <a href="room_book.php">Book a Room</a> to make a request.</h4></center> 
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    <?php } ?> 
     
    <?php if($data_array != array()) { ?>

      <div class="container-fluid">
        <div class="row" style="background-color: #f4f4f4">
          <div id="content">
            <div class="col-md-8 col-md-offset-2">
              <div class="panel panel-default">
                <div class="panel-body">
                  <div class="pull-right ">
                    <div class="btn-group">
                      <button type="button" class="active btn btn-default btn-filter" data-target="all" onclick="markActive(this)">All Requests</button>
                      <?php if($userRow['user_type'] == "0" || $userRow['user_type'] == "2" || $userRow['user_type'] != 3) {?>
                        <button type="button" class="btn btn-default btn-filter" data-target="pending" onclick="markActive(this)">Pending Requests</button>
                      <?php }?>
                      <button type="button" class="btn btn-default btn-filter" data-target="approved" onclick="markActive(this)">Approved Requests</button>
                      <button type="button" class="btn btn-default btn-filter" data-target="cancelled" onclick="markActive(this)">Cancelled Requests</button>
                      <?php if($userRow['user_type'] == "1") {?>
                        <button type="button" class="btn btn-default btn-filter" data-target="facultyapproved" onclick="markActive(this)">To be Approved </button>
                      <?php } ?>
                    </div>
                  </div>

                  <?php foreach($data_array as $data_row) {
                    
                    $stmt = $data->runQuery("SELECT * FROM rooms WHERE room_id=:room_id");
                    $stmt->execute(array(":room_id"=>$data_row['room_id']));
                    $room_row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $formatted_date = date("F j, Y", strtotime($data_row['req_date']));
                    $time_in = substr($data_row['req_time_in'],0,strrpos($data_row['req_time_in'],':'));
                    $time_out = substr($data_row['req_time_out'],0,strrpos($data_row['req_time_out'],':'));
                    $requested_on = date("F j, Y", $data_row['requested_on']);
                    ?>
                    
                    <div class="table-container">
                      <table class="table table-filter" style="">
                        <tbody>
                          <tr data-status="<?php 
                            if($data_row['id_authorized'] == "0") { echo "pending"; } 
                            else if($data_row['id_authorized'] == "1"){ echo "approved"; } 
                            else if($data_row['id_authorized'] == "-1"){ echo "cancelled"; } 
                            else if($data_row['fac_authorized'] == "0" && $data_row['fac_inc_id'] == $user_id){ echo "facultyapproved"; } ?>">

                            <?php if($userRow['user_type'] == "0" || $userRow['user_type'] == "1" || $userRow['user_type'] == "2" || $userRow['user_type'] == "4") {?>
                              <td>
                                <div class="ckbox">
                                  <input type="checkbox" name="auth[<?php echo $data_row['req_id']; ?>]" value = "1">
                                </div>
                              </td>
                            <?php }?>
                            <td>
                              <div class="media">
                                <div class="mediabody">
                                  <div class="col-md-6">
                                    <?php
                                    
                                    if($userRow['user_type']=="0" || $userRow['user_type']=="1" || $userRow['user_type']=="4") {
                                      $req_user_id=$data_row['user_id'];
                                      $req_stmt = $auth_user->runQuery("SELECT * FROM users WHERE user_id=:req_user_id");
                                      $req_stmt->execute(array(":req_user_id"=>$req_user_id));
                                      $req_userRow=$req_stmt->fetch(PDO::FETCH_ASSOC);
                                    }

                                    ?>
                                    <h4 class="<?php if($data_row['id_authorized']=="0") { echo "h4title_pending"; } elseif($data_row['id_authorized']=="1") { echo "h4title_approved"; } elseif($data_row['id_authorized']=="-1") { echo "h4title_cancelled"; } elseif($data_row['fac_inc_id'] == $user_id) { echo "h4title_fac_auth_pending"; }?>"><?php if($userRow['user_type']==0 || $userRow['user_type']==4){ echo $req_userRow['user_name'].":"." "; } else if($userRow['user_type']==1 && $data_row['fac_inc_id'] == $user_id && $data_row['user_id'] != $user_id) { echo $req_userRow['user_name'].":"." "; } echo $room_row['room_num'] ." ". "on" . " ". $formatted_date; ?> </h4><br />
                                    <p class="timehead"><b>Time:</b><?php echo " ".$time_in. " to " . $time_out; ?></p> <br />
                                    <p class="summary"><b>Reason:</b></p>
                                    <p class="summary"><?php echo $data_row['req_reason']; ?></p> <br />
                                  </div>
                                  <div class="col-md-6">
                                    <br /><br /><p class="summary" style="position:relative;top:-1px;"><b>Approximate Number of Students:</b><?php echo " " . $data_row['req_num_students']; ?></p> <br />
                                    <p class="summary"><b>Audio-Visual Equipment: </b><?php if($data_row['req_esd'] == 1) { echo "Yes"; } else { echo "No";}?></p> <br />
                                    <?php 
                                    if($userRow['user_type'] == "2") {
                                      if($data_row['fac_authorized'] == "1") { $status = "Yes"; } else { $status = "No"; }
                                      echo "<p class=\"summary\"><b>Faculty authorization: </b>" . $status . "</p> <br />"; 
                                    }
                                    ?>
                                    <p class="summary"><?php echo "<b>Requested on: </b>" . $requested_on; ?></p> 
                                  </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    <?php } ?>
  </form>
  <?php include("includes/footer.php"); ?>
  
  </body>
  
  <script>
    $('input:checkbox').change(function () {
        if ($('input[type=checkbox]:checked').length > 0) {
            $('#btn-group-hidden').show();
        } else {
            $('#btn-group-hidden').hide();
        }
    });
  </script>

  <script src="./scripts/bootstrap.js" ></script>

  <script>
    function markActive(el)
    {
       $(el).siblings().removeClass('active');
       $(el).addClass('active'); 
    }

    $(document).ready(function () {
       $('.btn-filter').on('click', function () {
        var $target = $(this).data('target');
        if ($target != 'all') {
          $('.table tr').css('display', 'none');
          $('.table tr[data-status="' + $target + '"]').fadeIn('slow');
        } else {
          $('.table tr').css('display', 'none').fadeIn('slow');
        }
        if ($target == 'cancelled' || $target == 'approved' || $target == 'pending') {
          $('.table').css('margin', '0px');
        }
      });
    });

    $(function() {
        $('.approve, .cancel, .delete').click(function() {
            return window.confirm("Are you sure?");
        });
    });

  </script>

</html>
  
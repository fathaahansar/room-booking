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

  $data = new USER();

  if(!isset($user_id)) {
    redirect("login.php");
  } 
?>

<html>

  <?php include("includes/forevery.php"); ?>

  <body>
  <form method="POST" action="home.php" style="padding-top:175px;padding-bottom:15px;">
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
            <li><a href="home.php">Home</a></li>
            <li><a href="room_report.php">Room Report</a></li>
            <li><a href="room_book.php">Book a Room</a></li>
            <li><a href="view_schedule.php">Room Schedule Calendar</a></li>
            <li><a href="logout.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Log Out</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div>
    </nav>

    <div class="container">
      <?php
          
        if(isset($error)) {
          foreach($error as $error) { ?>
              <div class="alert alert-danger">
                 <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
              </div>
            <?php 
          }
        }
      ?>

      <form method="post" class="form-horizontal" style="padding-top: 0px;">
        <div class= "col-md-8" style="position:relative;top:-50px"> 
          <div class="displayrooms">                     
            <div id='calendar'></div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-6 control-label" for="room_num" style="position:relative;bottom:580px;left:750px">Room Number</label>  
          <div class="col-md-4">
            <select class="select form-control" id="room_num" name="room_num" style="position:relative;bottom:550px;left:180px" value="<?php if(isset($_POST['room_num'])){ echo $_POST['room_num'];}?>">
              
              <option value="0">Select a room</option>
              <?php
              $room_num = 307;
              $room_id = 1;

              while($room_num != 311) {
                
                echo "<option "; 
                if (isset($_POST['room_num']) && $_POST['room_num']==$room_id) echo "selected=\"true\" ";
                echo "value=\"" . $room_id . "\">";
                echo "B". " " . $room_num;
                echo "</option>";
                $room_num++;
                $room_id++;
              }

              $room_num = 102;

              while($room_num != 205) {
                
                if($room_num == 110) {
                  $room_num = 201;
                }
                echo "<option value=\"" . $room_id . "\">";
                echo "F". " " . $room_num;
                echo "</option>";
                $room_num++;
                $room_id++;
              }

              $room_num = 101;

              while($room_num != 209) {
                
                if($room_num == 109) {
                  $room_num = 201;
                }
                echo "<option value=\"" . $room_id . "\">";
                echo "G". " " . $room_num;
                echo "</option>";
                $room_num++;
                $room_id++;
              }

              ?>
            </select>
          </div>
        </div>
      </form> 
    </div>
  
  <script>
    try {
      $("#calendar").fullCalendar({
      header: {
      left: 'prev,next today',
      center: 'title',
      right: 'month,agendaWeek,agendaDay'
    },
    defaultView: 'agendaWeek',
       eventSources: [ 
        {
            url: "./events.php",
            color: "#00cc44",
            data: function() {
                return {
                    approvedRooms: $("#room_num").val()
                };
            },
            type: "POST"
        },
        {
          url: "./events.php",
            data: function() {
                return {
                    pendingRooms: $("#room_num").val()
                };
            },
            type: "POST"
        }
      ]
    });

    $("#room_num").change(function () {
      $('#calendar').fullCalendar('removeEvents');
        $("#calendar").fullCalendar("refetchEvents");
    });
    }
  catch(err) {
    document.getElementsByClassName("alert alert-info").innerHTML = err.message;
  }

  </script>

  <?php include("includes/footer.php"); ?>

  </body>
</html>
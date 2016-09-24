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

  if($_POST['btn-room-report']) {
    $req_date  = strip_tags($_POST['req_date']);
    if($req_date=="") {
      $error[] = "Please Select the Date you need your Room";
    } else {
      $stmt = $auth_user->runQuery("SELECT * FROM room_requests WHERE req_date=:req_date");
      $stmt->execute(array(":req_date"=>$req_date));
      $request_array=$stmt->fetchAll(PDO::FETCH_ASSOC);

      $pdf = new FPDF();
      $pdf->AddPage();
      $pdf->SetFont('Times New Roman','B',12);    
      foreach($request_array as $request_array_single) {
        $pdf->Cell(90,12,$request_array_single[''],1);
      }
      foreach($result as $row) {
        $pdf->SetFont('Arial','',12); 
        $pdf->Ln();
        foreach($row as $column)
          $pdf->Cell(90,12,$column,1);
      }
      $pdf->Output();
    }
  }
?>

<html>

  <?php include("includes/forevery.php"); ?>

  <body>

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

    <form method="post" class="form-horizontal" style="padding-top: 130px;">
      <br />
      <fieldset>

        <!-- Form Name -->
        <legend>Room Report</legend>

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

        <div class="form-group">
          <div class="col-md-offset-4 col-md-4">
            <button id="btn-room-report" name="btn-room-report" class="btn btn-primary">Generate Report</button>
          </div>
        </div>

      </fieldset>
    </form>

    <!-- Include Date and Time Picker -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <script>
      $(document).ready(function(){
        var date_input=$('input[name="req_date"]'); //our date input has the name "req_date"
        var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
        
          date_input.datepicker({
          format: 'yyyy-mm-dd',
          startDate: '0d',
          //endDate: '+2d',
          container: container,
          todayHighlight: true,
          autoclose: true,
        })
      });
    </script>
    <?php include("includes/footer.php"); ?>

  </body>
</html>
<html>
  
  <?php require_once('includes/forevery.php');?>
  
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
          <li><a href="#">Home</a></li>
          <li><a href="#about">About Us</a></li>
          <li><a href="#contact">Contact Us</a></li>
          <li><a href="login.php">Log In</a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div>
  </nav>

  <div class="container-fluid" style="padding-top: 107px;">
    <div class="row"> <img src="images/header.jpg" class="img-responsive"> </div>
    <span class="anchor" id="about"></span>
    <div class="row" style="padding-top: 10px;">
      <div class="container">
        <div class="page-header" style="padding-top: 0.1px;"> <h1>About Us</h1> </div>
        <h4>Instruction Division is the part of various Faculty and Student Needy Things</h4> 
        <ul> 
          <li>Time-Table</li>
          <li>Test Scheduling and Invigilation Alltment</li>
          <li>CMS website</li> 
          <li>Project Allotment</li>
          <li>Feedback monitoring</li>
          <li>Question Paper and solution</li>
          <li>Mid-Sem Gradings</li>
          <li>Handouts and Textbooks</li>
          <li>Security and Classroom Booking</li>
        </ul>
      </div>
    </div>
    
    <div class="row" style="background-color: #f4f4f4">
      <div class="container" style="padding: 1em;">
        
        <div class="col-md-4 block">
          <a href="login.php"><img src="images/room.png" class="img-responsive" style="display: block;margin-left: auto;margin-right: auto">
            <h3 class="text-center">Book A Room</h3>
          </a>
        </div>
          
        <div class="col-md-4 block">
          <a href="#"><img src="images/ta.png" class="img-responsive" style="display: block;margin-left: auto;margin-right: auto">
            <h3 class="text-center">T.A. Feedback Form</h3>
          </a>
        </div>
            
        <div class="col-md-4 block">
          <a href="#"><img src="images/forms.png" class="img-responsive" style="display: block;margin-left: auto;margin-right: auto">
            <h3 class="text-center">Other Forms</h3>
          </a>
        </div>

      </div>
    </div>
  </div>

  <?php include("includes/footer.php"); ?>

  </body>
      
  <script src="./scripts/jquery-2.1.4.min.js" ></script>
  <script src="./scripts/bootstrap.js" ></script>

</html>

<?php
session_start();?>
<head>
  <meta charset="utf-8">
  <title>CIERP</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!-- Favicons -->
  <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500,600,700,700i|Montserrat:300,400,500,600,700" rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="css/style.css" rel="stylesheet">

  <!-- =======================================================
    Theme Name: Rapid
    Theme URL: https://bootstrapmade.com/rapid-multipurpose-bootstrap-business-template/
    Author: BootstrapMade.com
    License: https://bootstrapmade.com/license/
  ======================================================= -->
</head>

<body>
<body>
  <header id="header">

    <div class="container">

      <div class="logo float-left">
        <!-- Uncomment below if you prefer to use an image logo -->
        <h1 class="text-light"><a href="#intro" class="scrollto"><span>CIERP</span></a></h1>
        <!-- <a href="#header" class="scrollto"><img src="img/logo.png" alt="" class="img-fluid"></a> -->
      </div>

           <nav class="main-nav float-right d-none d-lg-block">
        <ul>
          <?php if(isset($_SESSION['accountID']))
          {
            echo'<li class="active"><a href="account_page.php">My Account</a></li>';
           
          }
          else{
            echo'<li class="active"><a href="index.php">Home</a></li>';
          }?>
          <li><a href="index.php#about">About Us</a></li>
          <li><a href="index.php#services">Services</a></li>
          <li><a href="index.php#team">Team</a></li>
          <?php if(isset($_SESSION['accountID']))
          {
            echo'<li><a href="logOut.php">Log Out</a></li>';
           
          }
          else{
          echo'
              <li><a href="login.php">Log In</a></li>
              <li class="drop-down"><a>Sign Up</a>
                <ul>
                  <li><a href="signUp_rep.php">I am a company representative</a></li>
                  <li><a href="reviewerSignUp.php">I am a reviewer</a></li>
                </ul>
              </li>';
          }?>
        </ul>
      </nav>

      
    </div>
  </header>
  <section id="about">
  	<div class="container">
      	<div class="row">
      		<div class="col-5">
				<h2>Welcome <?php echo $_SESSION['uname']?></h2> 
			</div>
		</div>
	</div>
    <div class="container">
      <div class="row">
        <div class="col-3">
          <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">General Information</a>
            <a class="nav-link" id="v-pills-qualifications-tab" data-toggle="pill" href="#v-pills-qualifications" role="tab" aria-controls="v-pills-profile" aria-selected="false">Qualifications</a>
            <a class="nav-link" id="v-pills-interviews-tab" data-toggle="pill" href="#v-pills-interviews" role="tab" aria-controls="v-pills-interviews" aria-selected="false">Interviews</a>
            <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</a>
          </div>
        </div>
        <div class="col-9">
          <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
            <div class="container">
              <div class="row">
                <div class="col-lg-4 col-md-4">
                  <div class="about-img">
                    <img src="img/testimonial-1.jpg" alt="">
                  </div>
                </div>
                <div class="col-lg-7 col-md-6">
                  <div class="about-content">
                    <h2>General Information</h2>
                    <h5>Age: </h5>
                    <h5>Gender: </h5>
                    <h5>Experience: </h5>
                    <h5>Account Type: </h5>
                  </div>
                </div>
              </div>
            </div>
        </div>
            <div class="tab-pane fade" id="v-pills-qualifications" role="tabpanel" aria-labelledby="v-pills-qualifications-tab">
	            <div class="container">
	              <div class="row">
	                <div class="col-lg-7 col-md-6">
	                  <h2>Qualifications</h2>
	                </div>
	              </div>
	            </div>
            </div>
            <div class="tab-pane fade" id="v-pills-interviews" role="tabpanel" aria-labelledby="v-pills-interviews-tab">
              <div class="container">
                <div class="row">
                  <div class="col-lg-7 col-md-6">
                    <h2>Interviews</h2>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
            	<div class="container">
	                <div class="row">
	              		<div class="col-lg-7 col-md-6">
	                		<h2>Settings</h2>
	            		</div>
             		</div>
               </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </section><!-- #about -->

</body>
</html>

  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
  <!-- Uncomment below i you want to use a preloader -->
  <!-- <div id="preloader"></div> -->

  <!-- JavaScript Libraries -->
  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/jquery/jquery-migrate.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/mobile-nav/mobile-nav.js"></script>
  <script src="lib/wow/wow.min.js"></script>
  <script src="lib/waypoints/waypoints.min.js"></script>
  <script src="lib/counterup/counterup.min.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>
  <script src="lib/isotope/isotope.pkgd.min.js"></script>
  <script src="lib/lightbox/js/lightbox.min.js"></script>
  <!-- Contact Form JavaScript File -->
  <script src="contactform/contactform.js"></script>

  <!-- Template Main Javascript File -->
  <script src="js/main.js"></script>

</body>
</html>

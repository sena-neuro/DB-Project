<?php
  session_start();
  if(isset($_SESSION["accountID"]))
  {
    require 'db_config.php';
    $conn = Connect();
    
    $sql_u = "SELECT * FROM Comp_Rep WHERE AccountID=?";
    $stmt_u = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt_u,$sql_u)){
      header("Location: account_page?error=sqlerrorcomprep");
      exit();
    }
    else
    {
      mysqli_stmt_bind_param($stmt_u,"i",$_SESSION["accountID"]);
      mysqli_stmt_execute($stmt_u);
      $res = mysqli_stmt_get_result($stmt_u);
      if($row = mysqli_fetch_assoc($res)){
        $account_type="Company Representative";
        exit();
      }
      else
        $account_type="Reviewer";
    }
  }
  else{
    header("Location: index.php");
  }
?>
<html>
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

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <style> 
        .checked {
          color: orange;
        }
      </style>
    </head>
    
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
      	<div class="row"> <div class="col-5"> <h2>Welcome <?php echo $_SESSION['uname']?></h2> </div> </div>
  	  </div>
      <div class="container">
        <div class="row">
          <div class="col-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
              <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">General Information</a>
              <a class="nav-link" id="v-pills-qualifications-tab" data-toggle="pill" href="#v-pills-qualifications" role="tab" aria-controls="v-pills-profile" aria-selected="false">Qualifications</a>
              <a class="nav-link" id="v-pills-reviews-tab" data-toggle="pill" href="#v-pills-reviews" role="tab" aria-controls="v-pills-reviews" aria-selected="false">Reviews</a>
              <a class="nav-link" id="v-pills-applications-tab" data-toggle="pill" href="#v-pills-applications" role="tab" aria-controls="v-pills-applications" aria-selected="false">Applications</a>
              <a class="nav-link" id="v-pills-notifications-tab" data-toggle="pill" href="#v-pills-notifications" role="tab" aria-controls="v-pills-notifications" aria-selected="false">Notifications</a>
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
                        <h5>Account Type: <?php echo $account_type?></h5>
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

              <div class="tab-pane fade" id="v-pills-reviews" role="tabpanel" aria-labelledby="v-pills-reviews-tab">
                <div class="container">
                  <div class="row">
                    <div class="col-lg-7 col-md-6"><h2>Reviews</h2> </div>
                    <?php 
                        $stmt_job_reviews ="SELECT *
                        FROM Post_Job_Review NATURAL JOIN Job_Review
                        WHERE(Post_Job_Review.AccountID = ".$_SESSION['accountID'].")";
                        if($result_job_rev = mysqli_query($conn,$stmt_job_reviews)){
                          $num_of_job_reviews = mysqli_num_rows($result_job_rev);
                          printf("Select returned %d rows.\n", $num_of_job_reviews);
                        } 
                        else{
                          printf("Error in Post Job Review");

                        }
                        $stmt_interview_reviews ="SELECT *
                        FROM Post_Interview_Review NATURAL JOIN Interview_Review
                        WHERE(Post_Interview_Review.AccountID = ".$_SESSION['accountID'].")";

                        if($result_int_rev = mysqli_query($conn,$stmt_interview_reviews)){
                          $num_of_interview_reviews = mysqli_num_rows($result_int_rev);
                          printf("Select returned %d rows.\n", $num_of_interview_reviews);
                        }
                        else{
                          printf("Error in Post Interview Review");
                        }
                        if(($num_of_job_reviews + $num_of_interview_reviews) == 0)
                        {
                          echo "No Reviews were found";
                        }
                        if(mysqli_num_rows($result_job_rev) > 0 ){
                          while($row = mysqli_fetch_assoc($result_job_rev)) {
                            $sql = "SELECT * FROM Review WHERE PostID=" . $row['PostID'];
                            $result = mysqli_query($conn, $sql);
                            $row_ = mysqli_fetch_assoc($result);
                            $sql_post = "SELECT * FROM Post WHERE PostID=" . $row['PostID'];
                            $result_post = mysqli_query($conn, $sql_post);
                            $row_p = mysqli_fetch_assoc($result_post);

                            echo '
                            <h5 class="card-title">Review for -company name-</h5>
                            <div class="card text-center">
                              <div class="card-header"> 
                                <ul class="nav nav-tabs card-header-tabs">
                                  <li class="nav-item">
                                   <a class="nav-link active" id="rating-tab" data-toggle="tab" href="#rating" role="tab" aria-controls="rating" aria-selected="true">Rating</a>
                                  </li>
                                  <li class="nav-item">
                                   <a class="nav-link" id="pros-tab" data-toggle="tab" href="#pros" role="tab" aria-controls="pros" aria-selected="false">Pros</a>
                                  </li>
                                  <li class="nav-item">
                                    <a class="nav-link" id="cons-tab" data-toggle="tab" href="#cons" role="tab" aria-controls="cons" aria-selected="false">Cons</a>
                                  </li>
                                  <li class="nav-item">
                                    <a class="nav-link" id="cons-tab" data-toggle="tab" href="#cons" role="tab" aria-controls="comments" aria-selected="false">Comments</a>
                                  </li>
                                  <li class="nav-item">
                                    <a class="nav-link" id="cons-tab" data-toggle="tab" href="#cons" role="tab" aria-controls="cons" aria-selected="false">Cons</a>
                                  </li>
                                </ul>
                              </div>
                              <div class="card-body">
                                <div class="tab-content" id="myTabContent">
                                  <div class="tab-pane fade show active" id="rating"  role="tabpanel" aria-labelledby="rating-tab"> <h2>Overall Rating</h2>
                                    <div class="star-ratings-css">';
                                      $rating = $row_["Rating"]; 
                                      for ($j=0; $j < 5; $j++) { 
                                        if ($j < $rating) {
                                          echo '<span class="fa fa-star checked"></span>';
                                        }
                                        else {
                                          echo '<span class="fa fa-star"></span>';
                                        }   
                                      }
                                      echo 
                                    '</div>
                                  </div>
                                  <div class="tab-pane fade" id="pros" role="tabpanel" aria-labelledby="pros-tab">'.$row_["Pros"].'</div>
                                  <div class="tab-pane fade" id="cons" role="tabpanel" aria-labelledby="cons-tab">'.$row_["Cons"].'</div>
                                  <div class="tab-pane fade" id="comments" role="tabpanel" aria-labelledby="comments-tab">Comments</div>
                                </div>
                              </div>
                            <div class="card-footer text">
                            '.row_p['Creation_Date'].'
                            </div>
                          </div>';
                          }
                        }
                      ?>
                    </div> <!-- row -->
                  </div> <!-- container -->
                </div> <!--tab-->
              <div class="tab-pane fade" id="v-pills-applications" role="tabpanel" aria-labelledby="v-pills-applications-tab">
                <div class="container">
                  <div class="row">
                    <div class="col-lg-7 col-md-6">
                      <h2>Applications</h2>
                    </div>
                  </div>
                </div>
              </div>

              <div class="tab-pane fade" id="v-pills-notifications" role="tabpanel" aria-labelledby="v-pills-notifications-tab">
                <div class="container">
                  <div class="row">
                    <div class="col-lg-7 col-md-6">
                      <h2>Notifications</h2>
                    </div>
                  </div>
                </div>
              </div>


              <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
              	<div class="container">
                  <div class="row">
                  	<div class="col-lg-7 col-md-6"> <h2>Settings</h2> </div>
               		</div>
                </div>
              </div>
            </div> <!--tab-content--> 
          </div> <!--col9---> 
        </div><!-- 119 row -->
      </div><!--  118 container -->
    </section><!-- #about -->
  </body>
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
</html>
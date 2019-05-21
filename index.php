<?php
session_start();
    require 'db_config.php';
    $conn = Connect();?>
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
  <!--==========================
  Header
  ============================-->
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
  </header><!-- #header -->

  <!--==========================
    Intro Section
  ============================-->
  <section id="intro" class="clearfix">
    <div class="container d-flex h-100">
      <div class="row justify-content-center align-self-center">
        <div class="col-md-6 intro-info order-md-first order-last">
          <h2>CIERP<br>Review Companies<span>Find Employees</span></h2>
          <div>
            <a href="#about" class="btn-get-started scrollto">Get Started</a>
          </div>
        </div>
  
        <div class="col-md-6 intro-img order-md-last order-first">
          <img src="img/intro-img.svg" alt="" class="img-fluid">
        </div>
      </div>

    </div>
  </section><!-- #intro -->

  <main id="main">

    <!--==========================
      About Us Section
    ============================-->
    <section id="about">

      <div class="container">
        <div class="row">

          <div class="col-lg-5 col-md-6">
            <div class="about-img">
              <img src="img/about-img.jpg" alt="">
            </div>
          </div>

          <div class="col-lg-7 col-md-6">
            <div class="about-content">
              <h2>About Us</h2>
              <h3>This is a company interview and employment review platform.</h3>
              <p></p>
              <p>Cierp can can help you find the most rewarding career path you can imagine. CIERP provides</p>
              <ul>
                <li><i class="ion-android-checkmark-circle"></i> Employement/Interview Reviews.</li>
                <li><i class="ion-android-checkmark-circle"></i> Keeping a history of your employements.</li>
                <li><i class="ion-android-checkmark-circle"></i> Hosting and being a part of competitions.</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

    </section><!-- #about -->


    <!--==========================
      Services Section
    ============================-->
    <section id="services" class="section-bg">
      <div class="container">

        <header class="section-header">
          <h3>Services</h3>
          <p>Laudem latine persequeris id sed, ex fabulas delectus quo. No vel partiendo abhorreant vituperatoribus.</p>
        </header>

        <div class="row">

          <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-duration="1.4s">
            <div class="box">
              <div class="icon" style="background: #fceef3;"><i class="ion-ios-analytics-outline" style="color: #ff689b;"></i></div>
              <h4 class="title"><a href="">Host Competitions</a></h4>
              <p class="description">
                <h4>10 Companies that host competitions more than average</h4>
              <?php 
              $adv_sql = "SELECT * FROM Company NATURAL JOIN (SELECT CompanyID, COUNT(*) as cnt FROM `Competition` NATURAL JOIN Represents NATURAL JOIN Company GROUP BY CompanyID HAVING cnt > avg(cnt)) AS counts Order By cnt LIMIT 10;";
              $result_adv = mysqli_query($conn, $adv_sql);
              if (mysqli_num_rows($result_adv) > 0) {
                    while($row = mysqli_fetch_assoc($result_adv)) {
                      echo $row['Name'];
                    }
                  }
              ?>

            </p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-duration="1.4s">
            <div class="box">
              <div class="icon" style="background: #fff0da;"><i class="ion-ios-bookmarks-outline" style="color: #e98e06;"></i></div>
              <h4 class="title"><a href="">Higher People</a></h4>
              <h4>10 Companies that hire people at most</h4>
              <p class="description">
                <?php 
                  $sql_hire = "SELECT Name FROM Company NATURAL JOIN (SELECT CompanyID, COUNT(AccountID) AS count FROM Applies NATURAL JOIN Post WHERE Applies.Result = True GROUP BY CompanyID) as cnt ORDER BY count LIMIT 10;";
                  $result_hire = mysqli_query($conn, $sql_hire);
                  if (mysqli_num_rows($result_adv) > 0) {
                    while($row = mysqli_fetch_assoc($result_hire)) {
                      echo $row['Name'];
                    }
                  }
                ?>

              </p>
            </div>
          </div>

          <div class="col-md-6 col-lg-4 wow bounceInUp" data-wow-delay="0.1s" data-wow-duration="1.4s">
            <div class="box">
              <div class="icon" style="background: #e6fdfc;"><i class="ion-ios-paper-outline" style="color: #3fcdc7;"></i></div>
              <h4 class="title"><a href="">Review Companies</a></h4>
              <p class="description">You Can Review Employement and Interview Processes</p>
            </div>
          </div>
        </div>
      </div>
    </section><!-- #services -->
  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
</body>
</html>

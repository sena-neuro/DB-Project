<?php
session_start();
if(isset($_GET['error'])){
  if($_GET['error'] == "email_or_uname"){
    echo "<script type='text/javascript'>alert('No account with that email or username');</script>";
  }
  else if($_GET['error'] == "password"){
    echo "<script type='text/javascript'>alert('You entered wrong password');</script>";
  }
}
;?>
<head>
  <meta charset="utf-8">
  <title>CIERP</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description"æ>

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
</head>

<body>
  <header id="header">

    <div class="container">

      <div class="logo float-left">
        <!-- Uncomment below if you prefer to use an image logo -->
        <h1 class="text-light"><a href="index.php" class="scrollto"><span>CIERP</span></a></h1>
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

        <div class="col-lg-5 col-md-6">
          <div class="about-img">
            <img src="img/about-img.jpg" alt="">
          </div>
        </div>
        <div class="col-lg-7 col-md-6">
          <h2>Login to CERP</h2>
          <div class="card">
          <article class="card-body">
          <h4 class="card-title mb-4 mt-1">Sign in</h4>
             <form action="login_action.php" method="post" role="form">
              <div class="form-group">
                <label>Your email</label>
                  <input name="email_or_uname" class="form-control" placeholder="Email" type="text">
              </div> <!-- form-group// -->
              <div class="form-group">
                <a class="float-right" href="#">Forgot Your Password?</a>
                <label>Your password</label>
                  <input class="form-control" placeholder="******" type="password" name="pass">
              </div> <!-- form-group// --> 
              <div class="form-group"> 
              <div class="checkbox">
                <label> <input type="checkbox"> Save password </label>
              </div> <!-- checkbox .// -->
              </div> <!-- form-group// -->  
              <div class="form-group">
                  <button type="submit" class="btn btn-primary btn-block" name=login-submit> Login  </button>
              </div> <!-- form-group// -->                                                           
          </form>
        </div>
    </div>
  </section>
</body>
</html>
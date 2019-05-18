<?php
session_start();
if(isset($_GET['error'])){
  if($_GET['error'] == "existingUserName"){
    echo "<script type='text/javascript'>alert('That Username is already taken');</script>";
  }
  else if($_GET['error'] == "existingEmail"){
    echo "<script type='text/javascript'>alert('There is account with that email, try signing in.');</script>";
  }
}?>
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

        <div class="col-lg-5 col-md-6">
          <div class="about-img">
            <img src="img/about-img.jpg" alt="">
          </div>
        </div>
        <div class="col-lg-7 col-md-6">
          <h2>Sign Up as a Reviewer</h2>
          <form action="signUp_action.php" method="post" role="form" onSubmit="return checkPassword(this)">
            <div class="form-group">
              <label for="uname"><br>Full Name</br></label>
              <input type="text" placeholder="your name" name="name" >
            </div>
            <div class="form-group">
              <label for="uname"><br>Username</br></label>
              <input type="text" placeholder="user name" name="uname" >
            </div>
            <div class="form-group">
              <label for="email"><br>Email</br></label>
              <input type="email" placeholder="email" name="email" >
            </div>
            <div class="form-group">
              <label for="bdate"><br>Birth Date</br></label>
              <input type="date" placeholder="your birth date" name="bdate" >
            </div>
              <label for="inputPassword" >Password</label>
              <div class="form-group col-sm-6">
                <input type="password" name="password" id="inputPassword" placeholder="Password" >
              </div>
              <div class="form-group col-sm-6">
                <input type="password" id="inputPasswordConfirm" placeholder="Confirm Password">
              </div>
              <div class="text-center"><button type="submit" title="Sign Up" name="signUpSubmitReviewer">Sign Up</button></div>
          </form>
        </div>
    </div>
  </section>
</body>
</html>
<script>
function checkPassword(form) { 
inputPassword = form.inputPassword.value; 
inputPasswordConfirm = form.inputPasswordConfirm.value; 
  if(inputPassword.length<6){
    alert("Password must be at least 6 characters");
    return false;
  }
    // If password not entered 
    if (inputPassword == ''){ 
        alert ("Please enter Password"); 
        return false;
    }

    // If confirm password not entered 
    else if (inputPasswordConfirm == ''){ 
        alert ("Please enter confirm password"); 
        return false;
    }
    // If Not same return False.     
    else if (inputPassword != inputPasswordConfirm) { 
        alert ("\nPasswords did not match: Please try again...") 
        return false; 
    } 
  }
</script>
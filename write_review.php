<?php
session_start();
$cid = $_POST['cid']; # todo: might be problematic
$_SESSION['cid'] = $cid;
?>
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
    <link rel="stylesheet" href="css/ratingstyle.css">
    <link href="css/style.css" rel="stylesheet">

</head>

<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
    $(document).ready(function(){
        // Check Radio-box
        $(".rating input:radio").attr("checked", false);

        $('.rating input').click(function () {
            $(".rating span").removeClass('checked');
            $(this).parent().addClass('checked');
        });

    });
    </script>
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
                <div class="col-lg-7 col-md-6">
                    <h2>Review this company</h2>
                    <form action="review_action.php" method="post" role="form">
                        <div class="form-group">
                            <div class="rating">
                                <span><input type="radio" name="rating" id="str5" value="5"><label class="fa fa-star" for="str5"></label></span>
                                <span><input type="radio" name="rating" id="str4" value="4"><label class="fa fa-star" for="str4"></label></span>
                                <span><input type="radio" name="rating" id="str3" value="3"><label class="fa fa-star" for="str3"></label></span>
                                <span><input type="radio" name="rating" id="str2" value="2"><label class="fa fa-star" for="str2"></label></span>
                                <span><input type="radio" name="rating" id="str1" value="1"><label class="fa fa-star" for="str1"></label></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <br>
                            <label style="display:block" for="anon"><br>Anonimity</label>
                            <input style="display:block" type="checkbox" id="anon" name="anon">
                        </div>
                        <div class="form-group">
                            <label for="pros"><br>Pros</br></label>
                            <textarea placeholder="Pros" id="pros" name="pros"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="cons"><br>Cons</br></label>
                            <textarea placeholder="Cons" id="cons" name="cons"></textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" title="Submit" id="reviewSubmit" name="reviewSubmit">Submit</button>
                            <button type="submit" title="Cancel" id="reviewCancel" name="reviewCancel">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>

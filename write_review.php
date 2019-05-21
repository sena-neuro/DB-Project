<?php
    session_start();
    $cid = $_POST['cid'];
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
                    <?php
                    if (isset($_SESSION['accountID'])) {
                        echo'<li class="active"><a href="account_page.php">My Account</a></li>';
                    }
                    else{
                        echo'<li class="active"><a href="index.php">Home</a></li>';
                    }
                    ?>
                    <li><a href="index.php#about">About Us</a></li>
                    <li><a href="index.php#services">Services</a></li>
                    <li><a href="index.php#team">Team</a></li>
                    <?php 
                    if(isset($_SESSION['accountID'])) {
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
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </header>
    <section id="about">
        <script>
            var rating = 10;
            var review_t = "<?php if (isset($_POST['job_review'])) { echo 'job'; } else { echo 'interview'; } ?>";
            var cid = "<?php echo $cid ?>";
            
            console.log(cid);
            console.log(rating);
            console.log(review_t);
            
            function giveRating(element) {
                id = element.id[element.id.length - 1];
                for (let i = 0; i < 10; i++) {
                    if (i <= id) {
                        document.getElementById("star" + i).style.color = "orange";
                    } else {
                        document.getElementById("star" + i).style.color = "black";
                    }
                }
                rating = parseInt(id) + 1;
            }
            
            function formSubmitted(event) {
                if (cid == null || cid == "") {
                    event.preventDefault();
                    alert("Invalid company.");
                    return;
                }
                
                if (review_t != "job" && review_t != "interview") {
                    event.preventDefault();
                    alert("Invalid review type.");
                    return;
                }
                
                // Cid
                var company_id = document.getElementById("company_id");
                company_id.value = cid;
                
                // Anonimity
                var checkbox = document.getElementById("checkbox");
                var anonymous = document.getElementById("anonymous");
                if (checkbox.checked == true) {
                    anonymous.value = 1;
                } else {
                    anonymous.value = 0;
                }
                
                // Rating
                var rating_ele = document.getElementById("rating");
                rating_ele.value = rating;
                
                // Title
                var title = document.getElementById("title");
                if (title.value.length < 5) {
                    alert("Title length must be at least 6");
                    event.preventDefault();
                    return;
                }
                
                // Description
                var description = document.getElementById("description");
                if (description.value.length < 5) {
                    alert("Description length must be at least 6");
                    event.preventDefault();
                    return;
                }
                
                // Position
                var position = document.getElementById("position");
                
                // Job
                var job = document.getElementById("job");
                
                // Pros & Cons
                var pros = document.getElementById("pros");
                var cons = document.getElementById("cons");
                
                // Review_Type
                var review_type = document.getElementById("review_type");
                review_type.value = review_t;
                
                var workplace = document.getElementById("workplace");
                var coworkers = document.getElementById("coworkers");
                var management = document.getElementById("management");
                
                var salary = document.getElementById("salary");
                
                // Date info
                var d = document.getElementById("date");
                var date = new Date();
                var year = date.getFullYear();
                var month = date.getMonth() + 1;
                var day = date.getDate();
                var finalDate = year + "-" + month + "-" + day;
                d.value = finalDate;
                
                console.log(rating_ele.value);
                console.log(title.value);
                console.log(description.value);
                console.log(position.value);
                console.log(job.value);
                console.log(pros.value);
                console.log(cons.value);
                console.log(salary.value);
                console.log(d.value);
                console.log(anonymous.value);
                console.log(workplace.value);
                console.log(coworkers.value);
                console.log(management.value);
                console.log(company_id.value);
                console.log(review_type.value);
                console.log(document.getElementById("submit-button").value);
            }
        </script>
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-6">
                    <div class="jumbotron w-100">
                        <h2>Review this company</h2>
                        <form method="post" onsubmit="formSubmitted(event);" action="review_action.php">
                            <div class="d-flex flex-column">
                                <h5>Rating:</h5>
                                <div class="w-100 d-flex justify-content-around">
                                    <?php
                                        echo "<p></p><p></p><p></p><p></p>";
                                        for ($i = 0; $i < 10; $i++) {
                                            echo '<span class="fa fa-star" id="star'.$i.'" onclick="giveRating(this);" style="color: orange"></span>';
                                        }
                                        echo "<p></p><p></p><p></p><p></p>";
                                    ?>
                                </div><br>
                                <input type="hidden" id="rating" name="rating" value=""></input>
                                <input type="hidden" id="company_id" name="company_id" value=""></input>
                                <input type="hidden" id="date" name="date" value=""></input>
                                <input type="hidden" id="anonymous" name="anonymous" value=""></input>
                                <div class="checkbox">
                                    <label style="font-size: 20px">Anonymous Post:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkbox" style="display:inline; transform: scale(1.5)"></label>
                                </div><br>
                                <h5>Title:</h5>
                                <input type="text" id="title" name="title" class="form-control" required></input><br>
                                <h5>Description:</h5>
                                <input type="text" id="description" name="description" class="form-control" required></input><br>
                                <h5>Position:</h5>
                                <input type="text" id="position" name="position" class="form-control" required></input><br>
                                <h5>Job:</h5>
                                <input type="text" id="job" name="job" class="form-control" required></input><br>
                                <h5>Pros:</h5>
                                <textarea class="form-control" id="pros" name="pros" rows="5" required></textarea><br>
                                <h5>Cons:</h5>
                                <textarea class="form-control" id="cons" name="cons" rows="5" required></textarea><br>
                                <input type="hidden" id="review_type" name="review_type" value=""></input>
                                <?php
                                    if (isset($_POST["job_review"])) {
                                        echo "<h5>Workplace:</h5>";
                                        echo '<textarea class="form-control" id="workplace" name="workplace" rows="5"></textarea><br>';
                                        echo "<h5>Coworkers:</h5>";
                                        echo '<textarea class="form-control" id="coworkers" name="coworkers" rows="5"></textarea><br>';
                                        echo "<h5>Management:</h5>";
                                        echo '<textarea class="form-control" id="management" name="management" rows="5"></textarea><br>';
                                    }
                                ?>
                                <h5>Salary:</h5>
                                <div class="form-row" role="group">
                                    <div class="form-group col-10">
                                        <input type="number" id="salary" name="salary" class="form-control" placeholder="5000" required>
                                    </div>
                                      
                                    <div class="form-group col">
                                        <select class="form-control" id="currency-select">
                                            <option>$</option>
                                            <option>€</option>
                                            <option>TL</option>
                                        </select>
                                    </div>
                                </div><br>
                                <input type="submit" id="submit-button" class="btn btn-primary" name="Submit" value="Submit"></input>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>

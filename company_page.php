<?php
    session_start();
    
    include("db_config.php");
    $sql_conn = Connect();
    
    if (isset($_POST["cid"])) {
        $comp_id = $_POST["cid"];
    }

    if (isset($_POST["address"]) && !empty($_POST["address"])) {
        $comp_id = $_POST["address"];
        $comp_address = "";
        $query = "SELECT Address_Street_Name, Address_Office_No, Address_Zipcode, Address_City, Address_Country
                  FROM Company
                  WHERE CompanyID = $comp_id";

        $result = $sql_conn->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $comp_address = "St: " . $row["Address_Street_Name"] . ", No: " . $row["Address_Office_No"] . ", Zipcode: " . $row["Address_Zipcode"] . ", " . $row["Address_City"] . "/" . $row["Address_Country"];
        } else {
            // No address info
        }
        echo $comp_address;
        exit();
    } else if (isset($_POST["geninfo"]) && !empty($_POST["geninfo"])) {
        $comp_id = $_POST["geninfo"];
        $comp_name = "";
        $comp_info = "";
        $query = "SELECT Name, Company_Info, Est_date
                  FROM Company
                  WHERE CompanyID = $comp_id";

        $result = $sql_conn->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $comp_name = "About " . $row["Name"];
            $comp_info = $row["Company_Info"];
        } else {
            $comp_info = "Sorry, we do not have information about this company!";
        }

        echo $comp_name . ":" . $comp_info;
        exit();
    } else if (isset($_POST["repr"]) && !empty($_POST["repr"])) {
        $representatives = [];
        $comp_id = $_POST["repr"];
        $query = "SELECT Name_first_name, Name_second_name, Contact_Info
                  FROM (Account NATURAL JOIN Comp_Rep) NATURAL JOIN Represents
                  WHERE CompanyID = $comp_id";

        $result = $sql_conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($representatives, array($row["Name_first_name"], $row["Name_second_name"], $row["Contact_Info"]));
            }
        } else {
            // No representatives
        }

        echo json_encode($representatives);
        exit();
    } else if (isset($_POST["follows"]) && !empty($_POST["follows"])) {
        $followers = [];
        $comp_id = $_POST["follows"];
        $query = "SELECT Name_first_name, Name_second_name, Username
                  FROM Account NATURAL JOIN Follows
                  WHERE CompanyID = ?";
        $stmt = mysqli_stmt_init($sql_conn);
        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, "i", $comp_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($followers, array($row["Name_first_name"], $row["Name_second_name"], $row["Username"]));
            }
        } else {
            // No followers
        }
        
        echo json_encode($followers);
        exit();
    } else if (isset($_POST["joboffer"]) && !empty($_POST["joboffer"])) {
        $joboffers = [];
        $comp_id = $_POST["joboffer"];
        $query = "SELECT Title, Description, Job_Type, Salary, Quota
                  FROM Post NATURAL JOIN Company NATURAL JOIN Job_Offering
                  WHERE CompanyID = ?";
                  
        $stmt = mysqli_stmt_init($sql_conn);
        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, "i", $comp_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($joboffers, array($row["Title"], $row["Description"], $row["Job_Type"], $row["Salary"], $row["Quota"]));
            }
        } else {
            // No job offers
        }
        
        echo json_encode($joboffers);
        exit();
    } else if (isset($_POST["review"]) && !empty($_POST["review"])) {
        $reviews = [];
        $comp_id = $_POST["review"];
        $query = "SELECT *
                  FROM Post NATURAL JOIN Review NATURAL JOIN Company
                  WHERE CompanyID = ?";
                  
        $stmt = mysqli_stmt_init($sql_conn);
        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, "i", $comp_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($reviews, array($row["Anonimity"], $row["Rating"], $row["Pros"], $row["Cons"]));
            }
        } else {
            // No job offers
        }
        
        echo json_encode($reviews);
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

    <!-- Main Stylesheet File -->
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


    <style>
        #mynav {
            width: 100%;
            color: #333333;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #mynav li {
            margin-left: 35px;
            margin-right: 35px;
        }

        ul li {
            list-style-type: none;
        }
    </style>
</head>

<body>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
        
        var cid = "<?php echo $comp_id ?>";
        
        function loadGeneralInfo(element) {
            var header = document.getElementById("company_info_header");
            var info = document.getElementById("company_info");
            var url = window.location.href;
            url = url.substring(url.lastIndexOf("/")+1);
            
            $.ajax({
                type: "POST",
                url: url,
                data: {geninfo: cid},
                success: function(res) {
                    head = res.substring(0, res.indexOf(":"));
                    header.innerHTML = head;

                    inf = res.substring(res.indexOf(":")+1);
                    info.innerHTML = inf;
                }
            });
        }
        
        function loadFollowers(element) {
            var followers = document.getElementById("company_followers");
            var url = window.location.href;
            url = url.substring(url.lastIndexOf("/")+1);
            
            $.ajax({
                type: "POST",
                url: url,
                data: {follows: cid},
                success: function(res) {
                    flws = JSON.parse(res);
                    
                    followers.innerHTML = "";
                    for (flw of flws) {
                        followers.innerHTML += "Full Name: " + flw[0] + " " + flw[1] + ", Username: " + flw[2] + "<br>";
                    }
                    
                    if (flws.length == 0) {
                        followers.innerHTML = "Sorry, this company has no followers.";
                    }
                }
            });
        }
        
        function loadRepresentatives(element) {
            var representatives = document.getElementById("company_representatives");
            var url = window.location.href;
            url = url.substring(url.lastIndexOf("/")+1);
            
            $.ajax({
                type: "POST",
                url: url,
                data: {repr: cid},
                success: function(res) {
                    console.log(res);
                    reps = JSON.parse(res);
                       
                    representatives.innerHTML = "";
                    for (rep of reps) {
                        representatives.innerHTML += "Full Name: " + rep[0] + " " + rep[1] + ", Phone: " + rep[2] + "<br>";
                    }
                    
                    if (reps.length == 0) {
                        representatives.innerHTML = "Sorry, there are no representatives for this company."
                    }
                }
            });
        }
        
        function loadReviews(element) {
            var reviews = document.getElementById("company_reviews");
            var url = window.location.href;
            url = url.substring(url.lastIndexOf("/")+1);
            
            $.ajax({
                type: "POST",
                url: url,
                data: {review: cid},
                success: function(res) {
                    rews = JSON.parse(res);
                    
                    reviews.innerHTML = "";
                    for (rew of rews) {
                        if (rew[0] == 1) {
                            reviews.innerHTML += "<p>Anonymous Post</p>";  
                        } else {
                            reviews.innerHTML += "<p>Poster: </p>";  
                        }
                        reviews.innerHTML += "<p>Rating: " + rew[1] + "</p>";
                        reviews.innerHTML += "<p>Pros: " + rew[2] + "</p>";
                        reviews.innerHTML += "<p>Cons: " + rew[3] + "</p>";
                    }
                    
                    if (rews.length == 0) {
                        reviews.innerHTML = "Sorry, there are no reviews for this company.";
                    }
                }
            });
        }
        
        function loadJobOfferings(element) {
            var jobofferings = document.getElementById("company_jobofferings");
            var url = window.location.href;
            url = url.substring(url.lastIndexOf("/")+1);
            
            $.ajax({
                type: "POST",
                url: url,
                data: {joboffer: cid},
                success: function(res) {
                    offers = JSON.parse(res);
                    
                    jobofferings.innerHTML = "";
                    for (offer of offers) {
                        jobofferings.innerHTML += "<h5>" + offer[0] + "</h5>";
                        jobofferings.innerHTML += "<p>" + offer[1] + "</p>";
                        jobofferings.innerHTML += "<p>Job Type: " + offer[2] + "</p>";
                        jobofferings.innerHTML += "<p>Salary: " + offer[3] + "</p>";
                        jobofferings.innerHTML += "<p>Quota: " + offer[4] + "</p><br>";
                    }
                    
                    if (offers.length == 0) {
                        jobofferings.innerHTML = "Sorry, this company has no job offers.";
                    }
                }
            });
        }
        
        function loadContactInfo(element) {
            var contact = document.getElementById("company_contact");
            var url = window.location.href;
            url = url.substring(url.lastIndexOf("/")+1);
            
            $.ajax({
                type: "POST",
                url: url,
                data: {address: cid},
                success: function(res) {
                    contact.innerHTML = res;
                }
            });
        }

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
              <li class="active"><a href="#intro">Home</a></li>
              <li><a href="account_page.php">My Account</a></li>
              <li><a href="#services">Companies</a></li>
              <li><a href="#team">Jobs</a></li>
              <li><a href="#">Interviews</a></li>
            </ul>
        </nav><!-- .main-nav -->
    </header>
    <section id="about">
        <div class="container">
            <div class="col-md-6 intro-info order-md-first order-last">
                <?php
                    $sql_conn = Connect();
                    $comp_id = $_POST["cid"];
                    $comp_name = "";
                    $comp_info = "";
                    $representatives = [];

                    $query = "SELECT Name, Company_Info, Est_date
                              FROM Company
                              WHERE CompanyID = $comp_id";

                    $result = $sql_conn->query($query);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $comp_name = $row["Name"];
                        $comp_info = $row["Company_Info"];
                    } else {
                        $comp_info = "Sorry, we do not have information about this company!";
                    }

                    $query = "SELECT Address_Street_Name, Address_Office_No, Address_Zipcode, Address_City, Address_Country
                              FROM Company
                              WHERE CompanyID = $comp_id";

                    $result = $sql_conn->query($query);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $comp_address = "St: " . $row["Address_Street_Name"] . ", No: " . $row["Address_Office_No"] . ", Zipcode: " . $row["Address_Zipcode"] . ", " . $row["Address_City"] . "/" . $row["Address_Country"];
                    } else {
                        // No address info
                    }

                    echo "<h2 style='font-size: 48px;font-weight: 700;'>$comp_name</h2>";
                ?>
            </div>
            <div class="container">
                <div class="row">
                    <div class="nav flex-row nav-pills" id="v-pills-tab" role="tablist" aria-orientation="horizontal">
                      <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" 
                      href="#geninfo" onclick="loadGeneralInfo(this);" role="tab" aria-controls="v-pills-home" aria-selected="true">General Information</a>
                      
                      <a class="nav-link" id="v-pills-employment-tab" data-toggle="pill" 
                      href="#followers" onclick="loadFollowers(this);" role="tab" aria-controls="v-pills-employment" aria-selected="false">Followers</a>
                      
                      <a class="nav-link" id="v-pills-reviews-tab" data-toggle="pill" 
                      href="#representatives" onclick="loadRepresentatives(this);" role="tab" aria-controls="v-pills-reviews" aria-selected="false">Representatives</a>
                      
                      <a class="nav-link" id="v-pills-applications-tab" data-toggle="pill" 
                      href="#notifications" role="tab" aria-controls="v-pills-applications" aria-selected="false">Notifications</a>
                      
                      <a class="nav-link" id="v-pills-notifications-tab" data-toggle="pill" 
                      href="#reviews" onclick="loadReviews(this);" role="tab" aria-controls="v-pills-notifications" aria-selected="false">Reviews</a>
                      
                      <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" 
                      href="#jobofferings" onclick="loadJobOfferings(this);" role="tab" aria-controls="v-pills-settings" aria-selected="false">Job Offerings</a>
                      
                      <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" 
                      href="#contact" onclick="loadContactInfo(this);" role="tab" aria-controls="v-pills-settings" aria-selected="false">Contact</a>
                    </div>
                </div>
            
                <div class="tab-content">
                    <div class="tab-pane fade container show active" id="geninfo" role="tabpanel">
                        <div style="margin-top: 40px;">
                            <div class="jumbotron">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h1 id="company_info_header"><?php echo "About $comp_name</h1>"; ?></h1>
                                        <p id="company_info"><?php echo $comp_info ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade container" id="followers" role="tabpanel">
                        <div style="margin-top: 40px;">
                            <div class="jumbotron">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h1 id="company_followers_header">Followers</h1>
                                        <p id="company_followers"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade container" id="representatives" role="tabpanel">
                        <div style="margin-top: 40px;">
                            <div class="jumbotron">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h1 id="company_representatives_header">Representatives</h1>
                                        <p id="company_representatives"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade container" id="notifications" role="tabpanel">
                        <div style="margin-top: 40px;">
                            <div class="jumbotron">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <?php echo "<h1 id='company_header'>About $comp_name</h1>"; ?>
                                        <p id="company_info"><?php echo $comp_info ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade container" id="reviews" role="tabpanel">
                        <div style="margin-top: 40px;">
                            <div class="jumbotron">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h1>Reviews</h1>
                                        <p id="company_reviews"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade container" id="jobofferings" role="tabpanel">
                        <div style="margin-top: 40px;">
                            <div class="jumbotron">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h1 id="company_jobofferings_header">Job Offerings</h1>
                                        <p id="company_jobofferings"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade container" id="contact" role="tabpanel">
                        <div style="margin-top: 40px;">
                            <div class="jumbotron">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <h1 id="company_contact_header">Contact Info</h1>
                                        <p id="company_contact"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>

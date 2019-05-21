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
        $query = "SELECT Title, Description, Job_Type, Salary, Quota, PostID
                  FROM Post NATURAL JOIN Company NATURAL JOIN Job_Offering
                  WHERE CompanyID = ?";
                  
        $stmt = mysqli_stmt_init($sql_conn);
        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, "i", $comp_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($joboffers, array($row["Title"], $row["Description"], $row["Job_Type"], $row["Salary"], $row["Quota"], $row["PostID"]));
            }
        } else {
            // No job offers
        }
        
        echo json_encode($joboffers);
        exit();
    } else if (isset($_POST["review"]) && !empty($_POST["review"])) {
        $reviews = [];
        
        $job_reviews = [];
        $comp_id = $_POST["review"];
        $query = "Select Title, Anonimity, Rating, Pros, Cons, Comments_Workplace, Comments_Coworkers, Comments_Management, Employee.Position, Job_Review.Salary, Username, Creation_Date
                  From Job_Review Natural Join Review, Post Natural Join Company, Employee Natural Join Account Natural Join Post_Job_Review
                  Where Post_Job_Review.PostID = Job_Review.PostID AND Job_Review.PostID = Post.PostID AND CompanyID = ?";
                  
        $stmt = mysqli_stmt_init($sql_conn);
        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, "i", $comp_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $cols = array_keys($row);
                $result_array = [];
                foreach ($cols as &$value) {
                    array_push($result_array, $row[$value]);
                }
                array_push($job_reviews, $result_array);
            }
        } else {
            // No job offers
        }
        
        $interview_reviews = [];
        $query = "Select Title, Anonimity, Rating, Pros, Cons, Offered_salary, Username, Creation_Date
                  From Interview_Review Natural Join Review, Post Natural Join Company, Interviewee Natural Join Account Natural Join Post_Interview_Review
                  Where Post_Interview_Review.PostID = Interview_Review.PostID AND Interview_Review.PostID = Post.PostID AND CompanyID = ?";
                  
        $stmt = mysqli_stmt_init($sql_conn);
        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, "i", $comp_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $cols = array_keys($row);
                $result_array = [];
                foreach ($cols as &$value) {
                    array_push($result_array, $row[$value]);
                }
                array_push($interview_reviews, $result_array);
            }
        } else {
            // No job offers
        }
        
        array_push($reviews, $job_reviews);
        array_push($reviews, $interview_reviews);
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
        var aid = "<?php echo $_SESSION['accountID']; ?>";
        
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
                    var rews = JSON.parse(res);
                    
                    // JOB REVIEWS
                    var innerHTMLString1 = createHTMLStringForJobReview(rews[0]);
                    var innerHTMLString2 = createHTMLStringForInterviewReview(rews[1]);
                    
                    var finalString = innerHTMLString1 + "<br>" + innerHTMLString2;
                    reviews.innerHTML = finalString;
                    
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
                    var innerHTMLString = "";
                    for (offer of offers) {
                        innerHTMLString += "<h5>" + offer[0] + "</h5>";
                        innerHTMLString += "<p>" + offer[1] + "</p>";
                        innerHTMLString += "<p>Job Type: " + offer[2] + "</p>";
                        innerHTMLString += "<p>Salary: " + offer[3] + "</p>";
                        innerHTMLString += "<p>Quota: " + offer[4] + "</p>";
                        innerHTMLString += "<form method='post' action='jobapply_action.php'>";
                        innerHTMLString += "<input type='submit' value='Apply for Job!' class='btn btn-primary'></input>";
                        innerHTMLString += "<input type='hidden' name='pid' value='" + offer[5] + "'></input>";
                        innerHTMLString += "<input type='hidden' name='aid' value='" + aid + "'></input>";
                        innerHTMLString += "</form><br>";
                    }
                    jobofferings.innerHTML = innerHTMLString;
                    
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
        
        function setCompanyID(event) {
            document.getElementById("hiddenCompanyId").value = cid;
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
        </div>
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
                    <div class="nav flex-row nav-pills nav-justified" id="v-pills-tab" role="tablist" aria-orientation="horizontal">
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
                                        <div class="d-flex justify-content-around" style="width: 980px; position: absolute; top: -30px;">
                                            <p></p>
                                            <form method="post" onsubmit="setCompanyID(event);" action="write_review.php">
                                                <input type="submit" name="job_review" class="btn btn-outline-primary" value="Write Job Review"></input>
                                                <input type="submit" name="interview_review" class="btn btn-outline-info" value="Write Interview Review"></input>
                                                <input type="hidden" name="cid" id="hiddenCompanyId" value="">
                                            </form>
                                            <p></p>
                                        </div>
                                        <h1>Reviews</h1>
                                        <div id="company_reviews"></div>
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
    
    <script>
        function createHTMLStringForJobReview(parseArray) {
            var index = 0;
            var innerHTMLString = "";
            if (parseArray.length != 0) {
                innerHTMLString += "<h2>Job Reviews</h2>";
            }
            innerHTMLString += "<div id='review_accordion' style='width: 980px'>";
            for (rew of parseArray) {
                console.log(rew);
                innerHTMLString +=  "<div class='card'>";
                innerHTMLString +=      "<div class='card-header'>";
                innerHTMLString +=          "<a class='card-link' data-toggle='collapse' href='#collapse" + index + "'>";
                innerHTMLString +=              rew[0];
                innerHTMLString +=          "</a>";
                innerHTMLString +=      "</div>";
                innerHTMLString +=      "<div id='collapse" + index + "' class='collapse' data-parent='#review_accordion'>";
                innerHTMLString +=          "<div class='card-body'>";
                innerHTMLString +=              "<div class='card'>";
                innerHTMLString +=                  "<div class='card-header'>";
                innerHTMLString +=                      "<ul class='nav nav-tabs card-header-tabs'>";
                innerHTMLString +=                          "<li class='nav-item'>";
                innerHTMLString +=                              "<a class='nav-link active' id='rating" + index + "-tab' data-toggle='tab' href='#rating"  + index + "' role='tab' aria-controls='rating" + index + "' aria-selected='true'>Rating</a>";
                innerHTMLString +=                          "</li>";
                innerHTMLString +=                          "<li class='nav-item'>";
                innerHTMLString +=                              "<a class='nav-link' id='pros" + index + "-tab' data-toggle='tab' href='#pros"  + index + "' role='tab' aria-controls='pros" + index + "' aria-selected='false'>Pros</a>";
                innerHTMLString +=                          "</li>";
                innerHTMLString +=                          "<li class='nav-item'>";
                innerHTMLString +=                              "<a class='nav-link' id='cons" + index + "-tab' data-toggle='tab' href='#cons"  + index + "' role='tab' aria-controls='cons" + index + "' aria-selected='false'>Cons</a>";
                innerHTMLString +=                          "</li>";
                innerHTMLString +=                          "<li class='nav-item'>";
                innerHTMLString +=                              "<a class='nav-link' id='comments_workplace" + index + "-tab' data-toggle='tab' href='#comments_workplace"  + index + "' role='tab' aria-controls='comments_workplace" + index + "' aria-selected='false'>Comments on Workplace</a>";
                innerHTMLString +=                          "</li>";
                innerHTMLString +=                          "<li class='nav-item'>";
                innerHTMLString +=                              "<a class='nav-link' id='comments_coworkers" + index + "-tab' data-toggle='tab' href='#comments_coworkers"  + index + "' role='tab' aria-controls='comments_coworkers" + index + "' aria-selected='false'>Comments on Coworkers</a>";
                innerHTMLString +=                          "</li>";
                innerHTMLString +=                          "<li class='nav-item'>";
                innerHTMLString +=                              "<a class='nav-link' id='comments_management" + index + "-tab' data-toggle='tab' href='#comments_management"  + index + "' role='tab' aria-controls='comments_management" + index + "' aria-selected='false'>Comments on Management</a>";
                innerHTMLString +=                          "</li>";
                innerHTMLString +=                      "</ul>";
                innerHTMLString +=                  "</div>";
                innerHTMLString +=                  "<div class='card-body'>";
                innerHTMLString +=                      "<div class='tab-content'>";
                innerHTMLString +=                          "<div class='tab-content'>";
                innerHTMLString +=                              "<div class='tab-pane fade container show active text-center' id='rating" + index + "' role='tabpanel'>";
                                                                if (rew[1] == 1) {
                innerHTMLString +=                                  "<h5>Anonymous Review</h5>";
                                                                } else {
                innerHTMLString +=                                  "<h5>Review by " + rew[10] + "</h5>";
                                                                }
                innerHTMLString +=                                  "<h5>Position: " + rew[8] + "</h5>";
                innerHTMLString +=                                  "<h5>Salary: " + rew[9] + "$</h5>";                   
                innerHTMLString +=                                  "<br><h4>Overall Rating</h4>";
                                                                for (let i = 0; i < 10; i++) {
                                                                    if (i < parseInt(rew[2])) {
                innerHTMLString +=                                  "<span class='fa fa-star' style='color: orange'></span>";
                                                                    } else {
                innerHTMLString +=                                  "<span class='fa fa-star'></span>";
                                                                    }
                                                                }
                innerHTMLString +=                                  "<br><br><h5> Posted: " + rew[11] + "</h5>";
                innerHTMLString +=                              "</div>";
                innerHTMLString +=                              "<div class='tab-pane fade container' id='pros" + index + "' role='tabpanel'>";
                innerHTMLString +=                                  "<p>" + rew[3] + "</p>";
                innerHTMLString +=                              "</div>";
                innerHTMLString +=                              "<div class='tab-pane fade container' id='cons" + index + "' role='tabpanel'>";
                innerHTMLString +=                                  "<p>" + rew[4] + "</p>";
                innerHTMLString +=                              "</div>";
                innerHTMLString +=                              "<div class='tab-pane fade container' id='comments_workplace" + index + "' role='tabpanel'>";
                innerHTMLString +=                                  "<p>" + rew[5] + "</p>";
                innerHTMLString +=                              "</div>";
                innerHTMLString +=                              "<div class='tab-pane fade container' id='comments_coworkers" + index + "' role='tabpanel'>";
                innerHTMLString +=                                  "<p>" + rew[6] + "</p>";
                innerHTMLString +=                              "</div>";
                innerHTMLString +=                              "<div class='tab-pane fade container' id='comments_management" + index + "' role='tabpanel'>";
                innerHTMLString +=                                  "<p>" + rew[7] + "</p>";
                innerHTMLString +=                              "</div>";
                innerHTMLString +=                          "</div>";
                innerHTMLString +=                      "</div>";
                innerHTMLString +=                  "</div>";
                innerHTMLString +=              "</div>";
                innerHTMLString +=          "</div>";
                innerHTMLString +=      "</div>";
                innerHTMLString +=  "</div>";
                
                index++;
            }
            innerHTMLString += "</div>";
            return innerHTMLString;
        }
        
        function createHTMLStringForInterviewReview(parseArray) {
            var index = -1;
            var innerHTMLString = "";
            if (parseArray.length != 0) {
                innerHTMLString += "<h2>Interview Reviews</h2>";
            }
            innerHTMLString += "<div id='review_accordion' style='width: 980px'>";
            for (rew of parseArray) {
                console.log(rew);
                innerHTMLString +=  "<div class='card'>";
                innerHTMLString +=      "<div class='card-header'>";
                innerHTMLString +=          "<a class='card-link' data-toggle='collapse' href='#collapse" + index + "'>";
                innerHTMLString +=              rew[0];
                innerHTMLString +=          "</a>";
                innerHTMLString +=      "</div>";
                innerHTMLString +=      "<div id='collapse" + index + "' class='collapse' data-parent='#review_accordion'>";
                innerHTMLString +=          "<div class='card-body'>";
                innerHTMLString +=              "<div class='card'>";
                innerHTMLString +=                  "<div class='card-header'>";
                innerHTMLString +=                      "<ul class='nav nav-tabs card-header-tabs'>";
                innerHTMLString +=                          "<li class='nav-item'>";
                innerHTMLString +=                              "<a class='nav-link active' id='rating" + index + "-tab' data-toggle='tab' href='#rating"  + index + "' role='tab' aria-controls='rating" + index + "' aria-selected='true'>Rating</a>";
                innerHTMLString +=                          "</li>";
                innerHTMLString +=                          "<li class='nav-item'>";
                innerHTMLString +=                              "<a class='nav-link' id='pros" + index + "-tab' data-toggle='tab' href='#pros"  + index + "' role='tab' aria-controls='pros" + index + "' aria-selected='false'>Pros</a>";
                innerHTMLString +=                          "</li>";
                innerHTMLString +=                          "<li class='nav-item'>";
                innerHTMLString +=                              "<a class='nav-link' id='cons" + index + "-tab' data-toggle='tab' href='#cons"  + index + "' role='tab' aria-controls='cons" + index + "' aria-selected='false'>Cons</a>";
                innerHTMLString +=                          "</li>";
                innerHTMLString +=                      "</ul>";
                innerHTMLString +=                  "</div>";
                innerHTMLString +=                  "<div class='card-body'>";
                innerHTMLString +=                      "<div class='tab-content'>";
                innerHTMLString +=                          "<div class='tab-content'>";
                innerHTMLString +=                              "<div class='tab-pane fade container show active text-center' id='rating" + index + "' role='tabpanel'>";
                                                                if (rew[1] == 1) {
                innerHTMLString +=                                  "<h5>Anonymous Review</h5>";
                                                                } else {
                innerHTMLString +=                                  "<h5>Review by " + rew[6] + "</h5>";
                                                                }
                innerHTMLString +=                                  "<h5>Offered Salary: " + rew[5] + "$</h5>";                   
                innerHTMLString +=                                  "<br><h4>Overall Rating</h4>";
                                                                for (let i = 0; i < 10; i++) {
                                                                    if (i < parseInt(rew[2])) {
                innerHTMLString +=                                  "<span class='fa fa-star' style='color: orange'></span>";
                                                                    } else {
                innerHTMLString +=                                  "<span class='fa fa-star'></span>";
                                                                    }
                                                                }
                innerHTMLString +=                                  "<br><br><h5> Posted: " + rew[7] + "</h5>";
                innerHTMLString +=                              "</div>";
                innerHTMLString +=                              "<div class='tab-pane fade container' id='pros" + index + "' role='tabpanel'>";
                innerHTMLString +=                                  "<p>" + rew[3] + "</p>";
                innerHTMLString +=                              "</div>";
                innerHTMLString +=                              "<div class='tab-pane fade container' id='cons" + index + "' role='tabpanel'>";
                innerHTMLString +=                                  "<p>" + rew[4] + "</p>";
                innerHTMLString +=                              "</div>";
                innerHTMLString +=                          "</div>";
                innerHTMLString +=                      "</div>";
                innerHTMLString +=                  "</div>";
                innerHTMLString +=              "</div>";
                innerHTMLString +=          "</div>";
                innerHTMLString +=      "</div>";
                innerHTMLString +=  "</div>";
                
                index--;
            }
            innerHTMLString += "</div>";
            return innerHTMLString;
        }
    </script>
</body>
</html>

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
  
  if (isset($_POST["searched"]) && !empty($_POST["searched"])) {
      $searchval = $_POST["searched"];
      $searchval = "%" . $searchval . "%";
      $query = 'SELECT CompanyID, Name
                FROM Company
                WHERE Name LIKE ?';
                
      $stmt = mysqli_stmt_init($conn);
      
      if(!mysqli_stmt_prepare($stmt, $query)){
          echo("Error description: " . mysqli_error($conn));
          header("Location: index.php?error=sqlerror");
          exit();
      }
      
      mysqli_stmt_bind_param($stmt, 's', $searchval);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
      
      $matching_companies = [];
      if ($result->num_rows > 0) {
          while($row = mysqli_fetch_assoc($result)) {
              array_push($matching_companies, array($row["CompanyID"], $row["Name"]));
          }
      }
      
      echo json_encode($matching_companies);
      exit();
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
        
        .scrollable-menu {
            height: auto;
            max-height: 300px;
            overflow-x: hidden;
        }
      </style>
    </head>
    
  <body>
    <header id="header">

      <div class="container">

        <div class="logo float-left">
          <!-- Uncomment below if you prefer to use an image logo -->
          <h1 class="text-light"><a href="index.php" class="scrollto"><span>CIERP</span></a></h1>
          <!-- <a href="#header" class="scrollto"><img src="img/logo.png" alt="" class="img-fluid"></a> -->
        </div>
        
      <div class="input-group mb-3" style="display:inline-block; width: 400px;">
        <div class="dropdown">
            <form method="post" action="company_page.php" onsubmit="submitted(event);">
                <input type="text" autocomplete="off" oninput="inputReceived(this);" id="search-bar" class="form-control dropdown-toggle" data-toggle="dropdown" size="50" placeholder="Search a company" name="search-bar" style="display: inline-block; width: 400px; margin-left:50px;">
                <ul id="search-bar-dropdown" class="dropdown-menu scrollable-menu" style="padding-left: 15px; padding-right: 15px">
                </ul>
                <span id="errorSpanSearchBar" style="color: red; margin-left: 55px;"></span>
                <input type="hidden" name="cid" id="hiddenCompanyId" value="">
            </form>
        </div>
      </div>
      
      <script>
        var curId = -1;
        
        function submitted(event) {
            console.log(curId);
            
            var span = document.getElementById("errorSpanSearchBar");
            if (curId == -1) {
                span.innerHTML = "Please select a company from the list*";   

                if ($('.dropdown').find('.dropdown-menu').is(":visible")){
                    $('#search-bar-dropdown').hide();
                }
                event.preventDefault();
                
            } else {
                span.innerHTML = "";
                
                var hiddenCompanyId = document.getElementById("hiddenCompanyId");
                hiddenCompanyId.value = curId;
                
                /*var url = window.location.href;
                url = url.substring(0, url.lastIndexOf("/"));
                url = url + "/company_page.php?id=" + curId;
                window.location = url;*/
            }
        }
        
        function inputReceived(searchbar) {
            curId = -1;
            
            if ($('#search-bar-dropdown').is(":hidden")){
                $('#search-bar-dropdown').show();
            }
            
            if (searchbar.value != "") {
                dropdown = document.getElementById("search-bar-dropdown");
                while(dropdown.firstChild) {
                    dropdown.removeChild(dropdown.firstChild);
                }
                
                var url = window.location.href;
                url = url.substring(url.lastIndexOf("/")+1);
                
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {searched: searchbar.value},
                    success: function(res) {
                        results = JSON.parse(res);
                        for (let i = 0; i < results.length; i++) {          
                            let li = document.createElement("li");
                            li.innerHTML = results[i][1];
                            li.onclick = function() {
                                searchbar.value = li.innerHTML;
                                curId = results[i][0];
                                
                                if ($('#search-bar-dropdown').is(":visible")){
                                    $('#search-bar-dropdown').hide();
                                }
                                searchbar.focus();
                            };
                            dropdown.appendChild(li);
                        }
                    }
                });
            }
        }
      </script>

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
        <div class="row"> <div class="col-5"> <h2>Welcome <?php echo $_SESSION['fname'] . " " . $_SESSION['lname']; ?></h2> </div> </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
              <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">General Information</a>
              <a class="nav-link" id="v-pills-employment-tab" data-toggle="pill" href="#v-pills-employment" role="tab" aria-controls="v-pills-employment" aria-selected="false">Employment Details</a>
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
                        <?php
                          $bd_query = "SELECT * FROM Account WHERE AccountID=".$_SESSION['accountID'];
                          $bd_res = mysqli_query($conn, $bd_query);
                          if (mysqli_num_rows($bd_res) > 0) {
                            // output data of each row
                            while($row = mysqli_fetch_assoc($bd_res)) {
                                
                              $bdate = $row['Birthdate'];
                              $age = date_diff(date_create($bdate), date_create('now'))->y;
                            }
                          } else {
                            $age = "Not stated";
                          }
                          $experience_sql = "WITH Difference AS ( SELECT (End_Date - Start_Date) as Diff 
                                            FROM Work_For
                                            WHERE AccountID = ".$_SESSION['accountID'].")
                                            SELECT SUM(Diff)
                                            FROM Difference";
                          // ADD LATER
                        ?>
                        <h2>General Information</h2>
                        <h5>Age: <?php echo $age?></h5>
                        <h5>Experience: </h5>
                        <h5>Account Type: <?php echo $account_type?></h5>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="tab-pane fade" id="v-pills-employment" role="tabpanel" aria-labelledby="v-pills-employment-tab">
                <div class="container">
                  <div class="row">
                    <div class="col-lg-10 col-md-6">
                      <h2>Employment Details</h2>

                      <div class="accordion" id="accordionExample">
                        <div class="card">
                          <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                              <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Add previous employment

                              </button>
                            </h2>
                          </div>

                          <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body">
                              <h5 class="card-title">You can add your previous employments from here.</h5>
                              <p class="card-text">Please keep in mind that your data will be shared publicly.</p>
                              <form action="employment_action.php" method="post" role="form">
                                <div class="form-group">
                                  <label>Job Title</label>
                                  <input type="text" class="form-control" placeholder="Senior Software Engineer" aria-label="Job Title" name="job_title" aria-describedby="basic-addon1">
                                </div>
                                <div class="form-group">
                                  <label>Company</label>
                                  <select class="form-control" id="companySelect" name="company">
                                    <?php  
                                      $company_query = "SELECT Name From Company";
                                      $comp_res = mysqli_query($conn,$company_query);
                                      if (mysqli_num_rows($comp_res) > 0) {
                                        // output data of each row
                                        while($row = mysqli_fetch_assoc($comp_res)) {
                                          echo "<option>".$row["Name"]."</option>";
                                        }
                                      }
                                    ?>
                                    
                                  <select>
                                </div>
                                <div class="form-group">
                                  <label>Start Date</label>
                                  <input class="form-control" type="start-date" value="2011-08-19" name="sdate" id="example-date-input">
                                </div>
                                <div class="form-group">
                                  <label>End Date</label>
                                  <input class="form-control" type="start-date" value="2011-08-19" name="edate" id="example-date-input">
                                </div>
                                <label>Monthly Salary</label>
                                <div class="form-row" role="group">
                                  <div class="form-group col-10">
                                    <input type="number" class="form-control" placeholder="5000" name="salary" id="salary">
                                  </div>
                                  
                                  <div class="form-group col">
                                    <select class="form-control" name="currency" id="currency-select">
                                      <option>$</option>
                                      <option>€</option>
                                      <option>TL</option>
                                    </select>
                                  </div>

                                </div>
                                <div class="form-group">
                                  <label>Location</label>
                                  <input type="text" class="form-control" placeholder="San Francisco, CA" name="location" aria-label="location" aria-describedby="basic-addon1">
                                </div>
                                <button type="submit" class="btn btn-primary" name="employment-submit">Submit</button>
                              </form>
                            </div>
                          </div>
                        </div>
                        <!---------------------------------------------------------------------------------------->
                        <div class="card">
                          <div class="card-header" id="headingTwo">
                            <h2 class="mb-0">
                              <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Old Employments
                              </button>
                            </h2>
                          </div>
                          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                            <div class="card-body">
                              
                              <div class="container">
                                <div class="row">
                                  <?php 
                                  $comp_sql = "SELECT * FROM Work_For WHERE AccountID=".$_SESSION['accountID'];
                                  $comp_result = mysqli_query($conn, $comp_sql);
                                  if(mysqli_num_rows($comp_result) > 0) {
                                    while($comp_row = mysqli_fetch_assoc($comp_result)) {
                                      $comp_name_sql = "SELECT * FROM Company WHERE CompanyID=".$comp_row['CompanyID'];
                                      $comp_name_result = mysqli_query($conn, $comp_name_sql);
                                      $comp_name_row = mysqli_fetch_assoc($comp_name_result);
                                      echo "<div class='col'>Company Name</div>
                                           <div class='col'>Start Date</div>
                                           <div class='col'>End Date</div>
                                          <div class='w-100'></div>
                                          <div class='col'>".$comp_name_row['Name']."</div>
                                          <div class='col'>".$comp_row['Start_Date']."</div>
                                          <div class='col'>".$comp_row['End_Date']."</div>";
                                    }
                                  } else {
                                    echo "No previous employment";
                                  } 
                                  ?>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="tab-pane fade" id="v-pills-reviews" role="tabpanel" aria-labelledby="v-pills-reviews-tab">
                <div class="container">
                  <div class="row">
                    <div class="col-lg-7 col-md-6"><h2>Reviews</h2> </div>
                    <?php
                        if($account_type == "Reviewer"){

                          // if reviewer get number of job reviews
                          $stmt_rev_post = "SELECT *
                          FROM Post_Job_Review NATURAL JOIN Post
                          WHERE Post_Job_Review.AccountID = ".$_SESSION['accountID']." 
                          UNION 
                          SELECT *
                          FROM Post_Interview_Review NATURAL JOIN Post
                          WHERE Post_Interview_Review.AccountID = ".$_SESSION['accountID'];

                          if($result_post = mysqli_query($conn,$stmt_rev_post)){
                            $num_of_rev_post= mysqli_num_rows($result_post);
                            //printf("Select returned %d rows.\n", $num_of_job_reviews);
                          } 
                          else{
                            printf("Error in Post Job Review");
                          }
                          if( $num_of_rev_post == 0)
                          {
                            echo "No Reviews were found";
                          }
                        }
                        elseif ($account_type == "Representative") 
                        {
                          // if representative get number of posts to their company
                          $stmt_post ="SELECT *
                          FROM Post
                          WHERE(CompanyID = ".$_SESSION['companyID'].")";

                          if($result_post= mysqli_query($conn,$stmt_post)){
                            $num_of_posts = mysqli_num_rows($result_post);
                            //printf("Select returned %d rows.\n", $num_of_job_reviews);
                          } 
                          else{
                            printf("Error in Review");
                          }
                          if($num_of_posts == 0){
                            echo "No Posts About Your Company were found";
                          }
                          else{
                            printf("Error in Post Interview Review");
                          }
                        }
                        while($row = mysqli_fetch_assoc($result_post)) 
                        {
                          $stmt_interview_reviews ="SELECT *
                          FROM Review
                          WHERE(PostID = ".$row['PostID'].")";
                          $res_rew = mysqli_query($conn,$res_rew);
                          $row_rew = mysqli_fetch_assoc($res_rew);

                          if(mysqli_num_rows($res_rew) > 0){
                            $sql_jb = "SELECT * FROM Job_Review WHERE PostID=" . $row['PostID'];
                            $result_jb = mysqli_query($conn, $sql_jb);
                            if(mysqli_num_rows($result_jb) >=0){
                               $row_ = mysqli_fetch_assoc($result_jb);
                               $type = "jb";
                            }
                            else{
                              $sql_int = "SELECT * FROM Interview_Review WHERE PostID=" . $row['PostID'];
                              $result_int = mysqli_query($conn, $sql_int);
                              $row_ = mysqli_fetch_assoc($result_int);
                              $type = "int";
                            }
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
                                    </li>';
                                    if($type == "jb"){
                                      echo '<li class="nav-item">
                                        <a class="nav-link" id="Comments_Workplace-tab" data-toggle="tab" href="#Comments_Workplace" role="tab" aria-controls="Comments_Workplace" aria-selected="false">Workplace Comments</a>
                                      </li>
                                      <li class="nav-item">
                                        <a class="nav-link" id="Comments_Coworkers-tab" data-toggle="tab" href="#Comments_Coworkers" role="tab" aria-controls="Comments_Coworkers" aria-selected="false">Coworkers Comments</a>
                                      </li>
                                      <li class="nav-item">
                                        <a class="nav-link" id="Comments_Management-tab" data-toggle="tab" href="#Comments_Management" role="tab" aria-controls="Comments_Management" aria-selected="false">Management Comments</a>
                                      </li>';
                                  }
                                  else
                                  {
                                    echo '<li class="nav-item">
                                        <a class="nav-link" id="questions-tab" data-toggle="tab" href="#questions" role="tab" aria-controls="questions" aria-selected="false">Questions</a>
                                      </li>';
                                  }
                                  echo '</ul>
                                </div>
                                <div class="card-body">
                                  <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="rating"  role="tabpanel" aria-labelledby="rating-tab"> <h2>Overall Rating</h2>
                                      <div class="star-ratings-css">';
                                        $rating = $row_rew["Rating"]; 
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
                                    <div class="tab-pane fade" id="pros" role="tabpanel" aria-labelledby="pros-tab">'.$row_rew["Pros"].'</div>
                                    <div class="tab-pane fade" id="cons" role="tabpanel" aria-labelledby="cons-tab">'.$row_rew["Cons"].'</div>';
                                    if($type == "jb"){
                                      echo '<div class="tab-pane fade" id="Comments_Workplace" role="tabpanel" aria-labelledby="Comments_Workplace-tab">'
                                        .$row_['Comments_Workplace'].
                                      '</div>';
                                      echo '<div class="tab-pane fade" id="Comments_Coworkers" role="tabpanel" aria-labelledby="Comments_Coworkers-tab">'
                                        .$row_['Comments_Coworkers'].
                                      '</div>';
                                      echo '<div class="tab-pane fade" id="Comments_Management" role="tabpanel" aria-labelledby="Comments_Management-tab">'
                                        .$row_['Comments_Management'].
                                      '</div>';
                                    }
                                    else{
                                      // Get questions using has
                                      echo '<div class="tab-pane fade" id="questions" role="tabpanel" aria-labelledby="questions-tab">Questions</div>';
                                    }
                                  echo '</div>
                                </div>
                              <div class="card-footer text">
                              '.$row['Creation_Date'].'
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
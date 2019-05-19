<?php
    session_start();
    
    include("dbconfig.php");
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
        $comp_id = $_POST["repr"];
        $query = "SELECT First_Name, Last_Name, Contact_Info
                  FROM Account NATURAL JOIN Comp_Rep
                  WHERE Comp_Rep.CompanyID = $comp_id";

        $result = $sql_conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($representatives, array($row["First_Name"], $row["Last_Name"], $row["Contact_Info"]));
            }
        } else {
            // No representatives
        }

        echo json_encode($representatives);
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
        
        function changeContent(element, type) {
            header = document.getElementById("company_header");
            info = document.getElementById("company_info");
            var url = window.location.href;
            url = url.substring(url.lastIndexOf("/")+1);

            if (type == 0) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {geninfo: cid},
                    success: function(res) {
                        console.log(res);
                        head = res.substring(0, res.indexOf(":"));
                        header.innerHTML = head;

                        inf = res.substring(res.indexOf(":")+1);
                        info.innerHTML = inf;
                    }
                });
            } else if (type == 1) {
                
            } else {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {repr: cid},
                    success: function(res) {
                        console.log(res);
                        head = res.substring(0, res.indexOf(":"));
                        header.innerHTML = head;

                        inf = res.substring(res.indexOf(":")+1);
                        info.innerHTML = inf;
                    }
                });
            }
        }

        function showContactInfo(element) {
            header = document.getElementById("company_header");
            info = document.getElementById("company_info");

            var url = window.location.href;
            url = url.substring(url.lastIndexOf("/")+1);
            $.ajax({
                type: "POST",
                url: url,
                data: {address: cid},
                success: function(res) {
                    info.innerHTML = res;
                }
            });

            header.innerHTML = "Contact Info";
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
              <li><a href="#about">Profile</a></li>
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
                    include("dbconfig.php");
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

            <nav style="margin-top: 20px" id="mynav" class="main-nav">
                <ul>
                    <li class="nav-item dropdown active">
                      <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                        About
                      </a>
                      <div class="dropdown-menu">
                        <a class="dropdown-item active" href="#" onclick="changeContent(this, 0);">General Information</a>
                        <a class="dropdown-item" href="#" onclick="changeContent(this, 1);">Departments</a>
                        <a class="dropdown-item" href="#" onclick="changeContent(this, 2);">Representatives</a>
                      </div>
                    </li>
                    <li><a href="#intro">Notifications</a></li>
                    <li><a href="#about">Reviews</a></li>
                    <li><a href="#services">Job Offerings</a></li>
                    <li><a href="#" onclick="showContactInfo(this)">Contact</a></li>
                </ul>
            </nav><!-- .main-nav -->

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
    </section>
</body>
</html>

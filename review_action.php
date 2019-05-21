<?php
    session_start();
    include('db_config.php');
            
    if (isset($_POST['Submit']) && isset($_POST['rating']) && isset($_POST['title']) && isset($_POST['description']) &&
        isset($_POST['position']) && isset($_POST['job']) && isset($_POST['pros']) && isset($_POST['cons']) &&
        isset($_POST['salary']) && isset($_POST['date']) && isset($_POST["review_type"]) && isset($_POST["anonymous"]) &&
        isset($_POST["company_id"])) {
        
        $conn = Connect();
        $query = "INSERT INTO Post(CompanyID, Title, Description, Creation_Date, Position, Job_Type)
                  VALUES(?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, "isssss", $_POST["company_id"], $_POST["title"], $_POST["description"], $_POST["date"], $_POST["position"], $_POST["job"]);
        mysqli_stmt_execute($stmt);
        $post_id = mysqli_insert_id($conn);
        
        $query = "INSERT INTO Review(PostID, Anonimity, Rating, Pros, Cons) 
                  VALUES(?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, "iiiss", $post_id, $_POST["anonymous"], $_POST["rating"], $_POST["pros"], $_POST["cons"]);
        mysqli_stmt_execute($stmt);
        
        if ($_POST["review_type"] == "job") {
            if (isset($_POST["workplace"]) && isset($_POST["coworkers"]) && isset($_POST["management"])) {
                $query = "INSERT INTO Job_Review(PostID, Comments_Workplace, Comments_Coworkers, Comments_Management, Position, Salary)
                          VALUES(?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $query);
                mysqli_stmt_bind_param($stmt, "issssi", $post_id, $_POST["workplace"], $_POST["coworkers"], $_POST["management"], $_POST["position"], $_POST["salary"]);
                mysqli_stmt_execute($stmt);
                
                // Check if User that posted a job review is in Employee's table.
                $query = "SELECT AccountID FROM Employee WHERE AccountID = ?";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $query);
                mysqli_stmt_bind_param($stmt, "i", $_SESSION["AccountID"]);
                $result = mysqli_stmt_execute($stmt);
                
                // User isn't in Employee's table.
                if ($result->num_rows == 0) {
                    $query = "INSERT INTO Employee(AccountID, Salary, Position) VALUES(?, ?, ?)";
                    $stmt = mysqli_stmt_init($conn);
                    mysqli_stmt_prepare($stmt, $query);
                    mysqli_stmt_bind_param($stmt, "iis", $_SESSION["AccountID"], $_POST["salary"], $_POST["position"]);
                    mysqli_stmt_execute($stmt);
                }
                
                // Now add to Post_Job_Review
                $query = "INSERT INTO Post_Job_Review(PostID, AccountID) VALUES(?, ?)";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $query);
                mysqli_stmt_bind_param($stmt, "ii", $post_id, $_SESSION["AccountID"]);
                mysqli_stmt_execute($stmt);
            } else {
                header("Location: account_page.php");
            }
        } else if ($_POST["review_type"] == "interview") {
            $query = "INSERT INTO Interview_Review(PostID, Offered_salary)
                      VALUES(?, ?)";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $query);
                mysqli_stmt_bind_param($stmt, "ii", $post_id, $_POST["salary"]);
                mysqli_stmt_execute($stmt);
                
                // Check if User that posted an interview review is in Interviewee's table.
                $query = "SELECT AccountID FROM Interviewee WHERE AccountID = ?";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $query);
                mysqli_stmt_bind_param($stmt, "i", $_SESSION["AccountID"]);
                $result = mysqli_stmt_execute($stmt);
                
                // User isn't in Interviewee's table.
                if ($result->num_rows == 0) {
                    $query = "INSERT INTO Interviewee(AccountID) VALUES(?)";
                    $stmt = mysqli_stmt_init($conn);
                    mysqli_stmt_prepare($stmt, $query);
                    mysqli_stmt_bind_param($stmt, "i", $_SESSION["AccountID"]);
                    mysqli_stmt_execute($stmt);
                }
                
                // Now add to Post_Interview_Review
                $query = "INSERT INTO Post_Interview_Review(PostID, AccountID) VALUES(?, ?)";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $query);
                mysqli_stmt_bind_param($stmt, "ii", $post_id, $_SESSION["AccountID"]);
                mysqli_stmt_execute($stmt);
        } else {
            header("Location: account_page.php");
        }
        
        header("Location: account_page.php");
    } else {
    }
    
?>

<?php
    session_start();
    include('db_config.php');
    
    if (isset($_POST["aid"]) && isset($_POST["pid"])) {
        $conn = Connect();
        $zero = 0;
        $query = "INSERT INTO Applies(AccountID, PostID, Result)
                  VALUES(?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt, $query);
        mysqli_stmt_bind_param($stmt, "iii", $_POST["aid"], $_POST["pid"], $zero);
        mysqli_stmt_execute($stmt);
        
        header("Location: account_page.php");
    }
    
?>
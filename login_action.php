<?php
if(isset($_POST['login-submit'])){
	require 'db_config.php';
	$conn = Connect();
	$email_or_uname = $_POST['email_or_uname'];
	$pass = $_POST['pass'];
	$sql_u = "SELECT * FROM Account WHERE Username=? OR Email=?";
	$stmt = mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmt,$sql_u)){
		echo("Error description: " . mysqli_error($conn));
		header("Location: index.php?error=sqlerror");
		exit();
	}
	else{
		mysqli_stmt_bind_param($stmt,"ss",$email_or_uname,$email_or_uname);
		mysqli_stmt_execute($stmt);
		$res = mysqli_stmt_get_result($stmt);
		if($row = mysqli_fetch_assoc($res)){
			$passCheck = password_verify($pass, $row['Password']);
			if($passCheck==true){
				$accountID = mysqli_insert_id($conn);
				session_start();
                $_SESSION['fname'] = $row['Name_first_name'];
                $_SESSION['lname'] = $row['Name_second_name'];
				$_SESSION['uname'] = $row['Username'];
				$_SESSION['accountID'] = $row['AccountID'];
				$stmt_rep ="SELECT *
                          FROM Represents 
                          WHERE AccountID = ".$row['AccountID'];
                          if($result_post = mysqli_query($conn,$stmt_post)){
                            	$row = mysqli_fetch_assoc($result_post))
                            	$_SESSION['companyID'] = $row['CompanyID'];
                          } 
                          else{
                            header("Location: index.php?error=sqlerror");
                          }
				header("Location: account_page.php");
				exit();
			}
			else{
				header("Location: login.php?error=password");
				exit();
			} 
			
		}
		else
		{
			header("Location: login.php?error=email_or_uname");
			exit();
		}
	}
}
else{
	header("Location: index.php?aaa");
	exit();
}

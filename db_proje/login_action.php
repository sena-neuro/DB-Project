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
				$_SESSION['uname'] = $uname;
				$_SESSION['accountID'] = $accountID;
				header("Location: account_page.php");
				exit();
			}
			else{
				echo $pass;
				echo 'alert("Wrong password")';
				#header("Location: login.php?error=wrongPassword");
				#exit();
			} 
			
		}
		else
		{
			echo 'alert("No users with specified Email or Username")';
			header("Location: login.php?error=email_or_uname");
			exit();
		}
	}
}
else{
	header("Location: index.php?aaa");
	exit();
}

<?php
if(isset($_POST['signUpSubmitRepresentative']) || isset($_POST['signUpSubmitReviewer'])){
	require 'db_config.php';
	$conn = Connect();
	$email = $_POST['email'];
	$fname = $_POST['name'];
	$uname = $_POST['uname'];
	$bdate = $_POST['bdate'];
	$pass = $_POST['password'];
	$branch =$_POST['branch'];
	$companyName = $_POST['companyName'];
	$jobTitle = $_POST['jobTitle'];
	$contact_info =  $_POST['contact_info'];
	$fname = trim($fname);
	if(isset($_POST['signUpSubmitRepresentative'])){
		$prevLoc = "signUp_rep.php";
	}
	else
		$prevLoc = "reviewerSignUp.php";
	// Translate full name to first and last names
    $last_name = (strpos($fname, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $fname);
    $first_name = trim( preg_replace('#'.$last_name.'#', '', $fname ));


    // check if there is any account with the name
	$sql_u = "SELECT * FROM Account WHERE Username=?";
	$stmt_u = mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmt_u,$sql_u)){
		header("Location: ".$prevLoc."?error=sqlerroraccount");
		exit();
	}
	else{
		mysqli_stmt_bind_param($stmt_u,"s",$uname);
		mysqli_stmt_execute($stmt_u);
		$res = mysqli_stmt_get_result($stmt_u);
		if($row = mysqli_fetch_assoc($res)){
			header("Location: ".$prevLoc."?error=existingUserName");
			exit();
		}
	}

	// check if there is any account with the username
	$sql_e = "SELECT * FROM Account WHERE email=?";
	$stmt_e = mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmt_e,$sql_e)){
		echo "2";
		header("Location: ".$prevLoc."?error=sqlerroremail");
		exit();
	}
	else{
		mysqli_stmt_bind_param($stmt_e,"s",$email);
		mysqli_stmt_execute($stmt_e);
		$res = mysqli_stmt_get_result($stmt_e);
		if($row = mysqli_fetch_assoc($res)){
			header("Location: ".$prevLoc."?error=existingEmail");  # maybe redirect to login
			exit();
		}
	}

	if(isset($_POST['signUpSubmitRepresentative'])){
		// Check if there is a company with company name
		$sql_c= "SELECT * FROM Company WHERE Name=?";
		$stmt_c = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt_c,$sql_c)){
			header("Location: ".$prevLoc."?error=compsqlerror");
			exit();
		}
		else{
			mysqli_stmt_bind_param($stmt_c,"s",$companyName);
			mysqli_stmt_execute($stmt_c);
			$res = mysqli_stmt_get_result($stmt_c);
			
			if(!($row = mysqli_fetch_assoc($res))){
				// there is no a company with specified company name.
				header("Location: ".$prevLoc."?error=NoCompany");
				exit();
			}
			$companyID = $row['CompanyID'];
		}
	}
	// insert into account
	$sql_a = "INSERT INTO Account (Email,Username,Password,Name_first_name,Name_second_name,Birthdate)
	VALUES (?, ?, ?, ?, ?, ?)";
	$stmt_a = mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmt_a,$sql_a)){
		header("Location: ".$prevLoc."?error=sqlerrorAccountInsert");
		exit();
	}

	$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
	mysqli_stmt_bind_param($stmt_a,"ssssss",$email,$uname,$hashed_pass,$first_name,$last_name,$bdate);
	mysqli_stmt_execute($stmt_a);
	$accountID = mysqli_insert_id($conn);
	if(isset($_POST['signUpSubmitRepresentative'])){
		// If the account is  a representative account Insert into representative table

		$sql = "INSERT INTO Comp_Rep(AccountID, CompanyID, Branch, Contact_Info, Job_Title)
		VALUES (?,?, ?, ?, ?)";
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt,$sql)){
			header("Location: ".$prevLoc."?error=sqlerrorRepInsert");
			exit();
		}
		mysqli_stmt_bind_param($stmt,"iisss",$accountID, $companyID ,$branch,$contact_info,$jobTitle);
		$res = mysqli_stmt_execute($stmt);
		$_SESSION['companyID'] = $companyID;
	}
	elseif (isset($_POST[''])) {
		$sql_us = "INSERT INTO User(AccountID) VALUES (?)";
		$stmt_us = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt_us,$sql_us)){
			header("Location: ".$prevLoc."?error=sqlerrorUserInsert");
			exit();
		}
		mysqli_stmt_bind_param($stmt_us,"i",$accountID);
		$res = mysqli_stmt_execute($stmt_us);
	}		
	session_start();
    $_SESSION['fname'] = $first_name;
    $_SESSION['lname'] = $last_name;
	$_SESSION['uname'] = $uname;
	$_SESSION['accountID'] = $accountID;
	header("Location: account_page.php");
	exit();
}
else{
	header("Location: index.php?aaa");
	exit();
}

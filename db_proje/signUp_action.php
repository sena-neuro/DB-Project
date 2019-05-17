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
    $last_name = (strpos($fname, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $fname);
    $first_name = trim( preg_replace('#'.$last_name.'#', '', $fname ));

	$sql_u = "SELECT * FROM Account WHERE Username=?";
	$stmt = mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmt,$sql_u)){
		echo "1";
		echo("Error description: " . mysqli_error($conn));
		#header("Location: index.php?error=sqlerror1");
		exit();
	}
	else{
		mysqli_stmt_bind_param($stmt,"s",$uname);
		mysqli_stmt_execute($stmt);
		$res = mysqli_stmt_get_result($stmt);
		if($row = mysqli_fetch_assoc($res)){
			echo "alert('This username is already taken')";
			header("Location: signUp_rep.php");
			exit();
		}
	}
	$sql_e = "SELECT * FROM Account WHERE email=?";
	$stmt = mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmt,$sql_e)){
		echo "2";
		header("Location: signUp_rep.php?error=sqlerror2");
		exit();
	}
	else{
		mysqli_stmt_bind_param($stmt,"s",$email);
		mysqli_stmt_execute($stmt);
		$res = mysqli_stmt_get_result($stmt);
		if($row = mysqli_fetch_assoc($res)){
			echo "alert('There is an account with this email, try loggin in.')";
			header("Location: signUp_rep.php?existingEmail");  # maybe redirect to login
			exit();
		}
	}
	$sql = "INSERT INTO Account (Email,Username,Password,Name_first_name,Name_second_name,Birthdate)
	VALUES (?, ?, ?, ?, ?, ?)";
	$stmt = mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmt,$sql)){
		header("Location: signUp_rep.php?error=sqlerror3");
		exit();
	}
	else{
		$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
		mysqli_stmt_bind_param($stmt,"ssssss",$email,$uname,$hashed_pass,$first_name,$last_name,$bdate);
		$res = mysqli_stmt_execute($stmt);
		mysqli_stmt_get_result($stmt);
		if($res){
			$sql_u = "SELECT * FROM Company WHERE Name=?";
				$stmt = mysqli_stmt_init($conn);
				if(!mysqli_stmt_prepare($stmt,$sql_u)){
					#echo("Error description: " . mysqli_error($conn));
					header("Location: index.php?error=sqlerror5");
					exit();
				}
				else{
					mysqli_stmt_bind_param($stmt,"s",$companyName);
					mysqli_stmt_execute($stmt);
					$res = mysqli_stmt_get_result($stmt);
				}
			if(isset($_POST['signUpSubmitRepresentative'])){
				#Insert into representative table
				$sql = "INSERT INTO Comp_Rep(Company_ID, Branch, Contact_Info, Job_Title)
				VALUES (?, ?, ?, ?)";
				$stmt = mysqli_stmt_init($conn);
				if(!mysqli_stmt_prepare($stmt,$sql)){
					header("Location: signUp_rep.php?error=sqlerror4");
					exit();
				}
			}
			else{
				$accountID = mysqli_insert_id($conn);
				session_start();
				$_SESSION['uname'] = $uname;
				$_SESSION['accountID'] = $accountID;
				mysqli_stmt_bind_param($stmt,"isss",$branch,$contact_info,$jobTitle,$companyId);
				$res = mysqli_stmt_execute($stmt);
				mysqli_stmt_get_result($stmt);
				header("Location: account_page.php");
				exit();
			}
		}
	}
}
else{
	header("Location: index.php?aaa");
	exit();
}

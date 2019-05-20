<?php  

	if(isset($_POST['employment-submit'])) {
		require 'db_config.php';
		$conn = Connect();
		session_start();
		$job_title = $_POST['job_title'];
		$company = $_POST['company'];
		$start_date = $_POST['sdate'];
		$end_date = $_POST['edate'];
		$salary = $_POST['salary'];
		$currency = $_POST['currency'];
		$location = $_POST['location'];

		$cid_query = "SELECT CompanyID FROM Company WHERE Name='".$company."'";
		$cid_res = mysqli_query($conn, $cid_query);
		if($cid_res) {
			if($cid_row = mysqli_fetch_assoc($cid_res)) {
				$cid = $cid_row['CompanyID'];
				echo $cid;
				echo "-----";
				echo $_SESSION['accountID'];
				echo "-----";
				echo $salary;
				echo "-----";
				echo $job_title;
				
				$emp_insert_query = "INSERT INTO Employee (AccountID, Salary, Position) VALUES (?, ?, ?)";	
				$stmt = mysqli_stmt_init($conn);
				if(!mysqli_stmt_prepare($stmt, $emp_insert_query)){
					header("Location: account_page.php?error=sqlerrorEmploymentInsert");
					exit();
				}
				mysqli_stmt_bind_param($stmt,"iis", $_SESSION['accountID'], $salary, $job_title);
				$res = mysqli_stmt_execute($stmt);
				if($res === false){
					echo "why?";
				}

				$wf_insert_query = "INSERT INTO Work_For (CompanyID, AccountID, Start_Date, End_Date) VALUES (?, ?, ?, ?)";	
				$stmt_wf = mysqli_stmt_init($conn);
				if(!mysqli_stmt_prepare($stmt_wf, $wf_insert_query)){
					header("Location: account_page.php?error=sqlerrorWFInsert");
					exit();
				}
				mysqli_stmt_bind_param($stmt_wf,"iiss", $cid, $_SESSION['accountID'], $start_date, $end_date);
				mysqli_stmt_execute($stmt_wf);
			}
		}
	}
	mysqli_close($conn);
	//header("Location: account_page.php#v-pills-employment");
	exit();
?>
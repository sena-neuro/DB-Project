<?php
session_start();

if (isset($_POST['reviewSubmit']) && isset($_POST['rating']) && isset($_POST['pros']) && isset($_POST['cons'])) {
	require 'db_config.php';
	$conn = Connect();
    $rating = (float)$_POST['rating'];
    $pros = $_POST['pros'];
    $cons = $_POST['cons'];
    $anon = false;
    $cid = $_SESSION['cid'];
    $date = date("Y-m-d");

    if (isset($_POST['anon'])) {
        $anon = true;
    }

    $insert_to_post = "INSERT INTO Post(CompanyID, Title, Description, Creation_Date, Position, Job_Type)
    VALUES($cid, 'Review', null, '$date', null, null)";

    if (!mysqli_query($conn, $insert_to_post)) {
        echo("Error description: " . mysqli_error($conn));
		header("Location: company_page.php");
		exit();
    }

    $last_id = $conn->insert_id;

    $insert_to_review = "INSERT INTO Review(PostID, Anonimity, Rating, Pros, Cons)
    VALUES($last_id, '$anon', $rating, '$pros', '$cons')";

    if (!mysqli_query($conn, $insert_to_review)) {
        echo("Error description: " . mysqli_error($conn));
		header("Location: company_page.php");
		exit();
    }
}

header("Location: company_page.php");
exit();
?>

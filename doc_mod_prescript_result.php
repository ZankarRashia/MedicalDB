<?php
	include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php";
?>

<html>
<head>
<title>Modify Prescription</title>
</head>
<body>

<link rel="stylesheet" type = "text/css" href="css/doc_portal_style.css" />
<nav>
    <p>Logged in as <?php echo $_SESSION['Name'] ?></p>
        <ul>
            <li><a href="doc_portal.php">Home</a></li>
            <li><a href="doc_appointments.php">View Upcoming Appointments</a></li>
            <li><a href="doc_patients.php">Check Your Patients Files</a></li>
            <li><a href="doc_prescript.php">Write Prescription</a></li>
            <li><a href="doc_reports.php">Demographic Reports </a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
</nav>
<br>

<?php

            $Prescript_Name = mysqli_escape_string($conn, $_POST['Prescript_Name']);
            $Dosage = mysqli_escape_string($conn, $_POST['Dosage']);

            mysqli_query($conn, "UPDATE Prescriptions SET Prescript_Name='".$Prescript_Name."', Dosage='".$Dosage."', Refill='".$_POST['Refill']."', Expiration_date='".$_POST['Expiration_date']."' WHERE Prescript_ID=".$_POST['Prescript_ID'].";") or die(mysqli_error($conn));

            record_action("Doctor", $_SESSION['User_ID'], "Modified Record", $_POST['Prescript_ID']);

            echo "<center>The record was successfully updated!</center><br><br>";

?>
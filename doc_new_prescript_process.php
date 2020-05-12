<?php
	include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php";
?>

<html>
<head>
<title>New Prescription Written</title>
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
<center>

<?php

    $Prescript_Name = mysqli_escape_string($conn, $_POST['Prescript_Name']);
    $Dosage = mysqli_escape_string($conn, $_POST['Dosage']);

    mysqli_query($conn, "INSERT INTO Prescriptions VALUES (NULL, '".$Prescript_Name."','".$Dosage."','".$_POST['Refill']."',".$_SESSION['User_ID'].",".$_POST['Patient'].",'".$_POST['Expiration_date']."');") or die(mysqli_error($conn));
    $pres_ID = mysqli_insert_id($conn);

    record_action("Doctor", $_SESSION['User_ID'], "Prescription Written", $pres_ID);

    echo "The prescription was successfully written!";

?>

</center>

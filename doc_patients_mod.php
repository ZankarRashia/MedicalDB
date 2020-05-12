<?php
	include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php";
?>

<html>
<head>
<title>Patient Records</title>
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
<center>
<?php

    $sql_doc = mysqli_query($conn, "SELECT * FROM Doctor_patient WHERE PID=".$_POST['PID']." AND NPI=".$_SESSION['User_ID'].";") or die(mysqli_error($conn));

    $check = "";

    if ($sql_doc) {
        $check = "Yes";
    } else {
        $check = "No";
    }

?>

<form action='doc_patients_mod_process.php' method='POST'>
<?php mod_patient($_POST['PID']); ?>
Is this patient one of your patients? (Current Value: <?php echo $check ?>) <br>
<label for=0>Yes</label>
<input type="radio" name="add_to_patients" value=0 required><br>
<label for=1>No</label>
<input type="radio" name="add_to_patients" value=1><br><br>
<input type='submit' value='Finish changes'>
</form>
</center>
<br><br>



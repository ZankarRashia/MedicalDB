<?php
    include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php";
?>

<html>
<head>
	<title>Delete Appointment</title>
</head>
<body>
<link rel="stylesheet" type = "text/css" href="css/patient_portal_style.css" />
<nav> 
    <p>Logged in as <?php echo $_SESSION['Name'] ?></p>
        <ul>
                <li><a href="patient_portal.php">Home</a></li>
                <li><a href="patient_info.php">View Medical Information</a></li>
                <li><a href="patient_prescript.php">View Prescriptions</a></li>
                <li><a href="patient_appointments_portal.php">Book Appointment</a></li>
                <li><a href="logout.php">Logout</a></li>
        </ul>
</nav>
<br>

<center>
<?php

    mysqli_query($conn, "DELETE FROM Appointments WHERE Appt_ID=".$_POST['Appt_ID'].";") or die(mysqli_error($conn));

    echo "Appointment deleted successfully!<br>";

?>
</center>
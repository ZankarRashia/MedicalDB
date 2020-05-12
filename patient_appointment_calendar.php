<?php
    include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php";
?>

<html>
<head>
	<title>Appointment Calendar</title>
</head>
<body>
<script>
function confirm_delete() {
    var x = confirm("Are you sure you want to delete this record?");

    if (x)
        return true;
    else
        return false;
}
</script>
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

<h3> Your Currently Scheduled Appointments </h3>

<?php gen_apmt_calendar($_SESSION['User_ID']) ?><br>

</center>

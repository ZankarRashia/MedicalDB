<?php
	include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php";
?>

<html>
<head>
<title>Doctor Portal</title>
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
<center>You are currently logged in as <?php echo $_SESSION['Name']." (".$_SESSION['Specialization'].")" ?><br>
        Your NPI is <?php echo $_SESSION['User_ID'] ?><br>
        You are logged in as a <?php echo $_SESSION['User_Type'] ?><br>
</center>
</body>
</html>

<?php
    include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php";
?>

<html>
<head>
    <title>Appointments Portal</title>
</head>
<body>
<link rel="stylesheet" type = "text/css" href="css/nurse_portal_style.css" />
<nav> 
        <p>Logged in as <?php echo $_SESSION['Name'] ?></p>
        <ul>
                <li><a href="nurse_portal.php">Home</a></li>
                <li><a href="nurse_patient_info.php">Search Patient Info</a></li>
                <li><a href="nurse_appointments_portal.php">Appointments Portal</a></li>
                <li><a href="logout.php">Logout</a></li>
        </ul>
</nav>
<br>
<center>
<h2>Schedule a New Appointment: </h2><br>
<form action="nurse_appointments_portal_process.php" method="POST">
<label for="PID">Enter the Patient's PID: </label>
<input type="text" minlength="6" maxlength="6" name="PID" required><br><br>
<input type="radio" id="gp" value=0 name="app_choice">
<label for="gp">General Practitioner</label><br>
<input type="radio" id="specialist" value=1 name="app_choice">
<label for="specialist">Specialist</label><br><br>
<input type="submit" name="submit" value="Submit"><br><br>
</form>

<a href="nurse_mod_appointments.php">Modify an existing appointment</a>
</center>
</body>
</html>

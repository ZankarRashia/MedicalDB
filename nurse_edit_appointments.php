<?php
    include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php";
?>

<html>
<head>
	<title>Medical Info</title>
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
<form action="nurse_edit_appointments_process.php" method="POST">
<?php mod_apmt($_POST['Appt_ID']) ?>
<input type='submit' value='Save changes'>
</form>
</center>

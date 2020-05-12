<?php
	include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php";
?>

<html>
<head>
<title>Prescriptions Portal</title>
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
<h2>Prescriptions Portal</h2>

Enter Prescription ID of the record you want to edit:<br><br>

<form action='doc_mod_prescript.php' method="POST">
<input type="text" name="Prescript_ID">
<input type="submit" value="search">
</form><br><br>

<?php gen_mod_prescript_list($_SESSION['User_ID']) ?><br>

<form action='doc_new_prescript.php' method='POST'>
<input type='submit' value='Write New Prescription'>
</form><br>


</center>

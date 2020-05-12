<?php
    include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php"
?>

<html>
<head>
<title>Modify Patient Record</title>
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

<link rel="stylesheet" type = "text/css" href="css/admin_portal_style.css" />
<nav> 
        <p>Logged in as <?php echo $_SESSION['Name'] ?></p>
        <ul>

                <li><a href="admin_portal.php">Home</a>
                <li><a href="admin_mod_portal.php">Modify Records</a>
                <li><a href="admin_search.php"> Search Activity </a>
                <li><a href="admin_report.php">View Reports </a>
                <li><a href="logout.php">Logout</a></li>
 
        </ul>
</nav>
<br>
<center>Enter PID of record you want to edit:<br><br>

<form action='admin_mod_patient_process.php' method="POST">
<input type="text" name="PID">
<input type="submit" value="search">
</form><br><br>

<?php gen_mod_patient_list() ?><br>

<form action='admin_mod_portal.php'>
<input type="submit" value="Return to Appointments Portal">
</form>
</center>

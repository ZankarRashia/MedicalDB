<?php
    include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php"
?>

<html>
<head>
<title>Modify Nurse Record</title>
</head>
<body>

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
<?php

            $job_desc = mysqli_escape_string($conn, $_POST['job_desc']);

            mysqli_query($conn, "UPDATE Nurses SET Name='".$_POST['Name']."', Job_description='".$job_desc."' WHERE NID=".$_POST['NID'].";") or die(mysqli_error($conn));

            record_action("Admin", $_SESSION['User_ID'], "Modified Record", $_POST['NID']);

            echo "<center>The record was successfully updated!</center><br><br>";

?>

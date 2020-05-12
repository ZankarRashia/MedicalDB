<?php
    include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php"
?>

<html>
<head>
<title>Record Deleted</title>
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

    mysqli_query($conn, "DELETE FROM ".$_POST['table']." WHERE ".$_POST['ID_type']."=".$_POST['ID'].";") or die(mysqli_error($conn));

    echo "<center>Record deleted successfully!<br>";

    echo "<a href='admin_mod_portal.php'> Return to modification portal </a></center>";


?>
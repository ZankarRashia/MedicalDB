<?php
    include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php"
?>

<html>
<head>
<title>Website Reports</title>
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
<center><h3>Generate report of recent user activity</h3><br><br>

<form method="POST">

Date range of activity<br>
<input type='datetime-local' step=1800 name='low'><br>
<input type='datetime-local' step=1800 name='high'><br><br>

<label for="compare">Compare to below range?</label><br>
<input type="checkbox" name="compare" value=1><br><br>

Compare to:<br>
<input type='datetime-local' step=1800 name='low_comp'><br>
<input type='datetime-local' step=1800 name='high_comp'><br><br>

<input type='submit' name='submit'>

</form></center>

<?php

echo "<center>";

if(isset($_POST['submit'])) {

    if(!empty($_POST['low']) && !empty($_POST['high'])) {
        $low = substr($_POST['low'],0,10).' '.substr($_POST['low'],11).':00';
        $high = substr($_POST['high'],0,10).' '.substr($_POST['high'],11).':00';
    } elseif (!empty($_POST['low']) && empty($_POST['high'])) {
        $low = substr($_POST['low'],0,10).' '.substr($_POST['low'],11).':00';
        $high = "9999-12-31 23:59:59";
    } elseif (empty($_POST['low']) && !empty($_POST['high'])) {
        $low = "1000-01-01 00:00:00";
        $high = substr($_POST['high'],0,10).' '.substr($_POST['high'],11).':00';
    } else {
        $low = "1000-01-01 00:00:00";
        $high = "9999-12-31 23:59:59";
    }

    action_report($low, $high);

    if(!empty($_POST['compare'])) {

        if(!empty($_POST['low_comp']) && !empty($_POST['high_comp'])) {
            $low_c = substr($_POST['low_comp'],0,10).' '.substr($_POST['low_comp'],11).':00';
            $high_c = substr($_POST['high_comp'],0,10).' '.substr($_POST['high_comp'],11).':00';
        } elseif (!empty($_POST['low_comp']) && empty($_POST['high_comp'])) {
            $low_c = substr($_POST['low_comp'],0,10).' '.substr($_POST['low_comp'],11).':00';
            $high_c = "9999-12-31 23:59:59";
        } elseif (empty($_POST['low_comp']) && !empty($_POST['high_comp'])) {
            $low_c = "1000-01-01 00:00:00";
            $high_c = substr($_POST['high_comp'],0,10).' '.substr($_POST['high_comp'],11).':00';
        } else {
            $low_c = "1000-01-01 00:00:00";
            $high_c = "9999-12-31 23:59:59";
        }

        echo "<br> ===================== <br>";

        action_report($low_c, $high_c);

    }




}


echo "<br><br><br></center>";

?>










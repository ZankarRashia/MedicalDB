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
<h2> Search Patient Records </h2><br>

<form action="" method="POST">
<label for="search">Patient PID: </label>
<input type="text" minlength="6" maxlength="6" name="PID"><br>

<label for="first_name">First Name: </label>
<input type="text" maxlength=80 name="first_name"><br>

<label for="last_name">Last Name: </label>
<input type="text" maxlength=80 name="last_name"><br><br>

<input type="submit" name="Search" value="Search">
</form><br><br>

<?php

    if(isset($_POST['Search'])) {

        $query = "SELECT * FROM Patients WHERE ";

        if(!empty($_POST['PID'])) {
            $query .= "(PID=".$_POST['PID'].") AND";
        }

        if(!empty($_POST['first_name'])) {
            $query .= "(First_Name='".$_POST['first_name']."') AND";
        }

        if(!empty($_POST['last_name'])) {
            $query .= "(Last_Name='".$_POST['last_name']."') AND";
        }

        $query = substr($query, 0, -4);
        $query .= ";";

        $sql_pid = mysqli_query($conn, $query);

        if (!$sql_pid || mysqli_num_rows($sql_pid) == 0) {

            echo "No results found";

        } else {

            $pid = mysqli_fetch_assoc($sql_pid);

            gen_patient_info($pid['PID']);

        }

    }

?>
</center>

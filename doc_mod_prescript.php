<?php
	include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php";
?>

<html>
<head>
<title>Modify Prescription</title>
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
<center>
<?php

    $sql_test = mysqli_query($conn, "SELECT * FROM Prescriptions WHERE Prescript_ID=".$_POST['Prescript_ID']." AND Prescribing_doc=".$_SESSION['User_ID'].";");

    if(mysqli_num_rows($sql_test) == 0) {
        echo "You are not the prescribing doctor for the prescription. You cannot edit other doctor's prescriptions";
    } else {
        echo "<form action='doc_mod_prescript_result.php' method='POST'>";
        mod_prescript($_POST['Prescript_ID']);
        echo "<input type='submit' value='Save changes'></form>";
    }

?>
</center>
</body>
</html>
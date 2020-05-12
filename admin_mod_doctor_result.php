<?php
    include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php"
?>

<html>
<head>
<title>Modify Doctor Record</title>
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

        $specialization = mysqli_escape_string($conn, $_POST['Specialization']);

        mysqli_query($conn, "UPDATE Doctors SET Name='".$_POST['Name']."', Work_phone='".$_POST['work_phone']."', Fax='".$_POST['fax']."', Email='".$_POST['email']."', Specialist='".$_POST['Specialist']."', Specialization='".$specialization."' WHERE NPI=".$_POST['NPI'].";") or die(mysqli_error($conn));

        mysqli_query($conn, "DELETE FROM Doctor_clinic WHERE NPI=".$_POST['NPI'].";");

        if(!empty($_POST['Clinics']) & is_array($_POST['Clinics'])) {

            foreach($_POST['Clinics'] as $clinic) {
                mysqli_query($conn, "INSERT INTO Doctor_clinic VALUES(".$clinic.",".$_POST['NPI'].");") or die(mysqli_error($conn));
            }
        }

        record_action("Admin", $_SESSION['User_ID'], "Modified Record", $_POST['NPI']);

        echo "<center>The record was successfully updated!<br><br></center>";

?>

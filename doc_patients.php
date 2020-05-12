<?php
	include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php";
?>

<html>
<head>
<title>Patient Records</title>
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
<h2>Patient Records</h2>

<h3>Search for a patient</h3><br>
<form action='' method='POST'>
<label for='First_Name'>First Name:</label>
<input type='text' name='First_Name'><br><br>
<label for='Last_Name'>Last Name:</label>
<input type='text' name='Last_Name'><br><br>
<label for='PID'>PID:</label>
<input type='text' minlength='6' maxlength ='6' name='PID'><br><br>
<input type='submit' name='submit' value='Search'>
</form><br>
</center>

<center>
<form action='' method='POST'>
<input type='submit' name='all_patient' value='Display Current Patients'>
</form><br><br>

<?php

    if(isset($_POST['all_patient'])) {

        gen_patient_info_doctor($_SESSION['User_ID']);

    }

    if(isset($_POST['submit'])) {


        $query = "SELECT * FROM Patients ";

        if (!empty($_POST['PID']) || !empty($_POST['First_Name']) || !empty($_POST['Last_Name'])) {

            $query .= "WHERE ";

            if(!empty($_POST['PID'])) {
                $query .= "(PID=".$_POST['PID'].") AND ";
            }

            if(!empty($_POST['First_Name'])) {
                $query .= "(First_Name='".$_POST['First_Name']."') AND ";
            }

            if(!empty($_POST['Last_Name'])) {
                $query .= "(Last_Name='".$_POST['Last_Name']."') AND ";
            }

            $query = substr($query, 0, -4);
            $query .= ";";

        }

        $sql_pid = mysqli_query($conn, $query);

        if ($sql_pid == FALSE) {

            echo "No patient found";

        } else {
            
            while($patient = mysqli_fetch_assoc($sql_pid)) {

                gen_patient_info($patient['PID']);
                echo "<br>";
                gen_prescriptions($patient['PID']);
                echo "<br>";
                echo "<form action='doc_patients_mod.php' method='POST'>";
                echo "<input type='hidden' name='PID' value=".$patient['PID'].">";
                echo "<input type='submit' value='Modify Patient Record'></form>";

            }             

        } 

    }

?>

</body>
</html>



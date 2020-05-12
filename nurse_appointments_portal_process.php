<?php
    include_once 'includes/dbh.php';
    include_once 'includes/session_check.php';
    include_once 'includes/query_func.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Appointment Scheduler</title>
<link rel="stylesheet" type = "text/css" href="css/nurse_portal_style.css" />
</head>
<body>
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
<h3>Create New Appointment</h3><br>

<?php

echo "Creating appointment for PID ".$_POST['PID']."<br><br>";

    if(isset($_POST['submit'])) {

        $sql_pid = mysqli_query($conn, "SELECT * FROM Patients WHERE PID=".$_POST['PID'].";") or die(mysqli_error($conn));
        $pid = $_POST['PID'];

        if(mysqli_num_rows($sql_pid) == 0) {

            echo "No patient record associated with that PID <br>";
            echo "Please <a href='nurse_appointments_portal.php'>return to the appointment scheduler</a><br><br>";

        } else {

            $sql_doc = mysqli_query($conn, "SELECT * FROM Doctors WHERE Specialist='No' AND NPI IN (SELECT NPI FROM Doctor_patient WHERE PID='".$_POST['PID']."');") or die(mysqli_error($conn));

            if (mysqli_num_rows($sql_doc) == 0) {
                $hasGP = FALSE;
            } else {
                $hasGP = TRUE;
            }

            $doc = mysqli_fetch_assoc($sql_doc);

            //No GP, scheduling for GP
            if ($hasGP==FALSE && $_POST['app_choice']==0) {

                echo "Schedule appointment with GP<br>";

                echo "<form action='nurse_appointments_process.php' method='POST'>";

                echo "<input type='hidden' name='PID' value=".$pid.">";

                select_GP();
                select_datetime();
                select_clinic();
            
                echo "<p><input type='submit' name='submit' value='Submit'></p>";

            //No GP, scheduling for specialist
            } elseif ($hasGP==FALSE && $_POST['app_choice']==1) {

                echo "This patient does not have a GP. They must have a GP before scheduling with a specialist<br>";

            //Has GP, scheduling for GP
            } elseif ($hasGP==TRUE && $_POST['app_choice']==0) {

                echo "The patient's current GP is ".$doc['Name']."<br><br>";

                echo "<form action='nurse_appointments_process.php' method='POST'>";

                echo "<input type='hidden' name='Doctor' value=".$doc['NPI'].">";
                echo "<input type='hidden' name='PID' value=".$pid.">";

                select_datetime();
                select_clinic();
            
                echo "<p><input type='submit' name='submit' value='Submit'></p>";

            //Has GP, scheduling for specialist
            } else {

                echo "<form action='nurse_appointments_process.php' method='POST'>";

                echo "<input type='hidden' name='PID' value=".$pid.">";

                select_specialist();
                select_datetime();
                select_clinic();
            
                echo "<p><input type='submit' name='submit' value='Submit'></p>";

            }

        }
    	
    }

?>

<br><br> <a href='nurse_portal.php'>Return to nurse portal</a>
</center>
</body>
</html>

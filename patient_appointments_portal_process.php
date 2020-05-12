<?php
	include_once 'includes/dbh.php';
    include_once 'includes/session_check.php';
    include_once 'includes/query_func.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Appointment Scheduler</title>
<link rel="stylesheet" type = "text/css" href="css/patient_portal_style.css" />
</head>
<body>
<nav> 
        <p>Logged in as <?php echo $_SESSION['Name'] ?></p>
        <ul>
                <li><a href="patient_portal.php">Home</a></li>
                <li><a href="patient_info.php">View Medical Information</a></li>
                <li><a href="patient_prescript.php">View Prescriptions</a></li>
                <li><a href="patient_appointments_portal.php">Book Appointment</a></li>
                <li><a href="logout.php">Logout</a></li>
        </ul>
</nav>
<br>
<?php

echo "<center>";

    if(isset($_POST['submit'])) {

        echo "<head><title>Appointment Scheduler</title></head>";

    	//No GP, scheduling for GP
    	if ($_SESSION['Has_GP']==FALSE && $_POST['app_choice']==0) {

    		echo "Welcome to UH Medical Clinic!"."<br><br>";

    		echo "<form action='patient_appointments_process.php' method='POST'>";

    		select_GP();
    		select_datetime();
    		select_clinic();
			
			echo "<p><input type='submit' name='submit' value='Submit'></p>";

    	//No GP, scheduling for specialist
    	} elseif ($_SESSION['Has_GP']==FALSE && $_POST['app_choice']==1) {

    		echo "We don't have record of a GP on file for you. Appointments with a specialist require a GP's approval"."<br>";
    		echo "Please schedule with a GP before choosing 'Specialist' on the appointment scheduler"."<br>";
    		echo "<a href='patient_appointments_portal.php'>Return to appointments portal</a>";


    	//Has GP, scheduling for GP
    	} elseif ($_SESSION['Has_GP']==TRUE && $_POST['app_choice']==0) {

    		echo "Welcome back, ".$_SESSION['Name']."!"."<br><br>";
    		echo "Your current GP is ".$_SESSION['GP_name']."<br><br>";

    		echo "<form action='patient_appointments_process.php' method='POST'>";

            echo "<input type='hidden' name='Doctor' value=".$_SESSION['GP_ID'].">";

            select_datetime();
    		select_clinic();
			
			echo "<p><input type='submit' name='submit' value='Submit'></p>";

    	//Has GP, scheduling for specialist
    	} else {

    		echo "Welcome back, ".$_SESSION['Name']."!";

    		echo "<form action='patient_appointments_process.php' method='POST'>";

    		select_specialist();
    		select_datetime();
    		select_clinic();
			
			echo "<p><input type='submit' name='submit' value='Submit'></p>";

    	}
    }

?>

<br><br> <a href='patient_portal.php'>Return to patient portal</a>
</center>

</body>
</html>

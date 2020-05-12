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
<?php

	if(isset($_POST['submit'])) {

		$datetime = substr($_POST['Time'],0,10).' '.substr($_POST['Time'],11).':00';
		
		$sql_doc = mysqli_query($conn, "SELECT * FROM Doctors WHERE NPI=".$_POST['Doctor'].";");
		$doc = mysqli_fetch_assoc($sql_doc) or die(mysqli_error($conn));;
		$sql_clinic = mysqli_query($conn, "SELECT * FROM Clinics WHERE Clinic_ID=".$_POST['Clinic'].";");
		$clinic = mysqli_fetch_assoc($sql_clinic) or die(mysqli_error($conn));;

		$time_check = mysqli_query($conn, "SELECT * FROM Appointments WHERE Doctor_ID=".$_POST['Doctor']." AND Appointment_time='".$datetime."';") or die(mysqli_error($conn));
		$clinic_check = mysqli_query($conn, "SELECT * FROM Doctor_clinic WHERE Clinic_ID=".$_POST['Clinic']." AND NPI=".$_POST['Doctor'].";") or die(mysqli_error($conn));

		$current_time = date("Y-m-d H:i:s");

		//Appointment time is in the past
		if($current_time > $datetime) {
			echo "That timeslot is invalid<br> <br>";
			echo "Please <a href='nurse_appointments_portal.php'>return to the appointment scheduler and pick another time</a><br><br>";
			echo "<form action='nurse_portal.php'><button type='submit'>Return to nurse portal</button></form>";

		// Check to see if time slot is taken
		} elseif(mysqli_num_rows($time_check) != 0) {
			echo "The timeslot ".$datetime." with Dr. ".$doc['Name']." is already taken <br> <br>";
			echo "Please <a href='nurse_appointments_portal.php'>return to the appointment scheduler and pick another time</a><br><br>";
			echo "<form action='nurse_portal.php'><button type='submit'>Return to nurse portal</button></form>";

		//Check to see if doctor practices at that clinic
		} elseif (mysqli_num_rows($clinic_check) == 0) {
			echo "Dr. ".$doc['Name']." doesn't currently practice out of ".$clinic['Clinic_name']."<br>";
			echo "Please <a href='nurse_appointments_portal.php'>return to the appointment scheduler and pick another clinic</a><br><br>";
			echo "<form action='nurse_portal.php'><button type='submit'>Return to patient portal</button></form>";

		} else {
			mysqli_query($conn, "INSERT INTO Appointments VALUES (NULL, 'Yes','".$datetime."', ".$_POST['Doctor'].", ".$_POST['PID'].", ".$_POST['Clinic'].");") or die(mysqli_error($conn));
			$appt_id = mysqli_insert_id($conn);

			record_action("Nurse", $_SESSION['User_ID'], "Scheduled Appointment", $appt_id);

			echo "The appointment with Dr. ".$doc['Name']." at ".$clinic['Clinic_name']." on ".$datetime." was successfully added! <br><br>";
			echo "<form action='nurse_portal.php'><button type='submit'>Return to nurse portal</button></form>";
		}		
			    
	}    
	


?>
</center>
</body>
</html>


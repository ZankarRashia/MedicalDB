<?php
	include_once "includes/dbh.php";
	include_once "includes/query_func.php";
	session_start();
?>

<link rel="stylesheet" type = "text/css" href="css/patient_portal_style.css" />

<html>
<head>
	<title> Patient Creation </title>
</head>
<body>
<nav> 
        <p>UH Medical Clinic</p>
        <ul>
                    <li><a href="homepage.php">Home</a></li>
                    <li><a href="login_options.php">Login</a></li>
                    <li><a href="about_us.php">About Us</a></li>
                    <li><a href="contact_form_index.php">Contact Us</a></li>
            </li>
        </ul>
</nav>
<br>
<center>
<?php

	if(isset($_POST['pwd'])) {
			//Generate random PID
			$check = TRUE;

			while($check) {
				$PID = rand(100000,999999);
				$sql_PID = mysqli_query($conn, "SELECT * from Patients WHERE PID=".$PID.";") or die(mysqli_error($conn));

				if(mysqli_num_rows($sql_PID)==0) {
					$check = FALSE;
				}
			}

			$allergies = mysqli_escape_string($conn, $_POST['allergies']);
            $prev_cond = mysqli_escape_string($conn, $_POST['prev_cond']);
            $past_surg = mysqli_escape_string($conn, $_POST['past_surg']);
            $past_prescript = mysqli_escape_string($conn, $_POST['past_prescript']);
            $family_hist = mysqli_escape_string($conn, $_POST['family_hist']);

			//Generate Demographic Entry
			mysqli_query($conn, "INSERT INTO Demographics VALUES(NULL,'".$_POST["insurance"]."','".$_POST["Age"]."','".$_POST["DOB"]."','".$_POST["gender"]."','".$_POST["ethnicity"]."','".$_POST["marital"]."','".$_POST["home_phone"]."','".$_POST["cell_phone"]."','".$_POST["work_phone"]."','".$_POST["email"]."','".$allergies."');") or die(mysqli_error($conn));
			$demo_ID = mysqli_insert_id($conn) or die(mysqli_error($conn));

			//Generate Medical History Entry
			mysqli_query($conn, "INSERT INTO Medical_history VALUES(NULL,'".$prev_cond."','".$past_surg."','".$_POST["blood_type"]."','".$past_prescript."');") or die(mysqli_error($conn));
			$med_hist_ID = mysqli_insert_id($conn) or die(mysqli_error($conn));

			//Generate Family History Entry
			mysqli_query($conn, "INSERT INTO Family_history VALUES(NULL,'".$family_hist."');") or die(mysqli_error($conn));
			$fam_hist_ID = mysqli_insert_id($conn) or die(mysqli_error($conn));

			//Generate New Patient
			mysqli_query($conn, "INSERT INTO Patients VALUES(".$PID.",'".$_POST["pwd"]."','".$_POST["First_name"]."','".$_POST["Last_name"]."',".$_POST["SSN"].",".$demo_ID.",".$med_hist_ID.",".$fam_hist_ID.", NULL);") or die(mysqli_error($conn));


			$_SESSION['loggedin'] = TRUE;
		    $_SESSION['User_ID'] = $PID;
		    $_SESSION['Name'] = $_POST["First_name"];
		    $_SESSION['User_Type'] = "Patient";
		    $_SESSION['Has_GP'] = FALSE;

		    record_action("Patient", $_SESSION['User_ID'], "Created New User", $_SESSION['User_ID']);
		    record_action("Patient", $_SESSION['User_ID'], "Logged In", $_SESSION['User_ID']);

			echo "<h2>Thank for joining UH Medical Clinic</h2><br>";
			echo "Your PID is ".$PID."<br><br>";
			echo "<a href='patient_portal.php'> Continue to your patient portal </a>";
			

	}

?>
</center>
</body>
</html>

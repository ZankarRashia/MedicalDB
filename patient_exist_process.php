<?php
    include_once "includes/dbh.php";
    include_once "includes/query_func.php";
    session_start();
?>

<head>
    <title> Login Failed </title>
</head>
    
<link rel="stylesheet" type = "text/css" href="css/patient_portal_style.css" />

<body>
    
    <nav> 
        <p>UH Medical Clinic</p>
        <ul>
                <li><a href="homepage.php">Home</a></li>
                <li><a href="login_options.php">Login</a></li>
                <li><a href="about_us.php">About Us</a></li>

        </ul>
    </nav>

<?php
    if(isset($_POST['PID'])) {
        
	    $patient = mysqli_query($conn, "SELECT * FROM Patients WHERE PID=".$_POST['PID'].";") or die(mysqli_error($conn));
	    $row = mysqli_fetch_assoc($patient);

	    if(mysqli_num_rows($patient) == 0){
		    echo "Invalid PID<br>";
		    echo "Return to <a href='patient_login.php'>patient login</a>";

		} elseif (strcmp($row['Password'],$_POST['password']) != 0) {
			echo "This password doesn't match this PID<br>";
			echo "Return to <a href='patient_login.php'>patient login</a>";

		} else {
		    $_SESSION['loggedin'] = TRUE;
		    $_SESSION['User_ID'] = $_POST['PID'];
	
		    $_SESSION['Name'] = $row['First_Name'];
		    $_SESSION['User_Type'] = "Patient";
 
		    $sql_doc = mysqli_query($conn, "SELECT * FROM Doctors WHERE Specialist='No' AND NPI IN (SELECT NPI FROM Doctor_patient WHERE PID='".$_POST['PID']."');") or die(mysqli_error($conn));
		    $doc = mysqli_fetch_assoc($sql_doc);

		    if(mysqli_num_rows($sql_doc) == 0) {
		    	$_SESSION['Has_GP'] = FALSE;
		    } else {
		    	$_SESSION['Has_GP'] = TRUE;
		    	$_SESSION['GP_name'] = $doc['Name'];
		    	$_SESSION['GP_ID'] = $doc['NPI'];
		    }

		    record_action("Patient", $_SESSION['User_ID'], "Logged In", $_SESSION['User_ID']);

		    header("location:patient_portal.php");

	    }
    }  
?>
</body>

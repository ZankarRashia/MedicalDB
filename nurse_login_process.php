<?php
    include_once "includes/dbh.php";
    include_once "includes/query_func.php";
    session_start();
?>

<head>
    <title> Login Failed </title>
</head>
    
    <link rel="stylesheet" type="text/css" href="css/nurse_portal_style.css" />

<body>
    
    <nav> 
        <p>UH Medical Clinic</p>
        <ul>
                <li><a href="homepage.php">Home</a></li>
                <li><a href="login_options.php">Login</a></li>
                <li><a href="about_us.php">About Us</a></li>
        </ul>
</nav>
<br>

<?php
    if(isset($_POST['NID'])) {
        
	    $nurse = mysqli_query($conn, "SELECT * FROM Nurses WHERE NID=".$_POST['NID'].";") or die(mysqli_error($conn));
	    $row = mysqli_fetch_assoc($nurse);

	    if(mysqli_num_rows($nurse) == 0){
		    echo "Invalid NID<br>";
		    echo "Return to <a href='nurse_login.php'>nurse login</a>";

		} elseif (strcmp($row['Password'],$_POST['password']) != 0) {
			echo "This password doesn't match this NID<br>";
			echo "Return to <a href='nurse_login.php'>nurse login</a>";

		} else {
		    $_SESSION['loggedin'] = TRUE;
		    $_SESSION['User_ID'] = $_POST['NID'];
	
		    $_SESSION['Name'] = $row['Name'];
		    $_SESSION['User_Type'] = "Nurse";

		    record_action("Nurse", $_SESSION['User_ID'], "Logged In", $_SESSION['User_ID']);

		    header("location:nurse_portal.php");

	    }
    }  
?>
</body>

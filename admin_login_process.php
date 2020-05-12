<?php
    include_once "includes/dbh.php";
    include_once "includes/query_func.php";
    session_start();
?>

<head>
    <title> Login Failed </title>
</head>
    
    <link rel="stylesheet" type="text/css" href="css/admin_login_process_style.css" />

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
    if(isset($_POST['Admin_ID'])) {
        
	    $admin = mysqli_query($conn, "SELECT * FROM Admin WHERE Admin_ID=".$_POST['Admin_ID'].";") or die(mysqli_error($conn));
	    $row = mysqli_fetch_assoc($admin);

	    if(mysqli_num_rows($admin) == 0){
		    echo "Invalid Admin ID<br>";
		    echo "Return to <a href='admin_login.php'>admin login</a>";

		} elseif (strcmp($row['Password'],$_POST['password']) != 0) {
			echo "This password doesn't match this Admin ID<br>";
			echo "Return to <a href='admin_login.php'>admin login</a>";

		} else {
		    $_SESSION['loggedin'] = TRUE;
		    $_SESSION['User_ID'] = $_POST['Admin_ID'];
            $_SESSION['Name'] = $row['Name'];
	
		    $_SESSION['User_Type'] = "Admin";

		    record_action("Admin", $_SESSION['User_ID'], "Logged In", $_SESSION['User_ID']);

		    header("location:admin_portal.php");

	    }
    }  
?>
</body>

<?php
    include_once "includes/dbh.php";
    include_once "includes/query_func.php";
    session_start();
?>

<head>
    <title> Login Failed </title>
</head>
    
	<link rel="stylesheet" type="text/css" href="css/doc_login.css" />

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
    if(isset($_POST['NPI'])) {
        
        $doc = mysqli_query($conn, "SELECT * FROM Doctors WHERE NPI=".$_POST['NPI'].";") or die(mysqli_error($conn));
        $row = mysqli_fetch_assoc($doc);

        if(mysqli_num_rows($doc) == 0){
            echo "Invalid NPI<br>";
            echo "Return to <a href='doc_login.php'>doctor login</a>";

        } elseif (strcmp($row['Password'],$_POST['password']) != 0) {
            echo "This password doesn't match this PID<br>";
            echo "Return to <a href='doc_login.php'>doctor login</a>";

        } else {
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['User_ID'] = $_POST['NPI'];
    
            $_SESSION['Name'] = $row['Name'];
            $_SESSION['Specialization'] = $row['Specialization'];
            $_SESSION['User_Type'] = "Doctor";

            record_action("Doctor", $_SESSION['User_ID'], "Logged In", $_SESSION['User_ID']);

            header("location:doc_portal.php");

        }
    }  
?>
</body>

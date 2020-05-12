<?php
    include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php"
?>

<html>
<head>
<title>New Nurse Profile Created</title>
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
<center>
<?php

        $check = TRUE;

            while($check) {
                $NID = rand(10000,99999);
                $sql_NID = mysqli_query($conn, "SELECT * from Nurses WHERE NID=".$NID.";") or die(mysqli_error($conn));

                if(mysqli_num_rows($sql_NID)==0) {
                    $check = FALSE;
                }
            }

            $job_desc = mysqli_escape_string($conn, $_POST['Job_description']);

            mysqli_query($conn, "INSERT INTO Nurses VALUES(".$NID.",'".$_POST['Password']."','".$_POST['Name']."', '".$_POST['Email']."','".$job_desc."');") or die(mysqli_error($conn));

            record_action("Admin", $_SESSION['User_ID'], "Created New User", $NID);

            echo "Nurse profile for ".$_POST['Name']." (NID: ".$NID.") created<br>";
            echo "<a href='admin_portal.php'> Return to admin portal </a>";

    


?>
</center>
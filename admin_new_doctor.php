<?php
    include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php"
?>

<html>
<head>
<title>New Doctor Record</title>
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

<center>Enter information to create new doctor profile:<br><br>

<form action='admin_new_doctor_process.php' method='POST'>
<label for='NPI'>NPI:</label>
<input type='text' minlength=10 maxlength=10 name='NPI' required><br>
<label for='Name'>Name:</label>
<input type='text' name='Name' required><br>
<label for='Email'>Email: </label>
<input type='text' maxlength=80 name='Email' required><br>
<label for='Password'>Password: </label>
<input type='password' name='Password' minlength='5' maxlength='80' required><br><br>
<label for='Work_phone'>Work Phone: </label>
<input type="tel" name="Work_phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" required>
<small> Format: (123) 345-1234</small><br>
<label for='Fax'>Fax: </label>
<input type="tel" name="Fax" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}">
<small> Format: (123) 345-1234</small><br><br>

Specialist?<br>
<label for="Yes">Yes</label>
<input type="radio" name="Specialist" value="Yes" required><br>
<label for="No">No</label>
<input type="radio" name="Specialist" value="No"><br><br>

<label for="Specialization">Specialization (enter 'General Practitioner' if not a specialist) </label>
<input type='text' name='Specialization' maxlength=80 required><br><br>


Clinics the doctor will work at:<br>
<?php

    $sql_clinic = mysqli_query($conn, "SELECT * FROM Clinics");

    while ($clinic = mysqli_fetch_assoc($sql_clinic)) {

        echo "<label for=".$clinic['Clinic_ID'].">".$clinic['Clinic_name']."</label>";
        echo "<input type='checkbox' name='Clinics[]' value=".$clinic['Clinic_ID']."><br>";

    }

    echo "<br>";

?>

<input type='submit' name='submit' value='Create New Doctor Profile'>
</form>

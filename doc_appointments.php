<?php
	include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php";
?>

<html>
<head>
<title>Upcoming Appointments</title>
</head>

<link rel="stylesheet" type = "text/css" href="css/doc_portal_style.css" />
<nav>
    <p>Logged in as <?php echo $_SESSION['Name'] ?></p>
        <ul>
            <li><a href="doc_portal.php">Home</a></li>
            <li><a href="doc_appointments.php">View Upcoming Appointments</a></li>
            <li><a href="doc_patients.php">Check Your Patients Files</a></li>
            <li><a href="doc_prescript.php">Write Prescription</a></li>
            <li><a href="doc_reports.php">Demographic Reports </a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
</nav>
<br>
<center>
    <h2>View Upcoming Appointments</h2><br>


Check your upcoming appointments for the following range:<br><br>
<form action='' method='POST'>
<label for='low'>From:</label>
<input type='datetime-local' step=1800 name='low'><br>
<label for='high'>To:</label>
<input type='datetime-local' step=1800 name='high'><br><br>
<label for='PID'>PID: </label>
<input type='text' name='PID'><br><br>

<label for='clinic'>Location</label>
<select name='clinic'>
<option value='' selected disable></option>
<?php
    $sql_clinic = mysqli_query($conn, "SELECT * FROM Clinics;");

    while ($clinic = mysqli_fetch_assoc($sql_clinic)) {
        echo "<option value=".$clinic['Clinic_ID'].">".$clinic['Clinic_name']."</option>";
    }

?>
</select><br><br>

<input type='submit' name='submit' value='Submit'>
</form><br><br>

</div>

<?php

    if(isset($_POST['submit'])) {


        if(!empty($_POST['low']) && !empty($_POST['high'])) {
            $low = substr($_POST['low'],0,10).' '.substr($_POST['low'],11).':00';
            $high = substr($_POST['high'],0,10).' '.substr($_POST['high'],11).':00';
        } elseif (!empty($_POST['low']) && empty($_POST['high'])) {
            $low = substr($_POST['low'],0,10).' '.substr($_POST['low'],11).':00';
            $high = "9999-12-31 23:59:59";
        } elseif (empty($_POST['low']) && !empty($_POST['high'])) {
            $low = "1000-01-01 00:00:00";
            $high = substr($_POST['high'],0,10).' '.substr($_POST['high'],11).':00';
        } else {
            $low = "1000-01-01 00:00:00";
            $high = "9999-12-31 23:59:59";
        }

        $dt_low = new DateTime($low);
        $dt_high = new DateTime($high);


        if ($dt_low >= $dt_high) {
            echo "Invalid time interval."; 
        
        } else {

            print_apmt_range($low, $high, $_SESSION['User_ID'], $_POST['PID'], $_POST['clinic']);

        }  

    }

?>
</center>
</body>
</html>



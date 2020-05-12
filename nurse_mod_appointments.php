<?php
    include_once "includes/dbh.php";
    include_once "includes/session_check.php";
    include_once "includes/query_func.php";
?>

<html>
<head>
	<title>Modify Appointments</title>
</head>
<body>
<script>
function confirm_delete() {
    var x = confirm("Are you sure you want to delete this record?");

    if (x)
        return true;
    else
        return false;
}
</script>
    
<link rel="stylesheet" type = "text/css" href="css/nurse_portal_style.css" />
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

<center>Search for an appointment to modify:<br><br>

<form action='' method='POST'>
<label for='low'>From</label>
<input type='datetime-local' step=1800 name='low'><br>
<label for='high'>To</label>
<input type='datetime-local' step=1800 name='high'><br><br>

<label for='PID'>PID: </label>
<input type='text' name='PID'> <br><br>

Which doctor?<br>
<select id="doc" name="doc">
<option value=""></option>
<?php
    $sql_doc = mysqli_query($conn, "SELECT * FROM Doctors;");

    while($doc = mysqli_fetch_assoc($sql_doc)) {
        echo "<option value=".$doc['NPI'].">".$doc['Name']."</option>";
    }
?>
</select><br>

Which location?<br>
<select id="clinic" name="clinic">
<option value=""></option>
<?php
    $sql_clinic = mysqli_query($conn, "SELECT * FROM Clinics;");

    while($clinic = mysqli_fetch_assoc($sql_clinic)) {
        echo "<option value=".$clinic['Clinic_ID'].">".$clinic['Clinic_name']."</option>";
    }
?>
</select><br><br>

<input type='submit' value='Search'><br><br></form>

<?php


        $query = "SELECT * FROM Appointments WHERE ";

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

        $query .= "(Appointment_time BETWEEN '".$low."' AND '".$high."')";

        if(!empty($_POST['PID'])) {
            $query .= "AND (Patient_ID=".$_POST['PID'].")";
        }

        if(!empty($_POST['doc'])) {

            $query .= "AND (Doctor_ID=".$_POST['doc'].")";
        }

        if(!empty($_POST['clinic'])) {
            $query .= "AND (Clinic_ID=".$_POST['clinic'].")";
        }

        $query .= ";";

        gen_mod_apmt($query);

    


?>
</center>


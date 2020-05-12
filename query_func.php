
<?php

    function sql_connect() {

        $dbServerName = "localhost";
        $dbUserName = "root";
        $dbPassword = "root";
        $dbName = "meddb";

        $conn = mysqli_connect($dbServerName, $dbUserName, $dbPassword, $dbName) or die("Bad connection: ".mysqli_connect_error());

        return $conn;
    }

    // ~~~APPOINTMENT LOGIC~~~ //

    function select_GP() {

        $conn = sql_connect();

        $gp_result = mysqli_query($conn, "SELECT * FROM Doctors WHERE Specialist='No';") or die(mysqli_error($conn));

        echo "Choose a general practitioner:<br>";
        echo "<p><select name='Doctor' required>";
        echo "<option value='' selected disabled></option>";
            
        while ($gp_rows = mysqli_fetch_assoc($gp_result)) {
            echo "<option value='".$gp_rows["NPI"]."'>".$gp_rows["Name"]."</option>";
        }

        echo "</select></p><br>";
    }

    function select_specialist() {

        $conn = sql_connect();

        $doc_result = mysqli_query($conn, "SELECT * FROM Doctors WHERE Specialist='Yes';") or die(mysqli_error($conn));

        echo "Choose a specialist:<br>";
        echo "<p><select name='Doctor' required>";
        echo "<option value='' selected disabled></option>";
            
        while ($doc_rows = mysqli_fetch_assoc($doc_result)) {
            echo "<option value='".$doc_rows["NPI"]."'>".$doc_rows["Name"]." (".$doc_rows["Specialization"].") </option>";
        }

        echo "</select></p><br>";
    }

    function select_clinic() {

        $conn = sql_connect();

        $loc_result = mysqli_query($conn, "SELECT * FROM Clinics;") or die(mysqli_error($conn));
        echo "Which clinic would you like to have your appointment at?<br>";

        echo "<p><select name='Clinic' required>";
        echo "<option value='' selected disabled></option>";

        while ($loc_rows = mysqli_fetch_assoc($loc_result)){
            echo "<option value='".$loc_rows["Clinic_ID"]."'>".$loc_rows["Clinic_name"]."</option>";
        }
        
        echo "</select></p><br>";
    }

    function select_datetime() {
        echo "Choose the desired appointment date and time:<br>";
        echo "<p><input type='datetime-local' step=1800 value='2020-01-01T08:00' name='Time' required></p><br>";
    }

    function print_apmt($Apmt_ID, $Doc_ID) {

        $conn = sql_connect();

        $sql_apmt = mysqli_query($conn, "SELECT * FROM Appointments WHERE Appt_ID=".$Apmt_ID." AND Doctor_ID=".$Doc_ID.";") or die(mysqli_error($conn));
        $apmt = mysqli_fetch_assoc($sql_apmt);

        $sql_doc = mysqli_query($conn, "SELECT * FROM Doctors WHERE NPI=".$apmt['Doctor_ID'].";") or die(mysqli_error($conn));
        $sql_patient = mysqli_query($conn, "SELECT * FROM Patients WHERE PID=".$apmt['Patient_ID'].";") or die(mysqli_error($conn));
        $sql_clinic = mysqli_query($conn, "SELECT * FROM Clinics WHERE Clinic_ID=".$apmt['Clinic_ID'].";") or die(mysqli_error($conn));

        $doc = mysqli_fetch_assoc($sql_doc);
        $patient = mysqli_fetch_assoc($sql_patient);
        $clinic = mysqli_fetch_assoc($sql_clinic);

        echo "<tr><td> ".$Apmt_ID." </td>";
        echo "<td> ".$doc['Name']." (".$doc['NPI'].") </td>";
        echo "<td> ".$patient['Last_Name'].", ".$patient['First_Name']." (".$patient['PID'].") </td>";
        echo "<td> ".$apmt['Has_approval']." </td>";
        echo "<td> ".$clinic['Clinic_name']." </td>";
        echo "<td> ".$apmt['Appointment_time']." </td>";

    }

    function print_apmt_range($low, $high, $Doc_ID, $PID, $Clinic_ID) {

        $conn = sql_connect();

        $query = "SELECT * FROM Appointments WHERE (Doctor_ID=".$Doc_ID.") AND (Appointment_time >= '".$low."' AND Appointment_time <= '".$high."')";

        if(!empty($PID)) {
            $query .= " AND (Patient_ID=".$PID.")";
        }

        if(!empty($Clinic_ID)) {
            $query .= " AND (Clinic_ID=".$Clinic_ID.")";
        }

        $query .= ";";

        $sql_apmt = mysqli_query($conn, $query) or die(mysqli_error($conn));

        if (mysqli_num_rows($sql_apmt) == 0) {

            echo "No appointments found in this range";

        } else {

            echo "<table><tr><th> Apmt ID </th>";
            echo "<th> Doctor (NPI) </th>";
            echo "<th> Patient (PID)</th>";
            echo "<th> Has approval? </th>";
            echo "<th> Location </th>";
            echo "<th> Appointment Time </th></tr>";

            while($apmt = mysqli_fetch_assoc($sql_apmt)) {

                print_apmt($apmt['Appt_ID'], $Doc_ID);
            }

        }

    }

    // ~~~PATIENT INFO LOGIC~~~ //

    //Displays patient info (Demo, Med Hist, etc.) for choosen PID
    function gen_patient_info($PID) {

        $conn = sql_connect();
    
        $sql_patient = mysqli_query($conn, "SELECT * FROM Patients WHERE PID= '$PID' ;") or die(mysqli_error($conn));
        $data = mysqli_fetch_assoc($sql_patient);
        
        $sql_demo = mysqli_query($conn, "SELECT * FROM Demographics WHERE Demo_ID= '$data[Demographics_ID]';") or die(mysqli_error($conn));
        $demo = mysqli_fetch_assoc($sql_demo);

        $sql_med = mysqli_query($conn, "SELECT * FROM Medical_history WHERE Med_Hist_ID='$data[Med_Hist_ID]';") or die(mysqli_error($conn));
        $med = mysqli_fetch_assoc($sql_med);

        $sql_fam = mysqli_query($conn, "SELECT * FROM Family_history WHERE Fam_Hist_ID='$data[Fam_Hist_ID]';") or die(mysqli_error($conn));
        $fam = mysqli_fetch_assoc($sql_fam);

        $sql_doc = mysqli_query($conn, "SELECT * FROM Doctors WHERE NPI IN (SELECT NPI FROM Doctor_patient WHERE PID='$PID') AND Specialist='Yes';") or die(mysqli_error($conn));
        $sql_gp = mysqli_query($conn, "SELECT * FROM Doctors WHERE NPI IN (SELECT NPI FROM Doctor_patient WHERE PID='$PID') AND Specialist='No';") or die(mysqli_error($conn));

        $sql_nurse = mysqli_query($conn, "SELECT * FROM Nurses WHERE NID= '$data[NID]';");
        $nurse = mysqli_fetch_assoc($sql_nurse);


        echo "<table><tr><th>Name</th>";
        echo "<th>PID</th>";
        echo "<th>Last 4 SSN</th>";
        echo "<th>Age</th>";
        echo "<th>Date of Birth</th>";
        echo "<th>Has insurance?</th>";
        echo "<th>Ethnicity</th>";
        echo "<th>Marital Status</th></tr>";

        echo "<tr><td align='center'>".$data['First_Name']." ".$data['Last_Name']."</td>";
        echo "<td align='center'>".$data['PID']."</td>";
        echo "<td align='center'>".$data['Last_4_SSN']."</td>";
        echo "<td align='center'>".$demo['Age']."</td>";
        echo "<td align='center'>".$demo['Date_of_birth']."</td>";
        echo "<td align='center'>".$demo['Has_insurance']."</td>";
        echo "<td align='center'>".$demo['Ethnicity']."</td>";
        echo "<td align='center'>".$demo['Marital_status']."</td></tr>";

        echo "<tr><th>Home Phone</th>";
        echo "<th>Cell Phone</th>";
        echo "<th>Work Phone</th>";
        echo "<th>Email</th>";
        echo "<th>Previous Conditions</th>";
        echo "<th>Past Surgeries</th>";
        echo "<th>Blood Type</th>";
        echo "<th>Gender</th></tr>";
       
        echo "<tr><td align='center'>".$demo['Home_phone']."</td>";
        echo "<td align='center'>".$demo['Cell_phone']."</td>";
        echo "<td align='center'>".$demo['Work_phone']."</td>";
        echo "<td align='center'>".$demo['Email']."</td>";
        echo "<td>".$med['Prev_conditions']."</td>";
        echo "<td>".$med['Past_surgeries']."</td>";
        echo "<td align='center'>".$med['Blood_type']."</td>";
        echo "<td align='center'>".$demo['Sex']."</td></tr>";

        echo "<tr><th>Past Prescriptions</th>";
        echo "<th>Family History</th>";
        echo "<th>Primary Care/GP</th>";
        echo "<th>Other Doctors</th>";
        echo "<th>Nurse</th></tr>";

        echo "<tr><td>".$med['Past_prescriptions']."</td>";
        echo "<td>".$fam['Fam_History']."</td>";
        echo "<td align='center'>";
        
        while($docs_gp = mysqli_fetch_assoc($sql_gp)) {
            echo $docs_gp['Name']."<br>";
        }
        echo "</td>";

        echo "<td align='center'>";

        while($docs = mysqli_fetch_assoc($sql_doc)) {
            echo $docs['Name']." (".$docs['Specialization'].") <br>";
        }
        echo "</td>";

        if(isset($nurse['Name'])) {
            echo "<td align='center'>".$nurse['Name']."</td></tr>";
        } else {
            echo "<td align='center'></td></tr>";
        }

        echo "</table>";

    }

    //Displays prescription info for choosen PID
    function gen_prescriptions($PID) {

        $conn = sql_connect();

        $sql_pres = mysqli_query($conn, "SELECT * FROM Prescriptions WHERE Patient='$PID';");

        if (mysqli_num_rows($sql_pres)==0) {
            echo "No prescriptions<br>";
            return;
        }

        echo "<table><tr><th> Drug </th>";
        echo "<th> Dosage </th>";
        echo "<th> Refill </th>";
        echo "<th> Expiration Date </th>";
        echo "<th> Expired? </th>";
        echo "<th> Prescribing Doctor </th></tr>";

        while($pres = mysqli_fetch_assoc($sql_pres)) {

            echo "<tr><td align='center'>".$pres['Prescript_Name']."</td>";
            echo "<td align='center'>".$pres['Dosage']."</td>";
            echo "<td align='center'>".$pres['Refill']."</td>";
            echo "<td align='center'>".$pres['Expiration_date']."</td>";

            $current_time = date("Y-m-d H:i:s");

            if($current_time > $pres['Expiration_date']) {
                echo "<td align='center'> Yes </td>";
            } else {
                echo "<td align='center'> No </td>";
            }

            $sql_doc = mysqli_query($conn, "SELECT * FROM Doctors WHERE NPI='$pres[Prescribing_doc]';");
            $doc = mysqli_fetch_assoc($sql_doc);

            echo "<td align='center'>".$doc['Name']."</td></tr>";

        }

        echo "</table>";

    }

    //Generates patient info for every patient associated with doctor ID $NPI
    function gen_patient_info_doctor($NPI) {

        $conn = sql_connect();

        $sql_patient = mysqli_query($conn, "SELECT * FROM Doctor_patient WHERE NPI=".$NPI.";");

        while($patient = mysqli_fetch_assoc($sql_patient)) {
            gen_patient_info($patient['PID']);
            echo "<br>";
            gen_prescriptions($patient['PID']);
            echo "<br>";
            echo "<form action='doc_patients_mod.php' method='POST'>";
            echo "<input type='hidden' name='PID' value=".$patient['PID'].">";
            echo "<input type='submit' value='Modify Patient Record'></form>";
            echo "<br> =================== <br>";
        }

    }

    // ~~MODIFY LOGIC~~

    //Generates modify patient record form (sans form heading w/ method/action) for given PID
    function mod_patient($PID) {

        $conn = sql_connect();
        $sql_patient = mysqli_query($conn, "SELECT * FROM Patients WHERE PID=".$PID.";");
        $patient = mysqli_fetch_assoc($sql_patient);

        $sql_demo = mysqli_query($conn, "SELECT * FROM Demographics WHERE Demo_ID=".$patient['Demographics_ID'].";") or die(mysqli_error($conn));
        $demo = mysqli_fetch_assoc($sql_demo);

        $sql_med = mysqli_query($conn, "SELECT * FROM Medical_history WHERE Med_Hist_ID=".$patient['Med_Hist_ID'].";") or die(mysqli_error($conn));
        $med = mysqli_fetch_assoc($sql_med);

        $sql_fam = mysqli_query($conn, "SELECT * FROM Family_history WHERE Fam_Hist_ID=".$patient['Fam_Hist_ID'].";") or die(mysqli_error($conn));
        $fam = mysqli_fetch_assoc($sql_fam);

        echo "<br>You are modifying the record of ".$patient['First_Name']." ".$patient['Last_Name']." (".$PID.")<br><br>";

        echo "<input type='hidden' name='PID' value=".$PID.">";

        echo "<label for='First_Name'> First name: </label>";
        echo "<input type='text' name='First_Name' value=".$patient['First_Name']." required><br>";

        echo "<label for='Last_Name'> First name: </label>";
        echo "<input type='text' name='Last_Name' value=".$patient['Last_Name']." required><br>";

        echo "<label for='SSN'> Last 4 Digits SSN: </label>";
        echo "<input type='text' minlength=4 maxlength=4 name='SSN' value=".$patient['Last_4_SSN']." required><br>";

        echo "<label for='Age'> Age: </label>";
        echo "<input type='number' min='0' max='120' name='Age' value=".$demo['Age']." required><br>";

        echo "<label for='DOB'> Date of Birth: </label>";
        echo "<input type='date' name='DOB' value=".$demo['Date_of_birth']." required><br><br>";

        echo "Ethnicity. (Current value: ".$demo['Ethnicity'].")<br>";
        echo "<label for='Asian/Pacific Islander'>Asian/Pacific Islander</label>";
        echo "<input type='radio' name='ethnicity' value='Asian/Pacific Islander' required><br>";
        echo "<label for='African-American'>African-American</label>";
        echo "<input type='radio' name='ethnicity' value='African-American'><br>";
        echo "<label for='Native American'>Native American</label>";
        echo "<input type='radio' name='ethnicity' value='Native American'><br>";
        echo "<label for='White'>White</label>";
        echo "<input type='radio' name='ethnicity' value='White'><br>";
        echo "<label for='Hispanic'>Hispanic</label>";
        echo "<input type='radio' name='ethnicity' value='Hispanic'><br>";
        echo "<label for='Other'>Other</label>";
        echo "<input type='radio' name='ethnicity' value='Other'><br><br>";

        echo "Marital Status. (Current value: ".$demo['Marital_status'].")<br>";
        echo "<label for='Single'>Single</label>";
        echo "<input type='radio' name='marital' value='Single' required><br>";
        echo "<label for='Married'>Married</label>";
        echo "<input type='radio' name='marital' value='Married'><br>";
        echo "<label for='Widowed'>Widowed</label>";
        echo "<input type='radio' name='marital' value='Widowed'><br>";
        echo "<label for='Divorced'>Divorced</label>";
        echo "<input type='radio' name='marital' value='Divorced'><br>";
        echo "<label for='Separated'>Separated</label>";
        echo "<input type='radio' name='marital' value='Separated'><br><br>";

        echo "Insurance Status. (Current value: ".$demo['Has_insurance'].")<br>";
        echo "<label for='Yes'>Yes</label>";
        echo "<input type='radio' name='insurance' value='Yes' required><br>";
        echo "<label for='No'>No</label>";
        echo "<input type='radio' name='insurance' value='No'><br><br>";

        echo "<label for='home_phone'>Home phone:</label>";
        echo "<input type='tel' name='home_phone' pattern='\([0-9]{3}\) [0-9]{3}-[0-9]{4}' value='".$demo['Home_phone']."' required>";
        echo "<small> Format: (123) 345-1234</small><br>";

        echo "<label for='cell_phone'>Cell phone:</label>";
        echo "<input type='tel' name='cell_phone' pattern='\([0-9]{3}\) [0-9]{3}-[0-9]{4}' value='".$demo['Cell_phone']."'>";
        echo "<small> Format: (123) 345-1234</small><br>";

        echo "<label for='work_phone'>Work phone:</label>";
        echo "<input type='tel' name='work_phone' pattern='\([0-9]{3}\) [0-9]{3}-[0-9]{4}' value='".$demo['Work_phone']."'>";
        echo "<small> Format: (123) 345-1234</small><br>";

        echo "<label for='email'>Email address:</label>";
        echo "<input type='text' maxlength=80 name='email' value=".$demo['Email']." required><br><br>";        

        echo "Allergies:<br>";
        echo "<textarea name='allergies' maxlength='225' rows='4' cols='50'>".$demo['Allergies']."</textarea><br><br>";

        echo "Previous Conditions:<br>";
        echo "<textarea name='prev_cond' maxlength='225' rows='4' cols='50'>".$med['Prev_conditions']."</textarea><br><br>";

        echo "Past Surgeries:<br>";
        echo "<textarea name='past_surg' maxlength='225' rows='4' cols='50'>".$med['Past_surgeries']."</textarea><br><br>";

        echo "Previous Prescriptions:<br>";
        echo "<textarea name='past_prescript' maxlength='225' rows='4' cols='50'>".$med['Past_prescriptions']."</textarea><br><br>";

        echo "Family History:<br>";
        echo "<textarea name='family_hist' maxlength='225' rows='4' cols='50'>".$fam['Fam_History']."</textarea><br><br>";

    }

    //Generates modify nurse record form (sans form heading w/ method/action) for given NID
    function mod_nurse($NID) {

        $conn = sql_connect();
        $sql_nurse = mysqli_query($conn, "SELECT * FROM Nurses WHERE NID=".$NID.";");
        $nurse = mysqli_fetch_assoc($sql_nurse);

        echo "You are modifying the record of ".$nurse['Name']." (".$NID.")<br><br>";

        echo "<input type='hidden' name='NID' value=".$NID.">";

        echo "<label for='Name'> Name: </label>";
        echo "<input type='text' name='Name' value='".$nurse['Name']."' required><br>";

        echo "<label for='Email'> Email: </label>";
        echo "<input type='text' name='Email' maxlength=80 value='".$nurse['Email']."' required><br><br>";

        echo "Job Description:<br>";
        echo "<textarea name='job_desc' maxlength='225' rows='4' cols='50'>".$nurse['Job_description']."</textarea><br><br>";


    }

    //Generates modify prescription record form (sans form heading w/ method/action) for given ID
    function mod_prescript($Pres_ID) {

        $conn = sql_connect();
        $sql_pres = mysqli_query($conn, "SELECT * FROM Prescriptions WHERE Prescript_ID=".$Pres_ID.";");
        $pres = mysqli_fetch_assoc($sql_pres);

        echo "You are modifying prescription ID ".$Pres_ID."<br><br>";
        echo "This prescription is for PID: ".$pres['Patient']."<br>";

        echo "<input type='hidden' name='Prescript_ID' value=".$Pres_ID.">";
        echo "<input type='hidden' name='Patient' value=".$pres['Patient'].">";

        echo "<label for='Prescript_Name'> Drug Name: </label>";
        echo "<input type='text' name='Prescript_Name' value='".$pres['Prescript_Name']."' required><br>";

        echo "<label for='Dosage'> Dosage: </label>";
        echo "<input type='text' name='Dosage' value='".$pres['Dosage']."'required><br><br>";

        echo "Refill allowed? (Current value: ".$pres['Refill']."<br>";
        echo "<label for='Y'> Yes </label>";
        echo "<input type='radio' name='Refill' value='Y' required><br>";
        echo "<label for='N'> No </label>";
        echo "<input type='radio' name='Refill' value='N'><br><br>";

        echo "Expiration Date: <br>";
        echo "<input type='datetime-local' step=1800 name='Expiration_date' value=".$pres['Expiration_date']."' required><br><br>";


    }

    //Form for a new prescription
    function new_prescript() {

        echo "<label for='Patient'> PID: </label>";
        echo "<input type='text' name='Patient' required><br>";

        echo "<label for='Prescript_Name'> Drug Name: </label>";
        echo "<input type='text' name='Prescript_Name' required><br>";

        echo "<label for='Dosage'> Dosage: </label>";
        echo "<input type='text' name='Dosage' required><br><br>";

        echo "Refill allowed?<br>";
        echo "<label for='Y'> Yes </label>";
        echo "<input type='radio' name='Refill' value='Y' required><br>";
        echo "<label for='N'> No </label>";
        echo "<input type='radio' name='Refill' value='N'><br><br>";

        echo "Expiration Date: <br>";
        echo "<input type='datetime-local' step=1800 name='Expiration_date'><br><br>";
    }

    //Generates modify appointment record form (sans form heading w/ method/action)
    function mod_apmt($ID) {

        $conn = sql_connect();
        $sql_apmt = mysqli_query($conn, "SELECT * FROM Appointments WHERE Appt_ID=".$ID.";");
        $apmt = mysqli_fetch_assoc($sql_apmt);

        $sql_patient = mysqli_query($conn, "SELECT * FROM Patients WHERE PID=".$apmt['Patient_ID'].";");
        $patient = mysqli_fetch_assoc($sql_patient);

        $sql_curr_doc = mysqli_query($conn, "SELECT * FROM Doctors WHERE NPI=".$apmt['Doctor_ID'].";");
        $curr_doc = mysqli_fetch_assoc($sql_curr_doc);

        echo "You are modifying the record of Appointment ID ".$ID."<br>";
        echo "This appointment is scheduled for ".$patient['Last_Name'].", ".$patient['First_Name']." (".$patient['PID'].") <br>";
        echo "This appointment is currently scheduled on ".$apmt['Appointment_time']." with ".$curr_doc['Name']."<br><br>";

        echo "<input type='hidden' name='Appt_ID' value=".$ID.">";
        echo "<input type='hidden' name='PID' value=".$patient['PID'].">";

        echo "<label for='time'>Appointment Time</label>";
        echo "<input type='datetime-local' step=1800 name='time' value=".$apmt['Appointment_time']." required><br>";

        echo "<label for='doc'>Doctor:</label>";
        echo "<select id='doc' name='doc'>";
        echo "<option value=''></option>";
    
        $sql_doc = mysqli_query($conn, "SELECT * FROM Doctors;");

        while($doc = mysqli_fetch_assoc($sql_doc)) {
            echo "<option value=".$doc['NPI'].">".$doc['Name']."</option>";
        }

        echo "</select><br>";

        echo "<label for='clinic'>Location:</label>";
        echo "<select id='clinic' name='clinic'>";
        echo "<option value=''></option>";
    
        $sql_clinic = mysqli_query($conn, "SELECT * FROM Clinics;");

        while($clinic = mysqli_fetch_assoc($sql_clinic)) {
            echo "<option value=".$clinic['Clinic_ID'].">".$clinic['Clinic_name']."</option>";
        }

        echo "</select><br><br>";

    }

    //Generates modify doctor record form (sans form heading w/ method/action) for given NPI
    function mod_doctor($NPI) {

        $conn = sql_connect();
        $sql_doctor = mysqli_query($conn, "SELECT * FROM Doctors WHERE NPI=".$NPI.";");
        $doctor = mysqli_fetch_assoc($sql_doctor);

        echo "You are modifying the record of ".$doctor['Name']." (".$NPI.")<br><br>";

        echo "<input type='hidden' name='NPI' value=".$NPI.">";

        echo "<label for='Name'> Name: </label>";
        echo "<input type='text' name='Name' value='".$doctor['Name']."' required><br><br>";

        echo "<label for='work_phone'>Work phone:</label>";

        echo "<input type='tel' name='work_phone' pattern='\([0-9]{3}\) [0-9]{3}-[0-9]{4}' value='".$doctor['Work_phone']."' required>";
        echo "<small> Format: (123) 345-1234</small><br>";

        echo "<label for='fax'>Fax:</label>";

        if(!is_null($doctor['Fax'])) {

            echo "<input type='tel' name='fax' pattern='\([0-9]{3}\) [0-9]{3}-[0-9]{4}' value='".$doctor['Fax']."'>";

        } else {

            echo "<input type='tel' name='fax' pattern='\([0-9]{3}\) [0-9]{3}-[0-9]{4}'>";
        }

        
        echo "<small> Format: (123) 345-1234</small><br><br>";

        echo "<label for='email'>Email address:</label>";
        echo "<input type='text' maxlength=80 name='email' value=".$doctor['Email']." required><br><br>";

        echo "Specialist? Current value: ".$doctor['Specialist']."<br>";
        echo "<label for='Yes'>Yes</label>";
        echo "<input type='radio' name='Specialist' value='Yes' required><br>";
        echo "<label for='No'>No</label>";
        echo "<input type='radio' name='Specialist' value='No'><br><br>";

        echo "<label for='Specialization'> Specialization: </label>";
        echo "<input type='text' name='Specialization' value='".$doctor['Specialization']."' required><br><br>";

        echo "Which clinic(s) are they currently working out of?<br>";
        
        $sql_clinic = mysqli_query($conn, "SELECT DISTINCT * FROM Clinics;");
        
        while($clinic = mysqli_fetch_assoc($sql_clinic)) {    

            echo "<label for=".$clinic['Clinic_ID'].">".$clinic['Clinic_name']."</label>";
            echo "<input type='checkbox' name='Clinics[]' value=".$clinic['Clinic_ID']."><br>";
        }

    }

    //Generates list of patient names to modify
    function gen_mod_patient_list() {

        $conn = sql_connect();
        $sql_patient = mysqli_query($conn, "SELECT * FROM Patients ORDER BY Last_Name");

        echo "<table><tr><th> PID </th>";
        echo "<th> Last Name </th>";
        echo "<th> First Name </th>";
        echo "<th> Action </th>";
        echo "<th></th></tr>";

        while($patient = mysqli_fetch_assoc($sql_patient)) {

            echo "<tr><td> ".$patient['PID']." </td>";
            echo "<td> ".$patient['Last_Name']." </td>";
            echo "<td> ".$patient['First_Name']." </td>";
            echo "<td><form action='admin_mod_patient_process.php' method='POST'>";
            echo "<input type='hidden' name='PID' value=".$patient['PID'].">";
            echo "<input type='submit' value='Modify Record'></form></td>";
            echo "<td><form action='admin_delete.php' method='POST'>";
            echo "<input type='hidden' name='ID' value=".$patient['PID'].">";
            echo "<input type='hidden' name='ID_type' value='PID'>";
            echo "<input type='hidden' name='table' value='Patients'>";
            echo "<input type='submit' value='Delete Record' onclick='return confirm_delete()'></form></td></tr>";

        }

        echo "</table>";

    }

    //Generates list of nurse names to modify
    function gen_mod_nurse_list() {

        $conn = sql_connect();
        $sql_nurse = mysqli_query($conn, "SELECT * FROM Nurses ORDER BY Name");

        echo "<table><tr><th> NID </th>";
        echo "<th> Name </th>";
        echo "<th> Action </th>";
        echo "<th></th></tr>";

        while($nurse = mysqli_fetch_assoc($sql_nurse)) {

            echo "<tr><td> ".$nurse['NID']." </td>";
            echo "<td> ".$nurse['Name']." </td>";
            echo "<td><form action='admin_mod_nurse_process.php' method='POST'>";
            echo "<input type='hidden' name='NID' value=".$nurse['NID'].">";
            echo "<input type='submit' value='Modify Record'></form></td>";
            echo "<td><form action='admin_delete.php' method='POST'>";
            echo "<input type='hidden' name='ID' value=".$nurse['NID'].">";
            echo "<input type='hidden' name='ID_type' value='NID'>";
            echo "<input type='hidden' name='table' value='Nurses'>";
            echo "<input type='submit' value='Delete Record' onclick='return confirm_delete()'></form></td></tr>";

        }

        echo "</table>";

    }

    //Generates list of current prescriptions for doctor NPI
    function gen_mod_prescript_list($NPI) {
        
        $conn = sql_connect();
        $sql_pres = mysqli_query($conn, "SELECT * FROM Prescriptions WHERE Prescribing_doc=".$NPI.";");

        echo "<table><tr><th>Prescription ID</th><th>Drug Name</th><th>Refill?</th><th>Patient ID</th><th>Expiration Date</th><th>Action</th><th></th></tr>";

        while ($pres = mysqli_fetch_assoc($sql_pres)) {

            echo "<tr><td> ".$pres['Prescript_ID']."</td><td>".$pres['Prescript_Name']." </td><td>".$pres['Refill']."</td><td>".$pres['Patient']."</td><td>".$pres['Expiration_date']."</td>";

            echo "<td><form action='doc_mod_prescript.php' method='POST'><input type='hidden' name='Prescript_ID' value=".$pres['Prescript_ID']."><input type='submit' value='Modify Script'></form></td>";
            echo "<td><form action='doc_delete_prescript.php' method='POST'><input type='hidden' name='Prescript_ID' value=".$pres['Prescript_ID']."><input type='submit' value='Delete Script' onclick='return confirm_delete()'></form></td></tr>";

        }

        echo "</table>";
    }

    //Generates list of doctor names to modify
    function gen_mod_doctor_list() {

        $conn = sql_connect();
        $sql_doctor = mysqli_query($conn, "SELECT * FROM Doctors ORDER BY Name");

        echo "<table><tr><th> NID </th>";
        echo "<th> Name </th>";
        echo "<th> Action </th>";
        echo "<th></th></tr>";

        while($doctor = mysqli_fetch_assoc($sql_doctor)) {

            echo "<tr><td> ".$doctor['NPI']." </td>";
            echo "<td> ".$doctor['Name']." </td>";
            echo "<td><form action='admin_mod_doctor_process.php' method='POST'>";
            echo "<input type='hidden' name='NPI' value=".$doctor['NPI'].">";
            echo "<input type='submit' value='Modify Record'></form></td>";
            echo "<td><form action='admin_delete.php' method='POST'>";
            echo "<input type='hidden' name='ID' value=".$doctor['NPI'].">";
            echo "<input type='hidden' name='ID_type' value='NPI'>";
            echo "<input type='hidden' name='table' value='Doctors'>";
            echo "<input type='submit' value='Delete Record' onclick='return confirm_delete()'></form></td></tr>";
        }

        echo "</table>";        

    }

    //Generates a button for a nurse to modify an appointment
    function gen_mod_apmt($query) {

        $conn = sql_connect();
        $sql_apmt = mysqli_query($conn, $query) or die(mysqli_error($conn));

        if(mysqli_num_rows($sql_apmt) == 0) {
            echo "No appointments found";
            return;
        }

        echo "<table><tr><th> Appointment ID </th><th> Doctor </th><th> Patient </th><th> Clinic </th><th> Appointment Time </th><th>Action</th><th></th></tr>";

        while($apmt = mysqli_fetch_assoc($sql_apmt)) {
            
            $sql_patient = mysqli_query($conn, "SELECT * FROM Patients WHERE PID=".$apmt['Patient_ID'].";");
            $patient = mysqli_fetch_assoc($sql_patient);

            $sql_doc = mysqli_query($conn, "SELECT * FROM Doctors WHERE NPI=".$apmt['Doctor_ID'].";");
            $doc = mysqli_fetch_assoc($sql_doc);

            $sql_clinic = mysqli_query($conn, "SELECT * FROM Clinics WHERE Clinic_ID=".$apmt['Clinic_ID'].";");
            $clinic = mysqli_fetch_assoc($sql_clinic);

            echo "<tr><td>".$apmt['Appt_ID']."</td><td>".$doc['Name']." (".$doc['NPI'].")</td><td>".$patient['Last_Name'].", ".$patient['First_Name']." (".$patient['PID'].") </td><td>".$clinic['Clinic_name']."</td><td>".$apmt['Appointment_time']."</td>";

            echo "<td><form action='nurse_edit_appointments.php' method='POST'><input type='hidden' name='Appt_ID' value=".$apmt['Appt_ID']."><input type='submit' value='Modify Appt'></form></td>";
            echo "<td><form action='nurse_delete_appointments.php' method='POST'><input type='hidden' name='Appt_ID' value=".$apmt['Appt_ID']."><input type='submit' value='Delete Appt' onclick='return confirm_delete()'></form></td></tr>";

        }

        echo "</table>";

    }

    //Generates patient appointment calendar
    function gen_apmt_calendar($PID) {

        $conn = sql_connect();
        $sql_apmt = mysqli_query($conn, "SELECT * FROM Appointments WHERE Patient_ID=".$PID.";") or die(mysqli_error($conn));

        if(mysqli_num_rows($sql_apmt) == 0) {
            echo "No appointments found";
            return;
        }

        echo "<table><tr><th> Appointment ID </th><th> Doctor </th><th> Patient </th><th> Clinic </th><th> Appointment Time </th><th>Action</th></tr>";

        while($apmt = mysqli_fetch_assoc($sql_apmt)) {
            
            $sql_patient = mysqli_query($conn, "SELECT * FROM Patients WHERE PID=".$apmt['Patient_ID'].";");
            $patient = mysqli_fetch_assoc($sql_patient);

            $sql_doc = mysqli_query($conn, "SELECT * FROM Doctors WHERE NPI=".$apmt['Doctor_ID'].";");
            $doc = mysqli_fetch_assoc($sql_doc);

            $sql_clinic = mysqli_query($conn, "SELECT * FROM Clinics WHERE Clinic_ID=".$apmt['Clinic_ID'].";");
            $clinic = mysqli_fetch_assoc($sql_clinic);

            echo "<tr><td>".$apmt['Appt_ID']."</td><td>".$doc['Name']." (".$doc['NPI'].")</td><td>".$patient['Last_Name'].", ".$patient['First_Name']." (".$patient['PID'].") </td><td>".$clinic['Clinic_name']."</td><td>".$apmt['Appointment_time']."</td>";

            echo "<td><form action='patient_delete_appointment.php' method='POST'><input type='hidden' name='Appt_ID' value=".$apmt['Appt_ID']."><input type='submit' value='Delete Appt' onclick='return confirm_delete()'></form></td></tr>";

        }

        echo "</table>";

    }

    // ~~~DEMOGRAPHIC REPORT LOGIC~~~

    //Generates demographic report for specific doctor
    function demo_report_doc($NPI) {

        $conn = sql_connect();

        $sql_pid = mysqli_query($conn, "SELECT * FROM Doctor_patient WHERE NPI=".$NPI.";") or die(mysqli_error($conn));

        $sql_doc = mysqli_query($conn, "SELECT * FROM Doctors WHERE NPI=".$NPI.";") or die(mysqli_error($conn));
        $doc = mysqli_fetch_assoc($sql_doc);

        $insurance_Y = 0;
        $insurance_N = 0;
        $age = 0;
        $sex_M = 0;
        $sex_F = 0;
        $sex_Other = 0;

        $ethn_api = 0;
        $ethn_afam = 0;
        $ethn_natam = 0;
        $ethn_white = 0;
        $ethn_hisp = 0;
        $ethn_other = 0;

        $marital_sing = 0;
        $marital_marr = 0;
        $marital_widow = 0;
        $marital_divor = 0;
        $marital_separ = 0;

        $total = 0;

        if($sql_pid) {

            while($pid = mysqli_fetch_assoc($sql_pid)) {

                $total++;

                $sql_patient = mysqli_query($conn, "SELECT * FROM Patients WHERE PID=".$pid['PID'].";") or die(mysqli_error($conn));
                $patient = mysqli_fetch_assoc($sql_patient);

                $sql_demo = mysqli_query($conn, "SELECT * FROM Demographics WHERE Demo_ID=".$patient['Demographics_ID'].";") or die(mysqli_error($conn));
                $demo = mysqli_fetch_assoc($sql_demo);

                if(strcmp($demo['Has_insurance'],'Yes') == 0) {
                    $insurance_Y++;
                } else {
                    $insurance_N++;
                }

                $age += $demo['Age'];

                if(strcmp($demo['Sex'],'M') == 0) {
                    $sex_M++;
                } elseif (strcmp($demo['Sex'],'F') == 0) {
                    $sex_F++;
                } else {
                    $sex_Other++;
                }

                if (strcmp($demo['Ethnicity'], 'Asian/Pacific Islander') == 0) {
                    $ethn_api++;
                } elseif (strcmp($demo['Ethnicity'], 'African-American') == 0) {
                    $ethn_afam++;
                } elseif (strcmp($demo['Ethnicity'], 'Native American') == 0) {
                    $ethn_natam++;
                } elseif (strcmp($demo['Ethnicity'], 'White') == 0) {
                    $ethn_white++;
                } elseif (strcmp($demo['Ethnicity'], 'Hispanic') == 0) {
                    $ethn_hisp++;
                } else {
                    $ethn_other++;
                }

                if(strcmp($demo['Marital_status'], 'Single') == 0) {
                    $marital_sing++;
                } elseif (strcmp($demo['Marital_status'], 'Married') == 0) {
                    $marital_marr++;
                } elseif (strcmp($demo['Marital_status'], 'Widowed') == 0) {
                    $marital_widow++;
                } elseif (strcmp($demo['Marital_status'], 'Divorced') == 0) {
                    $marital_divor++;
                } else {
                    $marital_separ++;
                }

            }

            if($total > 0) {
                $insurance_Y = number_format($insurance_Y/(float)$total, 3);
                $insurance_N = number_format($insurance_N/(float)$total, 3);
                $sex_M = number_format($sex_M/(float)$total, 3);
                $sex_F = number_format($sex_F/(float)$total, 3);
                $sex_Other = number_format($sex_Other/(float)$total, 3);
                $age = number_format($age/(float)$total, 1);
                $marital_sing = number_format($marital_sing/(float)$total, 3);
                $marital_marr = number_format($marital_marr/(float)$total, 3);
                $marital_widow = number_format($marital_widow/(float)$total, 3);
                $marital_divor = number_format($marital_divor/(float)$total, 3);
                $marital_separ = number_format($marital_separ, 3);
                $ethn_api = number_format($ethn_api/(float)$total, 3);
                $ethn_afam = number_format($ethn_afam/(float)$total, 3);
                $ethn_natam = number_format($ethn_natam/(float)$total, 3);
                $ethn_white = number_format($ethn_white/(float)$total, 3);
                $ethn_hisp = number_format($ethn_hisp/(float)$total, 3);
                $ethn_other = number_format($ethn_other/(float)$total, 3);

                echo "<h3> Patient Demographics for ".$doc['Name']."</h3><br>";
                echo "<table><tr><th>[Has Insurance]</th><th></th><th>[Gender]</th><th></th><th></th></tr>";
                echo "<tr><th>Yes</th><th>No</th><th>Male</th><th>Female</th><th>Other</th></tr>";
                echo "<tr><td><center>".($insurance_Y*100)."%</center></td><td><center>".($insurance_N*100)."%</center></td><td><center>".($sex_M*100)."%</center></td><td><center>".($sex_F*100)."%</center></td><td><center>".($sex_Other*100)."%</center></td></tr>";
                echo "<tr><th>[Average Age]</th><th></th><th></th><th>[Marital Status]</th><th></th><th></th><th></th></tr>";
                echo "<tr><th></th><th>Single</th><th>Married</th><th>Widowed</th><th>Divorced</th><th>Separated</th></tr>";
                echo "<tr><td><center>".$age."</center></td><td><center>".($marital_sing*100)."%</center></td><td><center>".($marital_marr*100)."%</center></td><td><center>".($marital_widow*100)."%</center></td><td><center>".($marital_divor*100)."%</center></td><td><center>".($marital_separ*100)."%</center></td></tr>";
                echo "<tr><th></th><th></th><th>[Ethnicity]</th><th></th><th></th><th></th></tr>";
                echo "<tr><th>Asian/Pacific Islander</th><th>Africa-American</th><th>Native American</th><th>White</th><th>Hispanic</th><th>Other</th></tr>";
                echo "<tr><td><center>".($ethn_api*100)."%</center></td><td><center>".($ethn_afam*100)."%</center></td><td><center>".($ethn_natam*100)."%</center></td><td><center>".($ethn_white*100)."%</center></td><td><center>".($ethn_hisp*100)."%</center></td><td><center>".($ethn_other*100)."%</center></td></tr></table>";

            } else {

                echo "<h3> Patient Demographics for ".$doc['Name']."</h3><br>";
                echo "No patients found for this doctor";

            }
            

        } else {

            echo "No patients found";

        }

    }

    //Generates demographic report for entire clinic
    function demo_report_all() {

        $conn = sql_connect();

        $sql_patient = mysqli_query($conn, "SELECT * FROM Patients;");

        $insurance_Y = 0;
        $insurance_N = 0;
        $age = 0;
        $sex_M = 0;
        $sex_F = 0;
        $sex_Other = 0;

        $ethn_api = 0;
        $ethn_afam = 0;
        $ethn_natam = 0;
        $ethn_white = 0;
        $ethn_hisp = 0;
        $ethn_other = 0;

        $marital_sing = 0;
        $marital_marr = 0;
        $marital_widow = 0;
        $marital_divor = 0;
        $marital_separ = 0;

        $total = 0;

        if($sql_patient) {

            while($patient = mysqli_fetch_assoc($sql_patient)) {

                $total++;

                $sql_demo = mysqli_query($conn, "SELECT * FROM Demographics WHERE Demo_ID=".$patient['Demographics_ID'].";") or die(mysqli_error($conn));
                $demo = mysqli_fetch_assoc($sql_demo);

                if(strcmp($demo['Has_insurance'],'Yes') == 0) {
                    $insurance_Y++;
                } else {
                    $insurance_N++;
                }

                $age += $demo['Age'];

                if(strcmp($demo['Sex'],'M') == 0) {
                    $sex_M++;
                } elseif (strcmp($demo['Sex'],'F') == 0) {
                    $sex_F++;
                } else {
                    $sex_Other++;
                }

                if (strcmp($demo['Ethnicity'], 'Asian/Pacific Islander') == 0) {
                    $ethn_api++;
                } elseif (strcmp($demo['Ethnicity'], 'African-American') == 0) {
                    $ethn_afam++;
                } elseif (strcmp($demo['Ethnicity'], 'Native American') == 0) {
                    $ethn_natam++;
                } elseif (strcmp($demo['Ethnicity'], 'White') == 0) {
                    $ethn_white++;
                } elseif (strcmp($demo['Ethnicity'], 'Hispanic') == 0) {
                    $ethn_hisp++;
                } else {
                    $ethn_other++;
                }

                if(strcmp($demo['Marital_status'], 'Single') == 0) {
                    $marital_sing++;
                } elseif (strcmp($demo['Marital_status'], 'Married') == 0) {
                    $marital_marr++;
                } elseif (strcmp($demo['Marital_status'], 'Widowed') == 0) {
                    $marital_widow++;
                } elseif (strcmp($demo['Marital_status'], 'Divorced') == 0) {
                    $marital_divor++;
                } else {
                    $marital_separ++;
                }

            }

            $insurance_Y = number_format($insurance_Y/(float)$total, 3);
            $insurance_N = number_format($insurance_N/(float)$total, 3);
            $sex_M = number_format($sex_M/(float)$total, 3);
            $sex_F = number_format($sex_F/(float)$total, 3);
            $sex_Other = number_format($sex_Other/(float)$total, 3);
            $age = number_format($age/(float)$total, 1);
            $marital_sing = number_format($marital_sing/(float)$total, 3);
            $marital_marr = number_format($marital_marr/(float)$total, 3);
            $marital_widow = number_format($marital_widow/(float)$total, 3);
            $marital_divor = number_format($marital_divor/(float)$total, 3);
            $marital_separ = number_format($marital_separ, 3);
            $ethn_api = number_format($ethn_api/(float)$total, 3);
            $ethn_afam = number_format($ethn_afam/(float)$total, 3);
            $ethn_natam = number_format($ethn_natam/(float)$total, 3);
            $ethn_white = number_format($ethn_white/(float)$total, 3);
            $ethn_hisp = number_format($ethn_hisp/(float)$total, 3);
            $ethn_other = number_format($ethn_other/(float)$total, 3);


            echo "<h3> Patient Demographics for Entire Clinic</h3><br>";
            echo "<table><tr><th>[Has Insurance]</th><th></th><th>[Gender]</th><th></th><th></th></tr>";
            echo "<tr><th>Yes</th><th>No</th><th>Male</th><th>Female</th><th>Other</th></tr>";
            echo "<tr><td><center>".($insurance_Y*100)."%</center></td><td><center>".($insurance_N*100)."%</center></td><td><center>".($sex_M*100)."%</center></td><td><center>".($sex_F*100)."%</center></td><td><center>".($sex_Other*100)."%</center></td></tr>";
            echo "<tr><th>[Average Age]</th><th></th><th></th><th>[Marital Status]</th><th></th><th></th><th></th></tr>";
            echo "<tr><th></th><th>Single</th><th>Married</th><th>Widowed</th><th>Divorced</th><th>Separated</th></tr>";
            echo "<tr><td><center>".$age."</center></td><td><center>".($marital_sing*100)."%</center></td><td><center>".($marital_marr*100)."%</center></td><td><center>".($marital_widow*100)."%</center></td><td><center>".($marital_divor*100)."%</center></td><td><center>".($marital_separ*100)."%</center></td></tr>";
            echo "<tr><th></th><th></th><th>[Ethnicity]</th><th></th><th></th><th></th></tr>";
            echo "<tr><th>Asian/Pacific Islander</th><th>Africa-American</th><th>Native American</th><th>White</th><th>Hispanic</th><th>Other</th></tr>";
            echo "<tr><td><center>".($ethn_api*100)."%</center></td><td><center>".($ethn_afam*100)."%</center></td><td><center>".($ethn_natam*100)."%</center></td><td><center>".($ethn_white*100)."%</center></td><td><center>".($ethn_hisp*100)."%</center></td><td><center>".($ethn_other*100)."%</center></td></tr></table>";

        } else {

            echo "No patients found";

        }

    }

    // ~~~ACTION/REPORT LOGIC~~~

    //Records user action
    function record_action($User_Type, $User_ID, $Action_Type, $Record_Modified) {

        $conn = sql_connect();

        date_default_timezone_set('America/Chicago');
        $time = date("Y-m-d H:i:s");

        mysqli_query($conn, "INSERT INTO Actions VALUES(NULL,'".$User_Type."',".$User_ID.",'".$Action_Type."',".$Record_Modified.",'".$time."');") or die(mysqli_error($conn));

    }

    //Prints out list of user actions from datetime range low to high
    function print_action($query) {

        $conn = sql_connect();

        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

        echo "<table>";
        echo "<tr><th> Action ID </th>";
        echo "<th> User ID </th>";
        echo "<th> User Type </th>";
        echo "<th> Action Type </th>";
        echo "<th> Record Modified </th>";
        echo "<th> Action Time </th></tr>";

        while($action = mysqli_fetch_assoc($res)) {

            echo "<tr><td>".$action['Action_ID']."</td>";
            echo "<td>".$action['User_ID']."</td>";
            echo "<td>".$action['User_Type']."</td>";
            echo "<td>".$action['Action_Type']."</td>";
            echo "<td>".$action['Record_Modified_ID']."</td>";
            echo "<td>".$action['Action_Time']."</td></tr>";

        }
        echo "</table>";
 
    }

    //Calculates daily frequency of all action type for interval
    function action_freq($low, $high, $action_type) {

        $conn = sql_connect();

        $query1 = "SELECT * FROM Actions WHERE (Action_Time BETWEEN '".$low."' AND '".$high."');";
        $query2 = "SELECT * FROM Actions WHERE (Action_Time BETWEEN '".$low."' AND '".$high."') AND (Action_Type='".$action_type."');";

        $sql_res1 = mysqli_query($conn, $query1);
        $sql_res2 = mysqli_query($conn, $query2);

        if (mysqli_num_rows($sql_res1) == 0) {
            return 0;
        }
    
        return number_format(mysqli_num_rows($sql_res2)/(float)mysqli_num_rows($sql_res1), 2);

    }

    //Calculates daily user type frequency for interval
    function user_freq($low, $high, $user_type) {

        $conn = sql_connect();

        $query1 = "SELECT * FROM Actions WHERE (Action_Time BETWEEN '".$low."' AND '".$high."');";
        $query2 = "SELECT * FROM Actions WHERE (Action_Time BETWEEN '".$low."' AND '".$high."') AND (User_Type='".$user_type."');";

        $sql_res1 = mysqli_query($conn, $query1);
        $sql_res2 = mysqli_query($conn, $query2);

        if (mysqli_num_rows($sql_res1) == 0) {
            return 0;
        }
    
        return number_format(mysqli_num_rows($sql_res2)/(float)mysqli_num_rows($sql_res1), 2);

    }

    //Total num of actions per user type in interval
    function total_action_user($low, $high, $user_type) {

        $conn = sql_connect();

        $query = "SELECT * FROM Actions WHERE (Action_Time BETWEEN '".$low."' AND '".$high."') AND (User_Type='".$user_type."');";
        $sql_res = mysqli_query($conn, $query);

        return mysqli_num_rows($sql_res);
    }

    //Total num of actions per action type in interval
    function total_action_action($low, $high, $action_type) {

        $conn = sql_connect();

        $query = "SELECT * FROM Actions WHERE (Action_Time BETWEEN '".$low."' AND '".$high."') AND (Action_Type='".$action_type."');";
        $sql_res = mysqli_query($conn, $query);

        return mysqli_num_rows($sql_res);
    }

    //Generates action report for interval
    function action_report($low, $high) {

        $range_l = '';
        $range_h = '';

        if(strcmp($low, '1000-01-01 00:00:00') == 0) {
            $range_l = "site's beginning";
        } else {
            $range_l = $low;
        }

        if(strcmp($high, '9999-12-31 23:59:59') == 0) {
            $range_h = "now";
        } else {
            $range_h = $high;
        }

        $freq_patient = user_freq($low, $high, "Patient");
        $freq_nurse = user_freq($low, $high, "Nurse");
        $freq_doctor = user_freq($low, $high, "Doctor");
        $freq_admin = user_freq($low, $high, "Admin");

        $total_patient = total_action_user($low, $high, "Patient");
        $total_nurse = total_action_user($low, $high, "Nurse");
        $total_doctor = total_action_user($low, $high, "Doctor");
        $total_admin = total_action_user($low, $high, "Admin");

        $freq_login = action_freq($low, $high, "Logged In");
        $freq_logout = action_freq($low, $high, "Logged Out");
        $freq_newuser = action_freq($low, $high, "Created New User");
        $freq_modrec = action_freq($low, $high, "Modified Record");
        $freq_apmt = action_freq($low, $high, "Scheduled Appointment");
        $freq_prescript = action_freq($low, $high, "Prescription Written");

        $total_login = total_action_action($low, $high, "Logged In");
        $total_logout = total_action_action($low, $high, "Logged Out");
        $total_newuser = total_action_action($low, $high, "Created New User");
        $total_modrec = total_action_action($low, $high, "Modified Record");
        $total_apmt = total_action_action($low, $high, "Scheduled Appointment");
        $total_prescript = total_action_action($low, $high, "Prescription Written");

        echo "<h2>Activity report for interval from ".$range_l." to ".$range_h."</h2><br>";
        
        echo "<h3>Average Daily Actions per User Type</h3>";
        echo "<table><tr><th>Patients</th><th>Nurses</th><th>Doctors</th><th>Admin</th></tr>";
        echo "<tr><td><center>".$freq_patient."</center></td><td><center>".$freq_nurse."</center></td><td><center>".$freq_doctor."</center></td><td><center>".$freq_admin."</center></td></tr></table>";

        echo "<h3>Total number of Actions per User Type</h3>";
        echo "<table><tr><th>Patients</th><th>Nurses</th><th>Doctors</th><th>Admin</th></tr>";
        echo "<tr><td><center>".$total_patient."</center></td><td><center>".$total_nurse."</center></td><td><center>".$total_doctor."</center></td><td><center>".$total_admin."</center></td></tr></table>";

        echo "<h3>Average Daily Actions per Action Type</h3>";
        echo "<table><tr><td>Logins: ".$freq_login." </td><td>Logouts: ".$freq_logout." </td></tr>";
        echo "<tr><td>New Users Created: ".$freq_newuser." </td><td>Records Modified: ".$freq_modrec." </td></tr>";
        echo "<tr><td>Appointments Scheduled: ".$freq_apmt."</td><td>Prescriptions Written: ".$freq_prescript."</td></tr></table>";

        echo "<h3>Total number of Actions per Action Type</h3>";
        echo "<table><tr><td>Logins: ".$total_login." </td><td>Logouts: ".$total_logout." </td></tr>";
        echo "<tr><td>New Users Created: ".$total_newuser." </td><td>Records Modified: ".$total_modrec." </td></tr>";
        echo "<tr><td>Appointments Scheduled: ".$total_apmt."</td><td>Prescriptions Written: ".$total_prescript."</td></tr></table>";


    }

    function print_session() {

        echo "Hello ".$_SESSION['User_Type'];
    }


?>

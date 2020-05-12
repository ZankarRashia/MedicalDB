<!DOCTYPE html>
<html>

<link rel="stylesheet" type = "text/css" href="css/patient_portal_style.css" />


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
<h2> <center> THANK YOU FOR CHOOSING U of H MEDICAL CENTER </center> </h2><br>

<body>

Please enter the following information to create your patient profile:<br><br>
<form action="patient_new_process.php" method="POST">
<label for="First_name"> First name: </label>
<input type="text" name="First_name" required><br>

<label for="Last_name"> Last name: </label>
<input type="text" name="Last_name" required><br><br>

Enter your patient password:
<input type="password" maxlength="80" name="pwd" required><br><br>

<label for="SSN"> Last 4 digits of SSN: </label>
<input type="text" minlength=4 maxlength=4 name="SSN" required><br><br>

What is your gender:<br>
<label for="M">Male</label>
<input type="radio" name="gender" value="M" required><br>
<label for="F">Female</label>
<input type="radio" name="gender" value="F"><br>
<label for="Other">Other</label>
<input type="radio" name="gender" value="Other"><br><br>

<label for="Age"> What is your age? </label>
<input type="number" min="0" max="120" name="Age" required><br>

<label for="DOB"> What is your date of birth </label>
<input type="date" name="DOB" required><br><br>

Do you have insurance?<br>
<label for="Yes">Yes</label>
<input type="radio" name="insurance" value="Yes" required><br>
<label for="No">No</label>
<input type="radio" name="insurance" value="No"><br><br>

What is your ethnicity?<br>
<label for="Asian/Pacific Islander">Asian/Pacific Islander</label>
<input type="radio" name="ethnicity" value="Asian/Pacific Islander" required><br>
<label for="African-American">African-American</label>
<input type="radio" name="ethnicity" value="African-American"><br>
<label for="Native American">Native American</label>
<input type="radio" name="ethnicity" value="Native American"><br>
<label for="White">White</label>
<input type="radio" name="ethnicity" value="White"><br>
<label for="Hispanic">Hispanic</label>
<input type="radio" name="ethnicity" value="Hispanic"><br>
<label for="Other">Other</label>
<input type="radio" name="ethnicity" value="Other"><br><br>

What is your marital status?<br>
<label for="Single">Single</label>
<input type="radio" name="marital" value="Single" required><br>
<label for="Married">Married</label>
<input type="radio" name="marital" value="Married"><br>
<label for="Widowed">Widowed</label>
<input type="radio" name="marital" value="Widowed"><br>
<label for="Divorced">Divorced</label>
<input type="radio" name="marital" value="Divorced"><br>
<label for="Separated">Separated</label>
<input type="radio" name="marital" value="Separated"><br><br>

What is your blood type?<br>
<label for="A+">A+</label>
<input type="radio" name="blood_type" value="A+"><br>
<label for="A-">A-</label>
<input type="radio" name="blood_type" value="A-"><br>
<label for="B+">B+</label>
<input type="radio" name="blood_type" value="B+"><br>
<label for="B-">B-</label>
<input type="radio" name="blood_type" value="B-"><br>
<label for="AB+">AB+</label>
<input type="radio" name="blood_type" value="AB+"><br>
<label for="AB-">AB-</label>
<input type="radio" name="blood_type" value="AB-"><br>
<label for="O+">O+</label>
<input type="radio" name="blood_type" value="O+"><br>
<label for="O-">O-</label>
<input type="radio" name="blood_type" value="O-"><br><br>

<label for="home_phone">Home phone:</label>
<input type="tel" name="home_phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" required>
<small> Format: (123) 345-1234</small><br>

<label for="cell_phone">Cell phone:</label>
<input type="tel" name="cell_phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}">
<small> Format: (123) 345-1234</small><br>

<label for="work_phone">Work phone:</label>
<input type="tel" name="work_phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}">
<small> Format: (123) 345-1234</small><br>

<label for="email">Email address:</label>
<input type="text" name="email" required><br><br>

<label for="allergies">Allergies:</label>
<textarea name="allergies" maxlength="225" rows="4" cols="50"></textarea><br><br>

<label for="prev_cond">Previous Conditions:</label>
<textarea name="prev_cond" maxlength="225" rows="4" cols="50"></textarea><br><br>

<label for="past_surg">Past Surgeries:</label>
<textarea name="past_surg" maxlength="225" rows="4" cols="50"></textarea><br><br>

<label for="past_prescript">Past Prescriptions:</label>
<textarea name="past_prescript" maxlength="225" rows="4" cols="50"></textarea><br><br>

<label for="family_hist">Family History:</label>
<textarea name="family_hist" maxlength="225" rows="4" cols="50"></textarea><br><br>

<input type="submit" value="Create Patient Profile"><br><br>

</form>

</body>
</html>

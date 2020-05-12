<!DOCTYPE html>
<html>
    
<link rel="stylesheet" type = "text/css" href="css/login_style.css" />

<head>
    <title>Login Options</title>
</head>

<nav> 
        <p>UH Medical Clinic</p>
        <ul>
                <li><a href="homepage.php">Home</a></li>
                <li><a href="login_options.php">Login</a></li>
                <li><a href="about_us.php">About Us</a></li>
        </ul>
</nav>
<br>
<br>
<div class="box">  
    <h1> <center> Choose Your Login: </center> </h1>
    <br>
    
    <body>
        
        <center> <input type = "radio" name = "choose" value = "Doctor" onclick = "document.location.href = 'doc_login.php'" /> Doctor </center><br>
        <center> <input type = "radio" name = "choose" value = "Patient" onclick = "document.location.href = 'patient_login.php'" /> Patient </center><br> 
		 <center> <input type = "radio" name = "choose" value = "Nurse" onclick = "document.location.href = 'nurse_login.php'" /> Nurse </center><br>
	   <center> <input type = "radio" name = "choose" value = "Admin" onclick = "document.location.href = 'admin_login.php'" /> Admin </center>

    </body>
</div>
</html>

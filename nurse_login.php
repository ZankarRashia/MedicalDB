<!DOCTYPE html>
<html>
<head>
    <title> Nurse Login </title>
</head>
<link rel="stylesheet" type="text/css" href="css/nurse_login_style.css" />

<nav> 
        <p>UH Medical Clinic</p>
        <ul>

                <li><a href="homepage.php">Home</a></li>
                <li><a href="login_options.php">Login</a></li>
                <li><a href="about_us.php">About Us</a></li>
        </ul>
</nav>
<br>


<center><h2> Nurse Login </h2></center><br>

<center><form action="nurse_login_process.php" method="POST">
<label for="PID"> Enter your NID:</label>
<input type="text" minlength="5" maxlength="5" name="NID" required><br>
<label for="Password"> Enter your Password:</label>
<input type="password" maxlength="80" name="password" required><br>
<input type="submit" value="Login"><br>
</form>
</center>

<center>
    <a href="login_options.php">Return to Login Options menu</a>
</center><br>

</html>

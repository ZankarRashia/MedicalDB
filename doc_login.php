<!DOCTYPE html>
<html>
<head>
	<title> Doctor Login </title>
</head>
<link rel="stylesheet" type="text/css" href="css/doc_login.css" />

<nav> 
        <p>UH Medical Clinic</p>
        <ul>
                <li><a href="homepage.php">Home</a></li>
                <li><a href="login_options.php">Login</a></li>
                <li><a href="about_us.php">About Us</a></li>

        </ul>
</nav>

<div class="box">
<center><h2> Doctor Login </h2></center><br>

<center><form action="doc_login_process.php" method="POST">
<label for="PID"> Enter your NPI:</label>
<input type="text" minlength="10" maxlength="10" name="NPI" required><br>
<label for="Password"> Enter your password:</label>
<input type="password" maxlength="80" name="password" required><br>
<input type="submit" value="Login"><br>
</form>
</center>
<center>
	<a href="login_options.php">Return to Login Options menu</a>
</center><br>
</div>
</html>

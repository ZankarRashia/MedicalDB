<!DOCTYPE html>
<html>
<head>
    <title> Admin Login </title>
</head>
<link rel="stylesheet" type="text/css" href="css/admin_login.css" />

<nav> 
        <p>UH Medical Clinic</p>
        <ul>
                <li><a href="homepage.php">Home</a></li>
                <li><a href="login_options.php">Login</a></li>
                <li><a href="about_us.php">About Us</a></li>
        </ul>
</nav>

<div class="box">
<center><h2> Admin Login </h2></center><br>

<center><form action="admin_login_process.php" method="POST">
<label for="PID"> Enter your Admin ID:</label>
<input type="text" minlength="4" maxlength="4" name="Admin_ID" required><br>
<label for="Password"> Enter your Password:</label>
<input type="password" maxlength="80" name="password" required><br>
<input type="submit" value="Login"><br>
</form>
</center>

<center>
    <a href="login_options.php">Return to Login Options Menu</a>
</center><br>
</div>
</html>

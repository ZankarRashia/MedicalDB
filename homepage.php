<!DOCTYPE html>
<html>

<head>
    <title> UH Medical Clinic Homepage </title>
</head>
    
    <link rel="stylesheet" type="text/css" href="css/homepage_styling.css" />

<body>
<nav>
        <p>UH Medical Clinic</p>
        <div class="logo">
        <img src="css/logo.png" width="50" height="50">
        </div>
        <ul>
            <li><a href="homepage.php">Home</a></li>
            <li><a href="login_options.php">Login</a></li>
            <li><a href="about_us.php">About Us</a></li>
        </ul>
    </nav>
    <div class="slideshow-container">
        <div class="mySlides fade">
            <img src="css/slider1.jpeg" style= "width:100%">
        </div>
        <div class="mySlides fade">
            <img src="css/slider2.jpeg" style= "width:100%">
        </div>
        <div class="mySlides fade">
            <img src="css/slider3.jpeg" style= "width:100%">
        </div>
    <div style="text-align:center">
        <span class="dot"></span>
        <span class="dot"></span>
        <span class="dot"></span>
    </div>
    </div>
    <br>
    
    <script>
        var slideIndex = 0;
        showSlides();

        function showSlides() {
            var i;
            var slides = document.getElementsByClassName("mySlides");
            var dots = document.getElementsByClassName("dot");
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slideIndex++;
            if (slideIndex > slides.length) {slideIndex = 1}
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex-1].style.display = "block";
            dots[slideIndex-1].className += " active";
            setTimeout(showSlides, 2000); // Change image every 2 seconds
        }
    </script>
</body>
</html>

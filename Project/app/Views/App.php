<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutritionix App</title>
    <link rel="stylesheet" href="../Styles/header.css">
    <link rel="stylesheet" href="../Styles/appstyle.css">
    <?php include 'Components/header.php'; ?>
</head>
<body>
    <div class="container">
        <section class="hero">
            <h1>Track Your Nutrition Easily with Nutritionix App</h1>
            <p>The most reliable and easy-to-use app to log your food intake and track your progress towards a healthier lifestyle.</p>
            <div class="cta-buttons">
                <a href="#" class="download-btn">
                    <img src="../Images/Download_on_the_App_Store_Badge_US-UK_135x40.svg" alt="Download on the App Store">
                </a>
                <a href="#" class="download-btn">
                    <img src="../Images/google-play-badge.png" width="135" height="40" alt="Get it on Google Play">
                </a>
            </div>
           
        </section>

        <section class="features">
            <h2>App Features</h2>
            <div class="feature-slideshow">
                <img class="mySlides" src="../Images/track_freeform.png" alt="Feature 1">
                <img class="mySlides" src="../Images/track_createrecipes.png" alt="Feature 2">
                <img class="mySlides" src="../Images/track_predictiveresults.png" alt="Feature 3">
            </div>
        </section>

        <section class="cta">
            <h2>Ready to Start Your Journey?</h2>
            <p>Download the Nutritionix app and make tracking your nutrition easier than ever.</p>
            <div class="cta-buttons">
                <a href="#" class="cta-download">
                    <img src="../Images/Download_on_the_App_Store_Badge_US-UK_135x40.svg" alt="Download on the App Store">
                </a>
                <a href="#" class="cta-download">
                    <img src="../Images/google-play-badge.png" width="135" height="40" alt="Get it on Google Play">
                </a>
            </div>
        </section>
    </div>

    <?php include 'Components/footer.php'; ?>

    <script type="text/javascript">
        var myIndex = 0;
        showSlides();

        function showSlides() {
            var slides = document.getElementsByClassName("mySlides");
            for (var i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            myIndex++;
            if (myIndex > slides.length) { myIndex = 1 }
            slides[myIndex - 1].style.display = "block";
            setTimeout(showSlides, 2000); // Change image every 2 seconds
        }
    </script>
</body>
</html>

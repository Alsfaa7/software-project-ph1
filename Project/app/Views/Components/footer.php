<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../Public/Styles/footer.css">
    <title>HOME</title>
</head>
<body>
    <!-- Your content goes here -->

    <footer class="footer">
        <div class="footer-container">
            <div>
                <h2 class="footerh2">Contact Info</h2>
                <p class="footerinfo">
                    <i class="bi bi-person-fill"></i> Name: NutritionX
                </p>
                <p class="footerinfo">
                    <i class="bi bi-envelope-fill"></i> Email: info@NutritionX.com
                </p>
                <p class="footerinfo">
                    <i class="bi bi-geo-alt-fill"></i> Address: Cairo, Maadi, 40 City of Brothers for Police Officers, Floor 1, Apartment 12
                </p>
            </div>
            <div>
                <h2 class="footerh2">About</h2>
                <p>عايز البطن شوكلاته<br> والصدر بلاطه<br> يبقي حلك عندنا<br> NutritionX مع</p>
            </div>
            <div>
                <h2 class="footerh2">Quick Links</h2>
                <ul>
                    <li><a href="HomeP.php">Privacy Policy</a></li>
                    <li><a href="HomeT.php">Terms of Service</a></li>
                   </ul>
            </div>
        </div>
        <div class="footer-bottom">
        <?php echo date("Y"); ?> Nutritionix. All rights reserved <a href="HomeP.php">Privacy Policy</a> | <a href="HomeT.php">Terms of Service</a>
        </div>
        <!-- Add this section for social icons in footer -->
<div class="footer-social">
    <a href="#"><i class="bi bi-youtube"></i></a>
    <a href="#"><i class="bi bi-instagram"></i></a>
    <a href="#"><i class="bi bi-facebook"></i></a>
    <a href="#"><i class="bi bi-twitter"></i></a>
    <a href="#"><i class="bi bi-linkedin"></i></a>
</div>

<!-- Add this section for payment logos in footer bottom -->
<div class="footer-logos">
    <img src="path/to/paypal-logo.png" alt="PayPal">
    <img src="path/to/visa-logo.png" alt="Visa">
    <img src="path/to/mastercard-logo.png" alt="MasterCard">
    <img src="path/to/googlepay-logo.png" alt="Google Pay">
    <img src="path/to/applepay-logo.png" alt="Apple Pay">
</div>

    </footer>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Public/Styles/navbar.css">
    <title>User Navbar</title>
</head>
<body>
    <!-- Navbar container -->
    <header class="navbar">
        <div class="navbar-logo">
            <a href="Home.php">
                <img src="../../Public/Images/logo.svg" alt="NutritionX Logo" />
            </a>
        </div>

        <!-- Centered menu links -->
        <nav class="navbar-menu">
            <a href="App.php">APP</a>
            <a href="CustomerTools.php">CUSTOMER TOOLS</a>
            <a href="Dashboard.php">DASHBOARD</a>
            <a href="AboutUs.php">ABOUT US</a>
            <a href="ContactUs.php">CONTACT US</a>
        </nav>

        <!-- Right-side icons and links -->
        <div class="navbar-right">
            <div class="navbar-images">
                <a href="#"><img src="../../Public/Images/OIP.jpeg" alt="Promotion 1"></a>
                <a href="#"><img src="../../Public/Images/OIP (1).jpeg" alt="Promotion 2"></a>
            </div>
<div class="navbar-icons">
    <?php
        if (isset($_SESSION['id'])) {
            $username = htmlspecialchars($_SESSION['user_name']);
            echo "<span>Welcome, $username</span>";
            echo "<a href='Logout.php'><i class='bi bi-box-arrow-right'></i> Logout</a>";
        } else {
            header("Location: LogIn.php"); // Redirect to login if not logged in
            exit();
        }
    ?>
    <a href="#"><i class="bi bi-search"></i></a>
</div>


            <!-- Hamburger Icon -->
            <div class="navbar-hamburger" onclick="toggleMenu(this)">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>
    </header>

    <script>
        function toggleMenu(element) {
            const menu = document.querySelector('.navbar-menu');
            menu.classList.toggle('active');

            // Animate hamburger to close icon
            element.classList.toggle('active');
        }
    </script>
</body>
</html>

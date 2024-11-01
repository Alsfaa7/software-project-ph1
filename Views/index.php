<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../Styles/index.css"> <!-- Link to your CSS file -->
</head>
<body>

<!-- Navigation Bar -->
<nav>
    <div class="nav-logo">
        <img src="../Images/logo.svg" alt="Logo" class="logo"> <!-- Adjust path if necessary -->
    </div>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="CustomerTools.php">Customer Tools</a></li>
        <li><a href="Database.php">Database</a></li>
        <li><a href="Aboutus.php">About Us</a></li>
        <li><a href="ContactUs.php">Contact Us</a></li>
    </ul>
</nav>

<main>
    <div class="container">
        <?php
        if (!empty($_SESSION['ID'])) {
            // Display options for logged-in users
            echo "<h1>Welcome, " . htmlspecialchars($_SESSION['FName']) . "!</h1>";
            echo "<a href='View.php' class='button'>View Profile</a>";
            echo "<a href='Edit.php' class='button'>Edit Profile</a>";
            echo "<a href='delete_account.php' class='button'>Delete Account</a>";
            echo "<a href='logout.php' class='button'>Sign Out Here</a>";
        } else {
            // Display options for guests
            echo "<h1>Welcome</h1>";
            echo "<a href='login.php' class='button'>Login</a>";
            echo "<a href='signup.php' class='button'>Sign Up Here</a>";
        }
        ?>
    </div>
</main>

<?php include 'Components/footer.php'; ?>

</body>
</html>

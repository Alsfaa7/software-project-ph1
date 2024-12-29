<?php
session_start();

if (!isset($_SESSION['ID'])) {
    header("Location: Login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile</title>
    <link rel="stylesheet" href="../styles/view.css">
</head>
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
<body>
    <div class="container">
        <div class="main5">
            <h1>Your Profile</h1>
            <p><strong>First Name:</strong> <?= htmlspecialchars($_SESSION["FName"]); ?></p>
            <p><strong>Last Name:</strong> <?= htmlspecialchars($_SESSION["LName"]); ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION["Email"]); ?></p>
            <a href="index.php" class="back-button">Back</a>
        </div>
    </div>
    <?php include 'Components/footer.php'; ?>

</body>
</html>

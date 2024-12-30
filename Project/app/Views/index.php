<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../../Public/Styles/index.css"> <!-- Link to your CSS file -->
</head>
<body>

<!-- Include User Navbar -->
<?php include 'Components/usernav.php'; ?>

<main>
    <div class="container">
        <?php
        if (!empty($_SESSION['id'])) {
            // Check if the session variable for user name is set
            $firstName = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'User';

            // Display options for logged-in users
            echo "<h1>Welcome, $firstName!</h1>";
            echo "<a href='profile.php' class='button'>View Profile</a>";
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

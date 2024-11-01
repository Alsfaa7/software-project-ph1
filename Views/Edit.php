<?php
session_start();
include_once "../includes/dbh.inc.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../styles/edit.css">
</head>
<!-- Navigation Bar -->
<nav>
    <div class="nav-logo">
        <img src="../Images/logo.svg" alt="Logo" class="logo"> <!-- Adjust path if necessary -->
    </div>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="CustomerTools.php">Customer Tools</a></li>
        <li><a href="#">Database</a></li>
        <li><a href="Aboutus.php">About Us</a></li>
        <li><a href="ContactUs.php">Contact Us</a></li>
    </ul>
</nav>
<body>
    <div class="container">
        <h1>Edit Profile</h1>
        <form action="" method="post">
            <label for="FName">First Name:</label>
            <input type="text" id="FName" value="<?= htmlspecialchars($_SESSION['FName']) ?>" name="FName">

            <label for="LName">Last Name:</label>
            <input type="text" id="LName" value="<?= htmlspecialchars($_SESSION['LName']) ?>" name="LName">

            <label for="Email">Email:</label>
            <input type="text" id="Email" value="<?= htmlspecialchars($_SESSION['Email']) ?>" name="Email">

            <label for="Password">Password:</label>
            <input type="password" id="Password" name="Password" placeholder="Enter new password">

            <input type="submit" value="Submit" name="Submit">
        </form>
    </div>
    <?php include 'Components/footer.php'; ?>

</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Fname = htmlspecialchars($_POST["FName"]);
    $Lname = htmlspecialchars($_POST["LName"]);
    $Email = htmlspecialchars($_POST["Email"]);
    $Password = $_POST["Password"];

    if (!empty($Password)) {
        $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);
    } else {
        $hashedPassword = $_SESSION["Password"];
    }

    $sql = "UPDATE users SET FirstName = ?, LastName = ?, Email = ?, Password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $Fname, $Lname, $Email, $hashedPassword, $_SESSION['ID']);

    if ($stmt->execute()) {
        $_SESSION["FName"] = $Fname;
        $_SESSION["LName"] = $Lname;
        $_SESSION["Email"] = $Email;
        $_SESSION["Password"] = $hashedPassword;

        header("Location: index.php?update=success");
        exit();
    } else {
        echo "Error updating profile.";
    }

    $stmt->close();
    $conn->close();
}
?>

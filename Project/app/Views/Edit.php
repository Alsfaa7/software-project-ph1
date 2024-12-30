<?php
session_start();
include_once "../includes/dbh.inc.php";

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");  // Redirect to login if not logged in
    exit();
}

// Fetch user data
$user_id = $_SESSION['id']; 
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if ($user) {
    $Fname = $user['FirstName'];
    $Lname = $user['LastName'];
    $Email = $user['Email'];
    $profilePic = $user['profile_pic'];  // Fetch current profile picture
} else {
    echo "Error: User data not found.";
    exit();
}

$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Fname = htmlspecialchars($_POST["FName"]);
    $Lname = htmlspecialchars($_POST["LName"]);
    $Email = htmlspecialchars($_POST["Email"]);
    $Password = $_POST["Password"];

    // Password hashing (if changed)
    if (!empty($Password)) {
        $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);
    } else {
        $hashedPassword = $_SESSION["Password"];
    }

    // No need to handle profile picture since we are removing it

    // Update user information in the database
    $sql = "UPDATE users SET FirstName = ?, LastName = ?, Email = ?, Password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $Fname, $Lname, $Email, $hashedPassword, $_SESSION['id']);

    if ($stmt->execute()) {
        // Update session variables
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../../Public/Styles/edit.css">
</head>
<body>
<?php include 'Components/usernav.php'; ?>

    <div class="container">
        <h1>Edit Profile</h1>
        <form action="" method="post">
            <!-- Removed the profile picture input and display -->
            
            <label for="FName">First Name:</label>
            <input type="text" id="FName" value="<?= htmlspecialchars($Fname) ?>" name="FName">

            <label for="LName">Last Name:</label>
            <input type="text" id="LName" value="<?= htmlspecialchars($Lname) ?>" name="LName">

            <label for="Email">Email:</label>
            <input type="text" id="Email" value="<?= htmlspecialchars($Email) ?>" name="Email">

            <label for="Password">Password:</label>
            <input type="password" id="Password" name="Password" placeholder="Enter new password">

            <input type="submit" value="Submit" name="Submit">
        </form>
    </div>

    <?php include 'Components/footer.php'; ?>

</body>
</html>

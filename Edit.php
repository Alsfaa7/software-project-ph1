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

    // Handle Profile Picture Upload
    $newProfilePic = $profilePic;  // Default to current profile picture
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        // Validate image
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['profile_pic']['type'];
        if (in_array($fileType, $allowedTypes)) {
            // Generate a unique name for the image
            $newProfilePic = uniqid('', true) . '.' . pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
            $uploadPath = "../Public/Images/profile_pics/" . $newProfilePic;
            
            // Move the uploaded file to the server directory
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploadPath)) {
                // Image uploaded successfully
            } else {
                echo "Error uploading image.";
            }
        } else {
            echo "Invalid file type. Only JPEG, PNG, or GIF images are allowed.";
        }
    }

    // Update user information in the database
    $sql = "UPDATE users SET FirstName = ?, LastName = ?, Email = ?, Password = ?, profile_pic = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $Fname, $Lname, $Email, $hashedPassword, $newProfilePic, $_SESSION['id']);

    if ($stmt->execute()) {
        // Update session variables
        $_SESSION["FName"] = $Fname;
        $_SESSION["LName"] = $Lname;
        $_SESSION["Email"] = $Email;
        $_SESSION["Password"] = $hashedPassword;
        $_SESSION["ProfilePic"] = $newProfilePic;  // Update session for profile picture

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
    <link rel="stylesheet" href="../styles/edit.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="nav-logo">
            <img src="../Images/logo.svg" alt="Logo" class="logo">
        </div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="CustomerTools.php">Customer Tools</a></li>
            <li><a href="#">Database</a></li>
            <li><a href="Aboutus.php">About Us</a></li>
            <li><a href="ContactUs.php">Contact Us</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>Edit Profile</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <!-- Display Current Profile Picture -->
            <div>
                <label for="profile_pic">Profile Picture:</label><br>
                <?php if ($profilePic): ?>
                    <img src="../Public/Images/profile_pics/<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture" class="profile-pic">
                <?php else: ?>
                    <img src="../Public/Images/default_profile.png" alt="Default Profile Picture" class="profile-pic">
                <?php endif; ?>
                <input type="file" name="profile_pic" id="profile_pic">
            </div>

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

<?php
session_start();  // Start the session
include_once "../../app/includes/dbh.inc.php"; 

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");  // Redirect to login if not logged in
    exit();
}

// Get the user's ID from the session
$user_id = $_SESSION['id']; 

// Fetch user data from the database
$sql = "SELECT * FROM users WHERE id = ?";  // Assuming the column is 'id'
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    // Assign user data to variables
    $Fname = $user['FirstName'];
    $Lname = $user['LastName'];
    $Email = $user['Email'];
    $Weight = $user['weight'];
    $Height = $user['height'];
    $calories = $user['calories'];
    $protein = $user['protein'];
    $carbs = $user['carbs'];
    $fat = $user['fat'];
    // Check if profile_pic exists, else assign null
    $profilePic = isset($user['profile_pic']) ? $user['profile_pic'] : null;  // Fetch the profile picture path
} else {
    echo "Error: User data not found.";
    exit();
}

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $targetDir = "../../Public/Images/profile_pics/";
    $targetFile = $targetDir . basename($_FILES["profile_pic"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    // Validate the uploaded file
    $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($imageFileType, $validExtensions)) {
        // Check if file already exists
        if (file_exists($targetFile)) {
            echo "Sorry, file already exists.";
        } else {
            // Attempt to move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)) {
                // Get the filename
                $fileName = basename($_FILES["profile_pic"]["name"]);

                // Ensure fileName is a string
                if (is_string($fileName)) {
                    // Update the database with the new profile picture
                    $sql = "UPDATE users SET profile_pic = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("si", $fileName, $user_id); // Bind the file name and user ID
                    
                    if ($stmt->execute()) {
                        // Successfully updated profile picture
                        $profilePic = $fileName;
                        echo "Profile picture updated successfully!";
                    } else {
                        echo "Error updating profile picture.";
                    }
                } else {
                    echo "Error: File name is not a valid string.";
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
    }
}

// Close the database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../../Public/Styles/profile.css"> <!-- Link to your CSS file -->
</head>
<body>

<!-- Navigation Bar -->
<nav>
    <div class="nav-logo">
        <img src="../../Public/Images/logo.svg" alt="Logo" class="logo"> <!-- Adjust path if necessary -->
    </div>
    <ul>
        <li><a href="Home.php">Home</a></li>
        <li><a href="signup.php">Sign Up</a></li>
        <li><a href="login.php">Login</a></li>
    </ul>
</nav>

<h1>Profile</h1>

<div class="container">
    <h2>User Information</h2>

    <!-- Display profile picture if it exists -->
    <img src="../../Public/Images/profile_pics/<?php echo htmlspecialchars($profilePic ?: 'default_profile.png'); ?>" alt="Profile Picture" class="profile-pic">

    <p><strong>First Name:</strong> <?php echo htmlspecialchars($Fname); ?></p>
    <p><strong>Last Name:</strong> <?php echo htmlspecialchars($Lname); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($Email); ?></p>
    <p><strong>Weight:</strong> <?php echo htmlspecialchars($Weight); ?> kg</p>
    <p><strong>Height:</strong> <?php echo htmlspecialchars($Height); ?> cm</p>
    
    <h3>Calculated Macros</h3>
    <p><strong>Calories:</strong> <?php echo htmlspecialchars($calories); ?> kcal</p>
    <p><strong>Protein:</strong> <?php echo htmlspecialchars($protein); ?> g</p>
    <p><strong>Carbs:</strong> <?php echo htmlspecialchars($carbs); ?> g</p>
    <p><strong>Fat:</strong> <?php echo htmlspecialchars($fat); ?> g</p>

    
</div>

<?php include 'Components/footer.php'; ?>

</body>
</html>

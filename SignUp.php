<?php
include_once "../../app/includes/dbh.inc.php"; 

function calculateCalories($weight, $height) {
    return (10 * $weight) + (6.25 * $height) - 5 * 25 + 5; // Adjust age and gender if needed
}

function calculateProtein($weight) {
    return $weight * 2; // Protein in grams (2 grams per kg)
}

function calculateCarbs($calories) {
    return ($calories * 0.5) / 4; // 50% of calories from carbs
}

function calculateFat($calories) {
    return ($calories * 0.25) / 9; // 25% of calories from fat
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="../../Public/Styles/signup.css">
</head>
<body>

<nav>
    <div class="nav-logo">
        <img src="../../Public/Images/logo.svg" alt="Logo" class="logo">
    </div>
    <ul>
        <li><a href="Home.php">Home</a></li>
        <li><a href="signup.php">Sign Up</a></li>
        <li><a href="login.php">Login</a></li>
    </ul>
</nav>

<h1>Sign Up</h1>

<div class="container">
    <form action="" method="post" enctype="multipart/form-data">
        <label>First Name:</label>
        <input type="text" name="FName" required>

        <label>Last Name:</label>
        <input type="text" name="LName" required>

        <label>Email:</label>
        <input type="email" name="Email" required>

        <label>Password:</label>
        <input type="password" name="Password" minlength="8" required>

        <label>Weight (kg):</label>
        <input type="number" name="Weight" step="0.1" required>

        <label>Height (cm):</label>
        <input type="number" name="Height" step="0.1" required>

        <label>Profile Picture:</label>
        <input type="file" name="ProfilePic" accept="image/*" required>

        <input type="submit" value="Submit" name="Submit">
        <input type="reset">
    </form>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $FName = htmlspecialchars($_POST["FName"]);
    $LName = htmlspecialchars($_POST["LName"]);
    $Email = htmlspecialchars($_POST["Email"]);
    $Password = $_POST["Password"];
    $Weight = floatval($_POST["Weight"]);
    $Height = floatval($_POST["Height"]);

    // Validate email
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Validate password strength (basic)
    if (strlen($Password) < 8) {
        echo "Password must be at least 8 characters long.";
        exit;
    }

    $PasswordHashed = password_hash($Password, PASSWORD_DEFAULT);

    // Handle profile picture upload
    $targetDir = "../../Public/Images/profile_pics/";
    $profilePic = $_FILES['ProfilePic'];

    if ($profilePic['error'] !== UPLOAD_ERR_OK) {
        echo "Error uploading file. Code: " . $profilePic['error'];
        exit;
    }

    $targetFile = $targetDir . uniqid() . "_" . basename($profilePic["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if file is an image
    $check = getimagesize($profilePic["tmp_name"]);
    if ($check === false) {
        echo "File is not a valid image.";
        exit;
    }

    // Check file size (limit: 5MB)
    if ($profilePic["size"] > 5000000) {
        echo "File size exceeds 5MB.";
        exit;
    }

    // Allow only specific file formats
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedTypes)) {
        echo "Only JPG, JPEG, PNG, and GIF formats are allowed.";
        exit;
    }

    // Move uploaded file
    if (!move_uploaded_file($profilePic["tmp_name"], $targetFile)) {
        echo "Error saving the uploaded file.";
        exit;
    }

    // Calculate macros
    $calories = calculateCalories($Weight, $Height);
    $protein = calculateProtein($Weight);
    $carbs = calculateCarbs($calories);
    $fat = calculateFat($calories);

    // Insert data into the database
    $sql = "INSERT INTO users (FirstName, LastName, Email, Password, weight, height, calories, protein, carbs, fat, profile_pic)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo "Database error: " . $conn->error;
        exit;
    }

    $stmt->bind_param("ssssddiiiss", $FName, $LName, $Email, $PasswordHashed, $Weight, $Height, $calories, $protein, $carbs, $fat, $targetFile);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<?php include 'Components/footer.php'; ?>

</body>
</html>

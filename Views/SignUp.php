<?php
include_once "../includes/dbh.inc.php"; 

function calculateCalories($weight, $height) {
    // Example formula for caloric needs
    return (10 * $weight) + (6.25 * $height) - 5 * 25 + 5; // Modify age, gender as needed
}

function calculateProtein($weight) {
    return $weight * 2; // Protein in grams (2 grams per kg of weight)
}

function calculateCarbs($calories) {
    return ($calories * 0.5) / 4; // Carbs in grams (50% of calories from carbs)
}

function calculateFat($calories) {
    return ($calories * 0.25) / 9; // Fat in grams (25% of calories from fat)
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="../Styles/signup.css"> <!-- Link to your CSS file -->
</head>
<body>

<!-- Navigation Bar -->
<nav>
    <div class="nav-logo">
        <img src="../Images/logo (1).svg" alt="Logo" class="logo"> <!-- Adjust path if necessary -->
    </div>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="signup.php">Sign Up</a></li>
        <li><a href="login.php">Login</a></li>
    </ul>
</nav>

<h1>Sign Up</h1>

<div class="container">
    <form action="" method="post">
        <label>First Name:</label>
        <input type="text" name="FName" required>

        <label>Last Name:</label>
        <input type="text" name="LName" required>

        <label>Email:</label>
        <input type="text" name="Email" required>

        <label>Password:</label>
        <input type="password" name="Password" required>

        <label>Weight (kg):</label>
        <input type="number" name="Weight" step="0.1" required>

        <label>Height (cm):</label>
        <input type="number" name="Height" step="0.1" required>

        <input type="submit" value="Submit" name="Submit">
        <input type="reset">
    </form>
</div>

<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Fname = htmlspecialchars($_POST["FName"]);
    $Lname = htmlspecialchars($_POST["LName"]);
    $Email = htmlspecialchars($_POST["Email"]);
    $Password = password_hash($_POST["Password"], PASSWORD_DEFAULT);
    $Weight = floatval($_POST["Weight"]);
    $Height = floatval($_POST["Height"]);

    // Calculate macros
    $calories = calculateCalories($Weight, $Height);
    $protein = calculateProtein($Weight);
    $carbs = calculateCarbs($calories);
    $fat = calculateFat($calories);

    // Insert data using prepared statement
    $sql = "INSERT INTO users (FirstName, LastName, Email, Password, weight, height, calories, protein, carbs, fat)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdiiiii", $Fname, $Lname, $Email, $Password, $Weight, $Height, $calories, $protein, $carbs, $fat);

    // Execute the statement and redirect
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

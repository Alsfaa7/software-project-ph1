<?php
include_once "../../app/includes/dbh.inc.php"; 

function calculateCalories($weight, $height) {
    return (10 * $weight) + (6.25 * $height) - 5 * 25 + 5;  // Modify for age, gender if needed
}

function calculateProtein($weight) {
    return $weight * 2;  // Protein in grams (2 grams per kg of weight)
}

function calculateCarbs($calories) {
    return ($calories * 0.5) / 4;  // Carbs in grams (50% of calories from carbs)
}

function calculateFat($calories) {
    return ($calories * 0.25) / 9;  // Fat in grams (25% of calories from fat)
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="../../Public/Styles/signup.css"> <!-- Link to your CSS file -->
    <script>
        function validateForm() {
            var weight = document.forms["signupForm"]["Weight"].value;
            var height = document.forms["signupForm"]["Height"].value;
            var password = document.forms["signupForm"]["Password"].value;
            var email = document.forms["signupForm"]["Email"].value;

            // Validate that weight and height are positive
            if (weight <= 0 || height <= 0) {
                alert("Weight and Height must be positive values.");
                return false;
            }

            // Validate password length
            if (password.length < 6) {
                alert("Password must be at least 6 characters long.");
                return false;
            }

            // Validate email format
            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
<?php include 'Components/header.php'; ?>
<h1>Sign Up</h1>

<div class="container">
    <form name="signupForm" action="" method="post" onsubmit="return validateForm()">
        <label>First Name:</label>
        <input type="text" name="FName" required>

        <label>Last Name:</label>
        <input type="text" name="LName" required>

        <label>Email:</label>
        <input type="text" name="Email" required>

        <label>Password:</label>
        <input type="password" name="Password" required>

        <label>Weight (kg):</label>
        <input type="number" name="Weight" min="0" step="0.1" required>

        <label>Height (cm):</label>
        <input type="number" name="Height" min="0" step="0.1" required>

        <input type="submit" value="Submit" name="Submit">
        <input type="reset">
    </form>
</div>

<?php
// Server-Side Validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Fname = htmlspecialchars($_POST["FName"]);
    $Lname = htmlspecialchars($_POST["LName"]);
    $Email = htmlspecialchars($_POST["Email"]);
    $Password = $_POST["Password"];
    $Weight = floatval($_POST["Weight"]);
    $Height = floatval($_POST["Height"]);

    // Validate email format
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit();
    }

    // Check for valid Weight and Height
    if ($Weight <= 0 || $Height <= 0) {
        echo "Weight and Height must be positive values.";
        exit();
    }

    // Ensure password length is sufficient (at least 6 characters)
    if (strlen($Password) < 6) {
        echo "Password must be at least 6 characters long.";
        exit();
    }

    // Hash the password for security
    $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

    // Calculate macros
    $calories = calculateCalories($Weight, $Height);
    $protein = calculateProtein($Weight);
    $carbs = calculateCarbs($calories);
    $fat = calculateFat($calories);

    // Insert data using prepared statement
    $sql = "INSERT INTO users (FirstName, LastName, Email, Password, weight, height, calories, protein, carbs, fat)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdiiiii", $Fname, $Lname, $Email, $hashedPassword, $Weight, $Height, $calories, $protein, $carbs, $fat);

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

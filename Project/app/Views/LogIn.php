<?php
// Start the session
session_start();

// Include database connection file
$host = '127.0.0.1'; // Database host
$db = 'nutritionx'; // Database name
$user = 'root'; // Database user
$password = ''; // Database password
$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email and password are set
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $conn->real_escape_string($_POST['email']);
        $password = $_POST['password'];

        // Query to check if the user exists
        $sql = "SELECT * FROM users WHERE Email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['Password'])) {
                // Store user details in session
                $_SESSION['id'] = $user['id'];
                $_SESSION['user_name'] = $user['FirstName'] . " " . $user['LastName'];

                // Redirect to index.php after successful login
                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid email or password!";
            }
        } else {
            $error = "Invalid email or password!";
        }

        $stmt->close();
    } else {
        $error = "Please enter both email and password!";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../Public/Styles/login.css">
</head>
<body>
<?php include 'Components/header.php'; ?>

<div class="container">
    <h1>Login</h1>

    <?php
    // Display error message if exists
    if (!empty($error)) {
        echo "<p style='color:red;'>$error</p>";
    }
    ?>

    <form action="login.php" method="post" id="loginForm">
        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email" placeholder="Enter your email" required><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" placeholder="Enter your password" required><br>

        <input type="submit" value="Submit" name="Submit">
        <input type="reset">
    </form>
</div>

<script>
    // Client-side validation
    document.getElementById("loginForm").addEventListener("submit", function (e) {
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;

        if (!email || !password) {
            e.preventDefault();
            alert("Please fill out both fields.");
        } else if (!validateEmail(email)) {
            e.preventDefault();
            alert("Please enter a valid email address.");
        }
    });

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
</script>

<?php include 'Components/footer.php'; ?>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../Styles/login.css">
</head>

<body>

<?php include 'Components/header.php'; ?>

    <!-- Login Form Container -->
    <div class="container">
        <h1>Login</h1>
        <form action="" method="post">
            <label>Email:</label><br>
            <input type="text" name="Email"><br>

            <label>Password:</label><br>
            <input type="password" name="Password"><br>

            <input type="submit" value="Submit" name="Submit">
            <input type="reset">
        </form>

        <?php
        session_start();
        include_once "../includes/dbh.inc.php";

        // Check if the form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $Email = htmlspecialchars($_POST["Email"]);
            $Password = $_POST["Password"];

            // Fetch user information from the database
            $sql = "SELECT * FROM users WHERE Email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $Email);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the user exists and verify password
            if ($row = $result->fetch_assoc()) {
                if (password_verify($Password, $row["Password"])) {
                    // Store user information in the session
                    $_SESSION["ID"] = $row["id"];
                    $_SESSION["FName"] = $row["FirstName"];
                    $_SESSION["LName"] = $row["LastName"];
                    $_SESSION["Email"] = $row["Email"];

                    // Redirect to index.php
                    header("Location: index.php?login=success");
                    exit();
                } else {
                    echo "<p style='color:red;'>Invalid Email or Password</p>";
                }
            } else {
                echo "<p style='color:red;'>Invalid Email or Password</p>";
            }

            $stmt->close();
            $conn->close();
        }
        ?>
    </div>

    <?php include 'Components/footer.php'; ?>

</body>
</html>

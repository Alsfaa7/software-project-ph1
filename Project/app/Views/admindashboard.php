<?php
// Start the session
session_start();

// Set session timeout (e.g., 30 minutes)
$timeout_duration = 1800; // 1800 seconds = 30 minutes

// Check if the last activity timestamp is set
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    // If session has timed out, destroy it and redirect to login page
    session_unset();
    session_destroy();
    header("Location: login.php?timeout=true");
    exit();
}

// Update last activity timestamp to the current time
$_SESSION['LAST_ACTIVITY'] = time();

// Check if the admin is logged in
if (!isset($_SESSION['id'])) {
    // If not logged in, redirect to the login page
    header('Location: login.php');
    exit();
}

// Optional: regenerate session ID periodically for security
if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > $timeout_duration) {
    // Regenerate session ID after 30 minutes
    session_regenerate_id(true);
    $_SESSION['CREATED'] = time();
}

// Handle logout action
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset(); // Clear session variables
    session_destroy(); // Destroy the session
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            margin: 10px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }

        .btn-users {
            background-color: #007bff;
        }

        .btn-users:hover {
            background-color: #0056b3;
        }

        .btn-food {
            background-color: #28a745;
        }

        .btn-food:hover {
            background-color: #218838;
        }

        .btn-logout {
            background-color: #dc3545;
        }

        .btn-logout:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Welcome, Admin</h1>
    <p>Choose what you want to manage:</p>
    <a href="useradminedit.php" class="btn btn-users">Manage Users</a>
    <a href="foodadminedit.php" class="btn btn-food">Manage Food Items</a>
    <a href="?action=logout" class="btn btn-logout">Logout</a> <!-- Logout button -->
</div>

</body>
</html>

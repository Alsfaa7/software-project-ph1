<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Nav bar styling */
        .navbar {
            background-color: #343a40;
            padding: 10px;
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        .navbar ul li {
            margin: 0 15px;
        }

        .navbar ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 10px 15px;
            display: block;
        }

        .navbar ul li a:hover {
            background-color: #007bff;
            border-radius: 5px;
        }

        .navbar ul li a.active {
            background-color: #007bff;
            border-radius: 5px;
        }

        /* Main content styling */
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>

<!-- Admin Navigation Bar -->
<div class="navbar">
    <ul>
        <li><a href="useradminedit.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'useradminedit.php') ? 'active' : ''; ?>">Manage Users</a></li>
        <li><a href="foodadminedit.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'foodadminedit.php') ? 'active' : ''; ?>">Manage Food Items</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</div>

<!-- Page Content -->
<div class="content">
    <!-- Content specific to the page goes here -->
</div>

</body>
</html>

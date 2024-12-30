<?php 
include '../includes/dbh.inc.php'; // Ensure this file connects to the database correctly

// Add user
if (isset($_POST['add_user'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (FirstName, LastName, Email, Password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $firstName, $lastName, $email, $password);

    if ($stmt->execute()) {
        echo "New user added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Update user
if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];

    $sql = "UPDATE users SET FirstName=?, LastName=?, Email=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $firstName, $lastName, $email, $user_id);

    if ($stmt->execute()) {
        echo "User updated successfully";
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
}

// Delete user
if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    $sql = "DELETE FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "User deleted successfully";
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
    $stmt->close();
}

// Retrieve users
$user_sql = "SELECT * FROM users";
$user_result = $conn->query($user_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 32px;
            text-align: center;
            margin-bottom: 20px;
            color: #1e88e5;
            font-weight: bold;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 12px 0 8px;
            font-weight: 500;
            color: #555;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            background-color: #1e88e5;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #1565c0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        thead {
            background-color: #1e88e5;
            color: #fff;
            text-transform: uppercase;
            font-size: 14px;
            font-weight: 600;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        td {
            color: #666;
        }

        .actions button {
            padding: 8px 12px;
            font-size: 14px;
            border-radius: 8px;
        }

        .actions .delete-button {
            background-color: #e53935;
        }

        .actions .delete-button:hover {
            background-color: #c62828;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 95%;
            }

            table th, table td {
                padding: 10px;
            }

            input[type="text"], input[type="email"], input[type="password"] {
                padding: 8px;
                font-size: 14px;
            }

            button {
                padding: 8px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<?php include 'Components/adminnavbar.php'; ?>

<div class="container">
    <h1>Manage Users</h1>
    
    <!-- Form to Add/Update Users -->
    <form method="POST" action="useradminedit.php">
        <input type="hidden" name="user_id" id="user_id">
        <label for="firstName">First Name</label>
        <input type="text" name="firstName" id="firstName" required>

        <label for="lastName">Last Name</label>
        <input type="text" name="lastName" id="lastName" required>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password">

        <button type="submit" name="add_user">Add User</button>
        <button type="submit" name="update_user">Update User</button>
    </form>

    <!-- Table to Display Users -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $user_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['FirstName']) ?></td>
                <td><?= htmlspecialchars($row['LastName']) ?></td>
                <td><?= htmlspecialchars($row['Email']) ?></td>
                <td class="actions">
                    <button onclick="editUser('<?= htmlspecialchars($row['id']) ?>', '<?= htmlspecialchars($row['FirstName']) ?>', '<?= htmlspecialchars($row['LastName']) ?>', '<?= htmlspecialchars($row['Email']) ?>')">Edit</button>
                    <form method="POST" action="useradminedit.php" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($row['id']) ?>">
                        <button type="submit" name="delete_user" class="delete-button">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
function editUser(id, firstName, lastName, email) {
    document.getElementById('user_id').value = id;
    document.getElementById('firstName').value = firstName;
    document.getElementById('lastName').value = lastName;
    document.getElementById('email').value = email;
}
</script>

</body>
</html>

<?php $conn->close(); ?>

<?php
session_start();

// Include the database connection
include_once "../includes/dbh.inc.php";

// Check if user is logged in before allowing deletion
if (isset($_SESSION['ID'])) {
    // Prepare the SQL statement to delete the user
    $sql = "DELETE FROM users WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['ID']); // Bind the session ID to the query

    if ($stmt->execute()) {
        // Destroy the session after deletion
        session_unset();
        session_destroy();
        
        // Redirect to the home page after deletion
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting account.";
    }

    $stmt->close();
} else {
    echo "User not logged in.";
}

// Close the database connection
$conn->close();
?>

<?php
session_start();      // Start the session
session_unset();      // Remove all session variables
session_destroy();    // Destroy the session

// Redirect to the homepage or login page
header("Location: home.php");
exit();
?>

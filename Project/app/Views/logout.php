<?php
session_start();
if (isset($_SESSION["id"])) {
    session_unset();
    session_destroy();
    header("Location: LogIn.php?logout=success");
    exit();
} else {
    header("Location: LogIn.php");
    exit();
}

?>

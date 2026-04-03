<?php
session_start();

// destroy all session data
unset($_SESSION['user_id']);
session_unset();
session_destroy();

// redirect to login page
header("Location: login.php");
exit();
?>

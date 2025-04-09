<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to the login page after logout
header('Location: ../frontend/pages/login-page.php');
exit();
?>

<?php
// Start the session to access session variables.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Unset all session variables.
$_SESSION = array();

// Destroy the session. This deletes the session file on the server.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// Redirect the user to the main index page after logout.
header("Location: ../index.php");
exit;

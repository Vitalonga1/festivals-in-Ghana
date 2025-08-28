<?php
session_start();

// Clear all session variables
$_SESSION = [];

// Delete the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), // Name of the session cookie
        '', // Empty value
        time() - 42000, // Expire the cookie in the past
        $params["path"], // Cookie path
        $params["domain"], // Cookie domain
        $params["secure"], // Secure flag
        $params["httponly"] // HTTP-only flag
    );
}

// Destroy the session
session_destroy();

// Prevent caching of the logout page
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

// Redirect to the login page
header("Location: index.php");
exit();

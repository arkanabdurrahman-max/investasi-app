<?php
session_start();

// Kosongkan array session dan hancurkan di server
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

// Redirect mutlak kembali ke login
header("Location: login.php");
exit;

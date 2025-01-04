<?php
function requireLogin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
        header("Location: /LoginPage");
        exit();
    }
}

function requireRole($role) {
    requireLogin();

    if ($_SESSION['role'] !== $role) {
        header("Location: /Unauthorized");
        exit();
    }
}
?>
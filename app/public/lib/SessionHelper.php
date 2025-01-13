<?php
if (!function_exists('requireLogin')) {
    function requireLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if the user is logged in
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
            header("Location: /LoginPage");
            exit();
        }
    }

    function getLoggedInUser() {
        return [
            'userId' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'role' => $_SESSION['role'] ?? null,
        ];
    }
}

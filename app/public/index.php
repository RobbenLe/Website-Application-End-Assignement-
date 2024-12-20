<?php
//echo "Requested URL: " . $_SERVER['REQUEST_URI'];
//phpinfo();
/**
 * Set env variables and enable error reporting in local environment
 */
require_once(__DIR__ . "/lib/env.php");
require_once(__DIR__ . "/lib/error_reporting.php");

/**
 * Start user session
 */
session_start();

/**
 * Require routing library
 */
require_once(__DIR__ . "/lib/Route.php");

/**
 * Require routes
 */
require_once(__DIR__ . "/routes/index.php");
require_once(__DIR__ . "/routes/test.php");
//require_once(__DIR__ . "/routes/user.php");
//require_once(__DIR__ . "/routes/guest_book.php");

// run router
Route::run("/");
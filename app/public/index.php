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
require_once(__DIR__ . "/routes/firstPage.php");
require_once(__DIR__ . "/routes/LoginRoute.php"); 
require_once(__DIR__ . "/routes/HomePageRoute.php");
require_once(__DIR__ . "/routes/RegisterRoute.php");
require_once(__DIR__ . "/routes/RegisterSuccess.php");
require_once(__DIR__ . "/routes/BookingTreatments.php");
require_once(__DIR__ . "/routes/ChooseTimeRoute.php");
require_once(__DIR__ . "/routes/AdminDashBoardRoute.php");
require_once(__DIR__ . "/routes/TechnicianDashBoardRoute.php");
require_once(__DIR__ . "/routes/UserAppointmentRoute.php");
//require_once(__DIR__ . "/routes/user.php");
//require_once(__DIR__ . "/routes/guest_book.php");

// run router
Route::run();
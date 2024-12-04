<?php
require_once __DIR__ . '/lib/env.php'; // Load the database configuration

//Get the database credential from $_ENV
$servername=$_ENV["DB_HOST"];
$username=$_ENV["DB_USER"];
$password=$_ENV["DB_PASSWORD"];
$dbname=$_ENV["DB_NAME"];

//Get database connection
$conn = new mysqli($servername, $username, $password, $dbname);

//Check Connection
if ($conn -> connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Query to fetch data


<?php
// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: /LoginPage");
    exit();
}

// Access session variables
$username = $_SESSION['username'];
$role = $_SESSION['role'];
$userId = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/technicianDashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h1>Welcome to Technician Dashboard</h1>
        </header>

        <!-- Success and Error Messages -->
        <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
        <?php if (!empty($_SESSION['success_message'])): ?>
            <div class="message success">
                <p><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
            </div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error_message'])): ?>
            <div class="message error">
                <p><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
            </div>
        <?php endif; ?>

        <!-- Date Picker Section -->
        <section class="date-picker-section">
            <label for="appointment-date"><strong>Select Date:</strong></label>
            <input type="date" id="appointment-date" name="appointment_date">
        </section>

        <!-- Appointments Section -->
        <section class="appointments-section">
            <h2>Appointments for Selected Date</h2>
            <ul id="appointment-list">
                <li>Loading appointments...</li>
            </ul>
        </section>

        <!-- Manage Availability Section -->
        <section class="availability-section">
            <h2>Manage Availability</h2>
            <form action="/SetAvailability" method="POST">
                <div class="input-group">
                    <label for="available-date">Available Date</label>
                    <input type="date" id="available-date" name="available_date" required>
                </div>

                <div class="input-group">
                    <label for="start-time">Start Time (HH:MM:SS)</label>
                    <input type="time" id="start-time" name="start_time" required>
                </div>

                <div class="input-group">
                    <label for="end-time">End Time (HH:MM:SS)</label>
                    <input type="time" id="end-time" name="end_time" required>
                </div>

                <button type="submit">Set Availability</button>
            </form>
        </section>
    </div>
    <script src="../../assets/js/technicianDashboard.js"></script>
</body>
</html>

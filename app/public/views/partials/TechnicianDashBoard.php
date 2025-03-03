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

    <!-- Include jQuery UI for multi-date picker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
</head>
<body>
    <!-- Navigation Bar -->
    <section class="navigationBar">
        <h1 class="logo">Technician Dashboard</h1>
        <nav class="nav">
            <a href="/LoginPage" class="loginButton">Log Out</a>
        </nav>
    </section>

    <div class="dashboard-container">
        <header>
            <h1>Welcome to Technician Dashboard</h1>
        </header>

        <!-- Success and Error Messages -->
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

        <!-- Appointments Section -->
<section class="appointments-section">
    <h2>Appointments for Selected Date</h2>

    <!-- Date Picker to Select Date -->
    <div class="date-picker-container" style="margin-bottom: 15px;">
        <label for="appointment-date" style="font-weight: bold;">Select Date:</label>
        <input type="date" id="appointment-date" name="appointment-date" 
               min="<?php echo date('Y-m-d'); ?>" 
               onchange="loadAppointmentsByDate()"
               style="padding: 5px; border: 1px solid #ccc; border-radius: 5px;">
    </div>

    <!-- Appointment List -->
    <ul id="appointment-list">
        <li>Loading appointments...</li>
    </ul>
</section>

        <!-- Manage Availability Section -->
        <section class="availability-section">
            <h2>Manage Availability</h2>
            <form id="availability-form">
                <div class="input-group">
                    <label for="available-dates">Select Available Dates</label>
                    <input type="text" id="available-dates" name="available_dates" placeholder="Pick multiple dates" required readonly>
                </div>

                <div class="input-group">
                    <label for="start-time">Start Time</label>
                    <input type="time" id="start-time" name="start_time" required>
                </div>

                <div class="input-group">
                    <label for="end-time">End Time</label>
                    <input type="time" id="end-time" name="end_time" required>
                </div>

                <button type="submit">Set Availability</button>
            </form>
        </section>
    </div>

    <script src="../../assets/js/technicianDashboard.js"></script>
</body>
</html>

<?php
// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: /LoginPage");
    exit();
}

// Capture the cancel message from session
$cancelMessage = isset($_SESSION['cancel_message']) ? $_SESSION['cancel_message'] : null;
unset($_SESSION['cancel_message']); // Clear message after displaying it
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Appointments - Rosa Nails & Spa</title>
    <link rel="stylesheet" href="../../assets/css/userAppointment.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>

<?php include(__DIR__ . '/../partials/header_nav.php'); ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Your Appointments</h2>

    <?php if (isset($appointments['success']) && !$appointments['success']): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($appointments['message']) ?>
        </div>
    <?php elseif (isset($appointments['success']) && $appointments['success'] && empty($appointments['appointments'])): ?>
        <div class="alert alert-info" role="alert">
            No appointments found.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Service</th>
                        <th>Price</th>
                        <th>Technician</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments['appointments'] as $appointment): ?>
                        <tr>
                            <td><?= htmlspecialchars(date('d/m/Y', strtotime($appointment['appointment_date']))) ?></td>
                            <td><?= htmlspecialchars($appointment['start_time'] . ' - ' . $appointment['end_time']) ?></td>
                            <td><?= htmlspecialchars($appointment['service_name']) ?></td>
                            <td>€<?= htmlspecialchars(number_format($appointment['service_price'], 2)) ?></td>
                            <td><?= htmlspecialchars($appointment['technician_name']) ?></td>
                            <td><?= htmlspecialchars($appointment['appointment_status']) ?></td> <!-- Display Status -->
                            <td>
                                <!-- Cancel Button -->
                                <?php if ($appointment['appointment_status'] === 'pending'): ?>
                                    <form action="/cancelAppointment" method="POST" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                                        <input type="hidden" name="appointment_id" value="<?= $appointment['appointment_id'] ?>">
                                        <button type="submit" class="btn btn-danger">Cancel</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted">Cancelled</span> <!-- Show text if already canceled -->
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <a href="/homePage" class="btn btn-primary mt-3">Back to Home</a>
</div>

<!-- Display Success Message if Set -->
<?php if ($cancelMessage): ?>
    <script type="text/javascript">
        alert("<?= htmlspecialchars($cancelMessage) ?>");
    </script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>

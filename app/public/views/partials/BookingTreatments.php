<?php
// Ensure $services is passed from the Route
if (!isset($services)) {
    die("Services data is not available.");
}
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
  <title>Service Booking - Rosa Nails & Spa</title>
  <link rel="stylesheet" href="../../assets/css/BookingTreatments.css">
</head>
<body>
  <!-- Main Container -->
  <div class="booking-container">

    <!-- Sidebar for Categories -->
    <aside class="service-sidebar">
      <h2>All Treatments</h2>
      <ul class="service-categories">
        <?php foreach ($services as $category => $treatments): ?>
          <li class="category" onclick="showCategory('<?php echo htmlspecialchars($category); ?>', event)">
            <?php echo htmlspecialchars($category); ?> (<?php echo count($treatments); ?>)
          </li>
        <?php endforeach; ?>
      </ul>
    </aside>

    <!-- Main Content for Treatments -->
    <section class="service-details">
  <?php foreach ($services as $category => $treatments): ?>
    <div id="<?php echo htmlspecialchars($category); ?>" class="service-category">
      <h3><?php echo htmlspecialchars($category); ?></h3>
      <ul>
        <?php foreach ($treatments as $service): ?>
          <li>
            <span><?php echo htmlspecialchars($service['name'] ?? 'Unknown Service'); ?></span>
            <span><?php echo htmlspecialchars($service['duration'] ?? '00:00:00'); ?></span>
            <span>€<?php echo htmlspecialchars($service['price'] ?? '0.00'); ?></span>
            <button class="select-btn" 
    onclick="selectTreatment(
        '<?php echo htmlspecialchars($service['id']); ?>', 
        '<?php echo htmlspecialchars($service['name']); ?>', 
        '<?php echo htmlspecialchars($service['duration']); ?>', 
        '<?php echo htmlspecialchars($service['price']); ?>'
    )">
    Choose
</button>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endforeach; ?>
</section>

  </div>

  <!-- Footer Popup for Time Selection -->
  <footer class="booking-footer" id="booking-footer">
    <p id="selected-treatment"></p>
    <button class="time-btn"  onclick="window.location.href='/ChooseTimePage'">Choose Time</button>
  </footer>

  <script src="../../assets/js/BookingTreatments.js"></script>
  <script src="../../assets/js/auth.js"></script>
</body>
</html>
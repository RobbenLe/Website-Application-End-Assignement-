<?php
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
        <li class="category active" onclick="showCategory('acrylic', event)">Artificial Nails (5)</li>
        <li class="category" onclick="showCategory('manicure', event)">Manicure & Pedicure (2)</li>
        <li class="category" onclick="showCategory('biab', event)">BIAB (2)</li>
        <li class="category" onclick="showCategory('gelpolish', event)">Gel Polish (2)</li>
      </ul>
    </aside>

    <!-- Main Content for Treatments -->
    <section class="service-details">
      <div id="acrylic" class="service-category active">
        <h3>Acrylic Nails</h3>
        <ul>
          <li>
            <span>Acrylic nails - New set</span>
            <span>50 min</span>
            <span>€50</span>
            <button class="select-btn" onclick="selectTreatment('Acrylic nails - New set', 50)">Kies</button>
          </li>
          <li>
            <span>Acrylic nails - Filling</span>
            <span>30 min</span>
            <span>€35</span>
            <button class="select-btn" onclick="selectTreatment('Acrylic nails - Filling', 35)">Kies</button>
          </li>
        </ul>
      </div>

      <div id="manicure" class="service-category">
        <h3>Manicure & Pedicure</h3>
        <ul>
          <li>
            <span>Basic Manicure</span>
            <span>30 min</span>
            <span>€25</span>
            <button class="select-btn" onclick="selectTreatment('Basic Manicure', 25)">Kies</button>
          </li>
          <li>
            <span>Deluxe Pedicure</span>
            <span>45 min</span>
            <span>€45</span>
            <button class="select-btn" onclick="selectTreatment('Deluxe Pedicure', 45)">Kies</button>
          </li>
        </ul>
      </div>
    </section>
  </div>

  <!-- Footer Popup for Time Selection -->
  <footer class="booking-footer" id="booking-footer">
    <p id="selected-treatment"></p>
    <button class="time-btn">Choose Time</button>
  </footer>

  <!-- JavaScript should be added just before closing </body> -->
  <script src="../../assets/js/BookingTreatments.js"></script>
</body>
</html>

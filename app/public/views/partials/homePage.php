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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Rosa Nails & Spa</title>
    <link rel="stylesheet" href="../../assets/css/homePage.css"/>
</head>
<body>
  <!-- Navigation Bar -->
  <section class="navigationBar">
    <h1 class="logo">Rosa Nails & Spa</h1>
    <nav class="nav">
      <span>Welcome, <?php echo htmlspecialchars($username); ?> (<?php echo htmlspecialchars($role); ?>)</span>
      <a type="button" class="loginButton" href="/LoginPage">Log Out</a>
    </nav>
  </section>

  <!-- Banner Section -->
  <section class="banner">
    <img 
      src="../../img/Screenshot 2025-01-01 210327.png"
      alt="Banner"
      class="banner-image"
      title="Welcome to Rosa Nails & Spa"
    />
    <div class="banner-text">
      <button class="banner-button" onclick="window.location.href='/BookingTreatments';">
        Book an Appointment
      </button>
    </div>
  </section>

  <!-- Introduction Section -->
  <section class="intro">
    <section class="introSection">
      <h2>About Us</h2>
      <h3>LET YOUR NAILS SHINE</h3>
    </section>
    <p>
      Rosa Nails & Spa is located in Amsterdam Bos & Lommer. We specialize in taking care of your nails. From a manicure to nail art for a special occasion. Our nail stylists are ready to provide you with advice on a suitable treatment.
    </p>
  </section>

  <!-- Services/Picture Gallery -->
  <section class="gallery">
    <h2>Our Services</h2>
    <div class="gallery-container">
      <div class="service-item">
        <img src="../../img/Acrylic.avif" alt="Manicure" class="service-image"/>
        <h3>Acrylic Aftergels</h3>
        <p>With acrylic nails, your nails are formed with layers of artificial nails. These stay beautiful and can be finished with all kinds of nail art.</p>
      </div>
      <div class="service-item">
        <img src="../../img/Pedicure&Medicure.avif" alt="Pedicure" class="service-image"/>
        <h3>Pedicure & Medicure</h3>
        <p>Let your hands and feet shine again with a manicure or pedicure treatment and finish it off with a beautiful gel polish.</p>
      </div>
      <div class="service-item">
        <img src="../../img/Biab.avif" alt="Biab" class="service-image"/>
        <h3>Biab</h3>
        <p>Gives your nails a firming gel layer. This is applied to your own nails and gives your nails a beautiful natural color.</p>
      </div>
      <div class="service-item">
        <img src="../../img/manicure.jpeg" alt="Gellak" class="service-image"/>
        <h3>Gellak</h3>
        <p>This is a gel-based varnish. This polish stays in place for 2 to 3 weeks without peeling off.</p>
      </div>
    </div>
  </section>

  <!-- Price List Section -->
  <section class="price-list-section">
    <h2>Our Price List</h2>
    <nav class="category-nav">
      <button class="category-btn active" onclick="showCategory('acrylic', this)">Acrylic Nails</button>
      <button class="category-btn" onclick="showCategory('manicure', this)">Manicure & Pedicure</button>
      <button class="category-btn" onclick="showCategory('gelpolish', this)">Gel Polish</button>
      <button class="category-btn" onclick="showCategory('biab', this)">Biab</button>
    </nav>
    <div class="price-category" id="acrylic">
      <h3>Acrylic Nails</h3>
      <ul class="price-list">
        <li>Natural <span>€50 | €40</span></li>
        <li>Natural with gel polish <span>€60 | €50</span></li>
        <li>Nude <span>€50 | €40</span></li>
        <li>Color powder <span>€55 | €45</span></li>
      </ul>
    </div>

    <!-- Manicure & Pedicure Category -->
  <div class="price-category" id="manicure" style="display: none;">
    <h3>Manicure & Pedicure</h3>
    <ul class="price-list">
      <li>Basic Manicure <span>€25</span></li>
      <li>Deluxe Pedicure <span>€45</span></li>
    </ul>
  </div>

  <!-- Gel Polish Category -->
  <div class="price-category" id="gelpolish" style="display: none;">
    <h3>Gel Polish</h3>
    <ul class="price-list">
      <li>Classic Gel Polish <span>€35</span></li>
      <li>Premium Gel Polish <span>€50</span></li>
    </ul>
  </div>

  <!-- Biab Category -->
  <div class="price-category" id="biab" style="display: none;">
    <h3>Biab</h3>
    <ul class="price-list">
      <li>BIAB Natural <span>€50</span></li>
      <li>BIAB Deluxe <span>€65</span></li>
    </ul>
  </div>
  </section>


  <!-- Contact Section -->
  <section class="map-section">
    <h2>Visit Us</h2>
    <p>Find us at Rosa Nails & Spa. We're located in the heart of Amsterdam!</p>
    <div class="map-container">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2435.5176357535042!2d4.8483795769009985!3d52.379165972024616!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c5e265f8783b31%3A0xc079a95f0e05dc4b!2sHofwijckstraat%202%2C%201055%20GE%20Amsterdam!5e0!3m2!1svi!2snl!4v1735762293251!5m2!1svi!2snl" 
        width="600" 
        height="450" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy">
      </iframe>
    </div>
  </section>

  <!-- Footer Section -->
  <footer class="footer">
    <p>&copy; 2024 Rosa Nails & Spa. All rights reserved.</p>
    <p>1234 Nail Street, Beauty City, NY 10001</p>
    <p>Phone: (123) 456-7890 | Email: info@rosanailsandspa.com</p>
  </footer>

  <script src="../../assets/js/SwitchingPriceList.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Rosa Nails & Spa</title>
  <link rel="stylesheet" href="../../assets/css/adminDashboard.css">
</head>
<body>
  <!-- Navigation Bar -->
  <section class="navigationBar">
    <h1 class="logo">Admin Dashboard</h1>
    <nav class="nav">
      <a href="/Logout" class="logoutButton">Logout</a>
    </nav>
  </section>

  <!-- Admin Panels -->
  <div class="admin-container">
    <h2>Welcome, Admin</h2>
    <div class="admin-panels">
      <!-- Manage Technicians -->
      <section class="admin-panel">
        <h3>Manage Technicians</h3>
        <button onclick="showTechnicianForm()">Add Technician</button>
        <table>
          <thead>
            <tr>66
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="technician-table">
            <!-- Populated with JS -->
          </tbody>
        </table>
      </section>

      <!-- Manage Services -->
      <section class="admin-panel">
        <h3>Manage Services</h3>
        <button onclick="showServiceForm()">Add Service</button>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Category</th>
              <th>Price</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="service-table">
            <!-- Populated with JS -->
          </tbody>
        </table>
      </section>
    </div>
  </div>

  <!-- Modals for Forms -->
  <div id="technician-modal" class="modal">
    <form id="technician-form">
      <h3>Add Technician</h3>
      <input type="text" id="tech-username" placeholder="Username" required>
      <input type="email" id="tech-email" placeholder="Email" required>
      <input type="password" id="tech-password" placeholder="Password" required>
      <button type="submit">Add Technician</button>
    </form>
  </div>

  <div id="service-modal" class="modal">
    <form id="service-form">
      <h3>Add Service</h3>
      <input type="text" id="service-name" placeholder="Service Name" required>
      <input type="text" id="service-category" placeholder="Category" required>
      <input type="number" id="service-price" placeholder="Price" required>
      <button type="submit">Add Service</button>
    </form>
  </div>

  <script src="../../assets/js/adminDashboard.js"></script>
</body>
</html>

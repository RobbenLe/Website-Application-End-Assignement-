

<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

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
    <section class="admin-panels">
    <!-- Manage Technicians -->
    <section class="admin-panel">
        <h3>Manage Technicians</h3>
        <button onclick="showTechnicianForm()">Add Technician</button>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="technician-table">
                <!-- Populated by JS -->
            </tbody>
        </table>
    </section>

    <!-- Manage Services -->
    <section class="admin-panel">
        <h3>Manage Services</h3>
        <button onclick="openAddServiceModal()">Add Service</button>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="service-table">
                <!-- Populated by JS -->
            </tbody>
        </table>
    </section>

    <!-- Manage Appointments -->
    <section class="admin-panel">
        <h3>Manage Appointments</h3>
        <button onclick="showAppointmentForm()">Add Appointment</button>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer Name</th>
                    <th>Technician Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="appointment-table">
                <!-- Populated by JS -->
            </tbody>
        </table>
    </section>
</section>

    </div>
  </div>

  <!-- Modals -->
  <div id="technician-modal" class="modal">
  <form id="technician-form" action="/api/createTechnician" method="POST">
    <h3>Create Technician</h3>
    <input type="text" name="tech-username" id="tech-name" placeholder="Name" required>
    <input type="email" name="tech-email" id="tech-email" placeholder="Email" required>
    <input type="password" name="tech-password" id="tech-password" placeholder="Password" required>
    <input type="hidden" name="form-type" value="create-technician">
    <button type="submit">Save</button>
  </form>
</div>

<div id="service-modal" class="modal">
  <form id="service-form" action="/AdminDashBoardPage" method="POST">
    <h3>Create Service</h3>
    <input type="text" name="service-name" id="service-name" placeholder="Service Name" required>
    <input type="text" name="service-category" id="service-category" placeholder="Category" required>
    <input type="number" name="service-price" id="service-price" placeholder="Price" required>
    <input type="text" name="service-duration" id="service-duration" placeholder="Duration (e.g., 00:30:00)" required>
    <input type="hidden" name="form-type" value="create-service">
    <button type="submit">Save</button>
  </form>
</div>

<div id="appointment-modal" class="modal">
  <form id="appointment-form" action="/AdminDashBoardPage" method="POST">
    <h3>Create Appointment</h3>
    <input type="number" name="appointment-customer" id="appointment-customer" placeholder="Customer ID" required>
    <input type="number" name="appointment-technician" id="appointment-technician" placeholder="Technician ID" required>
    <input type="date" name="appointment-date" id="appointment-date" required>
    <input type="time" name="appointment-start-time" id="appointment-start-time" placeholder="Start Time" required>
    <input type="time" name="appointment-end-time" id="appointment-end-time" placeholder="End Time" required>
    <input type="hidden" name="form-type" value="create-appointment">
    <button type="submit">Save</button>
  </form>
</div>

<div id="edit-technician-modal" class="modal">
  <form id="edit-technician-form" action="/api/updateTechnician" method="POST">
    <h3>Edit Technician</h3>
    <input type="hidden" id="edit-tech-id">
    <input type="text" id="edit-tech-username" placeholder="Name" required>
    <input type="email" id="edit-tech-email" placeholder="Email" required>
    <input type="password" id="edit-tech-password" placeholder="Password (leave blank to keep unchanged)">
    <button type="submit">Save</button>
  </form>
</div>

<div id="add-service-modal" class="modal">
  <form id="add-service-form">
    <h3>Create New Service</h3>

    <!-- Service Name -->
    <label for="add-service-name">Service Name</label>
    <input
      type="text"
      name="name"
      id="add-service-name"
      placeholder="e.g., Gel Nails - New Set"
      required
    />

    <!-- Category Dropdown -->
    <label for="add-service-category">Category</label>
    <select
      name="category"
      id="add-service-category"
      required
    >
      <!-- Options will be dynamically added here -->
    </select>

    <!-- Service Price -->
    <label for="add-service-price">Price</label>
    <input
      type="number"
      name="price"
      id="add-service-price"
      placeholder="e.g., 35.00"
      step="0.01"
      required
    />

    <!-- Service Duration -->
    <label for="add-service-duration">Duration (HH:MM:SS)</label>
    <input
      type="time"
      name="duration"
      id="add-service-duration"
      step="1"
      required
    />

    <!-- Submit Button -->
    <button type="submit">Create Service</button>
    <button type="button" onclick="closeAddServiceModal()">Cancel</button>
  </form>
  
<!-- Add Service Modal -->
<div id="add-service-modal" class="modal">
  <form id="add-service-form">
    <h3>Create New Service</h3>

    <!-- Service Name -->
    <label for="add-service-name">Service Name</label>
    <input
      type="text"
      name="name"
      id="add-service-name"
      placeholder="e.g., Gel Nails - New Set"
      required
    />

    <!-- Category Dropdown -->
    <label for="add-service-category">Category</label>
    <select
      name="category"
      id="add-service-category"
      required
    >
      <!-- Options will be dynamically added here -->
    </select>

    <!-- Service Price -->
    <label for="add-service-price">Price</label>
    <input
      type="number"
      name="price"
      id="add-service-price"
      placeholder="e.g., 35.00"
      step="0.01"
      required
    />

    <!-- Service Duration -->
    <label for="add-service-duration">Duration (HH:MM:SS)</label>
    <input
      type="time"
      name="duration"
      id="add-service-duration"
      step="1"
      required
    />

    <!-- Submit Button -->
    <button type="submit">Create Service</button>
    <button type="button" onclick="closeAddServiceModal()">Cancel</button>
  </form>
</div>




  <script src="../../assets/js/adminDashboard.js"></script>
</body>
</html>

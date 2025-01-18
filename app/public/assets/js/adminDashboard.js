// Load Admin Dashboard Data
async function loadAdminDashboardData() {
  console.log("Fetching admin dashboard data...");
  const response = await fetch("/api/getAdminDashboardData");
  const data = await response.json();
  console.log("Data received:", data);

  if (data.success) {
    populateTechnicianTable(data.technicians);
    populateServiceTable(data.services);
    populateAppointmentTable(data.appointments);
  } else {
    alert("Error loading dashboard data: " + data.message);
  }
}

// Populate Technician Table
function populateTechnicianTable(technicians) {
  const technicianTable = document.getElementById("technician-table");
  if (technicians.length === 0) {
    technicianTable.innerHTML = `<tr><td colspan="4">No technicians found</td></tr>`;
    return;
  }

  technicianTable.innerHTML = technicians
    .map(
      (tech) => `
          <tr>
            <td>${tech.technician_id}</td>
            <td>${tech.technician_name}</td>
            <td>${tech.email}</td>
            <td>
              <button onclick='openEditTechnicianModal(${JSON.stringify(
                tech
              )})'>Edit</button>
               <button onclick='deleteTechnician(${
                 tech.technician_id
               })'>Delete</button>
            </td>
          </tr>
        `
    )
    .join("");
}

// Populate Service Table
function populateServiceTable(groupedServices) {
  const serviceTable = document.getElementById("service-table");
  serviceTable.innerHTML = ""; // Clear existing content

  if (Object.keys(groupedServices).length === 0) {
    serviceTable.innerHTML = `<tr><td colspan="6">No services found</td></tr>`;
    return;
  }

  for (const [category, services] of Object.entries(groupedServices)) {
    // Add a category row
    serviceTable.innerHTML += `
        <tr>
          <td colspan="6" style="font-weight: bold; background-color: #f9f9f9;">${category}</td>
        </tr>
      `;

    // Add service rows
    services.forEach((service) => {
      serviceTable.innerHTML += `
          <tr>
            <td>${service.id}</td>
            <td>${service.name}</td>
            <td>${service.category}</td>
            <td>${service.price}</td>
            <td>${service.duration}</td>
            <td>
              <button onclick="editService(${service.id}, '${service.name}', '${service.category}', ${service.price}, '${service.duration}')">Edit</button>
              <button onclick="deleteService(${service.id})">Delete</button> <!-- Ensure service.id is passed -->
            </td>
          </tr>
        `;
    });
  }
}

// Populate Appointment Table
function populateAppointmentTable(appointments) {
  const appointmentTable = document.getElementById("appointment-table");
  if (appointments.length === 0) {
    appointmentTable.innerHTML = `<tr><td colspan="6">No appointments found</td></tr>`;
    return;
  }
  appointmentTable.innerHTML = appointments
    .map(
      (appt) => `
          <tr>
            <td>${appt.appointment_id}</td>
            <td>${appt.customer_name}</td>
            <td>${appt.technician_name}</td>
            <td>${appt.appointment_date}</td>
            <td>${appt.appointment_start_time} - ${appt.appointment_end_time}</td>
          </tr>
        `
    )
    .join("");
}

//////////////////////////////////////////////////////

function showTechnicianForm() {
  document.getElementById("technician-modal").style.display = "flex";
}

// Submit Technician Form
document
  .getElementById("technician-form")
  .addEventListener("submit", async (event) => {
    event.preventDefault();

    const username = document.getElementById("tech-name").value;
    const email = document.getElementById("tech-email").value;
    const password = document.getElementById("tech-password").value;

    try {
      const response = await fetch("/api/createTechnician", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ username, email, password }),
      });

      const result = await response.json();

      if (result.success) {
        alert("Technician created successfully!");
        loadAdminDashboardData(); // Reload dashboard data
      } else {
        alert("Error: " + result.message);
      }
    } catch (error) {
      alert("An error occurred while creating the technician.");
      console.error("Error:", error);
    }
  });

///////////////////////////////////////////// Edit Technician

// Open the Edit Technician Modal
function openEditTechnicianModal(technician) {
  document.getElementById("edit-tech-id").value = technician.technician_id;
  document.getElementById("edit-tech-username").value =
    technician.technician_name;
  document.getElementById("edit-tech-email").value = technician.email;
  document.getElementById("edit-tech-password").value = ""; // Leave password blank

  document.getElementById("edit-technician-modal").style.display = "flex";
}

// Close the Edit Technician Modal
function closeEditTechnicianModal() {
  document.getElementById("edit-technician-modal").style.display = "none";
}

// Submit the Edit Technician Form
document
  .getElementById("edit-technician-form")
  .addEventListener("submit", async (event) => {
    event.preventDefault();

    const id = document.getElementById("edit-tech-id").value;
    const username = document.getElementById("edit-tech-username").value;
    const email = document.getElementById("edit-tech-email").value;
    const password = document.getElementById("edit-tech-password").value;

    // Prepare payload with only fields that have values
    const payload = { id };
    if (username) payload.username = username;
    if (email) payload.email = email;
    if (password) payload.password = password;

    try {
      const response = await fetch("/api/updateTechnician", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
      });

      const result = await response.json();

      if (result.success) {
        alert("Technician updated successfully!");
        loadAdminDashboardData(); // Reload dashboard data
        closeEditTechnicianModal(); // Close the modal
      } else {
        alert("Failed to update technician: " + result.message);
      }
    } catch (error) {
      alert("An error occurred while updating the technician.");
      console.error("Error:", error);
    }
  });

// Delete Technician
async function deleteTechnician(technicianId) {
  if (!confirm("Are you sure you want to delete this technician?")) {
    return;
  }

  try {
    const response = await fetch("/api/deleteTechnician", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ id: technicianId }),
    });

    const result = await response.json();

    if (result.success) {
      alert("Technician deleted successfully!");
      loadAdminDashboardData(); // Reload dashboard data
    } else {
      alert("Failed to delete technician: " + result.message);
    }
  } catch (error) {
    alert("An error occurred while deleting the technician.");
    console.error("Error:", error);
  }
}

//////////////////////////////////////////////////////////// Add new service
// Open Add Service Modal

async function loadCategories(dropdownId) {
  try {
    const response = await fetch("/api/getCategories");
    const data = await response.json();

    if (data.success) {
      const dropdown = document.getElementById(dropdownId);

      // Clear existing options
      dropdown.innerHTML =
        '<option value="" disabled selected>Select Category</option>';

      // Populate categories
      data.categories.forEach((category) => {
        const option = document.createElement("option");
        option.value = category;
        option.textContent = category;
        dropdown.appendChild(option);
      });
    } else {
      console.error("Failed to load categories:", data.message);
    }
  } catch (error) {
    console.error("Error fetching categories:", error);
  }
}

// Call loadCategories when the page loads
document.addEventListener("DOMContentLoaded", loadCategories);

// Call loadCategories when the modal is opened
document
  .getElementById("add-service-modal")
  .addEventListener("show", loadCategories);

function openAddServiceModal() {
  loadCategories(); // Reload categories each time modal opens
  document.getElementById("add-service-modal").style.display = "flex";
}

function closeAddServiceModal() {
  document.getElementById("add-service-modal").style.display = "none";
}

document
  .getElementById("add-service-form")
  .addEventListener("submit", async (event) => {
    event.preventDefault();

    const serviceName = document
      .getElementById("add-service-name")
      .value.trim();
    const serviceCategory = document
      .getElementById("add-service-category")
      .value.trim();
    const servicePrice = document
      .getElementById("add-service-price")
      .value.trim();
    const serviceDuration = document
      .getElementById("add-service-duration")
      .value.trim();

    if (!serviceName || !serviceCategory || !servicePrice || !serviceDuration) {
      alert("All fields are required!");
      return;
    }

    try {
      const response = await fetch("/api/addService", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          name: serviceName,
          category: serviceCategory,
          price: parseFloat(servicePrice),
          duration: serviceDuration,
        }),
      });

      const result = await response.json();

      if (result.success) {
        alert("Service added successfully!");
        loadAdminDashboardData(); // Reload data
        closeAddServiceModal();
      } else {
        alert("Error: " + result.message);
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred while adding the service.");
    }
  });

// Call this function when the admin dashboard loads
document.addEventListener("DOMContentLoaded", loadAdminDashboardData);

////////////////////////////////////////////////////Delete Service by id
async function deleteService(serviceId) {
  if (!serviceId) {
    console.error("Service ID is missing.");
    alert("Service ID is required to delete the service.");
    return;
  }

  if (!confirm("Are you sure you want to delete this service?")) {
    return;
  }

  try {
    const response = await fetch("/api/deleteService", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ service_id: serviceId }), // Ensure service_id is included here
    });

    const result = await response.json();

    if (result.success) {
      alert("Service deleted successfully!");
      loadAdminDashboardData(); // Reload the table
    } else {
      alert("Failed to delete service: " + result.message);
    }
  } catch (error) {
    console.error("Error:", error);
    alert("An error occurred while deleting the service.");
  }
}

/////////////////////////////////////////////////////////Update Service
// Open the Edit Service Modal and Pre-fill Details
// For Add Service Modal
document.addEventListener("DOMContentLoaded", () =>
  loadCategories("add-service-category")
);

async function editService(id, name, category, price, duration) {
  // Populate the edit modal fields
  document.getElementById("edit-service-id").value = id;
  document.getElementById("edit-service-name").value = name;
  document.getElementById("edit-service-price").value = price;
  document.getElementById("edit-service-duration").value = duration;

  // Populate the dropdown with categories
  const categoryDropdown = document.getElementById("edit-service-category");

  try {
    const response = await fetch("/api/getCategories");
    const data = await response.json();

    if (data.success) {
      // Clear existing options
      categoryDropdown.innerHTML =
        '<option value="" disabled>Select Category</option>';

      // Populate categories and set the current category
      data.categories.forEach((cat) => {
        const option = document.createElement("option");
        option.value = cat;
        option.textContent = cat;
        if (cat === category) {
          option.selected = true; // Preselect current category
        }
        categoryDropdown.appendChild(option);
      });
    } else {
      console.error("Failed to load categories:", data.message);
    }
  } catch (error) {
    console.error("Error fetching categories:", error);
  }

  // Show the modal
  document.getElementById("edit-service-modal").style.display = "block";
}

// Close the Edit Service Modal
function closeEditServiceModal() {
  document.getElementById("edit-service-modal").style.display = "none";
}

// Submit Updated Service Details to Backend
function submitUpdateService() {
  const updatedService = {
    id: document.getElementById("edit-service-id").value,
    name: document.getElementById("edit-service-name").value,
    category: document.getElementById("edit-service-category").value,
    price: document.getElementById("edit-service-price").value,
    duration: document.getElementById("edit-service-duration").value,
  };

  fetch("/api/updateService", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(updatedService),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Service updated successfully!");
        location.reload(); // Refresh to show updated data
      } else {
        alert("Failed to update service: " + data.message);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("An error occurred while updating the service.");
    });
}

// Ensure userId is populated from sessionStorage
document.addEventListener("DOMContentLoaded", () => {
  const userId = sessionStorage.getItem("userId");
  if (!userId) {
    alert("User session is missing. Please log in again.");
    window.location.href = "/LoginPage";
  } else {
    console.log(`User ID: ${userId}`);
  }
});

console.log("loadDates function is defined.");
// Track selected date and technician
let currentDate = new Date();

// Navigate Dates
function navigateDay(offset) {
  currentDate.setDate(currentDate.getDate() + offset);
  displaySelectedDate();
}

// Calculate Total Duration
function calculateTotalDuration() {
  const selectedTreatments =
    JSON.parse(sessionStorage.getItem("selectedTreatments")) || [];
  let totalMinutes = 0;

  selectedTreatments.forEach((treatment) => {
    if (treatment.duration) {
      const [hours, minutes, seconds] = treatment.duration
        .split(":")
        .map(Number);
      totalMinutes += hours * 60 + minutes + (seconds || 0) / 60;
    }
  });

  console.log(`Total Duration in Minutes: ${totalMinutes}`);
  return totalMinutes;
}

// Load availability based on duration
function loadAvailabilityByDate() {
  const technicianId = document.getElementById("technician").value;
  const selectedDate = document.getElementById("selected-date").value;
  const totalDuration = calculateTotalDuration();

  if (!technicianId || !selectedDate) {
    document.getElementById("time-slots").innerHTML =
      "<li>Please select both a technician and a valid date.</li>";
    return;
  }

  fetch(
    `/getTechnicianAvailabilityByDate?technician_id=${technicianId}&selected_date=${selectedDate}&duration=${totalDuration}`
  )
    .then((response) => response.json())
    .then((data) => {
      const slots = document.getElementById("time-slots");
      slots.innerHTML = "";

      if (data.error) {
        console.warn("⚠️ Backend Error:", data.error);
        slots.innerHTML = `<li>${data.error}</li>`;
        return;
      }

      if (data.length === 0) {
        slots.innerHTML = "<li>No available slots for this date.</li>";
        return;
      }

      data.forEach((slot) => {
        const li = document.createElement("li");
        li.innerText = `${slot.available_start_time} - ${slot.available_end_time}`;
        li.onclick = () =>
          selectTime(slot.available_start_time, slot.available_end_time);
        slots.appendChild(li);
      });
    })
    .catch((error) => {
      console.error("❌ Error loading availability:", error);
      document.getElementById("time-slots").innerHTML =
        "<li>Error loading availability. Please try again.</li>";
    });
}

// Initialize default date display
document.addEventListener("DOMContentLoaded", function () {
  displaySelectedDate();
});

function loadSuggestedTimeSlots() {
  const technicianId = document.getElementById("technician").value;
  const selectedDate = document.getElementById("selected-date").value;
  const selectedTreatments =
    JSON.parse(sessionStorage.getItem("selectedTreatments")) || [];

  if (!technicianId || !selectedDate || selectedTreatments.length === 0) {
    document.getElementById("time-slots").innerHTML =
      "<p>Please select a technician, date, and treatments to see available time slots.</p>";
    return;
  }

  // Calculate total duration in minutes
  const totalDuration = selectedTreatments.reduce((sum, t) => {
    const durationParts = t.duration.split(":");
    return sum + parseInt(durationParts[0]) * 60 + parseInt(durationParts[1]);
  }, 0);

  fetch(
    `/getSuggestedTimeSlots?technician_id=${technicianId}&selected_date=${selectedDate}&duration=${totalDuration}`
  )
    .then((response) => {
      if (!response.ok) {
        throw new Error(`Server error: ${response.status}`);
      }
      return response.json();
    })
    .then((data) => {
      console.log("Fetched Slots:", data);
      if (data.error) {
        throw new Error(data.error);
      }

      // Populate slots
      const slotsContainer = document.getElementById("time-slots");
      slotsContainer.innerHTML = "";
      if (data.length === 0) {
        slotsContainer.innerHTML =
          "<p>No available slots. Please try another day.</p>";
        return;
      }

      data.forEach((slot) => {
        const slotElement = document.createElement("button");
        slotElement.className = "time-slot";
        slotElement.innerText = `${slot.start} - ${slot.end}`;
        slotElement.onclick = () => selectTime(slot.start, slot.end);
        slotsContainer.appendChild(slotElement);
      });
    })
    .catch((error) => {
      console.error("Failed to fetch slots:", error.message);
      const slotsContainer = document.getElementById("time-slots");
      slotsContainer.innerHTML = `<p>The Availibility Of Technician Was Not Set</p>`;
    });
}

// Select a time slot
function selectTime(startTime, endTime) {
  // Update the selected time display
  document.getElementById(
    "selected-time"
  ).innerText = `Selected Time: ${startTime} - ${endTime}`;

  // Save to hidden inputs
  document.getElementById("selected-start-time").value = startTime;
  document.getElementById("selected-end-time").value = endTime;

  // Save to session storage
  sessionStorage.setItem(
    "selectedTimeSlot",
    JSON.stringify({ startTime, endTime })
  );

  console.log("🕒 Selected Time Slot:", { startTime, endTime });
}

/**
 * Load available dates when a technician is selected
 * @param {string} technicianId
 */
function loadDates(technicianId) {
  if (!technicianId) {
    console.error("Technician ID is missing.");
    return;
  }

  // Optional: Update UI or loading indicator
  console.log(`Technician selected: ${technicianId}`);

  // Automatically load availability for the selected technician and default date
  loadAvailabilityByDate();
}

// Function to confirm the appointment
function confirmAppointment() {
  const customerId = sessionStorage.getItem("userId");
  const technicianId = document.getElementById("technician").value;
  const selectedDate = document.getElementById("selected-date").value;
  const startTime = document.getElementById("selected-start-time").value;
  const endTime = document.getElementById("selected-end-time").value;
  const serviceIds = JSON.parse(
    document.getElementById("selected-service-ids").value || "[]"
  );

  // Validate inputs
  if (
    !customerId ||
    !technicianId ||
    !selectedDate ||
    !startTime ||
    !endTime ||
    serviceIds.length === 0
  ) {
    alert("Please ensure all fields are filled in correctly.");
    return;
  }

  const appointmentData = {
    customerId,
    technicianId,
    selectedDate,
    startTime,
    endTime,
    serviceIds,
  };

  fetch("/createAppointment", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(appointmentData),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Failed to create appointment.");
      }
      return response.json();
    })
    .then((data) => {
      if (data.success) {
        // SweetAlert popup
        Swal.fire({
          title: "Success!",
          text: `Appointment created successfully!`,
          icon: "success",
          confirmButtonText: "OK",
        }).then((result) => {
          // Check if the "OK" button was clicked
          if (result.isConfirmed) {
            window.location.href = "/userAppointment"; // Redirect to home page after success
          }
        });
      } else {
        Swal.fire({
          title: "Error!",
          text: `Error: ${data.error}`,
          icon: "error",
          confirmButtonText: "OK",
        });
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      Swal.fire({
        title: "Error!",
        text: "An error occurred while creating the appointment. Please try again.",
        icon: "error",
        confirmButtonText: "OK",
      });
    });
}

// Attach the confirmAppointment function to the form submission event
document.addEventListener("DOMContentLoaded", function () {
  const confirmButton = document.getElementById("confirm-btn");
  if (confirmButton) {
    confirmButton.addEventListener("click", confirmAppointment);
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const selectedTreatments =
    JSON.parse(sessionStorage.getItem("selectedTreatments")) || [];
  const summaryDiv = document.getElementById("selected-treatments");

  summaryDiv.innerHTML = ""; // Clear previous content

  if (selectedTreatments.length > 0) {
    selectedTreatments.forEach((treatment) => {
      const treatmentInfo = document.createElement("p");
      treatmentInfo.textContent = `${treatment.name} - ${treatment.duration} min - €${treatment.price}`;
      summaryDiv.appendChild(treatmentInfo);
    });

    // Store service IDs for the confirmation process
    const serviceIds = selectedTreatments.map((treatment) => treatment.id);
    document.getElementById("selected-service-ids").value =
      JSON.stringify(serviceIds);
  } else {
    summaryDiv.innerHTML =
      "<p>No treatments selected. Please go back and select treatments.</p>";
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const selectedTreatments =
    JSON.parse(sessionStorage.getItem("selectedTreatments")) || [];
  const summaryDiv = document.getElementById("selected-treatments");

  summaryDiv.innerHTML = ""; // Clear default message

  if (selectedTreatments.length > 0) {
    selectedTreatments.forEach((treatment, index) => {
      const treatmentCard = document.createElement("div");
      treatmentCard.classList.add("treatment-card");

      // Treatment Name
      const treatmentName = document.createElement("p");
      treatmentName.classList.add("treatment-name");
      treatmentName.textContent = `${treatment.name || "Unknown Service"}`;

      // Treatment Duration and Price
      const treatmentMeta = document.createElement("p");
      treatmentMeta.classList.add("treatment-meta");
      treatmentMeta.innerHTML = `${treatment.duration || "00:00"} min - €${
        treatment.price || "0.00"
      }`;

      // Remove Button
      const removeButton = document.createElement("button");
      removeButton.classList.add("remove-btn");
      removeButton.textContent = "Remove";
      removeButton.onclick = function () {
        removeTreatment(index); // Call remove function
      };

      treatmentCard.appendChild(treatmentName);
      treatmentCard.appendChild(treatmentMeta);
      treatmentCard.appendChild(removeButton);
      summaryDiv.appendChild(treatmentCard);
    });
  } else {
    summaryDiv.innerHTML =
      "<p>No treatments selected. Please go back and select treatments.</p>";
  }

  // Extract and store service IDs explicitly
  const serviceIds = selectedTreatments
    .map((treatment) => treatment.id)
    .filter((id) => id !== undefined && id !== null);

  console.log("🛠️ Service IDs:", serviceIds);

  sessionStorage.setItem("serviceIds", JSON.stringify(serviceIds));
});

function removeTreatment(index) {
  let selectedTreatments =
    JSON.parse(sessionStorage.getItem("selectedTreatments")) || [];
  selectedTreatments.splice(index, 1); // Remove treatment from array
  sessionStorage.setItem(
    "selectedTreatments",
    JSON.stringify(selectedTreatments)
  ); // Update session storage
  location.reload(); // Reload the page to update the display
}

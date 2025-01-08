console.log("loadDates function is defined.");
// Track selected date and technician
let currentDate = new Date();

// Display selected date in UI
function displaySelectedDate() {
  const selectedDateDisplay = document.getElementById("selected-date-display");
  selectedDateDisplay.textContent = currentDate.toLocaleDateString("en-GB");
  document.getElementById("selected-date").value = currentDate
    .toISOString()
    .split("T")[0];
  loadAvailabilityByDate();
}

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

// Confirm Appointment
function confirmAppointment() {
  const technicianId = document.getElementById("technician").value;
  const selectedDate = document.getElementById("selected-date").value;
  const selectedTime =
    JSON.parse(sessionStorage.getItem("selectedTimeSlot")) || {};
  const selectedTreatments =
    JSON.parse(sessionStorage.getItem("selectedTreatments")) || [];
  const customerId = sessionStorage.getItem("userId");

  if (!customerId) {
    alert("User ID is missing. Please log in again.");
    window.location.href = "/LoginPage";
    return;
  }

  if (
    !technicianId ||
    !selectedDate ||
    !selectedTime.startTime ||
    !selectedTime.endTime ||
    selectedTreatments.length === 0
  ) {
    alert(
      "Please ensure you have selected a technician, date, time slot, and at least one treatment."
    );
    return;
  }

  const serviceIds = selectedTreatments
    .map((service) => service.id)
    .filter((id) => id !== undefined && id !== null);

  const appointmentData = {
    customer_id: customerId,
    technician_id: technicianId,
    selected_date: selectedDate,
    start_time: selectedTime.startTime,
    end_time: selectedTime.endTime,
    service_ids: serviceIds,
  };

  console.log("📝 Sending appointment data to backend:", appointmentData);

  fetch("/createAppointment", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(appointmentData),
  })
    .then(async (response) => {
      console.log("🔄 Response Status:", response.status);

      if (!response.ok) {
        const errorText = await response.text(); // Get response text
        console.error("❌ Raw Response:", errorText);
        throw new Error(
          `HTTP Error: ${response.status} - ${response.statusText}\nDetails: ${errorText}`
        );
      }

      const responseData = await response.json(); // Parse JSON safely

      console.log("✅ Backend Response:", responseData);

      return responseData;
    })
    .then((data) => {
      if (data.success) {
        console.log("✅ Appointment Created Successfully:", data);
        alert("Appointment successfully created!");
        window.location.href = "/AppointmentConfirmation";
      } else {
        console.warn("⚠️ Backend returned an error:", data.error);
        alert(`Failed to create appointment: ${data.error}`);
      }
    })
    .catch((error) => {
      console.error("❌ Detailed Error Information:", error);

      let errorMessage = "An unexpected error occurred.";
      if (error.message.includes("HTTP Error")) {
        errorMessage = `Server Error: ${error.message}`;
      } else if (error.name === "TypeError") {
        errorMessage = "Network error or invalid response from server.";
      } else if (error.message) {
        errorMessage = error.message;
      }

      alert(`🚨 Appointment Error:\n${errorMessage}`);
    });
}

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
    .then((response) => response.json())
    .then((data) => {
      const slotsContainer = document.getElementById("time-slots");
      slotsContainer.innerHTML = "";

      if (data.error) {
        slotsContainer.innerHTML = `<p>${data.error}</p>`;
        return;
      }

      if (data.length === 0) {
        slotsContainer.innerHTML =
          "<p>No suitable time slots available. Please select another day.</p>";
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
      console.error("Failed to fetch suggested time slots:", error);
      document.getElementById("time-slots").innerHTML =
        "<p>Error loading time slots. Please try again.</p>";
    });
}

// Select a time slot
function selectTime(startTime, endTime) {
  document.getElementById(
    "selected-time"
  ).innerText = `Selected Time: ${startTime} - ${endTime}`;
  sessionStorage.setItem(
    "selectedTimeSlot",
    JSON.stringify({ startTime, endTime })
  );
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

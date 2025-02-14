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
document.addEventListener("DOMContentLoaded", () => {
  const userId = sessionStorage.getItem("userId");
  const appointmentDateInput = document.getElementById("appointment-date");
  const appointmentList = document.getElementById("appointment-list");

  if (!userId) {
    alert("User session is missing. Please log in again.");
    window.location.href = "/LoginPage";
    return;
  }

  console.log(`User ID: ${userId}`);

  // Function to load appointments
  window.loadAppointments = async () => {
    const selectedDate = appointmentDateInput.value;

    if (!selectedDate) {
      appointmentList.innerHTML =
        "<li>Please select a date to view appointments.</li>";
      return;
    }

    try {
      console.log(
        `Fetching appointments for Technician ID: ${userId}, Date: ${selectedDate}`
      );
      const response = await fetch(
        `/getAppointmentsForTechnician?userId=${userId}&date=${selectedDate}`
      );

      if (!response.ok) {
        console.error("Failed to fetch appointments:", response.status);
        throw new Error(
          "Failed to fetch appointments. Please try again later."
        );
      }

      const data = await response.json();

      if (data.error) {
        appointmentList.innerHTML = `<li>${data.error}</li>`;
        return;
      }

      if (data.message) {
        appointmentList.innerHTML = `<li>${data.message}</li>`;
        return;
      }

      appointmentList.innerHTML = data
        .map(
          (appointment) => `
                <li>
                    <strong>Customer:</strong> ${appointment.customer_username}<br>
                    <strong>Time:</strong> ${appointment.start_time} - ${appointment.end_time}<br>
                    <strong>Services:</strong> ${appointment.services}
                </li>
            `
        )
        .join("");
    } catch (error) {
      console.error("Error loading appointments:", error);
      appointmentList.innerHTML = `<li>Error loading appointments. Please try again later.</li>`;
    }
  };

  // Add event listener for date picker
  appointmentDateInput.addEventListener("change", loadAppointments);
});

////////////////////////////////////////////// Pick Multiple Date
$(document).ready(function () {
  let selectedDates = [];

  $("#available-dates").datepicker({
    dateFormat: "yy-mm-dd",
    beforeShowDay: function (date) {
      let dateString = $.datepicker.formatDate("yy-mm-dd", date);
      return [true, selectedDates.includes(dateString) ? "selected-date" : ""];
    },
    onSelect: function (dateText) {
      if (selectedDates.includes(dateText)) {
        selectedDates = selectedDates.filter((d) => d !== dateText); // Remove if already selected
      } else {
        selectedDates.push(dateText); // Add new date
      }

      $("#available-dates").val(selectedDates.join(", ")); // Update input field
      $("#available-dates").datepicker("refresh"); // Refresh UI
    },
  });

  // Handle form submission
  $("#availability-form").on("submit", function (event) {
    event.preventDefault();

    const startTime = $("#start-time").val();
    const endTime = $("#end-time").val();

    if (selectedDates.length === 0 || !startTime || !endTime) {
      alert("Please select at least one date, start time, and end time.");
      return;
    }

    // Send data to backend as JSON
    fetch("/SetAvailability", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        available_dates: selectedDates,
        start_time: startTime,
        end_time: endTime,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        alert(data.message || "Availability set successfully!");
        location.reload(); // Reload to show updated availability
      })
      .catch((error) => console.error("Error:", error));
  });
});

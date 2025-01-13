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

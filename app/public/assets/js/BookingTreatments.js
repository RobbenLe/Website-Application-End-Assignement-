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

// Highlight selected category
function showCategory(categoryId, event) {
  document.querySelectorAll(".service-category").forEach((category) => {
    category.classList.remove("active");
  });

  document.querySelectorAll(".category").forEach((item) => {
    item.classList.remove("active");
  });

  document.getElementById(categoryId).classList.add("active");
  event.target.classList.add("active");
}

// Select a treatment and save to sessionStorage
function selectTreatment(id, name, duration, price) {
  const bookingFooter = document.getElementById("booking-footer");
  const selectedTreatment = document.getElementById("selected-treatment");

  selectedTreatment.innerText = `Selected Treatment: ${name} (${duration}) (€${price})`;
  bookingFooter.style.display = "flex";

  // Save to sessionStorage
  let selectedTreatments =
    JSON.parse(sessionStorage.getItem("selectedTreatments")) || [];

  if (!selectedTreatments.some((treatment) => treatment.id === id)) {
    selectedTreatments.push({ id, name, duration, price });
    sessionStorage.setItem(
      "selectedTreatments",
      JSON.stringify(selectedTreatments)
    );
  }
}

// Navigate to ChooseTimePage
function navigateToChooseTime() {
  const selectedTreatments =
    JSON.parse(sessionStorage.getItem("selectedTreatments")) || [];
  const totalDuration = selectedTreatments.reduce(
    (sum, t) => sum + parseInt(t.duration || 0),
    0
  );
  sessionStorage.setItem("totalDuration", totalDuration);

  if (selectedTreatments.length === 0) {
    alert("Please select at least one treatment before proceeding.");
    return;
  }

  window.location.href = "/ChooseTimePage";
}

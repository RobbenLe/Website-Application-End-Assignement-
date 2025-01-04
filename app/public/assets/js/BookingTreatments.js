// Switch between service categories
function showCategory(categoryId) {
  document
    .querySelectorAll(".service-category")
    .forEach((el) => el.classList.remove("active"));
  document
    .querySelectorAll(".category")
    .forEach((el) => el.classList.remove("active"));
  document.getElementById(categoryId).classList.add("active");
  event.target.classList.add("active");
}

// Show footer popup when a treatment is selected
function selectTreatment(treatment, price) {
  const footer = document.getElementById("booking-footer");
  const selectedTreatment = document.getElementById("selected-treatment");
  selectedTreatment.innerHTML = `1 treatment selected: ${treatment} (€${price})`;
  footer.style.display = "flex";
}

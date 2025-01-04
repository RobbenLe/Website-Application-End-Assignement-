// Switching Between Price List Categories
function showCategory(categoryId, button) {
  // Hide all categories
  document.querySelectorAll(".price-category").forEach((category) => {
    category.style.display = "none";
  });

  // Remove active class from all buttons
  document.querySelectorAll(".category-btn").forEach((btn) => {
    btn.classList.remove("active");
  });

  // Show selected category
  document.getElementById(categoryId).style.display = "block";

  // Add active class to the clicked button
  button.classList.add("active");
}

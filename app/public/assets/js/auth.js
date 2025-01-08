// auth.js - Validate sessionStorage on every authenticated page

document.addEventListener("DOMContentLoaded", () => {
  const userId = sessionStorage.getItem("userId");
  if (!userId) {
    alert("User session is missing. Please log in again.");
    window.location.href = "/LoginPage";
  }
});

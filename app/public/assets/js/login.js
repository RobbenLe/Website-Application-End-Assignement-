document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.querySelector("form"); // Select the login form

  if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
      e.preventDefault(); // Prevent default form submission

      // Retrieve input values
      const username = document.getElementById("username").value.trim();
      const password = document.getElementById("password").value.trim();

      // Input validation
      if (!username || !password) {
        alert("Please fill in both username and password.");
        return;
      }

      try {
        // Send login data to the server
        const response = await fetch("/LoginPage", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ username, password }),
        });

        const result = await response.json();

        if (response.ok && result.success) {
          // Save user info in sessionStorage
          sessionStorage.setItem("userId", result.user.id);
          sessionStorage.setItem("username", result.user.username);
          sessionStorage.setItem("role", result.user.role);

          // Redirect user based on role
          switch (result.user.role) {
            case "customer":
              window.location.href = "/homePage";
              break;
            case "technician":
              window.location.href = "/TechnicianDashboard";
              break;
            case "admin":
              window.location.href = "/AdminDashboardPage";
              break;
            default:
              window.location.href = "/LoginPage";
              break;
          }
        } else {
          throw new Error(result.error || "Failed to login. Please try again.");
        }
      } catch (error) {
        console.error("Login error:", error);
        alert(`Login failed: ${error.message}`);
      }
    });
  }
});

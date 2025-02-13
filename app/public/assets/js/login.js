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

        // Read response as text first (to debug issues)
        const textResponse = await response.text();
        console.log("Raw Response:", textResponse);

        // Convert response to JSON
        const result = JSON.parse(textResponse);

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
              window.location.href = "/TechnicianDashBoardPage";
              break;
            case "admin":
              window.location.href = "/AdminDashBoardPage";
              break;
            default:
              window.location.href = "/LoginPage";
              break;
          }
        } else {
          // Display specific error message from the server
          alert(result.error || "Failed to login. Please try again.");
        }
      } catch (error) {
        console.error("Login error:", error);
        alert(`Login failed. Please try again.`);
      }
    });
  }
});

// Fetch and Populate Tables
async function loadData() {
  let response = await fetch("/api/getTechnicians");
  let technicians = await response.json();
  document.getElementById("technician-table").innerHTML = technicians
    .map(
      (t) => `
      <tr>
        <td>${t.id}</td>
        <td>${t.username}</td>
        <td>${t.email}</td>
        <td><button>Delete</button></td>
      </tr>
    `
    )
    .join("");
}

// Show Technician Modal
function showTechnicianForm() {
  document.getElementById("technician-modal").style.display = "flex";
}

// Submit Technician
document
  .getElementById("technician-form")
  .addEventListener("submit", async (e) => {
    e.preventDefault();
    await fetch("/api/addTechnician", {
      method: "POST",
      body: JSON.stringify({ username: "tech1" }),
    });
    loadData();
  });
